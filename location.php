<?php

// Encrypt cookie
function encryptCookie( $value ) {
	$key = 'pushparaj_encryption_32n4kj2b342b3j4223b7sbd5g4jvg35f';
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($value, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
	$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
	return( $ciphertext );
}

// Decrypt cookie
function decryptCookie( $value ) {
	$key = 'pushparaj_encryption_32n4kj2b342b3j4223b7sbd5g4jvg35f';
	$c = base64_decode($value);
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len=32);
	$ciphertext_raw = substr($c, $ivlen+$sha2len);
	$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
	if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
	{
		return( $original_plaintext );
	}	
}

//check cookie
if( isset($_COOKIE['location_cookie'] )){ 
	//Decrypt cookie variable value
	$location = decryptCookie($_COOKIE['location_cookie']);
}
else {
	// Set cookie variables
	$days = 100;
	$value = encryptCookie($_GET['location']);
	setcookie ("location_cookie",$value,time()+ ($days * 86400), "/");
}

// Set cookie variables
$days = 100;
$value = encryptCookie($_GET['location']);
setcookie ("location_cookie",$value,time()+ ($days * 86400), "/");
	
echo json_encode(array($value));