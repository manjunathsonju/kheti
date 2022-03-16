<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));
		
		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['scripts'] = $this->document->getScripts('footer');
		
		//send manufacturers (farmer markets)
		$this->load->model('catalog/manufacturer');
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

		//send store id
		$data['store_id'] = $this->config->get('config_store_id');
		
		//logged in user's name
		$data['customer_first_name'] = $this->customer->getFirstName();
		//for testimonial 
		$this->load->language('extension/module/testimonials');
        static $module = 0;	
		$this->load->model('extension/module/testimonials');

		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
		$this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
        
			$result=$this->model_extension_module_testimonials->getTestimonials();
			foreach($result as $results)
            {
               $data['testimonials'][]=array(
				'author'            => $results['author'],
				'image'     	   => $results['image'],
				'thumb'     	   => $this->model_tool_image->resize($results['image'],200 ,200),
				'description'      => $results['description'],
				'status'       => $results['status'],
				'sort_order' => $results['sort_order'],
				'designation'     => $results['designation']
			);
				}
			$data['module_testimonials_status'] = '1';
			$data['module_testimonials_aheight'] = "200";
			$data['module_testimonials_awidth'] = "200";
			$data['module_testimonials_heading'] = "What our consumers say";
			$data['module_testimonials_bgclr'] =  "#0db04b";
			$data['module_testimonials_fontclr'] =  "white";
			$data['module'] = $module++;
			
		//end testimonial


		//send user location data
		$user_location = 'ktm'; //default location
		if(isset($_COOKIE['location_cookie'])){ 
			//Decrypt cookie variable value
			$data['user_location'] = $this->decryptCookie($_COOKIE['location_cookie']);
		}
		$data['customer_id'] = $this->customer->getId();
		
		return $this->load->view('common/footer', $data);
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
