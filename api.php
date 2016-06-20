<?php

require_once dirname(__FILE__) . "/controllers/apiController.php";
require_once dirname(__FILE__) . "/helpers/database.php";
require_once dirname(__FILE__) . "/config.php";
require_once dirname(__FILE__) . "/models/coupon.php";
require_once dirname(__FILE__) . "/models/user.php";
require_once dirname(__FILE__) . "/models/couponTransaction.php";
require_once dirname(__FILE__) . "/helpers/misc.php";

$method = $_SERVER['REQUEST_METHOD'];

// request type (create/apply/update) is taken from PATH_INFO
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);

// Storing user and authentication token, in case it is present
$user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
$auth = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

switch ($method) {
	case 'POST':
    	$result = APIController::create($request[1], $input, $user, $auth);
    	http_response_code($result['code']);
    	echo json_encode($result['data']);
    	break;
	case 'PUT':
    	$result = APIController::update($request[1], $input, $user, $auth);
    	http_response_code($result['code']);
    	echo json_encode($result['data']);
    	break;
	default:
    	http_response_code(400);
    	echo json_encode(array('msg' => 'Bad Request'));
    	break;
}
?>