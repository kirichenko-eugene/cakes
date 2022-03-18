<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/Orders.php';
require_once '../config/ResizeImage.php';
require_once '../config/Logger.php';

$autorization = new Autorization;
$form = new FormHelper;
$tag = new TagHelper;
$orders = new Orders;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Сформировать отчет';

	$content = '<div class="row justify-content-center m-2">';
	$content .= $form->openForm(['method' => 'POST']);

	$content .= $form->date(
			['class' => 'form-control', 
			'id' => 'dateStart', 
			'aria-describedby' => 'dateStart', 
			'name' => 'dateStart',
			'autocomplete' => 'off',
			'required' => true],
			'Дата начала формирования отчета');

	$content .= $form->date(
			['class' => 'form-control', 
			'id' => 'dateEnd', 
			'aria-describedby' => 'dateEnd', 
			'name' => 'dateEnd',
			'autocomplete' => 'off',
			'required' => true],
			'Окончание периода формирования отчета');

	$content .= $form->submit([
		'name' => 'createOrder', 
		'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
		'value' => 'Сформировать'
		]);

	$content .= $form->closeForm();
	$content .= '</div>';

	include '../elements/layout.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}