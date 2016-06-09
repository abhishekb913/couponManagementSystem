<?php
class couponTransaction {
	private $id;
	public $userID;
	public $couponID;
	public $transactionOn;

	public function apply() {
		
	}

	private function setID($id) {
		$this->id = $id;
	}

	public function getID() {
		return $this->id;
	}
}

?>