<?php 
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/ResizeImage.php';
require_once '../config/Orders.php';
require_once '../config/DirectoryList.php';
require_once '../config/Logger.php';

$autorization = new Autorization;
$form = new FormHelper;
$orders = new Orders;
$directoryList = new DirectoryList;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Новый заказ';
	$orders->createOrder($site, $cakeDir);
	$getAllRestaurants = $directoryList->getAllActiveRestaurants();
	$getAllDelivery = $directoryList->getAllActiveDelivery();
	$getAllCakes = $directoryList->getAllActiveCakes();
	
	$content = '<div class="row justify-content-center m-2">';
	$content .= $form->openForm(['method' => 'POST', 'enctype' => 'multipart/form-data']);

	$content .= $form->date(
			['class' => 'form-control', 
			'id' => 'finishDate', 
			'aria-describedby' => 'finishDate', 
			'name' => 'finishDate',
			'autocomplete' => 'off',
			'required' => true],
			'Дата готовности');

	$content .= $form->time(
			['class' => 'form-control', 
			'id' => 'finishTime', 
			'aria-describedby' => 'finishTime', 
			'name' => 'finishTime',
			'autocomplete' => 'off',
			'required' => true],
			'Время готовности');

	$selectAttrRestaurant = ['name' => 'restaurant', 'class' => 'form-control', 'required' => true];
	$selectLabelRestaurant = 'Выберите ресторан из списка';
	$selectOptionsRestaurant[0] = ['text' => 'Выберите ресторан', 'attrs' => ['value' => '']];
	foreach($getAllRestaurants as $restaurant) {
		$selectOptionsRestaurant[] = [
			'text' => $restaurant['name'], 
			'attrs' => ['value' => $restaurant['id']]
		];
	}
	$content .= $form->select($selectAttrRestaurant, $selectOptionsRestaurant, $selectLabelRestaurant);

	$selectAttrDelivery = ['name' => 'delivery', 'class' => 'form-control', 'required' => true];
	$selectLabelDelivery = 'Выберите способ доставки из списка';
	$selectOptionsDelivery[0] = ['text' => 'Выберите из списка', 'attrs' => ['value' => '']];
	foreach($getAllDelivery as $delivery) {
		$selectOptionsDelivery[] = [
			'text' => $delivery['name'], 
			'attrs' => ['value' => $delivery['id']]
		];
	}
	$content .= $form->select($selectAttrDelivery, $selectOptionsDelivery, $selectLabelDelivery);

	$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'client', 
			'aria-describedby' => 'client', 
			'name' => 'client',
			'autocomplete' => 'off',
			'placeholder' => 'ФИО клиента',
			'required' => true],
			'Клиент');

	$content .= $form->phone(
			['class' => 'form-control', 
			'id' => 'phone', 
			'aria-describedby' => 'phone', 
			'name' => 'phone',
			'autocomplete' => 'off',
			'placeholder' => 'Номер телефона',
			'required' => true],
			'Телефон клиента');

	$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'address', 
			'aria-describedby' => 'address', 
			'name' => 'address',
			'autocomplete' => 'off',
			'placeholder' => 'Введите адрес',
			'required' => true],
			'Адрес доставки / Название ресторана');

	$selectAttrCake = ['name' => 'selectCake', 'class' => 'form-control'];
	$selectLabelCake = 'Выберите торт из списка, если он не заказной';
	$selectOptionsCake[0] = ['text' => 'Выберите из списка', 'attrs' => ['value' => '']];
	foreach($getAllCakes as $cake) {
		$selectOptionsCake[] = [
			'text' => $cake['name'] . ', ЦЕНА: ' . $cake['price'] . ', ВЕС: ' . $cake['weight'], 
			'attrs' => ['value' => $cake['id']]
		];
	}
	$content .= $form->select($selectAttrCake, $selectOptionsCake, $selectLabelCake);

	$content .= $form->checkbox(
			['class' => 'form-check-input',
			'name' => 'changeCake',
			'id' => 'changeCake',
			'onclick' => 'displayHiddenFields()'], 
			'Это заказной торт?'
		);

	$content .= $form->textInputHidden(
			['class' => 'form-control', 
			'id' => 'cake', 
			'aria-describedby' => 'cake', 
			'name' => 'cake',
			'autocomplete' => 'off',
			'placeholder' => 'Торт'],
			'Название торта');

	$content .= $form->textInputHidden(
			['class' => 'form-control', 
			'id' => 'weight', 
			'aria-describedby' => 'weight', 
			'name' => 'weight',
			'autocomplete' => 'off',
			'placeholder' => 'Укажите вес'],
			'Вес торта');

	$content .= $form->textInputHidden(
			['class' => 'form-control', 
			'id' => 'price', 
			'aria-describedby' => 'price', 
			'name' => 'price',
			'autocomplete' => 'off',
			'placeholder' => 'Цена'],
			'Цена');

	$content .= $form->textarea('',
			['class' => 'form-control', 
			'id' => 'comment', 
			'aria-describedby' => 'comment', 
			'name' => 'comment',
			'autocomplete' => 'off',
			'required' => true, 
			'placeholder' => 'Комментарий'], 
			'Комментарий к заказу (обязательное поле)');

	$content .= $form->checkbox(
			['class' => 'form-check-input',
			'name' => 'changePhoto',
			'id' => 'changePhoto',
			'onclick' => 'displayHidden()'], 
			'Добавить фото торта?'
		);

	$content .= $form->fileInputHidden(
			['class' => 'form-control-file', 
			'id' => 'photo', 
			'aria-describedby' => 'photo', 
			'name' => 'photo'],
			'Формат: jpg, png, svg');

	$content .= $form->submit([
		'name' => 'createOrder', 
		'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
		'value' => 'Создать'
		]);
	$content .= $form->closeForm();
	$content .= '</div>';

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

function displayHiddenFields() {
    var checkBox = document.getElementById("changeCake");
    var cakeInput = document.getElementById("cake");
    var weightInput = document.getElementById("weight");
    var priceInput = document.getElementById("price");
    if (checkBox.checked == true){
        cakeInput.style.display = "block";
        weightInput.style.display = "block";
        priceInput.style.display = "block";
    } else {
       	cakeInput.style.display = "none";
       	weightInput.style.display = "none";
        priceInput.style.display = "none";
    }
}
</script>
