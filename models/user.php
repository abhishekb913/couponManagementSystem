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
		$result = $handle->select("SELECT * FROM User WHERE id = ".$id);
		if (!$result) return array('code' => 404, 'data' => array('msg' => 'User Not Found'));
		unset($result);
		$result = $handle->select("SELECT * FROM User WHERE id = ".$id." and auth = '".$auth."'");
		if ($result) return false;
		else {
			return array('code' => 401, 'data' => array('msg' => 'Not Authorized'));
		}
	}

	private function setID($id) {
		$this->id = $id;
	}

	public function getID() {
		return $this->id;
	}
}

?>