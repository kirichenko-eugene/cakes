<?php 
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Pagination.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/TableHelper.php';
require_once '../config/DirectoryList.php';

$autorization = new Autorization;
$pagination = new Pagination;
$form = new FormHelper;
$table = new TableHelper;
$tag = new TagHelper;
$directoryList = new DirectoryList;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Настройки';
	$directoryList->changeRestaurantStatus();
	$directoryList->changeDeliveryStatus();
	$directoryList->createRestaurant();
	$directoryList->createDelivery();

	$countElements = $directoryList->countAllRestaurants();
	$pagination->setPerPage(5);
	$pagination->setPagesCount($countElements);
	$pagination->setLinksNumber(7);
	$startPosition = $pagination->startPosition();
	$perPage = $pagination->getPerPage();
	$restaurantsForTable = $directoryList->restaurantsForTable($startPosition, $perPage);
	$deliveryForTable = $directoryList->deliveryForTable();

	$content = $tag->open('div', ['class' => 'row justify-content-center m-2']);
	$content .= $form->modalButton('Создать ресторан', [
			'data-target' => '#modal-createRestaurant', 
			'class' => 'btn btn-primary m-1']);
	$content .= $form->modalButton('Способ доставки', [
			'data-target' => '#modal-createDelivery', 
			'class' => 'btn btn-primary m-1']);

	$link = "document.location='{$site}/pages/cakeList.php'";
	$content .= $tag->open('button', ['class' => 'btn btn-primary m-1', 'onclick' => $link]);
	$content .= 'Справочник тортов';
	$content .= $tag->close('button');

	$content .= $tag->close('div');
	$createRestaurant = $tag->open('div', ['class' => 'row justify-content-center m-1']);
	$createRestaurant .= $form->openForm(['method' => 'POST']);
	$createRestaurant .= $form->input( 
			['class' => 'form-control', 
			'id' => 'restaurantName', 
			'aria-describedby' => 'restaurantName', 
			'name' => 'restaurantName',
			'autocomplete' => 'off', 
			'required' => true,
			'placeholder' => 'Новый ресторан'],
			'Название ресторана');
	$createRestaurant .= $form->submit([
		'name' => 'submitRestaurant', 
		'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
		'value' => 'Создать'
		]);
	$createRestaurant .= $form->closeForm();
	$createRestaurant .= $tag->close('div');
	$content .= $form->modalBody('createRestaurant', 'Создать ресторан', $createRestaurant);

	$createDelivery = '<div class="row justify-content-center m-1">';
	$createDelivery .= $form->openForm(['method' => 'POST']);
	$createDelivery .= $form->input( 
			['class' => 'form-control', 
			'id' => 'deliveryName', 
			'aria-describedby' => 'deliveryName', 
			'name' => 'deliveryName',
			'autocomplete' => 'off', 
			'required' => true,
			'placeholder' => 'Доставка'],
			'Название способа');

	$createDelivery .= $form->submit([
		'name' => 'submitDelivery', 
		'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
		'value' => 'Создать'
		]);
	$createDelivery .= $form->closeForm();
	$createDelivery .= $tag->close('div');
	$content .= $form->modalBody('createDelivery', 'Создать способ доставки', $createDelivery);

	$content .= '<div class="row justify-content-center m-2"><h2>Список ресторанов</h2></div>';
	$content .= $pagination->showPagination();
	$content .= $table->tableOpen();
	$content .= $table->tableHead([
		['thname' => 'Название'],  
		['thname' => 'Статус'], 
		['thname' => 'Редактировать'], 
		['thname' => 'Отключить/Восстановить'] 
	]);
	$content .= $table->tbodyOpen();
	foreach($restaurantsForTable as $key => $restaurant) {
		$restaurantTextName = $restaurant['name'];
		$restaurantStatus = $directoryList->getStatus($restaurant);
		$editRestaurantLink = "<a href=\"{$site}pages/editRestaurant.php?id={$restaurant['id']}\">Редактировать</a>";
		$changeRestaurantStatusLink = "<a href=\"?changeRestaurantStatus={$restaurant['id']}&status={$restaurant['status']}\">Отключить/восстановить</a>";

		$content .= $table->tableBody([
			['tdname' => $restaurantTextName], 
			['tdname' => $restaurantStatus], 
			['tdname' => $editRestaurantLink], 
			['tdname' => $changeRestaurantStatusLink] 
		]);
	}
	$content .= $table->tbodyClose();
	$content .= $table->tableClose();
	$content .= $tag->close('div');

	$content .= '<div class="row justify-content-center m-2"><h2>Способы доставки</h2></div>';
	$content .= $table->tableOpen();
	$content .= $table->tableHead([
		['thname' => 'Название'],  
		['thname' => 'Статус'], 
		['thname' => 'Редактировать'], 
		['thname' => 'Отключить/Восстановить'] 
	]);
	$content .= $table->tbodyOpen();
	foreach($deliveryForTable as $key => $delivery) {
		$deliveryTextName = $delivery['name'];
		$deliveryStatus = $directoryList->getStatus($delivery);
		$editDeliveryLink = "<a href=\"{$site}pages/editDelivery.php?id={$delivery['id']}\">Редактировать</a>";
		$changeDeliveryStatusLink = "<a href=\"?changeDeliveryStatus={$delivery['id']}&status={$delivery['status']}\">Отключить/восстановить</a>";

		$content .= $table->tableBody([
			['tdname' => $deliveryTextName], 
			['tdname' => $deliveryStatus], 
			['tdname' => $editDeliveryLink], 
			['tdname' => $changeDeliveryStatusLink] 
		]);
	}
	$content .= $table->tbodyClose();
	$content .= $table->tableClose();
	$content .= '</div>';

	include '../elements/layout.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}