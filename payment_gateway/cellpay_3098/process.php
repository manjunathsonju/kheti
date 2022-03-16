<?php
require 'vendor/autoload.php';

$req_dump = print_r($_REQUEST, true);
$fp = file_put_contents('payment_log_3099.log', $req_dump, FILE_APPEND);


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

	//order id posted
	if(isset($_POST['ap_amount']) && isset($_POST['ap_orderId'])) {
		$orderId = $_POST['ap_orderId'];
		$amount = $_POST['ap_amount'];
		
		//include cellpay
		require_once("cellpay_header.php");
		require_once("cellpay_footer.php");
	}
	elseif (isset($_GET['orderId']) && isset($_GET['cellPayId'])) {
		//verify transaction
		// $host = "https://test.cellpay.com.np/transaction_check/checkTransactionStatus";
		// $payload = [
						// 'cellpay_id' => $_GET['cellPayId']
				   // ];
		// //die(json_encode($payload, JSON_UNESCAPED_SLASHES));
		// set_time_limit(0);
		// $ch = curl_init($host);
		// curl_setopt($ch, CURLOPT_USERPWD, 'khetifood' . ":" . 'Qq63-pgnFR_Ak465');
		// curl_setopt($ch, CURLOPT_TIMEOUT, 500);
		// curl_setopt($ch, CURLOPT_POST, 1);
		// // Attach encoded JSON string to the POST fields
		// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_SLASHES ));
		// // Set the content type to application/json
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		// // Return response instead of outputting
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		// // Execute the POST request
		// $response = curl_exec($ch);
		
		// echo "<pre>";
		// if(curl_errno($ch)){
			// echo 'Request Error:' . curl_error($ch);
		// }
		
		// $client = new GuzzleHttp\Client();
		// $url = "https://test.cellpay.com.np/transaction_check/checkTransactionStatus";
		
		// $response = $client->post($url, [
					// 'headers' => ['Content-type' => 'application/json'],
					// 'auth' => [
						// 'khetifood', 
						// 'Qq63-pgnFR_Ak465'
					// ],
					// 'json' => [
						// "cellpay_id"=> $_GET['cellPayId'],
					// ], 
				// ]);
		// echo "<pre>";
		// print_r($response);die;
		
		// if(!$response) {
			// die("Something went wrong. Please contact support. Error Code: 00x2");
		// }
		
		// $result = json_decode($response, true);
		
		//verify transaction
		//$host = "https://test.cellpay.com.np/transaction_check/checkTransactionStatus"; //sandbox
		$host = "https://web.cellpay.com.np/transaction_check/checkTransactionStatus"; //live
		$payload = [
						'cellpay_id' => $_GET['cellPayId']
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
			//echo 'Request Error:' . curl_error($ch);
			header("Location: https://khetifood.com/index.php?route=checkout/failure");
		}

		$result = json_decode($result, true);
		//die;
		if(isset($result['status']) && $result['status'] != "true" && $result['payload']['transactionData']['status'] != "Success"
		&& $result['payload']['transactionData']['invoiceNumber'] != $_GET['orderId']){
			header("Location: https://khetifood.com/index.php?route=checkout/failure");
		}
		elseif(isset($result['status']) && $result['status'] == "true" && $result['payload']['transactionData']['status'] == "Success"
		&& $result['payload']['transactionData']['invoiceNumber'] == $_GET['orderId']) {
			//verify that this cellpayid is not previously used
			$cellpayIdTxt = $_GET['cellPayId']."||";

			$all_ids = explode("||", file_get_contents("cellpay_ids_3098.txt"));

			if (!in_array($_GET['cellPayId'], $all_ids) ) {
				//store cellpayid
				file_put_contents("cellpay_ids_3098.txt", $cellpayIdTxt, FILE_APPEND);
				
				echo $_GET['orderId'];die;
				//update order status
				$host = "http://store.kheti.farm/index.php?route=extension/payment/cellpay/callback";
				$payload = [
							'ap_securitycode' => 'R@mKr!5hn@',	
							'ap_itemcode' => $_GET['orderId'],	
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
				
				
				curl_close($ch);
			}
			else {
				header("Location: https://khetifood.com/index.php?route=checkout/failure");
				//die("Something went wrong. Please contact support. Error Code: 00x3");
			}
		}
	}
	else {
		header("Location: https://khetifood.com/index.php?route=checkout/failure");	
	}
?>