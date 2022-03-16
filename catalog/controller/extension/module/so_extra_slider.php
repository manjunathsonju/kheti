<?php
class ControllerExtensionModuleSoextraslider extends Controller {
	public function index($setting) {
		
		$data['category_id']= $setting["category"][0];
		$data['category_name']= $setting["name"];
		$data['module_name']= $setting["name"];
			return $this->load->view('extension/module/so_extra_slider/'.$setting['store_layout'], $data);
	
	}

 }