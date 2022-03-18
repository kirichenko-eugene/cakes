<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/DcConnect.php';

$autorization = new Autorization;

if($session->exists('auth')) {
	$autorization->issetAuth($session->get('auth'), $site);
}

$title = 'Добро пожаловать!';
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
	<div class="container d-flex h-100 justify-content-center">
		<div class="row align-self-center">
			<div class="col-xl-10 col-lg-10 mx-auto mt-5">
				<div class="jumbotron text-center">
					<h1>GoodCity <span class="badge badge-warning">Торты</span></h1>
					<h3>Войдите в учетную запись</h3>
					<form method="post">
						<div class="form-group">
							<label for="username">Имя пользователя</label>
							<input type="text" class="form-control" id="username" aria-describedby="loginHelp" placeholder="Введите логин" name="login">
						</div>
						<div class="form-group">
							<label for="userpass">Пароль</label>
							<input type="password" class="form-control" id="userpass" placeholder="Введите пароль" name="password">
						</div>
						<button type="submit" class="btn btn-warning btn-lg" name="submit">Войти</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php 
		if(isset($_POST['submit'])) {
			$domainConnect = new DcConnect;
			$login = $domainConnect->setLogin($_POST['login']);
			$password = $domainConnect->setPassword($_POST['password']);
			$domainConnect->getConnect($site);
		} 
	?>

	<script src="<?=$site?>assets/js/jquery-3.5.1.min.js"></script>
	<script src="<?=$site?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>