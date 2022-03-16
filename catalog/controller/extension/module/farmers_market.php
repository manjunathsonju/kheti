<?php
class ControllerExtensionModuleFarmersMarket extends Controller {

	public function index($setting = null) {
		$this->load->language('extension/module/farmers_market');

		if ($setting && $setting['status']) {
				$show_modal = false;
				$data = array();
				
				if (isset($this->session->data['module_farmers_market_pass']) && $this->session->data['module_age_restriction_pass'] < $setting['age']) {
					$show_modal = true;
				}

				if (!isset($this->session->data['module_farmers_market_pass'])) {
					$show_modal = true;
				}

				if ($show_modal) {
					$data['message'] = sprintf($setting['message'], $setting['age']);
					$data['age'] = $setting['age'];
					$data['redirect_url'] = $setting['redirect_url'];
					$data['session_redirect'] = $this->url->link('extension/module/farmers_market/startAgeSession');
	
					//return $this->load->view('extension/module/farmers_market', $data);
				}
				
				//send manufacturers (farmer markets)
				$this->load->model('catalog/manufacturer');
				$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
				
				/* return override */
				return $this->load->view('extension/module/farmers_market', $data);
		}
	}

	public function startAgeSession() { //ajax
		$this->session->data['module_farmers_market_pass'] = $this->request->post['age'];

		if (isset ($this->session->data['module_farmers_market_pass'] )) {
			if ($this->request->post['age'] > $this->session->data['module_farmers_market_pass']) {
				$this->session->data['module_farmers_market_pass'] = $this->request->get['age'] ;
			} 
		} else {
			$this->session->data['module_farmers_market_pass'] = $this->request->post['age'];
		}

		$data = array();
		$data['success'] = true;
		$this->response->setOutput($this->load->view('extension/module/farmers_market_session', $data));
	}
	
}