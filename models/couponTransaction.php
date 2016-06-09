<?php
class couponTransaction {
	private $id;
	public $userID;
	public $couponID;
	public $transactionOn;

	public function apply($userID, $auth, $couponID) {
		if (!User::authenticate($userID, $auth)); // Authentication error
		
	}

	private function setID($id) {
		$this->id = $id;
	}

	public function getID() {
		return $this->id;
	}
}

?>