<?php
class ControllerExtensionModuleMaximum extends Controller {
	private $error = array();

public function index() {
		$this->load->language('extension/module/maximum');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_maximum', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

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
			'href' => $this->url->link('extension/module/maximum', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/maximum', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_maximum_status'])) {
			$data['module_maximum_status'] = $this->request->post['module_maximum_status'];
		} else {
			$data['module_maximum_status'] = $this->config->get('module_maximum_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/maximum', $data));
	}


    public function install() {

       
           $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."maximum`(id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, product_id int(11) NOT NULL, maximum int(11) NOT NULL DEFAULT 0 )");

        $this->load->model('setting/setting');
        $value= array('module_maximum_status' => 1);
        $this->model_setting_setting->editSetting('module_maximum', $value);


    }
  public function uninstall() {
        $this->load->model('setting/setting');
        $value= array('module_maximum_status' => 0);
        $this->model_setting_setting->deleteSetting('module_maximum',$value);

        $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."maximum`");
    }
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/maximum')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
