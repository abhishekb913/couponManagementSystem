<?php
class Database {
	private static $con;
	function __construct() {
		if ($con == NULL) {
			$con = mysqli_connect(Config::HOST_SERVER, Config::USER_NAME, Config::PASSWORD, Config::DATABASE_NAME);
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
				die();
			}
		}
	}

	public function doQuery($query) {
		return mysqli_query(self::$con, $query);
	}

	public function close() {
		return mysqli_close(self::$con);
	}
}

?>