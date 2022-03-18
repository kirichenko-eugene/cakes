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
	$title = 'Редактировать торт';
	$checkId = $directoryList->checkId('cakes');
	
	$content = $tag->open('div', ['class' => 'row justify-content-center m-2']);
	$content .= $tag->open('h2');
	$content .= 'Редактировать торт';
	$content .= $tag->close('h2');
	$content .= $tag->close('div');

	if ($checkId) {
		if (isset($_POST['cakeName']) and isset($_POST['cakePrice']) and isset($_POST['cakeWeight'])) {
			$cakeName = htmlspecialchars($_POST['cakeName']);
			$cakePrice = htmlspecialchars($_POST['cakePrice']);
			$cakeWeight = htmlspecialchars($_POST['cakeWeight']);
		} else {
			$cakeName = $checkId[0]['name'];
			$cakePrice = $checkId[0]['price'];
			$cakeWeight = $checkId[0]['weight'];
		}

		$directoryList->changeCakeData($site);
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$content .= $form->openForm(['method' => 'POST']);
		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'cakeName', 
			'aria-describedby' => 'cakeName', 
			'name' => 'cakeName',
			'value' => $cakeName, 
			'required' => true],
			'Введите новое название');
		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'cakePrice', 
			'aria-describedby' => 'cakePrice', 
			'name' => 'cakePrice',
			'value' => $cakePrice, 
			'required' => true],
			'Введите новую цену');
		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'cakeWeight', 
			'aria-describedby' => 'cakeWeight', 
			'name' => 'cakeWeight',
			'value' => $cakeWeight, 
			'required' => true],
			'Введите новый вес');
		$content .= $form->submit(
			['name' => 'submit', 
			'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
			'value' => 'Изменить']);
		$content .= $form->closeForm();
		$content .= $tag->close('div');

	} else {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$content .= 'Данный торт не найден';
		$content .= $tag->close('div');
	}

	include '../elements/layout.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}