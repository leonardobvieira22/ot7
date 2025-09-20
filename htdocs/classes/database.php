<?php
if (!defined('INITIALIZED'))
	exit;

class Database extends PDO
{
	const DB_MYSQL = 1;

	public $connectionError = '';
	public $queriesCount = 0;
	public $printQueries = false;
	private $connected = false;
	private $db_driver;
	private $db_host = 'localhost';
	private $db_port = '3306';
	private $db_name;
	private $db_username;
	private $db_password;
	private $db_file;

	public function setPrintQueries($value)
	{
		return $this->printQueries = $value;
	}

	public function getDatabaseDriver()
	{
		return $this->db_driver;
	}

	public function getDatabaseHost()
	{
		return $this->db_host;
	}

	public function getDatabasePort()
	{
		return $this->db_port;
	}

	public function getDatabaseName()
	{
		return $this->db_name;
	}

	public function getDatabaseUsername()
	{
		return $this->db_username;
	}

	public function getDatabasePassword()
	{
		return $this->db_password;
	}

	public function getDatabaseFile()
	{
		return $this->db_file;
	}

	public function setDatabaseDriver($value)
	{
		$this->db_driver = $value;
	}

	public function setDatabaseHost($value)
	{
		$this->db_host = $value;
	}

	public function setDatabasePort($value)
	{
		$this->db_port = $value;
	}

	public function setDatabaseName($value)
	{
		$this->db_name = $value;
	}

	public function setDatabaseUsername($value)
	{
		$this->db_username = $value;
	}

	public function setDatabasePassword($value)
	{
		$this->db_password = $value;
	}

	public function setDatabaseFile($value)
	{
		$this->db_file = $value;
	}

	public function beginTransaction()
	{
		if ($this->isConnected() || $this->connect())
			return parent::beginTransaction();
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute "beginTransaction()"');
	}

	public function isConnected()
	{
		return $this->connected;
	}

	public function setConnected($value)
	{
		$this->connected = $value;
	}

	public function connect()
	{
		return false;
	}

	public function commit()
	{
		if ($this->isConnected() || $this->connect())
			return parent::commit();
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute "commit()"');
	}

	public function errorCode()
	{
		if ($this->isConnected() || $this->connect())
			return parent::errorCode();
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute "errorCode()"');
	}

	public function exec($statement)
	{
		if ($this->isConnected() || $this->connect())
			return parent::exec($statement);
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute exec($statement)');
	}

	public function getAttribute($attribute)
	{
		if ($this->isConnected() || $this->connect())
			return parent::getAttribute($attribute);
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute getAttribute($attribute)');
	}

	public function inTransaction()
	{
		if ($this->isConnected() || $this->connect())
			return parent::inTransaction();
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute inTransaction()');
	}

	public function lastInsertId($name = NULL)
	{
		if ($this->isConnected() || $this->connect())
			return parent::lastInsertId($name);
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute ');
	}

	public function prepare($statement, $driver_options = array())
	{
		if ($this->isConnected() || $this->connect())
			return parent::prepare($statement, $driver_options);
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute lastInsertId($name)');
	}

	public function query($statement)
	{
		$this->queriesCount++;
		// BETA TESTS - uncomment line below to print all queries on website before execution
		//echo'<br />' . $statement . '<br />';
		if ($this->isConnected() || $this->connect()) {
			$ret = parent::query($statement);
			if ($this->printQueries) {
				$_errorInfo = $this->errorInfo();
				echo '<table>';
				echo '<tr><td>Query: </td><td>' . $statement . '</td></tr>';
				echo '<tr><td>SQLSTATE: </td><td>' . $_errorInfo[0] . '</td></tr>';
				echo '<tr><td>Driver code: </td><td>' . $_errorInfo[1] . '</td></tr>';
				echo '<tr><td>Error message: </td><td>' . $_errorInfo[2] . '</td></tr>';
				echo '</table>';
			}
			return $ret;
		} else
			throw new RuntimeException('Website is not connected to database. Cannot execute query($statement)');
	}

	public function errorInfo()
	{
		if ($this->isConnected() || $this->connect())
			return parent::errorInfo();
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute errorInfo()');
	}

	public function quote($string, $parameter_type = PDO::PARAM_STR)
	{
		if ($this->isConnected() || $this->connect())
			return parent::quote($string, $parameter_type);
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute quote($string, $parameter_type)');
	}

	public function rollBack()
	{
		if ($this->isConnected() || $this->connect())
			return parent::rollBack();
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute rollBack()');
	}

	public function setAttribute($attribute, $value)
	{
		if ($this->isConnected() || $this->connect())
			return parent::setAttribute($attribute, $value);
		else
			throw new RuntimeException('Website is not connected to database. Cannot execute setAttribute($attribute, $value)');
	}

	public function setConnectionError($string)
	{
		$this->connectionError = $string;
	}
}
