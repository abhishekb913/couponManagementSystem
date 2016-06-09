<?php
class User {
	private $id;
	public $auth;
	public $name;
	public $email;
	public $createdOn;
	public $updatedOn;


	private function setID($id) {
		$this->id = $id;
	}

	public function getID() {
		return $this->id;
	}
}

?>