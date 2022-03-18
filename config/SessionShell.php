<?php 

class SessionShell
{
	public function __construct()
	{
		if(!isset($_SESSION)) {
			$time = 14400;
			session_set_cookie_params($time);
			ini_set('session.gc_maxlifetime', $time);
			session_start();
		}
	}

	public function set($name, $value)
	{
		$_SESSION[$name] = $value;
	}

	public function get($name)
	{
		return $_SESSION[$name];
	}

	public function del($name)
	{
		unset($_SESSION[$name]);
	}

	public function exists($name)
	{
		if (isset($_SESSION[$name])) {  
		    return true; 
		} else {
			return false;
		}
	}

	public function destroy()
	{
		session_destroy();
		$_SESSION = array();
	}
}