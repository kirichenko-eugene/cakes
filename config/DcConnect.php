<?php 

class DcConnect
{
	private $login;
	private $password;
	private $domain;
	private $ldaprdn;
	private $ldappass;
	private $userDn;
	private $autorization;
	private $session;

	public function __construct()
	{
		$this->autorization = new Autorization;
		$this->session = new SessionShell;
		$this->domain = "good.city";
		$this->ldaprdn  = 'good\DoorLock';     
		$this->ldappass = 'root';
		$this->userDn = 'DC=good,DC=city'; 
	}

	public function setLogin($login)
	{
		$this->login = $login;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getConnect($site)
	{
		$ldapConnect = ldap_connect($this->domain);
		if ($ldapConnect) {
			ldap_set_option($ldapConnect, LDAP_OPT_NETWORK_TIMEOUT, 5);
			ldap_set_option($ldapConnect, LDAP_OPT_TIMELIMIT, 5);
			ldap_set_option($ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldapConnect, LDAP_OPT_REFERRALS, 0);

			$ldapBind = ldap_bind($ldapConnect, $this->ldaprdn, $this->ldappass);
			
			if ($ldapBind) {
				$mail = $this->login;
				$userPassword = $this->password;

				if (strpos($mail, '@') === false) {
					$mail = $mail . '@goodcity.com.ru';
				}
				$filter = "mail=$mail"; 

				$ldapSearch = ldap_search($ldapConnect, $this->userDn, $filter); 
				$results  = ldap_get_entries($ldapConnect, $ldapSearch);
				if ($results[0]['dn'] != '') {
					if (ldap_bind($ldapConnect, $results [0]['dn'], $userPassword)) {
						$messageData = ['text' => 'Логин пользователя выполнен успешно!', 
						'status' => 'success'];
						$this->session->set('auth', true);
						$this->session->set('userLogin', $mail);
						$this->session->set('message', $messageData);
						$this->autorization->toPage($site);
					} else {
						echo '<div class="text-center"><p class="font-weight-bold text-danger">Неверный пароль!</p></div>';
					}
				} else {
					echo '<div class="text-center"><p class="font-weight-bold text-danger">Почта или логин введены неверно!</p></div>';
				}
			} else {
				echo '<div class="text-center"><p class="font-weight-bold text-danger">Нет подключения к серверу!</p></div>';
			}
		}
	}
}