<?php
class ControllerExtensionModuleUserInfo extends Controller {

	public function index($setting = null) {
		$this->load->language('extension/module/user_info');

		if ($setting && $setting['status']) {
			$data = array();	
			$data['firstName'] = $this->customer->getFirstName();
			$data['lastName'] = $this->customer->getLastName();
			$data['email'] = $this->customer->getEmail();
			$data['mobile'] = $this->customer->getTelephone();
		
			// $balance= $this->db->query("SELECT balance FROM oc_customer WHERE customer_id='".$this->customer->getId()."'");
			//  $data['balance']= $balance->row['balance'];
			
			
			//return json_encode($data);
			return $this->load->view('extension/module/user_info', $data);				
		}
	}

	public function startAgeSession() { //ajax
		$this->session->data['module_user_info_pass'] = $this->request->post['age'];

		if (isset ($this->session->data['module_user_info_pass'] )) {
			if ($this->request->post['age'] > $this->session->data['module_user_info_pass']) {
				$this->session->data['module_user_info_pass'] = $this->request->get['age'] ;
			} 
		} else {
			$this->session->data['module_user_info_pass'] = $this->request->post['age'];
		}

		$data = array();
		$data['success'] = true;
		$this->response->setOutput($this->load->view('extension/module/user_info_session', $data));
	}
	
}