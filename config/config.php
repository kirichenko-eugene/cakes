<?php

require_once 'SessionShell.php';
require_once 'DatabaseShell.php';

//SESSIONS
$session = new SessionShell();

//PATH
$site = 'https://cakes.goodcity.com.ru/';
$cakePath = 'img/cakes/';
$pathUrl = mb_strrchr(dirname(__FILE__), '/', true);
$cakeDir = $pathUrl . '/' . $cakePath;

//ADMINS
$admins = ['kirichenko@goodcity.com.ru', 'Ivachshenko_NV@goodcity.com.ru', 'dolgova@goodcity.com.ru', 'nikolaichenko@goodcity.com.ru'];

foreach ($admins as $admin) {
	if ($session->exists('userLogin') === true and $session->exists('superUser') === false) {
		if ($admin == $session->get('userLogin')) {
			$session->set('superUser', true);
		} 
	}	
}
