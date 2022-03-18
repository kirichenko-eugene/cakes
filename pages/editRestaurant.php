<?php 
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/DirectoryList.php';

$autorization = new Autorization;
$form = new FormHelper;
$tag = new TagHelper;
$directoryList = new DirectoryList;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Редактировать ресторан';
	$checkId = $directoryList->checkId('restaurants');
	
	$content = $tag->open('div', ['class' => 'row justify-content-center m-2']);
	$content .= $tag->open('h2');
	$content .= 'Редактировать ресторан';
	$content .= $tag->close('h2');
	$content .= $tag->close('div');

	if ($checkId) {
		if (isset($_POST['restaurantName'])) {
			$restaurantName = htmlspecialchars($_POST['restaurantName']);
		} else {
			$restaurantName = $checkId[0]['name'];
		}

		$directoryList->changeRestaurantName($site);
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$content .= $form->openForm(['method' => 'POST']);
		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'restaurantName', 
			'aria-describedby' => 'restaurantName', 
			'name' => 'restaurantName',
			'value' => $restaurantName, 
			'required' => true],
			'Введите новое название');
		$content .= $form->submit(
			['name' => 'submit', 
			'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
			'value' => 'Изменить']);
		$content .= $form->closeForm();
		$content .= $tag->close('div');

	} else {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$content .= 'Данный ресторан не найден';
		$content .= $tag->close('div');
	}

	include '../elements/layout.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}