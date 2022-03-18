<?php
	include '../config/config.php';
	$session->destroy();
	$title = 'До свидания!';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=$site?>assets/css/bootstrap.min.css">
</head>
<body>

	<div class="content">
		<div class="row justify-content-center m-2">
			<div class="col-sm-12 col-md-12 col-lg-12 text-center"><h1>До свидания!</h1></div>
			<a href="<?=$site?>pages\login.php" class="btn btn-warning m-2">К логину</a>
		</div>
	</div>

	<script src="<?=$site?>assets/js/jquery-3.5.1.min.js"></script>
	<script src="<?=$site?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>