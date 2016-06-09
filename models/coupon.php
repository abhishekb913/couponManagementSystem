<?php
class Coupon {
	private $id;
	public $couponCode;
	public $couponType;
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
				if (array_key_exists('couponType', $data)) {
					if ($data['couponType'] == 'multi-use') {
						if (array_key_exists('num', $data)) {
							couponTransaction::createLog($this->getID, $data['num']);
						}
						else {
							// error. error bad request
						}
					}
				}
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

}


?>