<?php 

class Orders
{
	private $connection;
	private $session;
	private $autorization;
	private $resizeImage;
	private $log;

	public function __construct()
	{
		$this->connection = new DatabaseShell;
		$this->session = new SessionShell;
		$this->autorization = new Autorization;
		$this->resizeImage = new ResizeImage;
		$this->log = new Logger;
	}

	public function checkId()
	{
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$status = $_GET['status'];
			$result = $this->connection->select("SELECT cl.id AS id, cl.startDate AS startDate, cl.manager AS manager, cl.finishDate AS finishDate, cl.restaurant AS restaurant, r.name AS restaurantName, cl.delivery AS delivery, d.name AS deliveryName, cl.client AS client, cl.phone AS phone, cl.address AS address, cl.cake AS cake, cl.weight AS weight, cl.price AS price, cl.comment AS comment, cl.feedback AS feedback, cl.photo AS photo, cl.status AS status 
				FROM cakesList cl 
				LEFT JOIN restaurants r ON cl.restaurant = r.id 
				LEFT JOIN delivery d ON cl.delivery = d.id 
				WHERE cl.id = ? AND cl.status = ? LIMIT 1", [$id, $status]);
			return $result;
		}
	}

	public function compareDates($finishDate)
	{
		$today = date("d.m.Y H:m:s");
		if (strtotime($finishDate) > strtotime($today)) {
			return true;
		} else {
			return false;
		}
	}

	public function textStatus($status)
	{
		if ($status == 0) {
			return 'Новая';
		} elseif ($status == 1) {
			return 'Выполнена';
		} elseif ($status == 2) {
			return 'В работе';
		} elseif ($status == 3) {
			return 'Отклонена';
		}
	}

	public function countAllOrders($userMail)
	{
		$result = $this->connection->selectCount("SELECT * FROM cakesList WHERE manager = ?", [$userMail]);
		return $result;	
	}

	public function countAllAdminOrders()
	{
		$result = $this->connection->selectCount("SELECT * FROM cakesList WHERE status = 0 OR status = 2");
		return $result;	
	}

	public function countAllFinishOrders()
	{
		$result = $this->connection->selectCount("SELECT * FROM cakesList WHERE status = 1");
		return $result;	
	}

	public function countAllRejectOrders()
	{
		$result = $this->connection->selectCount("SELECT * FROM cakesList WHERE status = 3");
		return $result;	
	}

