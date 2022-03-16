<?php
class ControllerApiB2bDashlogin extends Controller {
	public function index() {
		$hash=$this->db->query("SELECT * FROM b2b_users where firstname='".$this->request->get['firstname']."'"); 
		if($hash){
		$id=$hash->row['user_id']; 
		$options=0;
		$ciphering = "AES-128-CTR"; 
		$decryption_iv = '1236667891011121'; 
        $decryption_key = "satanrajiv";
		$decryption=openssl_decrypt ($hash->row['password'], $ciphering,  $decryption_key, $options, $decryption_iv); 
		// var_dump($decryption);
		if($decryption==$this->request->get['password']){
			$token=token(9);
			$this->db->query("UPDATE b2b_users SET token='$token' WHERE user_id='".$id."'");
			$dt=strtotime( now);
            $dtm=(date("Y-m-d H:i:s", $dt));
			$sql="INSERT INTO `b2b_activity_log_dashboard` (activity_type,user,change_id,date) VALUES ('login','".$id."','0','".$dtm."')";

			$this->db->query($sql);


			header("location: https://khetifood.com/index.php?route=b2b/home&id=".$id."&tkn=".$token);
		}else{
			header("location: https://khetifood.com/business");
		}
		}else{
			echo('invalid login');
		}	
	}

	public function adduser() {
		if($this->request->get['pass']=='rajiv'){
		$simple_string = $this->request->get['password'];
		//  cipher method 
		$ciphering = "AES-128-CTR"; 
		// Use OpenSSl Encryption method 
		$iv_length = openssl_cipher_iv_length($ciphering); 
		$options = 0; 
		// Non-NULL Initialization Vector for encryption 
		$encryption_iv = '1236667891011121'; 
		$encryption_key = "satanrajiv"; 
		// Use openssl_encrypt() function to encrypt the data 
		$encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv); 
		$this->db->query("INSERT INTO b2b_users (firstname, lastname, password) values ('".$this->request->get['firstname']."','".$this->request->get['lastname']."','".$encryption."')");
		}
	}
}
