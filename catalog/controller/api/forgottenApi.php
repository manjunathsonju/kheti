<?php
class ControllerApiForgottenApi extends Controller {
	private $error = array();

	public function index() {


		$this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['status']) {
            $json['error']= 'Customer not approved';
		}

        if(isset($this->request->post['email'])  && $customer_info['status'] ) {
            $this->model_account_customer->editCode($this->request->post['email'], token(40));
            $json['success'] = 'Link sent to email';
        }else{
            $json['error']= 'no email posted or invalid email';


        }
        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

		

	}

	
}
