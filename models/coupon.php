<?php
class Coupon {
	private $id;
	public $couponCode;
	public $couponType;
	public $redemptionsLeft;
	public $validUpto;
	public $createdOn;
	public $updatedOn;

	public function create($data) {
		if (is_array($data)) {
			if (!array_key_exists('id', $data) && !array_key_exists('createdOn', $data) && !array_key_exists('updatedOn', $data)) {
				$handle = new Database;
				if (!array_key_exists('couponCode', $data)) {
					$flag = '';
					$code = '';
					while ($flag != NULL) {
						$code = Misc::generateCouponCode();
						$flag = $handle->doQuery("SELECT * FROM Coupon WHERE couponCode = '".$code."'");
					}
					$data['couponCode'] = $code;
					$this->setCouponCode($code);
				}
				if (array_key_exists('redemptionsLeft', $data) && (!array_key_exists('couponType', $data) || $data['couponType'] != 'multi-use')) {
					// error bad request
				}
				$keys = '';
				$values = '';
				foreach ($data as $key => $value) {
					$keys .= $key.",";
					if (is_string($value)) $values .= '\"'. $value . '\",';
					else $value .= $value . ",";
				}
				$keys = rtrim($keys, ",");
				$values = rtrim($values, ",");
				$handle->doQuery("INSERT INTO Coupon (".$keys." , createdOn) values (".$values."), NOW()");
				$result = $handle->doQuery("SELECT MAX(id) as m FROM Coupon");
				$this->setID($result[0]['m']);
				$handle->close();
			}
			else {
				// error bad request
			}
		}
		else {
			// error bad request
		}
	}

	public function update ($id, $data) {
		if (is_array($data)) {
			if (!array_key_exists('id', $data) && !array_key_exists('createdOn', $data) && !array_key_exists('updatedOn', $data)) {
				$handle = new Database;
				$str = '';
				foreach ($data as $key => $value) {
					$str .= $key . "=";
					if (is_string($value)) $str .= '\"'. $value . '\",';
					else $str .= $value . ",";
				}
				$str = rtrim($str, ",");
				$handle->doQuery("UPDATE Coupon SET ".$str.", updatedOn = NOW() where id = ".$id);
				$handle->close();
			}
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