
<?php
class ControllerApiLoginApi extends Controller
{
	public function index()
    {
		$json = array();
		$this->load->model('account/customer');
		$customer_id_email = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		// $customer_id_num = $this->model_account_customer->getCustomerByPhone($this->request->get['number']);
        if($customer_id_email!= NULL){
if($this->customer->login($this->request->post['email'], $this->request->post['password'])){
    $json['success'] = '1';

} else{
    $json['error']= 'Wrong password, login failed';

}
}
else{
    $json['error']= 'Not Registered, login failed';

}
$this->response->addHeader('Content-Type: application/json');
$this->response->addHeader('Access-Control-Allow-Origin: *');
$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
$this->response->addHeader('Access-Control-Max-Age: 600');
$this->response->setOutput(json_encode($json));
       
    }
}