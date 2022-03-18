<?php 
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Pagination.php';
require_once '../config/TagHelper.php';
require_once '../config/Orders.php';
require_once '../config/TableHelper.php';
require_once '../config/ResizeImage.php';
require_once '../config/FormHelper.php';
require_once '../config/Logger.php';

$autorization = new Autorization;
$pagination = new Pagination;
$orders = new Orders;
$tag = new TagHelper;
$table = new TableHelper;
$form = new FormHelper;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Непринятые заказы';
	$orders->changeOrderStatus();

	$countElements = $orders->countAllRejectOrders();
	$pagination->setPerPage(10);
	$pagination->setPagesCount($countElements);
	$pagination->setLinksNumber(7);
	$startPosition = $pagination->startPosition();
	$perPage = $pagination->getPerPage();
	$ordersForTable = $orders->rejectOrdersForTable($startPosition, $perPage);
	
	$content = $pagination->showPagination();
	$content .= $table->tableOpen();

	$headArray = [ 
		['thname' => '№'],
		['thname' => 'Отправитель'], 
		['thname' => 'Готовность'], 
		['thname' => 'Ресторан'], 
		['thname' => 'Доставка'], 
		['thname' => 'Клиент'], 
		['thname' => 'Торт'], 
		['thname' => 'Вес'], 
		['thname' => 'Цена'], 
		['thname' => 'Комментарий'], 
		['thname' => 'Обратная связь'], 
		['thname' => 'Фото']
	];

	if ($session->get('superUser') == true) {
		$headArray[] = ['thname' => 'В работу']; 
	}

	$content .= $table->tableHead($headArray);

	$content .= $table->tbodyOpen();
	foreach($ordersForTable as $key => $order) {
		$modalIdOrderInfoButton = '#modal-'. $order['id'];
		$strMailPos = strpos($order['manager'], "@");
		$newMailStr = substr($order['manager'], 0, $strMailPos);
		
		$orderInfo = $form->modalButton($newMailStr, [
			'data-target' => $modalIdOrderInfoButton, 
			'class' => 'btn btn-link']);
		$showOrderInfo = '<div class="row justify-content-center m-1">';
		$showOrderInfo .= $form->openForm();
		$showOrderInfo .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$showOrderInfo .= 'Дата формирования заказа - ' . strftime("%d.%m.%Y %H:%M", strtotime($order['startDate'])) . $tag->open('br');
		$showOrderInfo .= 'Пользователь - ' . $order['manager'];
		$showOrderInfo .= $tag->close('div');
		$showOrderInfo .= $form->closeForm();
		$showOrderInfo .= $tag->close('div');
		$content .= $form->modalBody($order['id'], 'Отправитель', $showOrderInfo);

		$modalIdClientInfoButton = '#modal-client-'. $order['id'];
		$clientInfo = $form->modalButton($order['phone'], [
			'data-target' => $modalIdClientInfoButton, 
			'class' => 'btn btn-link']);
		$showClientInfo = '<div class="row justify-content-center m-1">';
		$showClientInfo .= $form->openForm();
		$showClientInfo .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$showClientInfo .= 'Клиент - ' . $order['client'] . $tag->open('br');
		$showClientInfo .= 'Телефон - ' . $order['phone'] . $tag->open('br');
		$showClientInfo .= 'Адрес - ' . $order['address'];
		$showClientInfo .= $tag->close('div');
		$showClientInfo .= $form->closeForm();
		$showClientInfo .= $tag->close('div');
		$content .= $form->modalBody('client-' . $order['id'], $order['client'], $showClientInfo);

		$feedback = $order['feedback'];
		if ($feedback != '') {
			$modalIdFeedbackInfoButton = '#modal-feedback-'. $order['id'];
			$feedbackInfo = $form->modalButton('Обратная связь', [
				'data-target' => $modalIdFeedbackInfoButton, 
				'class' => 'btn btn-link']);
			$showFeedbackInfo = '<div class="row justify-content-center m-1">';
			$showFeedbackInfo .= $form->openForm();
			$showFeedbackInfo .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
			$showFeedbackInfo .= $feedback;
			$showFeedbackInfo .= $tag->close('div');
			$showFeedbackInfo .= $form->closeForm();
			$showFeedbackInfo .= $tag->close('div');
			$content .= $form->modalBody('feedback-' . $order['id'], 'Обратная связь', $showFeedbackInfo);
		} else {
			$feedbackInfo = '';
		}

		$cakeImg = $order['photo'];
		$modalIdImgButton = '#modal-showPhoto-'. $order['id'];
		$modalButtonImg = $form->modalButton($cakeImg, [
			'data-target' => $modalIdImgButton, 
			'class' => 'btn btn-secondary']);
		if ($cakeImg != '') {
			$cakeImg = $modalButtonImg;
			$imgPath = $site.''.$cakePath.''.$order['photo'];
			$orderImage = "<img src=\"$imgPath\" class=\"img-fluid\" alt=\"{$order['cake']}\">";
		}

		$comment = $order['comment'];
		if ($comment != '') {
			$modalIdCommentInfoButton = '#modal-comment-'. $order['id'];
			$commentInfo = $form->modalButton('Комментарий', [
				'data-target' => $modalIdCommentInfoButton, 
				'class' => 'btn btn-link']);
			$showCommentInfo = '<div class="row justify-content-center m-1">';
			$showCommentInfo .= $form->openForm();
			$showCommentInfo .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
			$showCommentInfo .= $comment;
			$showCommentInfo .= $tag->close('div');
			$showCommentInfo .= $form->closeForm();
			$showCommentInfo .= $tag->close('div');
			$content .= $form->modalBody('comment-' . $order['id'], 'Комментарий', $showCommentInfo);
		} else {
			$commentInfo = '';
		}
		
		$id = $order['id'];
		$finishDate = strftime("%d.%m.%Y %H:%M:%S", strtotime($order['finishDate']));
		$restaurant = $order['restaurantName'];
		$delivery = $order['deliveryName'];
		$cake = $order['cake'];
		$weight = $order['weight'];
		$price = $order['price'];
		
		if ($orders->compareDates($finishDate)) {
			$workStatusLink = "<a href=\"?workStatus={$order['id']}\">В работу</a>";
		} else {
			$workStatusLink = '';
		}

		$bodyArray = [
			['tdname' => $id],
			['tdname' => $orderInfo], 
			['tdname' => $finishDate],
			['tdname' => $restaurant],
			['tdname' => $delivery],
			['tdname' => $clientInfo],
			['tdname' => $cake],
			['tdname' => $weight],
			['tdname' => $price],
			['tdname' => $commentInfo],
			['tdname' => $feedbackInfo],
			['tdname' => $cakeImg],
		];

		if ($session->get('superUser') == true) {
			$bodyArray[] = ['tdname' => $workStatusLink];
		}

		$content .= $table->tableBody($bodyArray);

		if (isset($orderImage)) {
			$content .= $form->modalBody('showPhoto-'.$order['id'], $order['cake'], $orderImage);
		}
	}
	$content .= $table->tbodyClose();
	$content .= $table->tableClose();

	include '../elements/layout.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}
