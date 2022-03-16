<?php
class ControllerB2bLogout extends Controller {
	public function index() {
        if($this->request->get['id']){
            $this->db->query("UPDATE `b2b_users` SET token='' WHERE user_id='".$this->request->get['id']."'");
            
            header('Location: https://khetifood.com/business/');


        }

    }
}