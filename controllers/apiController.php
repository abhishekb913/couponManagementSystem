<?php

class APIController{

	public static function create($component, $data) {
		switch ($component) {
			case 'create':
				$coupon = new Coupon();
				return $coupon->create($data);
				break;
			case 'apply':
				
				break;
			default:
				return array('code' => 400, 'data' => array('msg' => 'Bad request'));
				break;
		}
	}

	public static function update($component, $data) {
		switch ($component) {
			case 'update':
				if (!array_key_exists('id', $data)) return array('code' => 400, 'data' => array('msg' => 'Bad request'));
				$id = $data['id'];
				unset($data['id']);
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