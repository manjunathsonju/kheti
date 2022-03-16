
<?php
class ControllerApiRegisterB2b extends Controller
{
	public function index()
    {
		$json = array();
		$this->load->model('account/customer');
		$customer_id_email = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		$customer_id_num = $this->model_account_customer->getCustomerByPhone($this->request->post['number']);
        // register
        if($customer_id_email== NULL && $customer_id_num== NULL){
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . '3' . "', store_id = '" . '1' . "', language_id = '" . '1' . "', firstname = '" . $this->request->post['firstname'] . "', lastname = '" . $this->request->post['lastname']  . "', email = '" . $this->request->post['email'] . "', telephone = '" . $this->request->post['number'] . "', custom_field = '" .''. "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($this->request->post['password'])))) . "', newsletter = '" . '1' . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '" . '0' . "', date_added = NOW()");
		

        $json['success_register'] = '1'; 
        // end register

    if($this->customer->login($this->request->post['email'], $this->request->post['password'])){
    
	 $customerdetails = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
	 if($this->request->post['address']){
		$this->db->query("INSERT INTO b2b_address SET customer_id = '" . $customerdetails[customer_id]."' , address= '".$this->request->post['address']."'");
	
	}
    $json['user_id']= $customerdetails[customer_id];

	if($this->request->post['location']){
		$this->db->query("INSERT INTO `customer_locations` (customer_id,location) VALUES ('".$customerdetails[customer_id]."','".$this->request->post['location']."')"); 

		}
	if($this->request->post['pan_no']){
		$this->db->query("INSERT INTO b2b_PAN SET customer_id = '" . $customerdetails[customer_id]."' , pan_no= '".$this->request->post['pan_no']."'");
	}


    $json['success_login'] = '1';
    $this->session->data['user_token'] = token(32);
    $json['user_token'] = $this->session->data['user_token'];
}


}
else{
	if($customer_id_email!= NULL){
		$json['error']= 'Already Registered through this email';


	}
	if($customer_id_num!= NULL){
		$json['error']= 'Already Registered through this mobile number';


	}
}
$this->response->addHeader('Content-Type: application/json');
$this->response->addHeader('Access-Control-Allow-Origin: *');
$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
$this->response->addHeader('Access-Control-Max-Age: 600');
$this->response->setOutput(json_encode($json));
       
    }
}