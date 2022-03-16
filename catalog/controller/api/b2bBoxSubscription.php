<?php
class ControllerApiB2bBoxSubscription extends Controller {
	public function index() {
        if($this->request->post['key']=='opnxwq1189'){

            $abc=$this->db->query("SELECT * FROM `box_subscription` WHERE customer_id='".$this->request->post['customer_id']."'");
            if($abc->num_rows=='0'){
                $this->db->query("INSERT INTO `box_subscription` SET customer_id='".$this->request->post['customer_id']."'");
            }
        }

    }
}