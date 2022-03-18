<?php 

class Autorization
{
	private $login = '';
	private $password = '';

	public function __construct()
	{
		if(isset($_POST['login'])) {
			$this->login = htmlspecialchars($_POST['login']);
		} else {
			$this->login = '';
		}

		if(isset($_POST['password'])) {
			$this->password = htmlspecialchars($_POST['password']);
		} else {
			$this->password = '';
		}
	}

	public function issetAuth($sessionAuth, $url)
	{
		if(isset($sessionAuth)) {
			header("Location: $url");
			die();
		}
	}

	public function noEmptyAuth($sessionAuth)
	{
		return !empty($sessionAuth);
	}

	public function toPage($url)
	{
		header("Location: $url");
		die();
	}

	public function userVerify($user)
	{
		if($user['0']['name'] == $this->login AND $user['0']['password'] == password_verify($this->password, $user['0']['password'])) {
			return true;
		} else {
			return false;
		}
	}

	public function getLogin()
	{
		return $this->login;
	}

	public function getPassword()
	{
		return $this->password;
	}
}