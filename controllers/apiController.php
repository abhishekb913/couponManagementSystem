<?php

class APIController{

	public static function create($component, $data, $user, $auth) {
		switch ($component) {
			case 'create':
				$coupon = new Coupon();
				return $coupon->create($data);
				break;
			case 'apply':
				$transaction = new couponTransaction();
				return $transaction->apply($data, $user, $auth);
				break;
			default:
				return array('code' => 400, 'data' => array('msg' => 'Bad request'));
				break;
		}
	}

	public static function update($component, $data, $user, $auth) {
		switch ($component) {
			case 'update':
				if (!array_key_exists('couponID', $data)) return array('code' => 400, 'data' => array('msg' => 'Bad request'));
				$id = $data['couponID'];
				unset($data['couponID']);
				$coupon = new Coupon();
				return $coupon->update($id, $data);
				break;
			default:
				return array('code' => 400, 'data' => array('msg' => 'Bad request'));
				break;
		}
	}
}
?>