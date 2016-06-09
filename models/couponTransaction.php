<?php
class couponTransaction {
	private $id;
	public $userID;
	public $couponID;
	public $transactionOn;

	public function apply($data, $user, $auth) {
		if (!is_null($user) && !is_null($auth)) {
			$result = User::authenticate($user, $auth);
			if ($result) return $result;
			if (array_key_exists('userID', $data) && $data['userID'] != $user) return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'userID did not match authenticated user'));
		}
		if (!array_key_exists('couponCode', $data)) {
			return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'couponCode must be set'));
		}
		$handle = new Database();
		if (array_key_exists('userID', $data)) {
			$result = $handle->select("SELECT * FROM User WHERE id = ".$data['userID']);
			if (!$result) return array('code' => 404, 'data' => array('msg' => 'User Not Found'));
		}
		$coupon = $handle->select("SELECT * FROM Coupon WHERE couponCode = '".$data['couponCode']."'");
		if (!$coupon) return array('code' => 404, 'data' => array('msg' => 'Coupon Not Found'));
		if ($coupon['couponType'] == 'single-use') {
			$transaction = $handle->select("SELECT * FROM CouponTransaction WHERE couponID = ".$coupon['id']);
			if ($transaction) return array('code' => 403, 'data' => array('msg' => 'Forbidden', 'details' => 'Coupon cannot be redeemed anymore'));
		}
		else if ($coupon['couponType'] == 'single-use-per-user') {
			if (is_null($user)) return array('code' => 403, 'data' => array('msg' => 'Forbidden', 'details' => 'Authentication required'));
			if (!array_key_exists('userID', $data)) return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'UserID must be set for single-use-per-user'));
			if (is_null($user)) return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'User authentication required'));
			$transaction = $handle->select("SELECT * FROM CouponTransaction WHERE couponID = ".$coupon['id']. " and userID = ".$user);
			if ($transaction) return array('code' => 403, 'data' => array('msg' => 'Forbidden', 'details' => 'User has redeemed the coupon'));
		}
		else if ($coupon['couponType'] == 'multi-use') {
			if ($coupon['redemptionsLeft'] == 0) return array('code' => 403, 'data' => array('msg' => 'Forbidden', 'details' => 'Coupon cannot be redeemed anymore'));
		}
		if (!is_null($coupon['validUpto']) && date('Y-m-d H:i:s') > $coupon['validUpto']) {
			return array('code' => 403, 'data' => array('msg' => 'Forbidden', 'details' => 'Coupon not valid anymore'));
		}
		$userID = array_key_exists('userID', $data) ? $data['userID'] : 0;
		$userID = $user ? $user : 0;
		$handle->doQuery("INSERT INTO CouponTransaction (userID, couponID, transactionOn) VALUES ($userID, ".$coupon['id'].", NOW())");
		if ($coupon['couponType'] == "multi-use") $handle->doQuery("UPDATE coupon SET redemptionsLeft = ".($coupon['redemptionsLeft'] - 1). ", updatedOn = NOW() WHERE id = ".$coupon['id']);
		$handle->close();
		return array('code' => 200, 'data' => array());
	}

	private function setID($id) {
		$this->id = $id;
	}

	public function getID() {
		return $this->id;
	}
}

?>