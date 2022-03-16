<?php
class ControllerExtensionPaymentPayza extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        //print_r($order_info);

		$data['action'] = 'https://login.connectips.com:7443/connectipswebgw/loginpage';
        
        //format date
        $txnDate = new DateTime($order_info['date_added']);
        
        //other form params        
        $data['MERCHANTID'] = "337";
        $data['APPID'] = "MER-337-APP-1";
        $data['APPNAME'] = "Kheti";
        $data['TXNID'] = $order_info['order_id'];
        $data['TXNDATE'] = $txnDate->format('d-m-Y');
        $data['TXNCRNCY'] = "NPR";
        $data['TXNAMT'] = floor($order_info['total'] * 100);
        $data['REFERENCEID'] = "REF-" . $order_info['order_id'];
        $data['REMARKS'] = "RMKS-" . $order_info['order_id'];
        $data['PARTICULARS'] = "KHETIFOOD";
		
        $string = "MERCHANTID=337,APPID=MER-337-APP-1,APPNAME=Kheti,TXNID=" . $order_info['order_id'] . ",TXNDATE=" . $txnDate->format('d-m-Y') .",TXNCRNCY=NPR,TXNAMT=" . floor($order_info['total'] * 100) . ",REFERENCEID=" . "REF-" . $order_info['order_id'] .",REMARKS=" . "RMKS-" . $order_info['order_id'] . ",PARTICULARS=KHETIFOOD,TOKEN=TOKEN";
        
		$data['TOKEN'] = $this->generateHash($string);
		
		return $this->load->view('extension/payment/payza', $data);
	}
    
    function generateHash($string) {
        // Try to locate certificate file
        if (!$cert_store = file_get_contents(__DIR__ . "/KHETI.pfx")) {
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
    
	public function callback() {
		if (isset($this->request->post['ap_securitycode']) && ($this->request->post['ap_securitycode'] == $this->config->get('payment_payza_security'))) {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->request->post['ap_itemcode'], $this->config->get('payment_payza_order_status_id'));
		}
	}
}