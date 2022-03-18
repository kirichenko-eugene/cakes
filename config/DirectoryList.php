<?php 

class DirectoryList 
{
	private $connection;
	private $session;
	private $autorization;

	public function __construct()
	{
		$this->connection = new DatabaseShell;
		$this->session = new SessionShell;
		$this->autorization = new Autorization;
	}

	public function getStatus($category)
	{
		if ($category['status'] == 1) {
			$textStatus = 'Активно';
		} else {
			$textStatus = 'Отключено';
		}
		return $textStatus;
	}

	public function countAllRestaurants()
	{
		$result = $this->connection->selectCount("SELECT * FROM restaurants");
		return $result;	
	}

	public function countAllCakes()
	{
		$result = $this->connection->selectCount("SELECT * FROM cakes");
		return $result;	
	}

	public function getAllActiveCakes()
	{
		$result = $this->connection->select("SELECT * FROM cakes WHERE status = 1");
		return $result;
	}

	public function getAllActiveRestaurants()
	{
		$result = $this->connection->select("SELECT * FROM restaurants WHERE status = 1");
		return $result;
	}

	public function getAllActiveDelivery()
	{
		$result = $this->connection->select("SELECT * FROM delivery WHERE status = 1");
		return $result;
	}

	public function restaurantsForTable($startPosition, $perPage)
	{
		$result = $this->connection->select("SELECT * FROM restaurants ORDER BY status DESC LIMIT ?, ?", [$startPosition, $perPage]);
		return $result;
	}

	public function cakesForTable($startPosition, $perPage)
	{
		$result = $this->connection->select("SELECT * FROM cakes ORDER BY id ASC LIMIT ?, ?", [$startPosition, $perPage]);
		return $result;
	}

	public function deliveryForTable()
	{
		$result = $this->connection->select("SELECT * FROM delivery ORDER BY status DESC");
		return $result;
	}

	public function changeRestaurantStatus()
	{
		if (isset($_GET['changeRestaurantStatus'])) {
			$id = $_GET['changeRestaurantStatus'];
			$status = $_GET['status'];
			if($status == 1) {
				$newstatus = 0;
				$result = $this->connection->update("UPDATE restaurants SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Ресторан был отключен!', 
				'status' => 'error'];
				$this->session->set('message', $messageData);
			} elseif ($status == 0) {
				$newstatus = 1;
				$result = $this->connection->update("UPDATE restaurants SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Ресторан снова активен!', 
				'status' => 'success'];
				$this->session->set('message', $messageData);
			}

			return $status;
		}
	}

	public function changeDeliveryStatus()
	{
		if (isset($_GET['changeDeliveryStatus'])) {
			$id = $_GET['changeDeliveryStatus'];
			$status = $_GET['status'];
			if($status == 1) {
				$newstatus = 0;
				$result = $this->connection->update("UPDATE delivery SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Способ доставки был отключен!', 
				'status' => 'error'];
				$this->session->set('message', $messageData);
			} elseif ($status == 0) {
				$newstatus = 1;
				$result = $this->connection->update("UPDATE delivery SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Способ доставки снова активен!', 
				'status' => 'success'];
				$this->session->set('message', $messageData);
			}

			return $status;
		}
	}

	public function changeCakeStatus()
	{
		if (isset($_GET['changeCakeStatus'])) {
			$id = $_GET['changeCakeStatus'];
			$status = $_GET['status'];
			if($status == 1) {
				$newstatus = 0;
				$result = $this->connection->update("UPDATE cakes SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Торт был отключен!', 
				'status' => 'error'];
				$this->session->set('message', $messageData);
			} elseif ($status == 0) {
				$newstatus = 1;
				$result = $this->connection->update("UPDATE cakes SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Торт снова активен!', 
				'status' => 'success'];
				$this->session->set('message', $messageData);
			}

			return $status;
		}
	}

	public function createCake()
	{
		if (isset($_POST['submitCake'])) {
			$cakeName = htmlspecialchars($_POST['cakeName']);
			$cakePrice = htmlspecialchars($_POST['cakePrice']);
			$cakeWeight = htmlspecialchars($_POST['cakeWeight']);
			$result = $this->connection->insert("INSERT into cakes (name, price, weight, status) VALUES (?, ?, ?, 1)", [$cakeName, $cakePrice, $cakeWeight]);
			$messageData = ['text' => 'Торт успешно добавлен',
							'status' => 'success'];
			$this->session->set('message', $messageData);
		} else {
			return '';
		}
	}

