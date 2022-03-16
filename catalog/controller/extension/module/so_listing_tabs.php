<?php
class ControllerExtensionModuleSolistingtabs extends Controller {
	public function index($setting) {
		$data['category_id']= $setting["category"][0];
		$data['category_name']= $setting["head_name"];
		$data['module_name']= $setting["name"];

		return $this->load->view('extension/module/so_listing_tabs/'.$setting['store_layout'], $data);
	
}}