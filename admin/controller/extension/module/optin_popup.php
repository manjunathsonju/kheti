<?php
class ControllerExtensionModuleOptinPopup extends Controller {
	private $error = array();

	/*
	 * Extension settings
	 */
	public function index() {
		$this->load->model('setting/setting');
		$this->load->language('extension/module/optin_popup');
		$this->load->model('extension/module/optin_popup');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate('settings')) {
			$this->model_setting_setting->editSetting('module_optin_popup', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module/optin_popup', 'user_token=' . $this->session->data['user_token'], TRUE));
		}

		$this->document->setTitle($this->language->get('heading_title'));
		$data['module_optin_popup_status'] = $this->model_setting_setting->getSettingValue('module_optin_popup_status');

		$data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$data['success'] = isset($this->session->data['success']) ? $this->session->data['success'] : '';
		unset($this->session->data['success']);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], TRUE),
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', TRUE),
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/optin_popup', 'user_token=' . $this->session->data['user_token'], TRUE),
		);

		$data['action'] = $this->url->link('extension/module/optin_popup', 'user_token=' . $this->session->data['user_token'], TRUE);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', TRUE);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}
		
		if (isset($this->request->post['module_description'])) {
			$data['module_description'] = $this->request->post['module_description'];
		}
		elseif (!empty($module_info)) {
			$data['module_description'] = $module_info['module_description'];
		}
		else {
			$data['module_description'] = array();
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		}
		elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		}
		else {
			$data['status'] = '';
		}

		$data['optin_popups'] = $this->model_extension_module_optin_popup->loadPopups();
		$data['add_url'] = $this->url->link('extension/module/optin_popup/add', 'user_token=' . $this->session->data['user_token'], TRUE);
		$data['edit_url'] = $this->url->link('extension/module/optin_popup/edit', 'user_token=' . $this->session->data['user_token'], TRUE);
		$data['delete_url'] = str_replace('&amp;', '&', $this->url->link('extension/module/optin_popup/delete', 'user_token=' . $this->session->data['user_token'], TRUE));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/optin_popup/optin_popup', $data));
	}

	/*
	 * Add new popup
	 */
	public function add() {
		$this->load->model('extension/module/optin_popup');
		$this->load->language('extension/module/optin_popup');
		$this->document->setTitle($this->language->get('heading_title_add'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate('add')) {
			$id = $this->model_extension_module_optin_popup->addPopup($this->request->post['values']);
			$this->session->data['success'] = $this->language->get('text_success_add');
			$this->response->redirect($this->url->link('extension/module/optin_popup', 'user_token=' . $this->session->data['user_token'], TRUE));
		}

		$popup = array();
		return $this->getForm($popup);
	}

	/*
	 * Edit popup
	 */
	public function edit() {
		$this->load->model('extension/module/optin_popup');
		$this->load->language('extension/module/optin_popup');
		$this->document->setTitle($this->language->get('heading_title_edit'));
		$id = (int)$this->request->get['id'];

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate('edit')) {
			$this->model_extension_module_optin_popup->editPopup($id, $this->request->post['values']);
			$this->session->data['success'] = $this->language->get('text_success_edit');
			$this->response->redirect($this->url->link('extension/module/optin_popup', 'user_token=' . $this->session->data['user_token'], TRUE));
		}

		$popup = $this->model_extension_module_optin_popup->loadPopupForEdit($id);
		return $this->getForm($popup);
	}

	/*
	 * Popup add/edit form
	 */
	private function getForm($popup) {
		$this->load->model('localisation/language');
		$this->load->model('tool/image');

		$this->document->addStyle('view/stylesheet/optin_popup.css');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['optin_popup'] = $popup;
		$data['is_new'] = empty($popup);
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$data['cancel'] = $this->url->link('extension/module/optin_popup', 'user_token=' . $this->session->data['user_token'], TRUE);

		if (!empty($popup)) {
			$data['optin_popup']['pages_lines'] = !empty($popup['pages']) ? implode(PHP_EOL, $popup['pages']) : '';
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], TRUE),
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/optin_popup', 'user_token=' . $this->session->data['user_token'], TRUE),
		);

		$data['error_name'] = isset($this->error['name']) ? $this->error['name'] : '';
		$data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/optin_popup/optin_popup_form', $data));
	}

	/*
	 * Delete popup
	 */
	public function delete() {
		$this->load->model('extension/module/optin_popup');
		$this->load->language('extension/module/optin_popup');
		$id = (int)$this->request->post['id'];

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate('delete') && $id > 0) {
			$this->model_extension_module_optin_popup->deletePopup($id);
			$json = json_encode(array('success' => sprintf($this->language->get('text_success_delete'), $id)));
			$this->response->setOutput($json);
		}
	}

	/*
	 * Validate requests
	 */
	protected function validate($op) {
		if (!$this->user->hasPermission('modify', 'extension/module/optin_popup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if ($op == 'edit' && empty($this->request->post['values']['name'])) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	/*
	 * Add Optin Popup link to admin Design menu
	 */
	public function viewColumnLeftBefore(&$route, &$data, &$output) {
		$this->load->model('setting/setting');
		if (!$this->model_setting_setting->getSettingValue('module_optin_popup_status')) return;

		$this->load->language('extension/module/optin_popup');
		foreach ($data['menus'] as $k => $menu) {
			if ($menu['id'] == 'menu-design') {
				$data['menus'][$k]['children'][] = array(
					'id' => 'optin-popup',
					'icon' => 'fa-bars',
					'name' => $this->language->get('heading_title'),
					'href' => $this->url->link('extension/module/optin_popup', 'user_token=' . $this->session->data['user_token'], TRUE),
				);
			}
		}
	}

	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "optin_popup (
				optin_popup_id int(11) NOT NULL AUTO_INCREMENT,
				status TINYINT(3) UNSIGNED NOT NULL DEFAULT 1,
				name VARCHAR(255) NOT NULL,
				styles JSON,
  				PRIMARY KEY (optin_popup_id),
				KEY status (status)
			) DEFAULT COLLATE=utf8_general_ci
		");
		$this->db->query("
			CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "optin_popup_description (
				optin_popup_id int(11) NOT NULL,
  				language_id int(11) NOT NULL,
  				title VARCHAR(255),
  				subtitle VARCHAR(255),
				description TEXT,
  				image VARCHAR(255),
				button_label VARCHAR(255),
				button_url VARCHAR(255),
  				KEY (optin_popup_id, language_id),
  				KEY title (title)
			) DEFAULT COLLATE=utf8_general_ci
		");
		$this->db->query("
			CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "optin_popup_pages (
				optin_popup_id int(11) NOT NULL,
  				page VARCHAR(255),
  				KEY page (page)
			) DEFAULT COLLATE=utf8_general_ci
		");

		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/optin_popup');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/optin_popup');

 		$this->load->model('setting/setting');
 		$this->model_setting_setting->editSetting('module_optin_popup', array('module_optin_popup_status' => '1'));

		$this->load->model('setting/event');
		$this->model_setting_event->addEvent('optin_popup', 'admin/view/common/column_left/before', 'extension/module/optin_popup/viewColumnLeftBefore');
		$this->model_setting_event->addEvent('optin_popup', 'catalog/view/common/footer/before', 'extension/module/optin_popup/viewCommonFooterBefore');
		$this->model_setting_event->addEvent('optin_popup', 'catalog/view/common/footer/after', 'extension/module/optin_popup/viewCommonFooterAfter');
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "optin_popup");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "optin_popup_description");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "optin_popup_pages");

		$this->load->model('user/user_group');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/module/optin_popup');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/module/optin_popup');

		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('optin_popup');
	}
}