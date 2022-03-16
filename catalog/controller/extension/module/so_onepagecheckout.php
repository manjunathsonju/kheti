<?php
class ControllerExtensionModuleSoOnepageCheckout extends Controller {
	public function index() {
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('so_onepagecheckout');

		$this->load->language('extension/module/so_onepagecheckout');
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['billing_address'] = $this->language->get('billing_address');
		$data['delivery_address'] = $this->language->get('delivery_address');
		
		if(isset($this->request->get['route']))
		{
			$this->session->data['route']=$this->request->get['route'];
		}
		​


		// Shipping Methods
		$method_data = array();

		$this->load->model('extension/extension');

		$results = $this->model_extension_extension->getExtensions('shipping');

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('extension/shipping/' . $result['code']);

				$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

				if ($quote) {
					$method_data[$result['code']] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
		}

		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);

		$this->session->data['shipping_methods'] = $method_data;
		//send user location data
		$user_location = 'ktm'; //default location
		if(isset($_COOKIE['location_cookie'])){ 
			//Decrypt cookie variable value
			$data['user_location'] = $this->decryptCookie($_COOKIE['location_cookie']);
		}
		var_dump($data['user_location'] );
		
		
		if(VERSION >= '2.2.0.0'){
        	return $this->load->view('extension/module/so_onepagecheckout', $data);
        }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/so_onepagecheckout.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/so_onepagecheckout.tpl', $data);
        } else {
            return $this->load->view('default/template/module/so_onepagecheckout.tpl', $data);
        }
	}
	// Decrypt cookie
    public function decryptCookie( $value ) {
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
}