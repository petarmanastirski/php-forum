<?php

require_once 'config.php';  //Config file

class Database
{
	private $conn;

	private function __construct()
	{
		$this->conn = mysqli_connect(host, username, password, db_name);
	}

	/**
	 * SINGLETON
	 * @return Database
	 */
	public static function getDatabase() {
		static $db = null;
		if ($db === null) {
			$db = new self;
		}
		return $db;
	}

	/**
	 * @return PDO
	 */
	public function getConnection()
	{
		return $this->conn;
	}


	function errorHandler($errnumb, $errstr)
	{
		switch ($errnumb) {
		    case E_WARNING:
				echo '<b>There has been an error with the MySQL database connection. '.
				  	 'Please, make sure the config file is OK.</b>';
				die();
		    default:
		        return false;
		}
	}


	function getThreads()
	{
		$query = "SELECT p.title, date_format(p.date,'%m/%d/%Y %T') as date, p.permalink, p.author ".
				 "FROM posts p ".
				 "WHERE permalink_parent IS NULL";
		return mysqli_fetch_all(mysqli_query($this->conn, $query), MYSQLI_ASSOC);
	}


	function getContentThread($permalink)
	{
		 $query = "SELECT p.title, date_format(p.date,'%m/%d/%Y %T') as date, p.author, p.content, p.permalink_parent ".
				  "FROM posts p ".
				  "WHERE permalink = '$permalink' OR permalink_parent = '$permalink'".
				  "ORDER BY p.date ASC"; // we get the initial post first

		return mysqli_fetch_all(mysqli_query($this->conn, $query), MYSQLI_ASSOC);
	}


	private function isValidPost($input, $permalink, $newThread)
	{
 		if ($newThread) {
			return !empty($input['title'])
				&& !empty($input['content'])
				&& !empty($input['author'])
				&& !empty($permalink);
		} else {
			return !empty($input['content'])
				&& !empty($input['author'])
				&& !empty($permalink);
		}
	}

	function savePost($input, $permalink, $newThread)
	{

		$postOK = $this->isValidPost($input, $permalink, $newThread);

		if (!$postOK) {
			return -1;
		}

		if ($newThread) {
			$query = 'INSERT INTO posts '
					. '(title, content, author, permalink, permalink_parent) VALUES'
					. '("%s","%s","%s","%s", NULL)';

			$query = sprintf($query, $input["title"],$input["content"], $input["author"],$permalink);
		} else {
			$query = 'INSERT INTO posts '
					. '(content, permalink_parent, author) VALUES'
					. '("%s","%s","%s")';

			$query = sprintf($query, $input['content'], $permalink, $input['author']);
		}

		if (mysqli_query($this->conn, $query)) {
			return 1;   // Ok
		} elseif (mysql_errno() == 1062) {
			return -2;  // Duplicated title error
		} else {
			return -3;  // DB error
		}
	}

}
?>