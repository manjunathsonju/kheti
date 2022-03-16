<?php
class ControllerApiGetIdentifier extends Controller
{
    public function index()
    {
		
		$this->load->model('account/customer');

		$type = $this->request->get['type'];
		$data = $this->request->get['data'];
		if($type == 'email') {
			$customer_info = $this->model_account_customer->getCustomerByEmail($data);
			echo $customer_info['telephone'];
		}
		else if ($type == 'phone') {
			$customer_info = $this->model_account_customer->getCustomerByPhone($data);
			echo $customer_info['email'];
		}
    }
}