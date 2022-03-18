<?php 

class DatabaseShell
{
	private static $pdo;

	public function __construct()
	{
		$sqltype = "mysql";
		$dbhost = "localhost";
		$dbuser = "cakeuser";
		$dbpassword = "root";
		$dbname = "cakes";
		$dbcharset = "utf8mb4";

		if(!isset(self::$pdo)){
			$dsn = "$sqltype:host=$dbhost;dbname=$dbname;charset=$dbcharset";
			$options = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false,
			];
			self::$pdo = new PDO($dsn, $dbuser, $dbpassword, $options);
			self::$pdo->query('SET NAMES '.$dbcharset);
		}

		return self::$pdo;
	}

	public function insert($query, $params = [])
	{
		$stmt = self::$pdo->prepare($query);
		$stmt->execute($params);

		return self::$pdo->lastInsertId();
	}

	public function delete($query, $params = [])
	{
		$stmt = self::$pdo->prepare($query);
		$stmt->execute($params);

		return $stmt->rowCount();
	}

	public function update($query, $params = [])
	{
		$stmt = self::$pdo->prepare($query);
		$stmt->execute($params);

		return $stmt->rowCount();
	}

	public function select($query, $params = [])
	{
		$stmt = self::$pdo->prepare($query);
		$stmt->execute($params);

		return $stmt->fetchAll();
	}

	public function selectCount($query, $params = [])
	{
		$stmt = self::$pdo->prepare($query);
		$stmt->execute($params);

		return $stmt->rowCount();
	}
}

