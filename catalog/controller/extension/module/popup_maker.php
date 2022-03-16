<?php
class ControllerExtensionModulePopupMaker extends Controller {
	const SGPM_SERVICE_URL = 'https://popupmaker.com/';
	private $allowed_popups = array();

	public function initPopupLoader() {
		/**
		 * Options for loading a page by URL
		 *
		 * @param route : path to the page template -> [?route=common/home]
		 * @param category : path to the category type by id -> [&path=20]
		 * @param product : path to the product type by id -> [&product_id=47]
		 */

		@$route = $this->request->get['route'];
		@$category = $this->request->get['path'];
		@$product = $this->request->get['product_id'];

		// for page load from blank
		if ($route == NULL) {
			$route = 'common/home';
		}

		$loader_file = DIR_APPLICATION.'view/javascript/popup_loader.js';
		$embed_code = "
			window.SGPMPopupLoader=window.SGPMPopupLoader||{ids:[],popups:{},call:function(w,d,s,l,id){
				w['sgp']=w['sgp']||function(){(w['sgp'].q=w['sgp'].q||[]).push(arguments[0]);};
				var sg1=d.createElement(s),sg0=d.getElementsByTagName(s)[0];
				if(SGPMPopupLoader && SGPMPopupLoader.ids && SGPMPopupLoader.ids.length > 0){SGPMPopupLoader.ids.push(id); return;}
				SGPMPopupLoader.ids.push(id);
				sg1.onload = function(){SGPMPopup.openSGPMPopup();}; sg1.async=true; sg1.src=l;
				sg0.parentNode.insertBefore(sg1,sg0);
				return {};
			}};
		";
		$popup_loader = '';
		$loader = "SGPMPopupLoader.call(window,document,'script','https://popupmaker.com/assets/lib/SGPMPopup.min.js','";

		$this->load->model('extension/module/popup_maker');
		$options = $this->model_extension_module_popup_maker->getPopups();

		foreach ($options['popup_data'] as $popup) {
			$this->allowToOPenPopup($popup);
		}

		foreach ($this->allowed_popups as $popup) {
			$target_data = $popup['target'];
			$hash_id = $popup['hashId'];

			if ($target_data['layouts']['all']) {
				$popup_loader .= $loader.$hash_id."');";
			}

			foreach ($target_data['layouts']['selected'] as $selected) {
				if (count($selected)) {
					foreach ($selected as $node) {
						switch ($node['operator']) {
							case '==':
								if ($route == $node['route']) {
									$popup_loader .= $loader.$hash_id."');";
								}
								break;

							case '!=':
								$match = preg_match_all('/'.$hash_id.'/', $popup_loader);
								if ($match) {
									$popup_loader = preg_replace('/'.$hash_id.'/', '', $popup_loader);
								}

								if ($route != $node['route']) {
									$popup_loader .= $loader.$hash_id."');";
								}
								break;
						}
					}
				}
			}

			if ($target_data['categories']['all'] && $route == 'product/category') {
				$popup_loader .= $loader.$hash_id."');";
			}

			foreach ($target_data['categories']['selected'] as $selected) {
				if (count($selected)) {
					foreach ($selected as $node) {
						switch ($node['operator']) {
							case '==':
								if ($route == 'product/category' && $category == $node['route']) {
									$popup_loader .= $loader.$hash_id."');";
								}
								break;

							case '!=':
								$match = preg_match_all('/'.$hash_id.'/', $popup_loader);
								if ($match) {
									$popup_loader = preg_replace('/'.$hash_id.'/', '', $popup_loader);
								}

								if ($route == 'product/category' && $category != $node['route']) {
									$popup_loader .= $loader.$hash_id."');";
								}
								break;
						}
					}
				}
			}

			if ($target_data['products']['all'] && $route == 'product/product') {
				$popup_loader .= $loader.$hash_id."');";
			}

			foreach ($target_data['products']['selected'] as $selected) {
				if (count($selected)) {
					foreach ($selected as $node) {
						switch ($node['operator']) {
							case '==':
								if ($route == 'product/product' && $product == $node['route']) {
									$popup_loader .= $loader.$hash_id."');";
								}
								break;

							case '!=':
								$match = preg_match_all('/'.$hash_id.'/', $popup_loader);
								if ($match) {
									$popup_loader = preg_replace('/'.$hash_id.'/', '', $popup_loader);
								}

								if ($route == 'product/product' && $product != $node['route']) {
									$popup_loader .= $loader.$hash_id."');";
								}
								break;
						}
					}
				}
			}
		}

		$content = $embed_code.$popup_loader;
		@file_put_contents($loader_file, $content);
	}

	private function allowToOPenPopup($option_data) {
		if ($option_data['status'] == 'enabled') {
			array_push($this->allowed_popups, $option_data);
		}
	}
}

