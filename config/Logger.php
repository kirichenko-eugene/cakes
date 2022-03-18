<?php 

class Logger
{
	private $connection;
	private $session;
	private $data;

	public function __construct()
	{
		$this->connection = new DatabaseShell;
		$this->session = new SessionShell;
	}

	public function setData($data)
	{
		$result = '';
		foreach ($data as $element) {
			$result .= $element . ' || ';
		}
		$this->data = $result;
		return $this->data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function saveLog($operationType)
	{
		$author = $this->getAuthor();
		$data = $this->getData();
		$result = $this->connection->insert("INSERT INTO log SET author = ?, operationType = ?, info = ?", [$author, $operationType, $data]);
	}

	private function getAuthor()
	{
		if ($this->session->exists('userLogin')) {
			$author = $this->session->get('userLogin');
		}
		return $author;
	}
}