<?php 
    ini_set('display_errors', '1');
    date_default_timezone_set("Asia/Kathmandu");	
    function generateHash($string) {
        // Try to locate certificate file
        if (!$cert_store = file_get_contents("KHETI.pfx")) {
        	echo "Error: Unable to read the cert file\n";
        	exit;
        }
        
        // Try to read certificate file
        if (openssl_pkcs12_read($cert_store, $cert_info, "123")) {
        	if($private_key = openssl_pkey_get_private($cert_info['pkey'])){
        		$array = openssl_pkey_get_details($private_key);
        	    // print_r($array);
        	}
        } else {
        	echo "Error: Unable to read the cert store.\n";
        	exit;
        }
        $hash = "";
        if(openssl_sign($string, $signature , $private_key, "sha256WithRSAEncryption")){
	        $hash = base64_encode($signature);
	        openssl_free_key($private_key);
        } else {
            echo "Error: Unable openssl_sign";
            exit;
        }    
        return $hash;
    }
    
    $string = "MERCHANTID=380,APPID=MER-380-APP-1,APPNAME=Kheti,TXNID=3098,TXNDATE=23-01-2020,TXNCRNCY=NPR,TXNAMT=1000,REFERENCEID=REF-3098,REMARKS=RMKS-3098,PARTICULARS=PART-3098,TOKEN=TOKEN";

    $token = generateHash($string);

    //echo $token;