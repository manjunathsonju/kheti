<?php
class ControllerExtensionModulePopupMaker extends Controller {
	private $error = array();
	private $version = '1.0.0';
	private $event_name = 'sgpm_popup_maker';

	public function index() {
		$this->load->language('extension/module/popup_maker');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('popup_maker', array('popup_maker_status' => 1));
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$scripts = array(
			'view/javascript/popup_maker_main.js',
			'view/javascript/popup_maker_loader.js',
			'view/javascript/select2/select2.min.js'
		);
		$styles = array(
			'view/stylesheet/popup_maker_main.css',
			'view/stylesheet/select2/select2.min.css'
		);
		$layouts = $this->getLayouts();
		$categories = $this->getCategories();
		$products = $this->getProducts();

		$this->addScripts($scripts);
		$this->addStyles($styles);

		$data['heading_title'] = $this->language->get('heading_title').' '.$this->version;
		$data['user_token'] = $this->session->data['user_token'];

		$data['menu_sgpm_popups_label'] = $this->language->get('menu_sgpm_popups_label');
		$data['menu_sgpm_api_credentials_label'] = $this->language->get('menu_sgpm_api_credentials_label');
		$data['menu_sgpm_support_label'] = $this->language->get('menu_sgpm_support_label');

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
			'href' => $this->url->link('extension/module/pupup_maker', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/popup_maker', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		$data['user_token'] = $this->session->data['user_token'];
		$data['layouts_list'] = json_encode($layouts);
		$data['categories_list'] = json_encode($categories);
		$data['products_list'] = json_encode($products);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$options = $this->getOptions();

		if (!$options['isAuthenticate'] && $options['apiKey']) {
			$data['error_warning'] = $this->language->get('error_sgpm_wrong_api');
		} else {
			$data['sgpm_popups_list'] = $options['popup_data'];
			$data['input_user_api_key'] = $options['apiKey'];
			$data['is_authenticate'] = $options['isAuthenticate'];
			$data['data_user_name'] = $options['user']['firstname'];
		}

		$data['button_sgpm_edit_settings'] = $this->language->get('button_sgpm_edit_settings');
		$sgpm_service_url = $this->language->get('url_sgpm_service');
		$sgpm_utm_source_url = $this->language->get('url_sgpm_utm_source');

		$data['url_sgpm_create_accaunt'] = $sgpm_service_url.'signup'.$sgpm_utm_source_url;
		$data['url_sgpm_get_api_key'] = $sgpm_service_url.'settings/index'.$sgpm_utm_source_url;
		$data['url_sgpm_dashboard'] = $sgpm_service_url.'dashboard';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/popup_maker', $data));
	}

	public function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/popup_maker')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function addScripts($scripts) {
		foreach ($scripts as $script) {
			$this->document->addScript($script, 'header');
		}
	}

	public function addStyles($styles)	{
		foreach ($styles as $style) {
			$this->document->addStyle($style);
		}
	}

	public function connect() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$api = $this->request->post['api'];

