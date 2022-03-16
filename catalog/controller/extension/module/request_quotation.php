<?php
class ControllerExtensionModuleRequestQuotation extends Controller { 
	public function index($setting = null) { 
		$this->load->language('extension/module/request_quotation');
		if ($setting && $setting['status']) {
				$data['message'] = sprintf($setting['message'], $setting['age']);
				$data['age'] = $setting['age'];
				$data['redirect_url'] = $setting['redirect_url'];
				$data['session_redirect'] = $this->url->link('extension/module/request_quotation/startAgeSession');
				
				//get product list
				$data['product_list'] = $this->model_catalog_product->getProducts();
				
				return $this->load->view('extension/module/request_quotation', $data);
			/*
				$show_modal = false;
				$data = array();
				
				if (isset($this->session->data['module_request_quotation_pass']) && $this->session->data['module_request_quotation_pass'] < $setting['age']) {
					$show_modal = true;
				}
				if (!isset($this->session->data['module_request_quotation_pass'])) {
					$show_modal = true;
				}
				if ($show_modal) {
					$data['message'] = sprintf($setting['message'], $setting['age']);
					$data['age'] = $setting['age'];
					$data['redirect_url'] = $setting['redirect_url'];
					$data['session_redirect'] = $this->url->link('extension/module/request_quotation/startAgeSession');
	
					return $this->load->view('extension/module/request_quotation', $data);
				}
			*/
		}
	}
	public function startAgeSession() { //ajax
		$this->session->data['module_request_quotation_pass'] = $this->request->post['age'];
		if (isset ($this->session->data['module_request_quotation_pass'] )) {
			if ($this->request->post['age'] > $this->session->data['module_request_quotation_pass']) {
				$this->session->data['module_request_quotation_pass'] = $this->request->get['age'] ;
			} 
		} else {
			$this->session->data['module_request_quotation_pass'] = $this->request->post['age'];
		}
		$data = array();
		$data['success'] = true;
		$this->response->setOutput($this->load->view('extension/module/request_quotation_session', $data));
	}
	
}