<?php
class couponTransaction {
	private $id;
	public $couponID;
	public $redemptionsAllowed;
	public $redemptionsLeft;
	public $lastRedemptionAt;

	public static function createLog($couponID, $num) {
		$handle = new Database();
		$handle->doQuery("INSERT INTO MultiUseCouponLog (couponID, redemptionsAllowed, redemptionsLeft, lastRedemptionAt) VALUES ($couponID, $num, $num, NOW())");
	}

	private function setID($id) {
		$this->id = $id;
	}

	public function getID() {
		return $this->id;
	}
}

?>