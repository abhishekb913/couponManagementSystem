<?php
class User {
	private $id;
	public $auth;
	public $name;
	public $email;
	public $createdOn;
	public $updatedOn;

	public static function authenticate($id, $auth) {
		$handle = new Database();
		$result = $handle->select("SELECT * FROM User WHERE id = ".$id." and auth = '".$auth."'");
		if ($result) return true;
		else return false;
	}

	private function setID($id) {
		$this->id = $id;
	}

	public function getID() {
		return $this->id;
	}
}

?>