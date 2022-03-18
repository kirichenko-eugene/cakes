<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=$site?>assets/css/bootstrap.min.css">
</head>
<body>
	<?php 
		include 'header.php'; 
		include 'info.php';
	?>

	<div class="content">
		<div class="body-main">
			<?=$content?>
		</div>	
	</div>

	<script src="<?=$site?>assets/js/jquery-3.5.1.min.js"></script>
	<script src="<?=$site?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>