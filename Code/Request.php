<?php
include("config.php"); 
use DAO\WalletDAO;
use Entity\Wallet;
use DAO\TransactionDAO;
use Entity\Transaction;

function getStatusCodeMesage($status)
{
	$codes = Array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => '(Unused)',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
	);

	return (isset($codes[$status])) ? $codes[$status] : '';
}

function sendResponse($status = 200, $body = '', $content_type = 'text/html')
{
	$status_header = 'HTTP/1.1 ' . $status . ' ' . getStatusCodeMesage($status);
	header($status_header);
	header('Content-type: ' . $content_type);
	echo $body;
}

function parseInput()
{
	$data = file_get_contents("php://input");
	if($data == false)
		return array();
	parse_str($data, $result);
	return $result;
}

function CommonValidateWallet($params){
	$name = $params['name'];
	$key = $params['key'];
	$isValid = true;
	if(!isset($name) || strlen($name) < 3 || strlen($name) > 255 || preg_match('/[^a-z0-9]+/i', $name))
		$isValid = false;
	else if(!isset($key) || strlen($key) < 3 || strlen($key) > 255)
		$isValid = false;

	return $isValid;
}

$type = $_GET['type'];

if($type == 'CreateWallet'){
	if(CommonValidateWallet($_POST)){
		$obj = new Wallet();
		$obj->NAME = $_POST['name'];
		$obj->HASH_KEY = md5($_POST['key']);
		$response = (new WalletDAO())->CreateWallet($obj);

		sendResponse($response['statusCode'],'{"data":'.json_encode($response['data']).'}');
	}
	else{
		sendResponse(403,'{"data":'.json_encode($response['data']).'}');
	}
}
else if($type == 'GetWallet'){
	$response = (new WalletDAO())->GetWallet();
	sendResponse($response['statusCode'],'{"data":'.json_encode($response['data']).'}');
}
else if($type == 'DeleteWalletByID'){
	$_DELETE = parseInput();
	$response = (new WalletDAO())->DeleteWalletByID($_DELETE['id']);
	sendResponse($response['statusCode'],'{"data":'.json_encode($response['data']).'}');
}
else if($type == 'DeleteWallet'){
	$_DELETE = parseInput();
	if(CommonValidateWallet($_DELETE)){
		$obj = new Wallet();
		$obj->NAME = $_DELETE['name'];
		$obj->HASH_KEY = md5($_DELETE['key']);
		$response = (new WalletDAO())->DeleteWallet($obj);
		sendResponse($response['statusCode'],'{"data":'.json_encode($response['data']).'}');
	}
	else{
		sendResponse(403,'{"data":'.json_encode($response['data']).'}');
	}	
}
else if($type == 'GetTransactions'){
	$response = (new TransactionDAO())->GetTransactions();
	sendResponse($response['statusCode'],'{"data":'.json_encode($response['data']).'}');
}
else if($type == 'CreateTransaction'){
	$isValid = true;

	$name = $_POST['name'];
	$amount = $_POST['amount'];
	$reference = $_POST['reference'];
	$type = $_POST['type'];
	if(!isset($name) || strlen($name) < 3 || strlen($name) > 255 || preg_match('/[^a-z0-9]+/i', $name))
		$isValid = false;
	else if(!intval($amount) || ($type == "BET" && intval($amount) > 0) || ($type == "WIN" && intval($amount) < 0))
		$isValid = false;
	else if(!str_starts_with($reference, 'TR-') || strlen($reference) < 3 || strlen($reference) > 255)
		$isValid = false;

	if($isValid){
		//get existing wallet info base on name
		$wallet = (new WalletDAO())->GetWalletByName($name);
		if(is_array($wallet) && $wallet['statusCode'] == 200 && intval($wallet['data']->ID) > 0)
		{//found wallet
			//build hash_check base on 'hash_key.name.type.amount.reference.hash_check'
			$hash_check_str = md5($wallet['data']->HASH_KEY . '.' . $name . '.' . $type . '.' . strval($amount) . '.' . $reference . '.' . $_POST['hash_check']);
			
			///TODO: need to consider about this check ???
			///If hash_check doesn’t match MD5(hash_key.name.type.amount.reference.hash_check), code 404 must be sent without any details. 
			///from what I see, origin 'hash_check is come from $_POST that user input, then we md5 encrypt with input by combine itself with other params and hash_key get from wallt table base on 'name'
			///this is create transaction method. So, what should we check here???

			$obj = new Transaction();
			$obj->WALLET_ID = $wallet['data']->ID;
			$obj->NAME = $name;
			$obj->TYPE = $type;
			$obj->AMOUNT = $amount;
			$obj->REFERENCE = $reference;
			$obj->HASH_CHECK = $hash_check_str;
			
			$response = (new TransactionDAO())->CreateTransaction($obj);

			sendResponse($response['statusCode'],'{"data":'.json_encode($response['data']).'}');
		}
		else{
			//name cannot be found
			sendResponse(500,'{"data":null}');
		}
	}
	else{
		sendResponse(403,'{"data":null}');
	}
}
?>