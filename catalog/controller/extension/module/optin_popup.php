<?php

class ControllerExtensionModuleOptinPopup extends Controller {
	public function viewCommonFooterBefore(&$route, &$data, &$output) {
		if (!$this->config->get('module_optin_popup_status')) return;

		$this->load->model('extension/module/optin_popup');
		$this->load->language('extension/module/optin_popup');

		$url = $this->request->server['REQUEST_URI'];
		$popup = $this->model_extension_module_optin_popup->loadPopupFromUrl($url);
		if ($popup) {
			if ($popup['image']) $popup['image'] = (defined('DIR_CATALOG') ? HTTPS_CATALOG : HTTPS_SERVER). 'image/' . $popup['image'];
			$data['optin_popup'] = $this->load->view('extension/module/optin_popup', $popup);
		}
	}

	public function viewCommonFooterAfter(&$route, &$data, &$output) {
		if (empty($data['optin_popup'])) return;

		$output = preg_replace('~(<\/body>)~Us', $data['optin_popup'].'${1}', $output);
	}
}