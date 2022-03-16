<?php
class ControllerB2bNav extends Controller {
	public function index($data) {
		
		return $this->load->view('b2b/nav',$data);

    }
}
