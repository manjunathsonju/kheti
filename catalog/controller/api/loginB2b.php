<?php
class ControllerApiLoginB2b extends Controller {
	public function index() {
		$this->load->language('api/login');

		$json = array();

        $this->load->model('account/api');
        //login for b2b

        if(!($this->customer->login($this->request->post['email'], $this->request->post['password']))){
        
                $json['error']= 'Login Failed';
        }

        $telephone= $customer_id_email['telephone'];
        // login

		// Login with API Key
		if(isset($this->request->post['username'])) {
			$api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['key']);
		} else {
			$api_info = $this->model_account_api->login('Default', $this->request->post['key']);
		}

		if ($api_info) {
			// Check if IP is allowed
			$ip_data = array();
	
			$results = $this->model_account_api->getApiIps($api_info['api_id']);
	
			foreach ($results as $result) {
				$ip_data[] = trim($result['ip']);
			}
	
			if (!in_array($this->request->server['REMOTE_ADDR'], $ip_data)) {
				// $json['error']['ip'] = sprintf($this->language->get('error_ip'), $this->request->server['REMOTE_ADDR']);
			}				
				
			if (!$json) {
				$json['success'] = $this->language->get('text_success');
				
				$session = new Session($this->config->get('session_engine'), $this->registry);
				
				$session->start();
				
				$this->model_account_api->addApiSession($api_info['api_id'], $session->getId(), '110.44.121.53');
				
				$session->data['api_id'] = $api_info['api_id'];
				
				// Create Token
                $json['api_token'] = $session->getId();
                
                // send customer information
                $this->load->model('account/customer');
                $customer_id_email = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
                $json['customer_id'] = $customer_id_email['customer_id'];
                $json['customer_group_id'] = $customer_id_email['customer_group_id'];
                $json['firstname'] = $customer_id_email['firstname'];
                $json['lastname'] = $customer_id_email['lastname'];
                $json['email'] = $customer_id_email['email'];
                $json['telephone'] = $customer_id_email['telephone'];
				$noo1=$this->db->query("SELECT logo FROM b2b_logo WHERE customer_id='".$customer_id_email['customer_id']."'");
     
                $json['logo']= $noo1->row['logo'];  
				$loc=$this->db->query("SELECT location FROM `customer_locations` WHERE customer_id='".$customer_id_email['customer_id']."'");
				if($loc->num_rows=='0'){
					$json['location']= '1';  

				}else{
					$json['location']= $loc->row['location'];  

				}
                // send customer info

			} else {
				$json['error']['key'] = $this->language->get('error_key');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function getAddress() {
		$json['error']= 'Already Registered through this mobile number';
		$this->response->addHeader('Content-Type: application/json');
		$this->response->addHeader('Access-Control-Allow-Origin: *');
		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
		$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
		$this->response->addHeader('Access-Control-Max-Age: 600');
		$this->response->setOutput(json_encode($json));
		
	}
	public function setAddress() {
		
	}
}
