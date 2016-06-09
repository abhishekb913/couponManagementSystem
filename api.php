<?php

require_once dirname(__FILE__) . "/controllers/apiController.php";
require_once dirname(__FILE__) . "/helpers/database.php";
require_once dirname(__FILE__) . "/config.php";
require_once dirname(__FILE__) . "/models/coupon.php";
require_once dirname(__FILE__) . "/helpers/misc.php";

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);
switch ($method) {
	case 'POST':
    	$result = APIController::create($request[1], $input);
    	http_response_code($result['code']);
    	echo json_encode($result['data']);
    	break;
	case 'PUT':
    	$result = APIController::update($request[1], $input);
    	http_response_code($result['code']);
    	echo json_encode($result['data']);
    	break;
	default:
    	http_response_code(400);
    	echo json_encode(array('msg' => 'Bad Request'));
    	break;
}
?>