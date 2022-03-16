<?php
//ini_set('display_errors', '1');

/***********************************/

//database credentials
define('DBHOST','localhost'); //HOSTNAME
define('DBUSER','dvexcellus'); //DATABASE USERNAME
define('DBPASS','dvexcElluS123ktm'); //DATABASE PASSWORD
define('DBNAME','store_kheti_farm_opencart'); //DATABASE NAME

//database connection
try {
	//create PDO connection 
	$db = new PDO("mysql:host=".DBHOST.";port=3306;dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} 
catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}


/****************************************************/

include('myHash.php');

$req_dump = print_r($_REQUEST, true);
$fp = file_put_contents('success_request.log', $req_dump, FILE_APPEND);

if(isset($_GET['TXNID'])) {
	//transaction id (kheti's order ID)
	$txnId = $_GET['TXNID'];
	//fetch associated order and get transaction amount
	$stmt = $db->prepare("SELECT * FROM oc_order WHERE order_id=:order_id");
	$stmt->execute(['order_id' => $txnId]); 
	$order = $stmt->fetch();
	if(!$order) {
		die("Something went wrong. Please contact support.");
	}
	
	$txnAmount = floor($order['total'] * 100);
	
	//validate transaction
	
	$string = "MERCHANTID=337,APPID=MER-337-APP-1,REFERENCEID={$txnId},TXNAMT={$txnAmount}";
	//die($string);
	$token = generateHash($string);	
	$host = "https://login.connectips.com:7443/connectipswebws/api/creditor/validatetxn";
	$payload = [
					'merchantId' => '337',	
					'appId' => 'MER-337-APP-1',	
					'txnAmt' => $txnAmount,	
					'referenceId' => $txnId,	
					'token' => $token,	
				   ];
	//die(json_encode($payload, JSON_UNESCAPED_SLASHES));
	$ch = curl_init($host);
	curl_setopt($ch, CURLOPT_USERPWD, 'MER-337-APP-1' . ":" . 'Abcd@123');
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_POST, 1);
	// Attach encoded JSON string to the POST fields
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_SLASHES ));
	// Set the content type to application/json
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	// Return response instead of outputting
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	// Execute the POST request
	$response = curl_exec($ch);
	if(!$response) {
		die("Something went wrong. Please contact support.");
	}
	
	$result = json_decode($response, true);
	
	if(isset($result['status']) && $result['status'] == "FAILED"){
		header("Location: https://khetifood.com/index.php?route=checkout/failure");
	}
	
	//validate transaction parameters
	if($result['txnAmt'] == $txnAmount && $result['status'] == "SUCCESS") {
		$host = "https://store.kheti.farm/index.php?route=extension/payment/payza/callback";
		$payload = [
					'ap_securitycode' => 'Ld68%N@wkr5a',	
					'ap_itemcode' => $txnId,	
				   ];
		//die(json_encode($payload, JSON_UNESCAPED_SLASHES));
		$ch = curl_init($host);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		// Attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// Execute the POST request
		$response = curl_exec($ch);
		
		header("Location: https://khetifood.com/index.php?route=checkout/success");
	}
	else {
		print_r($result);
		die("Something went wrong. Please contact support.");
	}
	
	curl_close($ch);
}
else {
	echo "Something went wrong. Please contact support.";
}
?>