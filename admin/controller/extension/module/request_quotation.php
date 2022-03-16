<?php
class ControllerExtensionModuleRequestQuotation extends Controller {
	
	const DEFAULT_MODULE_SETTINGS = [
		'name' => 'Request Quotation',
		'message' => '',
		'age' => 21,
		'redirect_url' => 'http://www.example.org',
		'status' => 1 /* Enabled by default*/
	];
    
	private $error = array();
	
	public function index() {  
		if (!isset($this->request->get['module_id'])) {
			$module_id = $this->addModule();
			$this->response->redirect($this->url->link('extension/module/request_quotation','&user_token='.$this->session->data['user_token'].'&module_id='.$module_id));
		} else {
		   $this->editModule($this->request->get['module_id']);
		}
	}
	
	private function addModule() {
		$this->load->model('setting/module');
	
		$this->model_setting_module->addModule('request_quotation', self::DEFAULT_MODULE_SETTINGS);
		
		return $this->db->getLastId();
	}
	protected function editModule($module_id) {
		$this->load->model('setting/module');
		
		/* Set page title */
		$this->load->language('extension/module/request_quotation');
		$this->document->setTitle($this->language->get('heading_title'));
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		$data = array(); 
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/request_quotation', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$module_setting = $this->model_setting_module->getModule($module_id);
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} else {
			$data['name'] = $module_setting['name'];
		}
		if (isset($this->request->post['message'])) {
			$data['message'] = $this->request->post['message'];
		} else {
			$data['message'] = $module_setting['message'];
		}
		if (isset($this->request->post['redirect_url'])) {
			$data['redirect_url'] = $this->request->post['redirect_url'];
		} else {
			$data['redirect_url'] = $module_setting['redirect_url'];
		}
		
		if (isset($this->request->post['age'])) {
			$data['age'] = $this->request->post['age'];
		} else {
			$data['age'] = $module_setting['age'];
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} else {
			$data['status'] = $module_setting['status'];
		} 
		$data['action']['cancel'] = $this->url->link('marketplace/extension', 'user_token='.$this->session->data['user_token'].'&type=module');
		$data['action']['save'] = "";
		$data['error'] = $this->error;	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		//get quotations from database
		$sql = "SELECT * FROM " . DB_PREFIX . "quotations";
		
		$query = $this->db->query($sql);
		
		$rows = $query->rows;
		$result_data = "";
			
		for($i = 0;  $i < count($rows); $i++) {
			$newArray = array_keys(json_decode($rows[$i]['veg_quantity'], true));
			$newArrayValues = array_values(json_decode($rows[$i]['veg_quantity'], true));
			
			for($j = 0;  $j < count($newArray); $j++) {
				//get veg name from database
				$sql = "SELECT name FROM " . DB_PREFIX . "product_description WHERE product_id = " . $newArray[$j];
				$result = $this->db->query($sql);
				
				$result_data .= ($result->rows)[0]['name'] . " - " . $newArrayValues[$j] . 'kg <br>';
			}				
			
			$rows[$i]['order_details'] = $result_data;
		}
		
		$data['quotations'] = $rows;
		
		
		$htmlOutput = $this->load->view('extension/module/request_quotation', $data);
		$this->response->setOutput($htmlOutput);
	}
	public function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/request_quotation')) {
			$this->error['permission'] = true;
			return false;
		}
		
		if (!utf8_strlen($this->request->post['name'])) {
			$this->error['name'] = true;
		}
		if (!utf8_strlen($this->request->post['message'])) {
			$this->error['message'] = true;
		}
		if (!utf8_strlen($this->request->post['redirect_url'])) {
			$this->error['redirect_url'] = true;
		}
		
		if (!is_numeric($this->request->post['age'])) {
			$this->error['age'] = true;
		}
		
		return empty($this->error);
	}
	
	public function install() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('module_request_quotation', ['module_request_quotation_status'=>1]);
	}
	
	public function uninstall() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('module_request_quotation');
	}
}