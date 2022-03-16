<?php


class ControllerCheckoutCheckout extends Controller {

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

    public function index() {
		$store_id = $this->config->get('config_store_id');

        // if($this->decryptCookie($_COOKIE['location_cookie'])=='pkr'&&$store_id==1){ //checkout unavailale on pokhara
        //     $this->session->data['error']="Dear Valued Customers, 

        //     We've currently paused the delivery for Pokhara until further notice. We'll be back to serving you soon. 
            
        //     Sorry for the inconvenience caused!   Stay Indoor. Stay Safe";
        //     $this->response->redirect($this->url->link('checkout/cart'));
        // }


        /*
        if ($this->cart->getSubTotal() < '500') {
            //echo "<div class='warning'>Minimum Order total should be Rs.500</div>";
            $this->response->redirect($this->url->link('checkout/cart'));
        }
        */

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            //product location
            $this->load->model('catalog/product');
            $product_info = $this->model_catalog_product->getProduct($product['product_id']);
            $product_location = $product_info['location'];

            //validate location availability
            $user_location = 'ktm'; //default location
            if(isset($_COOKIE['location_cookie'])){
                //Decrypt cookie variable value
                $data['user_location'] = $this->decryptCookie($_COOKIE['location_cookie']);

                $user_location = $this->decryptCookie($_COOKIE['location_cookie']);
            }

            if(!(strpos($product_location, $user_location) !== false)) {
                $location_value = $product_location == 'ktm' ? 'Kathmandu' : ($product_location == 'pkr' ? 'Pokhara' : 'error');
				if($location_value == "error") {
					$this->session->data['error'] = "The product \"{$product['name']}\" has technical issues, please contact support with product id: {$product['product_id']}";
                $this->response->redirect($this->url->link('checkout/cart'));
				}
                $user_location_value = $user_location == 'ktm' ? 'Kathmandu' : 'Pokhara';
                $this->session->data['error'] = "The product \"{$product['name']}\" is available only in {$location_value} but your current location is set to {$user_location_value}";
                $this->response->redirect($this->url->link('checkout/cart'));
            }

            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }
            if($product['minimum'] > $product_total) {
                $this->response->redirect($this->url->link('checkout/cart'));
            }

            if($product['maximum']!='0'&&(int) $product['maximum']<(int) $product['quantity']){
                $this->response->redirect($this->url->link('checkout/cart'));

            }
        }

        $this->load->language('checkout/checkout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        // Required by klarna
        if ($this->config->get('payment_klarna_account') || $this->config->get('payment_klarna_invoice')) {
            $this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_cart'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('checkout/checkout', '', true)
        );

        $data['text_checkout_option'] = sprintf($this->language->get('text_checkout_option'), 1);
        $data['text_checkout_account'] = sprintf($this->language->get('text_checkout_account'), 2);
        $data['text_checkout_payment_address'] = sprintf($this->language->get('text_checkout_payment_address'), 2);
        $data['text_checkout_shipping_address'] = sprintf($this->language->get('text_checkout_shipping_address'), 3);
        $data['text_checkout_shipping_method'] = sprintf($this->language->get('text_checkout_shipping_method'), 4);

        if ($this->cart->hasShipping()) {
            $data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 5);
            $data['text_checkout_confirm'] = sprintf($this->language->get('text_checkout_confirm'), 6);
        } else {
            $data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 3);
            $data['text_checkout_confirm'] = sprintf($this->language->get('text_checkout_confirm'), 4);
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        $data['logged'] = $this->customer->isLogged();

        if (isset($this->session->data['account'])) {
            $data['account'] = $this->session->data['account'];
        } else {
            $data['account'] = '';
        }

        $data['shipping_required'] = $this->cart->hasShipping();

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        //error_log("debug 30 september");
        $checkoutquery= $this->db->query(" SELECT * FROM permission WHERE id=1");
        $data['checkoutpermission']=$checkoutquery->row['status'];
        //var_dump($data['checkoutpermission']);
        // var_dump($data);
        $data['customer_id'] = $this->customer->getId();
        // $data['order_id'] = $this->session->data['order_id'];
        // var_dump($data['order_id']);



        $this->response->setOutput($this->load->view('checkout/checkout', $data));
    }

    public function country() {
        $json = array();

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = array(
                'country_id'        => $country_info['country_id'],
                'name'              => $country_info['name'],
                'iso_code_2'        => $country_info['iso_code_2'],
                'iso_code_3'        => $country_info['iso_code_3'],
                'address_format'    => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status'            => $country_info['status']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function customfield() {
        $json = array();

        $this->load->model('account/custom_field');

        // Customer Group
        if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->get['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

        foreach ($custom_fields as $custom_field) {
            $json[] = array(
                'custom_field_id' => $custom_field['custom_field_id'],
                'required'        => $custom_field['required']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}