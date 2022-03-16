<?php

header('Content-Type: text/html');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 600');

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
    //echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	die("Something went wrong. Please contact support. Error code: 00x7s");
    exit;
}


/****************************************************/


// $req_dump = print_r($_REQUEST, true);
// $fp = file_put_contents('success_request.log', $req_dump, FILE_APPEND);

if(isset($_POST['status']) && $_POST['status'] == 'SUCCESS') {
	$refId = $_POST['refId'];
	$invoice_no = $_POST['invoice_no']; //invoice id (kheti's order ID)
	$amount = $_POST['amount'];
	$net_amount = $_POST['net_amount'];
	
	//fetch associated order and get transaction amount
	$stmt = $db->prepare("SELECT * FROM oc_order WHERE order_id=:order_id");
	$stmt->execute(['order_id' => $invoice_no]); 
	$order = $stmt->fetch();
	
	if(!$order) {
		//die("Something went wrong. Please contact support.");
		//header("Location: https://khetifood.com/index.php?route=checkout/failure");
		echo "https://khetifood.com/index.php?route=checkout/failure";
	}
	
	$txnAmount = $order['total'];
	
	if($txnAmount != $amount) {
		//die("Something went wrong. Please contact support.");
		//header("Location: https://khetifood.com/index.php?route=checkout/failure");
		echo "https://khetifood.com/index.php?route=checkout/failure";
	}
	
	
	//validate transaction
	//$host = "https://test.cellpay.com.np/transaction_check/checkTransactionStatus"; //sandbox
	$host = "https://web.cellpay.com.np/transaction_check/checkTransactionStatus"; //live
	$payload = [
					'cellpay_id' => $refId	
			   ];
	//die(json_encode($payload, JSON_UNESCAPED_SLASHES));
	set_time_limit(0);
	$ch = curl_init($host);
	curl_setopt($ch, CURLOPT_USERPWD, 'khetifood' . ":" . 'Qq63-pgnFR_Ak465');
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_POST, 1);
	// Attach encoded JSON string to the POST fields
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_SLASHES ));
	// Set the content type to application/json
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	// Return response instead of outputting
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	// Execute the POST request
	$result = curl_exec($ch);

	//echo "<pre>";
	
	if(curl_errno($ch)){
		//echo 'Request Error:' . curl_error($ch);die;
		//header("Location: https://khetifood.com/index.php?route=checkout/failure");
		echo "https://khetifood.com/index.php?route=checkout/failure";
	}

	$result = json_decode($result, true);
	// echo $refId;
	//print_r(json_encode($result));
	//die;
	if(isset($result['status']) && $result['status'] != "true" && $result['payload']['transactionData']['status'] != "Success"){
		//header("Location: https://khetifood.com/index.php?route=checkout/failure");
		echo "https://khetifood.com/index.php?route=checkout/failure";
	}
	elseif(isset($result['status']) && $result['status'] == "true" && $result['payload']['transactionData']['status'] == "Success") {
		//update order status
		$host = "https://store.kheti.farm/index.php?route=extension/payment/cellpay/callback";
		$payload = [
					'ap_securitycode' => 'R@mKr!5hn@',	
					'ap_itemcode' => $invoice_no,	
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
		
		curl_close($ch);
		
		//header("Location: https://khetifood.com/index.php?route=checkout/success");
		echo "https://khetifood.com/index.php?route=checkout/success";
	}
	
	
	//temporary (controlled environment)
	/*
	//update order status
	$host = "https://store.kheti.farm/index.php?route=extension/payment/cellpay/callback";
	$payload = [
				'ap_securitycode' => 'R@mKr!5hn@',	
				'ap_itemcode' => $invoice_no,	
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
	
	curl_close($ch);
	
	header("Location: https://khetifood.com/index.php?route=checkout/success");
	*/
}
else {
	//header("Location: https://khetifood.com/index.php?route=checkout/failure");
	echo "https://khetifood.com/index.php?route=checkout/failure";
}
?>