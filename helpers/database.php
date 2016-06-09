<?php
class Database {
	private static $con;
	function __construct() {
		if (self::$con == NULL) {
			self::$con = mysqli_connect(Config::HOST_SERVER, Config::USER_NAME, Config::PASSWORD, Config::DATABASE_NAME);
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
				die();
			}
		}
	}

	public function select($query) {
		$result = mysqli_query(self::$con, $query);
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	}

	public function doQuery($query) {
		$result = mysqli_query(self::$con, $query);
		return;
	}

	public function close() {
		return mysqli_close(self::$con);
	}
}

?>