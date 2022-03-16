<?php
class ModelExtensionModulePopupMaker extends Model {
	const TABLE = 'popup_maker';
	private $version = '1.0.0';

	public function connect($api) {
		$options = $this->getPopups();
		$current_api = $options['apiKey'];

		$sgpm_service_url = 'https://popupmaker.com/app/connect';

		$args = array(
			'apiKey' => $api,
			'appname' => 'Opencart'
		);

		$target_data = array(
			'layouts' => array(
				'all' => false,
				'selected' => array()
			),
			'categories' => array(
				'all' => false,
				'selected' => array()
			),
			'products' => array(
				'all' => false,
				'selected' => array()
			)
		);

		$query_args = http_build_query($args);

		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL, $sgpm_service_url);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $query_args);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$responce = curl_exec($ch);
		$responce = json_decode($responce, true);

		curl_close($ch);

		if (count($responce)) {
			if (empty($options['popup_data']) || $current_api != $api) {
				if (isset($responce['isAuthenticate']) && $responce['isAuthenticate']) {
					$options['oldUser'] = true;
					$options['isAuthenticate'] = true;
					$options['apiKey'] = $responce['apiKey'];
					$options['popup_data'] = array_reverse($responce['popups'], true);
					$options['user'] = $responce['user'];
				}

				foreach ($options['popup_data'] as $popup_id => $popup) {
					$options['popup_data'][$popup_id]['status'] = 'disabled';
					$options['popup_data'][$popup_id]['target'] = $target_data;
				}

				foreach ($options['popup_data'] as $popup_id => $popup) {
					$options['popup_data'][$popup_id]['status'] = 'enabled';
					$options['popup_data'][$popup_id]['target']['layouts']['all'] = true;
					break;
				}
			} else {
				// add new popups to top
				foreach (array_reverse($responce['popups'], true) as $key => &$value) {
					if (!array_key_exists($key, $options['popup_data'])) {
						$value['status'] = 'disabled';
						$value['target'] = $target_data;

						$options['popup_data'] = array($key => $value) + $options['popup_data'];
					}
				}

				// remove popup
				foreach ($options['popup_data'] as $key => &$value) {
					if (!array_key_exists($key, $responce['popups'])) {
						unset($options['popup_data'][$key]);
					}
				}
			}
		} else {
			$options = $this->defaultOptions();
			$options['apiKey'] = $api;
		}

		$popup_data = serialize($options);
		return $this->savePopupData($popup_data);
	}

	public function savePopupData($popup_data) {
		$table = DB_PREFIX.self::TABLE;

		$querys_to_execute = array(
			"update" => "UPDATE $table SET `option_data` = '$popup_data' WHERE `option_name` = 'sgpm_popup_maker_api_option'"
		);

		$this->db->query($querys_to_execute['update']);

		return true;
	}

	public function getPopups() {
		$query = 'SELECT `option_data` FROM '.DB_PREFIX.self::TABLE.' WHERE 1';
		$popups = $this->db->query($query);
		$options = $popups->row['option_data'];
		return unserialize($options);
	}

	public function getAllPages() {
		$query = 'SELECT '.DB_PREFIX.'layout.name, '.DB_PREFIX.'layout_route.route AS `data_value` FROM '.DB_PREFIX.'layout INNER JOIN '.DB_PREFIX.'layout_route ON '.DB_PREFIX.'layout.layout_id = '.DB_PREFIX.'layout_route.layout_id';

		$layouts = $this->db->query($query);
		return $layouts->rows;
	}

	public function getAllCategories() {

		$query = 'SELECT '.DB_PREFIX.'category.parent_id AS `path`, '.DB_PREFIX.'category_description.category_id AS `data_value`, '.DB_PREFIX.'category_description.name FROM '.DB_PREFIX.'category_description INNER JOIN '.DB_PREFIX.'category ON '.DB_PREFIX.'category_description.category_id = '.DB_PREFIX.'category.category_id';

		$product = $this->db->query($query);
		return $product->rows;
	}

	public function getAllProducts() {
		$query = 'SELECT `product_id` AS `data_value`, `name` FROM '.DB_PREFIX.'product_description WHERE 1';

		$product = $this->db->query($query);
		return $product->rows;
	}

	public function install() {
		$default_options = serialize($this->defaultOptions());
		$table = DB_PREFIX.self::TABLE;

		$querys_to_execute = array(
			"CREATE TABLE IF NOT EXISTS $table ( `option_name` CHAR(192) NOT NULL, `option_data` LONGTEXT NOT NULL );",
			"INSERT INTO $table (`option_name`, `option_data`) VALUES ('sgpm_popup_maker_api_option', '$default_options');"
		);

		foreach ($querys_to_execute as $query) {
			$this->db->query($query);
		}
	}

	public function clearLoader() {
		$loader_file = DIR_APPLICATION.'view/javascript/popup_loader.js';

		$content = '';
		@file_put_contents($loader_file, $content);
	}

	public function uninstall() {
		$this->clearLoader();

		$querys_to_execute = array(
			'DROP TABLE IF EXISTS '.DB_PREFIX.self::TABLE
		);

		foreach ($querys_to_execute as $query) {
			$this->db->query($query);
		}
	}

	public function defaultOptions()
	{
		$options = array(
			'isAuthenticate' => false,
			'apiKey' => '',
			'popup_data' => array(),
			'user' => array(
				'isActive' => false,
				'isExpired'  => false,
				'isDisabled' => false,
				'email' => '',
				'firstname' => '',
				'lastname' => ''
			),
			'oldUser' => false,
			'pluginVersion' => $this->version
		);

		return $options;
	}
}