	public function createRestaurant()
	{
		if (isset($_POST['submitRestaurant'])) {
			$restaurantName = htmlspecialchars($_POST['restaurantName']);
			$result = $this->connection->insert("INSERT into restaurants (name, status) VALUES (?, 1)", [$restaurantName]);
			$messageData = ['text' => 'Ресторан успешно добавлен',
							'status' => 'success'];
			$this->session->set('message', $messageData);
		} else {
			return '';
		}
	}

	public function createDelivery()
	{
		if (isset($_POST['submitDelivery'])) {
			$deliveryName = htmlspecialchars($_POST['deliveryName']);
			$result = $this->connection->insert("INSERT into delivery (name, status) VALUES (?, 1)", [$deliveryName]);
			$messageData = ['text' => 'Способ доставки успешно добавлен',
							'status' => 'success'];
			$this->session->set('message', $messageData);
		} else {
			return '';
		}
	}

	public function checkId($table)
	{
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$result = $this->connection->select("SELECT * FROM $table WHERE id = ? LIMIT 1", [$id]);
			return $result;
		}
	}

	public function countRestaurantsByName($name)
	{
		$countRestaurants = $this->connection->selectCount("SELECT * FROM restaurants WHERE name = ?", [$name]);
		return $countRestaurants;
	}

	public function countDeliveryByName($name)
	{
		$countDelivery = $this->connection->selectCount("SELECT * FROM delivery WHERE name = ?", [$name]);
		return $countDelivery;
	}

	public function countCakeByName($name)
	{
		$countCake = $this->connection->selectCount("SELECT * FROM cakes WHERE name = ?", [$name]);
		return $countCake;
	}

	public function changeCakeData($site)
	{
		if (isset($_POST['cakeName']) and isset($_POST['cakePrice']) and isset($_POST['cakeWeight'])) {
			$cakeName = htmlspecialchars($_POST['cakeName']);
			$cakePrice = htmlspecialchars($_POST['cakePrice']);
			$cakeWeight = htmlspecialchars($_POST['cakeWeight']);
			if (isset($_GET['id'])) {
				$id = $_GET['id'];

				if ($this->countCakeByName($name) !== 0) {
					$messageData = ['text' => 'Торт с таким названием уже зарегистрирован', 
					'status' => 'error'];
					$this->session->set('message', $messageData);
				} else {
					$result = $this->connection->update("UPDATE cakes SET name = ?, price = ?, weight = ? WHERE id = ?", [$cakeName, $cakePrice, $cakeWeight, $id]);

					$messageData = ['text' => 'Торт успешно обновлен', 
					'status' => 'success'];
					$this->session->set('message', $messageData);
					$this->autorization->toPage($site.'pages/cakeList.php');
				}
			}
		}
	}

	public function changeRestaurantName($site)
	{
		if (isset($_POST['restaurantName'])) {
			$name = htmlspecialchars($_POST['restaurantName']);
			if (isset($_GET['id'])) {
				$id = $_GET['id'];

				if ($this->countRestaurantsByName($name) !== 0) {
					$messageData = ['text' => 'Ресторан с таким названием уже зарегистрирован', 
					'status' => 'error'];
					$this->session->set('message', $messageData);
				} else {
					$result = $this->connection->update("UPDATE restaurants SET name = ? WHERE id = ?", [$name, $id]);

					$messageData = ['text' => 'Ресторан успешно обновлен', 
					'status' => 'success'];
					$this->session->set('message', $messageData);
					$this->autorization->toPage($site.'pages/settings.php');
				}
			}
		}
	}

	public function changeDeliveryName($site)
	{
		if (isset($_POST['deliveryName'])) {
			$name = htmlspecialchars($_POST['deliveryName']);
			if (isset($_GET['id'])) {
				$id = $_GET['id'];

				if ($this->countDeliveryByName($name) !== 0) {
					$messageData = ['text' => 'Способ доставки с таким названием уже зарегистрирован', 
					'status' => 'error'];
					$this->session->set('message', $messageData);
				} else {
					$result = $this->connection->update("UPDATE delivery SET name = ? WHERE id = ?", [$name, $id]);

					$messageData = ['text' => 'Способ доставки успешно обновлен', 
					'status' => 'success'];
					$this->session->set('message', $messageData);
					$this->autorization->toPage($site.'pages/settings.php');
				}
			}
		}
	}
}