	public function newOrdersForTable()
	{
		$result = $this->connection->select("SELECT cl.id AS id, cl.startDate AS startDate, cl.manager AS manager, cl.finishDate AS finishDate, r.name AS restaurantName, d.name AS deliveryName, cl.client AS client, cl.phone AS phone, cl.address AS address, cl.cake AS cake, cl.weight AS weight, cl.price AS price, cl.comment AS comment, cl.feedback AS feedback, cl.photo AS photo, cl.status AS status 
			FROM cakesList cl 
			LEFT JOIN restaurants r ON cl.restaurant = r.id 
			LEFT JOIN delivery d ON cl.delivery = d.id 
			WHERE cl.status = 0 ORDER BY finishDate DESC");
		return $result;
	}

	public function inWorkOrdersForTable()
	{
		$result = $this->connection->select("SELECT cl.id AS id, cl.startDate AS startDate, cl.manager AS manager, cl.finishDate AS finishDate, r.name AS restaurantName, d.name AS deliveryName, cl.client AS client, cl.phone AS phone, cl.address AS address, cl.cake AS cake, cl.weight AS weight, cl.price AS price, cl.comment AS comment, cl.feedback AS feedback, cl.photo AS photo, cl.status AS status 
			FROM cakesList cl 
			LEFT JOIN restaurants r ON cl.restaurant = r.id 
			LEFT JOIN delivery d ON cl.delivery = d.id 
			WHERE cl.status = 2 ORDER BY finishDate DESC");
		return $result;
	}

	public function rejectOrdersForTable($startPosition, $perPage)
	{
		$result = $this->connection->select("SELECT cl.id AS id, cl.startDate AS startDate, cl.manager AS manager, cl.finishDate AS finishDate, r.name AS restaurantName, d.name AS deliveryName, cl.client AS client, cl.phone AS phone, cl.address AS address, cl.cake AS cake, cl.weight AS weight, cl.price AS price, cl.comment AS comment, cl.feedback AS feedback, cl.photo AS photo, cl.status AS status 
			FROM cakesList cl 
			LEFT JOIN restaurants r ON cl.restaurant = r.id 
			LEFT JOIN delivery d ON cl.delivery = d.id 
			WHERE cl.status = 3 ORDER BY finishDate DESC LIMIT ?, ?", [$startPosition, $perPage]);
		return $result;
	}

	public function finishOrdersForTable($startPosition, $perPage)
	{
		$result = $this->connection->select("SELECT cl.id AS id, cl.startDate AS startDate, cl.manager AS manager, cl.finishDate AS finishDate, r.name AS restaurantName, d.name AS deliveryName, cl.client AS client, cl.phone AS phone, cl.address AS address, cl.cake AS cake, cl.weight AS weight, cl.price AS price, cl.comment AS comment, cl.feedback AS feedback, cl.photo AS photo, cl.status AS status 
			FROM cakesList cl 
			LEFT JOIN restaurants r ON cl.restaurant = r.id 
			LEFT JOIN delivery d ON cl.delivery = d.id 
			WHERE cl.status = 1 ORDER BY finishDate DESC LIMIT ?, ?", [$startPosition, $perPage]);
		return $result;
	}

	public function allOrdersForTable($startPosition, $perPage, $userMail)
	{
		$result = $this->connection->select("SELECT cl.id AS id, cl.startDate AS startDate, cl.manager AS manager, cl.finishDate AS finishDate, r.name AS restaurantName, d.name AS deliveryName, cl.client AS client, cl.phone AS phone, cl.address AS address, cl.cake AS cake, cl.weight AS weight, cl.price AS price, cl.comment AS comment, cl.feedback AS feedback, cl.photo AS photo, cl.status AS status 
			FROM cakesList cl 
			LEFT JOIN restaurants r ON cl.restaurant = r.id 
			LEFT JOIN delivery d ON cl.delivery = d.id 
			WHERE cl.manager = ? ORDER BY startDate DESC LIMIT ?, ?", [$userMail, $startPosition, $perPage]);
		return $result;
	}

	public function allAdminOrdersForTable($startPosition, $perPage)
	{
		$result = $this->connection->select("SELECT cl.id AS id, cl.startDate AS startDate, cl.manager AS manager, cl.finishDate AS finishDate, r.name AS restaurantName, d.name AS deliveryName, cl.client AS client, cl.phone AS phone, cl.address AS address, cl.cake AS cake, cl.weight AS weight, cl.price AS price, cl.comment AS comment, cl.feedback AS feedback, cl.photo AS photo, cl.status AS status 
			FROM cakesList cl 
			LEFT JOIN restaurants r ON cl.restaurant = r.id 
			LEFT JOIN delivery d ON cl.delivery = d.id 
			WHERE cl.status = 0 OR cl.status = 2 ORDER BY startDate DESC LIMIT ?, ?", [$startPosition, $perPage]);
		return $result;
	}

	public function changeOrderStatus()
	{
		if (isset($_GET['workStatus'])) {
			$id = $_GET['workStatus'];
			$newstatus = 2;
			$result = $this->connection->update("UPDATE cakesList SET status = ? WHERE id = ?", [$newstatus, $id]);
			$data = [$id, $newstatus];
			$this->log->setData($data);
			$this->log->saveLog('Статус - в работе');
			$messageData = ['text' => 'Статус заявки - в работе!', 
			'status' => 'success'];
			$this->session->set('message', $messageData);
		}

		if (isset($_GET['rejectStatus'])) {
			$id = $_GET['rejectStatus'];
			$newstatus = 3;
			$result = $this->connection->update("UPDATE cakesList SET status = ? WHERE id = ?", [$newstatus, $id]);
			$data = [$id, $newstatus];
			$this->log->setData($data);
			$this->log->saveLog('Статус - отклонена');
			$messageData = ['text' => 'Статус заявки - отклонена!', 
			'status' => 'success'];
			$this->session->set('message', $messageData);
		}

		if (isset($_GET['finishStatus'])) {
			$id = $_GET['finishStatus'];
			$newstatus = 1;
			$result = $this->connection->update("UPDATE cakesList SET status = ? WHERE id = ?", [$newstatus, $id]);
			$data = [$id, $newstatus];
			$this->log->setData($data);
			$this->log->saveLog('Статус - выполнена');
			$messageData = ['text' => 'Статус заявки - выполнена!', 
			'status' => 'success'];
			$this->session->set('message', $messageData);
		}
	}

	public function createOrder($site, $cakeDir)
	{
		if (isset($_POST['createOrder'])) {
			$manager = $this->session->get('userLogin');
			$finishDate = $_POST['finishDate'] . 'T' . $_POST['finishTime'];
			$restaurant = htmlspecialchars($_POST['restaurant']);
			$delivery = htmlspecialchars($_POST['delivery']);
			$client = htmlspecialchars($_POST['client']);
			$phone = htmlspecialchars($_POST['phone']);
			$address = htmlspecialchars($_POST['address']);
			
			if (isset($_POST['comment'])) {
				$comment = htmlspecialchars($_POST['comment']);
			} else {
				$comment = '';
			}

			if (isset($_POST['feedback'])) {
				$feedback = htmlspecialchars($_POST['feedback']);
			} else {
				$feedback = '';
			}

			if ($_POST['changeCake'] != 1) {
				$cakeId = htmlspecialchars($_POST['selectCake']);
				$cakeResult = $this->connection->select("SELECT * FROM cakes WHERE id = ?", [$cakeId]);
				$cake = $cakeResult['0']['name'];
				$weight = $cakeResult['0']['weight'];
				$price = $cakeResult['0']['price'];
			} elseif ($_POST['changeCake'] == 1) {
				$cake = htmlspecialchars($_POST['cake']);
				$weight = htmlspecialchars($_POST['weight']);
				$price = htmlspecialchars($_POST['price']);
			} 

			if (isset($_POST['changePhoto'])) {
				$checkStatus = htmlspecialchars($_POST['changePhoto']);
			} else {
				$checkStatus = 0;
			}

			if ($cake == NULL or $weight == NULL or $price == NULL)	{
				$messageData = ['text' => 'Заполните данные о торте', 
						'status' => 'error'];
				$this->session->set('message', $messageData);
				$this->autorization->toPage($site.'pages/newOrder.php');
			}

			if($checkStatus == 1) {
				$file = $this->resizeImage->setFile($_FILES['photo']);
				$photoPath = $this->resizeImage->getImageName();
				$types = $this->resizeImage->getSupportTypes();
				$fullFileName = $this->resizeImage->getFullFileName();
				if (in_array($this->resizeImage->getImageMime(), $types)) {
					if ($this->resizeImage->imageRegExp($fullFileName) === false) {
						$messageData = ['text' => 'Имя файла может состоять только из букв английского алфавита, цифр, тире, нижнего подчеркивания, точек, без пробелов и иметь длину от 3 до 30 символов', 
						'status' => 'error'];
						$this->session->set('message', $messageData);
					} else {
						$result = $this->connection->insert("INSERT INTO cakesList SET manager = ?, finishDate = ?, restaurant = ?, delivery = ?, client = ?, phone = ?, address = ?, cake = ?, weight = ?, price = ?, comment = ?, feedback = ?, photo = ?, status = 0", [$manager, $finishDate, $restaurant, $delivery, $client, $phone, $address, $cake, $weight, $price, $comment, $feedback, $photoPath]);
						$this->resizeImage->simpleSaveImage($cakeDir);
						$data = [$manager, $finishDate, $restaurant, $delivery, $client, $phone, $address, $cake, $weight, $price, $comment, $feedback, $photoPath];
						$this->log->setData($data);
						$this->log->saveLog('Создание');
						$messageData = ['text' => 'Заказ создан успешно', 
						'status' => 'success'];
						$this->session->set('message', $messageData);
						$this->autorization->toPage($site);
					}
				} else {
					$messageData = ['text' => 'Неподдерживаемый тип файла',
					'status' => 'error'];
					$this->session->set('message', $messageData);
				}

			} else {
				$photoPath = '';
				$result = $this->connection->insert("INSERT INTO cakesList SET manager = ?, finishDate = ?, restaurant = ?, delivery = ?, client = ?, phone = ?, address = ?, cake = ?, weight = ?, price = ?, comment = ?, feedback = ?, photo = ?, status = 0", [$manager, $finishDate, $restaurant, $delivery, $client, $phone, $address, $cake, $weight, $price, $comment, $feedback, $photoPath]);
				$data = [$manager, $finishDate, $restaurant, $delivery, $client, $phone, $address, $cake, $weight, $price, $comment, $feedback, $photoPath];
				$this->log->setData($data);
				$this->log->saveLog('Создание');
				$messageData = ['text' => 'Заказ создан успешно', 
				'status' => 'success'];
				$this->session->set('message', $messageData);

				$this->autorization->toPage($site);
			}
		} else {
			return '';
		}
	}

	public function changeOrder($site, $cakeDir)
	{
		if (isset($_POST['submit'])) {
			$manager = $this->session->get('userLogin');
			$finishDate = $_POST['finishDate'] . 'T' . $_POST['finishTime'];
			$restaurant = htmlspecialchars($_POST['restaurant']);
			$delivery = htmlspecialchars($_POST['delivery']);
			$client = htmlspecialchars($_POST['client']);
			$phone = htmlspecialchars($_POST['phone']);
			$address = htmlspecialchars($_POST['address']);
			$cake = htmlspecialchars($_POST['cake']);
			$weight = htmlspecialchars($_POST['weight']);
			$price = htmlspecialchars($_POST['price']);

			if (isset($_POST['comment'])) {
				$comment = htmlspecialchars($_POST['comment']);
			} else {
				$comment = '';
			}

			if (isset($_POST['feedback'])) {
				$feedback = htmlspecialchars($_POST['feedback']);
			} else {
				$feedback = '';
			}

			if (isset($_POST['newFeedback'])) {
				if ($_POST['newFeedback'] != '') {
					$newFeedback = $manager . ' --- ' . htmlspecialchars($_POST['newFeedback']);
					$feedback = $feedback . ' /// ' . $newFeedback;
				}
			} 

			if (isset($_POST['changePhoto'])) {
				$checkStatus = htmlspecialchars($_POST['changePhoto']);
			} else {
				$checkStatus = 0;
			}

			if (isset($_GET['id']) AND isset($_GET['status'])) {
				$id = htmlspecialchars($_GET['id']);
				$status = htmlspecialchars($_GET['status']);
				if($checkStatus == 1) {
					$file = $this->resizeImage->setFile($_FILES['photo']);
					$photoPath = $this->resizeImage->getImageName();
					$types = $this->resizeImage->getSupportTypes();
					$fullFileName = $this->resizeImage->getFullFileName();
					if (in_array($this->resizeImage->getImageMime(), $types)) {
						if ($this->resizeImage->imageRegExp($fullFileName) === false) {
							$messageData = ['text' => 'Имя файла может состоять только из букв английского алфавита, цифр, тире, нижнего подчеркивания, точек, без пробелов и иметь длину от 3 до 30 символов', 
							'status' => 'error'];
							$this->session->set('message', $messageData);
						} else {
							$result = $this->connection->insert("UPDATE cakesList SET finishDate = ?, restaurant = ?, delivery = ?, client = ?, phone = ?, address = ?, cake = ?, weight = ?, price = ?, comment = ?, feedback = ?, photo = ?, status = ? WHERE id = ?", [$finishDate, $restaurant, $delivery, $client, $phone, $address, $cake, $weight, $price, $comment, $feedback, $photoPath, $status, $id]);
							$this->resizeImage->simpleSaveImage($cakeDir);
							$data = [$id, $finishDate, $restaurant, $delivery, $client, $phone, $address, $cake, $weight, $price, $comment, $feedback, $status];
							$this->log->setData($data);
							$this->log->saveLog('Редактирование');
							$messageData = ['text' => 'Заказ успешно обновлен', 
							'status' => 'success'];
							$this->session->set('message', $messageData);
							$this->autorization->toPage($site);
						}
					} else {
						$messageData = ['text' => 'Неподдерживаемый тип файла',
						'status' => 'error'];
						$this->session->set('message', $messageData);
					}

				} else {
					$result = $this->connection->insert("UPDATE cakesList SET finishDate = ?, restaurant = ?, delivery = ?, client = ?, phone = ?, address = ?, cake = ?, weight = ?, price = ?, comment = ?, feedback = ?, status = ? WHERE id = ?", [$finishDate, $restaurant, $delivery, $client, $phone, $address, $cake, $weight, $price, $comment, $feedback, $status, $id]);
					$data = [$id, $finishDate, $restaurant, $delivery, $client, $phone, $address, $cake, $weight, $price, $comment, $feedback, $status];
					$this->log->setData($data);
					$this->log->saveLog('Редактирование');
					$messageData = ['text' => 'Заказ успешно обновлен', 
					'status' => 'success'];
					$this->session->set('message', $messageData);

					$this->autorization->toPage($site);
				}
			} else {
				$messageData = ['text' => 'Нет идентификатора или статуса заказа',
				'status' => 'error'];
				$this->session->set('message', $messageData);
			}
		} else {
			return '';
		}
	}
}