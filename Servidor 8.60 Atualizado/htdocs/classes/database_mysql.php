<?php
if (!defined('INITIALIZED'))
	exit;

class Database_MySQL extends Database
{
	public function __construct()
	{
		$this->setDatabaseDriver(self::DB_MYSQL);
	}

	public function connect()
	{
		try {
			parent::__construct('mysql:host=' . $this->getDatabaseHost() . ';port=' . $this->getDatabasePort() . ';dbname=' . $this->getDatabaseName(), $this->getDatabaseUsername(), $this->getDatabasePassword());
			$this->setConnected(true);
			return true;
		} catch (PDOException $error) {
			throw new RuntimeException('CANNOT CONNECT TO DATABASE: ' . $error->getMessage());
		}
	}

	/**
	 * @param $name
	 * @return string
	 */
	public function fieldName($name)
	{
		if (strspn($name, "1234567890qwertyuiopasdfghjklzxcvbnm_") != strlen($name))
			throw new InvalidArgumentException('Invalid field name format.');

		return '`' . $name . '`';
	}

	public function tableName($name)
	{
		if (strspn($name, "1234567890qwertyuiopasdfghjklzxcvbnm_") != strlen($name))
			throw new InvalidArgumentException('Invalid table name format.');

		return '`' . $name . '`';
	}
}
