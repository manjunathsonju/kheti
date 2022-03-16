<?php
class ControllerB2bHeader extends Controller {
	public function index() {
		return $this->load->view('b2b/header', $data);

    }
}
