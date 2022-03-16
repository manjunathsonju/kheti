<?php
class ControllerApiUser extends Controller
{
    public function index()
    {
		$this->load->model('account/customer');
		
		// Check how many login attempts have been made.
		$login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

		if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$this->error['warning'] = $this->language->get('error_attempts');
		}

		// Check if customer has been approved.
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['status']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		if (!$this->error) {
			if ($this->customer->login($this->request->post['email'], $this->request->post['password'])) {
				echo "login success";
			} else {
				echo "login failed";
			}
		}
		else {
			
		}

		//return !$this->error;
		
        $json = array();
        //$json['products'] = $data['products'];
        $this->response->addHeader('Content-Type: application/json');
        //$this->response->setOutput(json_encode("api test"));
    }
}