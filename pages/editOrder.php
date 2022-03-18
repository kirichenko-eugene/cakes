<?php 
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/Orders.php';
require_once '../config/ResizeImage.php';
require_once '../config/DirectoryList.php';
require_once '../config/Logger.php';

$autorization = new Autorization;
$form = new FormHelper;
$tag = new TagHelper;
$orders = new Orders;
$directoryList = new DirectoryList;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Редактировать заказ';
	$checkId = $orders->checkId();
	$getAllRestaurants = $directoryList->getAllActiveRestaurants();
	$getAllDelivery = $directoryList->getAllActiveDelivery();

	$content = $tag->open('div', ['class' => 'row justify-content-center m-2']);
	$content .= $tag->open('h2');
	$content .= 'Редактировать заказ';
	$content .= $tag->close('h2');
	$content .= $tag->close('div');

	if ($checkId) {
		if (isset($_POST['restaurant'])) {
			$restaurantId = $_POST['restaurant'];
		} else {
			$restaurantId = $checkId[0]['restaurant'];
		}

		if (isset($_POST['delivery'])) {
			$deliveryId = $_POST['delivery'];
		} else {
			$deliveryId = $checkId[0]['delivery'];
		}

		if (isset($_POST['client'])) {
			$client = $_POST['client'];
		} else {
			$client = $checkId[0]['client'];
		}

		if (isset($_POST['phone'])) {
			$phone = $_POST['phone'];
		} else {
			$phone = $checkId[0]['phone'];
		}

		if (isset($_POST['address'])) {
			$address = $_POST['address'];
		} else {
			$address = $checkId[0]['address'];
		}

		if (isset($_POST['cake'])) {
			$cake = $_POST['cake'];
		} else {
			$cake = $checkId[0]['cake'];
		}

		if (isset($_POST['weight'])) {
			$weight = $_POST['weight'];
		} else {
			$weight = $checkId[0]['weight'];
		}

		if (isset($_POST['price'])) {
			$price = $_POST['price'];
		} else {
			$price = $checkId[0]['price'];
		}

		if (isset($_POST['comment'])) {
			$comment = $_POST['comment'];
		} else {
			$comment = $checkId[0]['comment'];
		}
		 
		if (isset($_POST['finishDate'])) {
			$finishDate = $_POST['finishDate'];
			$finishTime = $_POST['finishTime'];
		} else {
			$finishDateSql = $checkId[0]['finishDate'];
			$date = new DateTime($finishDateSql);
			$time = new DateTime($finishDateSql);
			$finishDate = $date->format("Y-m-d");
			$finishTime = $time->format("H:i");
		}

		$feedback = $checkId[0]['feedback'];
		
		$orders->changeOrder($site, $cakeDir);

		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$content .= $form->openForm(['method' => 'POST', 'enctype' => 'multipart/form-data']);

		$content .= $form->date(
			['class' => 'form-control', 
			'id' => 'finishDate', 
			'aria-describedby' => 'finishDate', 
			'name' => 'finishDate',
			'value' => $finishDate,
			'autocomplete' => 'off',
			'required' => true],
			'Дата готовности');

		$content .= $form->time(
			['class' => 'form-control', 
			'id' => 'finishTime', 
			'aria-describedby' => 'finishTime', 
			'name' => 'finishTime',
			'value' => $finishTime,
			'autocomplete' => 'off',
			'required' => true],
			'Дата готовности');

		$selectAttrRestaurant = ['name' => 'restaurant', 'class' => 'form-control', 'required' => true];
		$selectLabelRestaurant = 'Выберите ресторан из списка';
		$selectOptionsRestaurant[0] = ['text' => 'Выберите ресторан', 'attrs' => ['value' => '']];
		foreach($getAllRestaurants as $restaurant) {
			if ($restaurantId == $restaurant['id']) {
				$selectOptionsRestaurant[] = [
				'text' => $restaurant['name'], 
				'attrs' => ['value' => $restaurant['id'], 'selected' => true]
				];
			} else {
				$selectOptionsRestaurant[] = [
				'text' => $restaurant['name'], 
				'attrs' => ['value' => $restaurant['id']]
				];
			}	
		}
		$content .= $form->select($selectAttrRestaurant, $selectOptionsRestaurant, $selectLabelRestaurant);

		$selectAttrDelivery = ['name' => 'delivery', 'class' => 'form-control', 'required' => true];
		$selectLabelDelivery = 'Выберите способ доставки из списка';
		$selectOptionsDelivery[0] = ['text' => 'Выберите из списка', 'attrs' => ['value' => '']];
		foreach($getAllDelivery as $delivery) {
			if ($deliveryId == $delivery['id']) {
				$selectOptionsDelivery[] = [
				'text' => $delivery['name'], 
				'attrs' => ['value' => $delivery['id'], 'selected' => true]
				];
			} else {
				$selectOptionsDelivery[] = [
				'text' => $delivery['name'], 
				'attrs' => ['value' => $delivery['id']]
				];
			}	
		}
		$content .= $form->select($selectAttrDelivery, $selectOptionsDelivery, $selectLabelDelivery);

		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'client', 
			'aria-describedby' => 'client', 
			'name' => 'client',
			'autocomplete' => 'off',
			'value' => $client,
			'placeholder' => 'ФИО клиента',
			'required' => true],
			'Клиент');

		$content .= $form->phone(
			['class' => 'form-control', 
			'id' => 'phone', 
			'aria-describedby' => 'phone', 
			'name' => 'phone',
			'autocomplete' => 'off',
			'value' => $phone,
			'placeholder' => 'Номер телефона',
			'required' => true],
			'Телефон клиента');

		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'address', 
			'aria-describedby' => 'address', 
			'name' => 'address',
			'autocomplete' => 'off',
			'value' => $address,
			'placeholder' => 'Введите адрес',
			'required' => true],
			'Адрес доставки / Название ресторана');

		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'cake', 
			'aria-describedby' => 'cake', 
			'name' => 'cake',
			'autocomplete' => 'off',
			'value' => $cake,
			'placeholder' => 'Торт',
			'required' => true],
			'Название торта');

		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'price', 
			'aria-describedby' => 'weight', 
			'name' => 'weight',
			'autocomplete' => 'off',
			'value' => $weight,
			'placeholder' => 'Укажите вес',
			'required' => true],
			'Вес торта');

		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'price', 
			'aria-describedby' => 'price', 
			'name' => 'price',
			'autocomplete' => 'off',
			'value' => $price,
			'placeholder' => 'Цена',
			'required' => true],
			'Цена');

		$content .= $form->textarea($comment,
			['class' => 'form-control', 
			'id' => 'comment', 
			'aria-describedby' => 'comment', 
			'name' => 'comment',
			'autocomplete' => 'off',
			'placeholder' => 'Комментарий'], 
			'Комментарий к заказу (обязательное поле)');

		$content .= $form->textarea($feedback, 
			['class' => 'form-control', 
			'id' => 'feedback', 
			'aria-describedby' => 'feedback', 
			'name' => 'feedback',
			'autocomplete' => 'off',
			'value' => $feedback,
			'placeholder' => 'Здесь будут сообщения',
			'readonly' => true],
			'Обратная связь');

		$content .= $form->textarea('', 
			['class' => 'form-control', 
			'id' => 'newFeedback', 
			'aria-describedby' => 'newFeedback', 
			'name' => 'newFeedback',
			'autocomplete' => 'off',
			'placeholder' => 'Напишите сообщение'], 
			'Написать сообщение для обратной связи');

		$content .= $form->checkbox(
			['class' => 'form-check-input',
			'name' => 'changePhoto',
			'id' => 'changePhoto',
			'onclick' => 'displayHidden()'], 
			'Добавить (изменить) фото торта?'
		);

		$content .= $form->fileInputHidden(
			['class' => 'form-control-file', 
			'id' => 'photo', 
			'aria-describedby' => 'photo', 
			'name' => 'photo'],
			'Формат: jpg, png, svg');

		$content .= $form->submit(
			['name' => 'submit', 
			'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
			'value' => 'Изменить']);
		$content .= $form->closeForm();
		$content .= $tag->close('div');
		
	} else {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$content .= 'Данный заказ не найден';
		$content .= $tag->close('div');
	}

	include '../elements/layout.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}
?>

<script>
function displayHidden() {
    var checkBox = document.getElementById("changePhoto");
    var photoInput = document.getElementById("photo");
    if (checkBox.checked == true){
        photoInput.style.display = "block";
    } else {
       photoInput.style.display = "none";
    }
}
</script>