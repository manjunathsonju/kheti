
<?php
class ControllerApiRegisterAndLogin extends Controller
{
	public function index()
    {
		$json = array();
		$this->load->model('account/customer');
		$customer_id_email = $this->model_account_customer->getCustomerByEmail($this->request->get['email']);
		$customer_id_num = $this->model_account_customer->getCustomerByPhone($this->request->get['number']);
// register
if($customer_id_email== NULL && $customer_id_num== NULL){

$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . '1' . "', store_id = '" . '0' . "', language_id = '" . '1' . "', firstname = '" . $this->request->get['firstname'] . "', lastname = '" . $this->request->get['lastname']  . "', email = '" . $this->request->get['email'] . "', telephone = '" . $this->request->get['number'] . "', custom_field = '" .''. "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($this->request->get['password'])))) . "', newsletter = '" . '1' . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '" . '1' . "', date_added = NOW()");

$json['success'] = '1';
 // end register
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