<?php 
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Pagination.php';
require_once '../config/TagHelper.php';
require_once '../config/DirectoryList.php';
require_once '../config/TableHelper.php';
require_once '../config/ResizeImage.php';
require_once '../config/FormHelper.php';
require_once '../config/Logger.php';

$autorization = new Autorization;
$directoryList = new DirectoryList;
$pagination = new Pagination;
$tag = new TagHelper;
$table = new TableHelper;
$form = new FormHelper;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Справочник тортов';
	$directoryList->changeCakeStatus();
	$directoryList->createCake();

	$countElements = $directoryList->countAllCakes();
	$pagination->setPerPage(8);
	$pagination->setPagesCount($countElements);
	$pagination->setLinksNumber(7);
	$startPosition = $pagination->startPosition();
	$perPage = $pagination->getPerPage();
	$cakesForTable = $directoryList->cakesForTable($startPosition, $perPage);


	$content = $tag->open('div', ['class' => 'row justify-content-center m-2']);
	$content .= $form->modalButton('Создать торт', [
			'data-target' => '#modal-createCake', 
			'class' => 'btn btn-primary m-1']);
	$content .= $tag->close('div');
	$createCake = $tag->open('div', ['class' => 'row justify-content-center m-1']);
	$createCake .= $form->openForm(['method' => 'POST']);
	$createCake .= $form->input( 
			['class' => 'form-control', 
			'id' => 'cakeName', 
			'aria-describedby' => 'cakeName', 
			'name' => 'cakeName',
			'autocomplete' => 'off', 
			'required' => true,
			'placeholder' => 'Новый торт'],
			'Название торта');
	$createCake .= $form->input( 
			['class' => 'form-control', 
			'id' => 'cakePrice', 
			'aria-describedby' => 'cakePrice', 
			'name' => 'cakePrice',
			'autocomplete' => 'off', 
			'required' => true,
			'placeholder' => 'Цена'],
			'Цена торта');
	$createCake .= $form->input( 
			['class' => 'form-control', 
			'id' => 'cakeWeight', 
			'aria-describedby' => 'cakeWeight', 
			'name' => 'cakeWeight',
			'autocomplete' => 'off', 
			'required' => true,
			'placeholder' => 'Вес'],
			'Вес торта');
	$createCake .= $form->submit([
		'name' => 'submitCake', 
		'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
		'value' => 'Создать'
		]);
	$createCake .= $form->closeForm();
	$createCake .= $tag->close('div');
	$content .= $form->modalBody('createCake', 'Создать торт', $createCake);

	$content .= '<div class="row justify-content-center m-2"><h2>Список тортов</h2></div>';
	$content .= $pagination->showPagination();
	$content .= $table->tableOpen();
	$content .= $table->tableHead([
		['thname' => 'Название'],  
		['thname' => 'Цена'], 
		['thname' => 'Вес'], 
		['thname' => 'Статус'], 
		['thname' => 'Редактировать'], 
		['thname' => 'Отключить/Восстановить'] 
	]);
	$content .= $table->tbodyOpen();
	foreach($cakesForTable as $key => $cake) {
		$cakeTextName = $cake['name'];
		$cakeTextPrice = $cake['price'];
		$cakeTextWeight = $cake['weight'];
		$cakeStatus = $directoryList->getStatus($cake);
		$editCakeLink = "<a href=\"{$site}pages/editCake.php?id={$cake['id']}\">Редактировать</a>";
		$changeCakeStatusLink = "<a href=\"?changeCakeStatus={$cake['id']}&status={$cake['status']}\">Отключить/восстановить</a>";

		$content .= $table->tableBody([
			['tdname' => $cakeTextName], 
			['tdname' => $cakeTextPrice], 
			['tdname' => $cakeTextWeight], 
			['tdname' => $cakeStatus], 
			['tdname' => $editCakeLink], 
			['tdname' => $changeCakeStatusLink] 
		]);
	}
	$content .= $table->tbodyClose();
	$content .= $table->tableClose();
	$content .= $tag->close('div');

include '../elements/layout.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}