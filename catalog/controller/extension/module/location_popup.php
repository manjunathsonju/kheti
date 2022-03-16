<?php
class ControllerExtensionModuleLocationPopup extends Controller {

	public function index($setting = null) {
		$this->load->language('extension/module/location_popup');
		$store_id = $this->config->get('config_store_id');


		if ($setting && $setting['status']) {
				$show_modal = false;
				$data = array();
				
				if (isset($this->session->data['module_location_popup_pass']) && $this->session->data['module_location_popup_pass'] < $setting['age']) {
					$show_modal = true;
				}

				if (!isset($this->session->data['module_location_popup_pass'])) {
					$show_modal = true;
				}

				if ($show_modal) {
					$data['message'] = sprintf($setting['message'], $setting['age']);
					$data['age'] = $setting['age'];
					$data['redirect_url'] = $setting['redirect_url'];
					if($store_id == 1 ){
						$data['session_redirect'] = 'https://khetifood.com/index.php?route=extension/module/location_popup/startAgeSession';
					}else{
						$data['session_redirect'] = 'https://store.kheti.farm/index.php?route=extension/module/location_popup/startAgeSession';

					}
					// if model show condition
					$data['customer_id'] = $this->customer->getId();
					if($data['customer_id']){
						// var_dump($data['customer_id']);
						$loc = $this->db->query("SELECT shipping_zone_id FROM `oc_order` WHERE order_id=(SELECT MAX(order_id) FROM oc_order WHERE customer_id='".$data['customer_id']."' AND shipping_zone_id IS NOT NULL)");
								if((int)$loc->row['shipping_zone_id']==2318){
									// 2315 ktm
									$data['userlocation'] ="pkr";
								}else{
									$data['userlocation'] ="ktm";
								}
							}else{
						$data['userlocation'] ="ktm";
			
					}
				return $this->load->view('extension/module/location_popup', $data);				
					
				}
		}
	}

	public function startAgeSession() { //ajax
		$this->session->data['module_location_popup_pass'] = $this->request->post['age'];

		if (isset ($this->session->data['module_location_popup_pass'] )) {
			if ($this->request->post['age'] > $this->session->data['module_location_popup_pass']) {
				$this->session->data['module_location_popup_pass'] = $this->request->get['age'] ;
			} 
		} else {
			$this->session->data['module_location_popup_pass'] = $this->request->post['age'];
		}

		$data = array();
		$data['success'] = true;
		$this->response->setOutput($this->load->view('extension/module/location_popup_session', $data));
	}
	
}