			$this->load->model('extension/module/popup_maker');
			echo $this->model_extension_module_popup_maker->connect($api);
		}
	}

	public function savePopupData($popup_data) {
		$this->load->model('extension/module/popup_maker');
		$options = $this->model_extension_module_popup_maker->savePopupData($popup_data);
		return $options;
	}

	public function getOptions() {
		$this->load->model('extension/module/popup_maker');
		$options = $this->model_extension_module_popup_maker->getPopups();
		return $options;
	}

	public function getLayouts() {
		$this->load->model('extension/module/popup_maker');
		return $this->model_extension_module_popup_maker->getAllPages();
	}

	public function getProducts() {
		$this->load->model('extension/module/popup_maker');
		return $this->model_extension_module_popup_maker->getAllProducts();
	}

	public function getCategories() {
		$this->load->model('extension/module/popup_maker');
		return $this->model_extension_module_popup_maker->getAllCategories();
	}

	public function install() {
		$this->load->model('extension/module/popup_maker');
		$this->model_extension_module_popup_maker->install();

		// register events
		$this->addCustomEvent();
	}

	public function uninstall() {
		$this->load->model('extension/module/popup_maker');
		$this->model_extension_module_popup_maker->uninstall();

		// remove events
		$this->removeCustomEvent();
	}

	public function addCustomEvent() {
		$code = $this->event_name;
		$trigger = 'catalog/view/common/header/before';
		$action = 'extension/module/popup_maker/initPopupLoader';

		$this->load->model('setting/event');
		$this->model_setting_event->addEvent($code, $trigger, $action);
	}

	public function removeCustomEvent() {
		$code = $this->event_name;

		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode($code);
	}

	public function changeStatus() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$options = $this->getOptions();

			$id = $this->request->post['id'];
			$status = $this->request->post['status'];

			foreach ($options['popup_data'] as &$option) {
				if ($option['hashId'] === $id) {
					$option['status'] = $status;
					break;
				}
			}

			$popup_data = serialize($options);
			echo $this->savePopupData($popup_data);
			die;
		}
	}

	public function saveOptions() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data = @$this->request->post['options'];

			$current_options = $this->getOptions();

			$hash_id = $data[0];
			$options = $data[1];

			foreach ($current_options['popup_data'] as &$current_option) {
				if ($hash_id == $current_option['hashId']) {
					// reset values
					$current_option['target']['layouts']['all'] = false;
					$current_option['target']['categories']['all'] = false;
					$current_option['target']['products']['all'] = false;
					$current_option['target']['layouts']['selected'] = array();
					$current_option['target']['categories']['selected'] = array();
					$current_option['target']['products']['selected'] = array();

					foreach ($options as $key => $option) {
						if ($option['target'] == 'layouts_all') {
							$current_option['target']['layouts']['all'][$key] = true;
						}

						if ($option['target'] == 'layouts_selected') {
							$current_option['target']['layouts']['selected'][$key] = $option['page'];
						}

						if ($option['target'] == 'categories_all') {
							$current_option['target']['categories']['all'][$key] = true;
						}

						if ($option['target'] == 'categories_selected') {
							$current_option['target']['categories']['selected'][$key] = $option['page'];
						}

						if ($option['target'] == 'products_all') {
							$current_option['target']['products']['all'][$key] = true;
						}

						if ($option['target'] == 'products_selected') {
							$current_option['target']['products']['selected'][$key] = $option['page'];
						}
					}
				}
			}
			$popup_data = serialize($current_options);
			echo $this->savePopupData($popup_data);
			die;
		}
	}

	public function loadOptions() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$output = null;
			$hash_id = @$this->request->post['id'];

			$options = $this->getOptions();

			$data['option_types'] = array(
				'Layouts' => array('layouts_all' => 'All layouts', 'layouts_selected' => 'Selected layouts'),
				'Categories' => array('categories_all' => 'All categories', 'categories_selected' => 'Selected categories'),
				'Products' => array('products_all' => 'All products', 'products_selected' => 'Selected products')
			);
			$data['operator_types'] = array(
				'==' => 'Is',
				'!=' => 'Is not'
			);

			$data['popup_title'] = null;
			$data['option_selected'] = null;
			$data['targets_list'] = array();
			$data['target_selected'] = array();

			$layouts = $this->getLayouts();
			$categories = $this->getCategories();
			$products = $this->getProducts();

			foreach ($options['popup_data'] as $option) {
				if ($hash_id == $option['hashId']) {
					$data['popup_title'] = $option['title'];
					$data['popup_hash_id'] = $option['hashId'];
					foreach ($option['target'] as $key => $selected) {
						if ($option['target'][$key]['all']) {
							$data['option_selected'] = $key.'_all';
							$data['target_selected'] = array(array('route' => 'null', 'operator' => '=='));
							$output .= $this->load->view('extension/module/popup_maker_options', $data);
						}

						if (count($option['target'][$key]['selected'])) {
							switch ($key) {
								case 'layouts':
									$data['option_selected'] = $key.'_selected';
									foreach ($option['target'][$key]['selected'] as $triggers) {
										$data['targets_list'] = $layouts;
										$data['target_selected'] = $triggers;
										$output .= $this->load->view('extension/module/popup_maker_options', $data);
									}
									break;

								case 'categories':
									$data['option_selected'] = $key.'_selected';
									foreach ($option['target'][$key]['selected'] as $triggers) {
										$data['targets_list'] = $categories;
										$data['target_selected'] = $triggers;
										$output .= $this->load->view('extension/module/popup_maker_options', $data);
									}
									break;

								case 'products':
									$data['option_selected'] = $key.'_selected';
									foreach ($option['target'][$key]['selected'] as $triggers) {
										$data['targets_list'] = $products;
										$data['target_selected'] = $triggers;
										$output .= $this->load->view('extension/module/popup_maker_options', $data);
									}
									break;
							}
						}
					}
					break;
				}
			}

			if ($hash_id == null || !$output) {
				$data['option_selected'] = 'layouts_all';
				$data['targets_list'] = $layouts;
				$data['target_selected'] = array(array('route' => 'null', 'operator' => '=='));
				$output .= $this->load->view('extension/module/popup_maker_options', $data);
			}

			echo $output;
			die;
		}
	}
}
