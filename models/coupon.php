<?php
class Coupon {
	private $id;
	public $couponCode;
	public $couponType;
	public $redemptionsLeft;
	public $validUpto;
	public $createdOn;
	public $updatedOn;

	// Create coupon
	public function create($data) {
		if (is_array($data)) {
			// id, createdOn, updatedOn cannot be set manually. Although couponCode can be set manually
			if (!array_key_exists('id', $data) && !array_key_exists('createdOn', $data) && !array_key_exists('updatedOn', $data)) {
				// couponType is mandatory
				if (!array_key_exists('couponType', $data)) {
					return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'coupon type not mentioned'));
				}
				$handle = new Database;
				// generating couponCode
				if (!array_key_exists('couponCode', $data)) {
					$flag = '123456';
					$code = '';
					while ($flag != NULL) {
						$code = Misc::generateCouponCode();
						$flag = $handle->select("SELECT * FROM Coupon WHERE couponCode = '".$code."'");
					}
					$data['couponCode'] = $code;
					$this->setCouponCode($code);
				}
				else {
					$flag = $handle->select("SELECT * FROM Coupon WHERE couponCode = '".$data['couponCode']."'");
					if (!$flag) return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'Coupon already exists.'));
				}
				// redemptionsLeft must exist for multi-use and must not exist otherwise
				if ( ($data['couponType'] == 'multi-use' && !array_key_exists('redemptionsLeft', $data)) || (array_key_exists('redemptionsLeft', $data) && $data['couponType'] != 'multi-use')) {
					return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'multi-use and redemptionsLeft would go together'));
				}
				$keys = '';
				$values = '';
				foreach ($data as $key => $value) {
					$keys .= $key.",";
					if (is_string($value)) $values .= '"'. $value . '",';
					else $values .= $value . ",";
				}
				$keys = rtrim($keys, ",");
				$values = rtrim($values, ",");
				$handle->doQuery("INSERT INTO Coupon (".$keys." , createdOn) values (".$values.",NOW())");
				$result = $handle->select("SELECT MAX(id) as m FROM Coupon");
				$this->setID(intval($result['m']));
				$handle->close();
				return array('code' => 200, 'data' => array('couponID' => $this->getID(), 'couponCode' => $this->getCouponCode()));
			}
			else {
				return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'Cannot set coupon id, createdOn, updatedOn'));
			}
		}
		else {
			return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'Invalid data'));
		}
	}

	// Update coupon
	public function update ($id, $data) {
		if (is_array($data)) {
			// id, createdOn, updatedOn cannot be updated manually. Although couponCode can be set manually
			if (!array_key_exists('id', $data) && !array_key_exists('createdOn', $data) && !array_key_exists('updatedOn', $data)) {
				$handle = new Database;
				$existingRow = $handle->select("SELECT * FROM Coupon WHERE id = ".$id);
				if (!$existingRow) {
					return array('code' => 404, 'data' => array('msg' => 'Coupon Not Found'));
				}
				else {
					// taking care of multi-use coupon while updation.

					$type = array_key_exists('couponType', $data) ? $data['couponType']: 'none';
					$red = array_key_exists('redemptionsLeft', $data) ? $data['redemptionsLeft']: -1;
					// if updation to multi-use but redemptionsLeft not set
					
					if ($existingRow['couponType'] != 'multi-use' && $type == 'multi-use' && $red == -1) {
						return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'multi use must have redemptionsLeft'));
					} // if updation to non multi-use from non multi-use but redemptionsLeft is set
					else if ($existingRow['couponType'] != 'multi-use' && $type != 'multi-use' && $red != -1) {
						return array('code' => 403, 'data' => array('msg' => 'Forbidden', 'details' => 'Only multi use can have redemptionsLeft'));
					} // if updation to non multi-use from multi-use but redemptionsLeft is set
					else if ($existingRow['couponType'] == 'multi-use' && $red != -1 && $type != 'none' && $type != 'multi-use') {
						return array('code' => 403, 'data' => array('msg' => 'Forbidden', 'details' => 'Only multi use can have redemptionsLeft'));
					}// if updation to non multi-use. update redemptionsLeft to NULL
					else if ($existingRow['couponType'] == 'multi-use' && $type != 'multi-use' && $type != 'none') {
						$data['redemptionsLeft'] = "NULL";
					}
				}
				$str = '';
				foreach ($data as $key => $value) {
					if ($key == 'redemptionsLeft') {
						$str .= $key . "=";
						$str .= $value . ",";
					}
					else {
						$str .= $key . "=";
						if (is_string($value)) $str .= '"'. $value . '",';
						else $str .= $value . ",";
					}
				}
				$str = rtrim($str, ",");
				$handle->doQuery("UPDATE Coupon SET ".$str.", updatedOn = NOW() where id = ".$id);
				$handle->close();
				return array('code' => 200, 'data' => array());
			}
			else {
				return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'Cannot set coupon id, createdOn, updatedOn'));
			}
		}
		else {
			return array('code' => 400, 'data' => array('msg' => 'Bad request', 'details' => 'Invalid data'));
		}
	}

	private function setID($id) {
		$this->id = $id;
	}

	public function getID() {
		return $this->id;
	}

	private function setCouponCode($couponCode) {
		$this->couponCode = $couponCode;
	}

	public function getCouponCode() {
		return $this->couponCode;
	}

}
?>