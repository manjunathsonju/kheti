<?php
class ControllerApiB2bLocations extends Controller {
	public function index() {
        var_dump('yo');
    }
    public function checkSet() {
		$exits=$this->db->query("SELECT * FROM customer_locations where customer_id='".$this->request->post['customer_id']."'"); 
        if($exits->num_rows=='0'){
            $json['message']="not set";
        }else{
            $json['message']="set";
        
            // $json['location']=($exits->row['location']=='1')? "ktm" : "pkr";

            $json['location']=$exits->row['location'];
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));

    }

    public function SetLocation() {
        if($this->request->post['key']=='$3tL0c@t!0n'){
            $exits=$this->db->query("SELECT * FROM customer_locations where customer_id='".$this->request->post['customer_id']."'"); 
        if($exits->num_rows=='0'){

            $this->db->query("INSERT INTO `customer_locations` (customer_id,location) VALUES ('".$this->request->post['customer_id']."','".$this->request->post['location']."')"); 
            $json['success']="Success";

        }else{
            $this->db->query("UPDATE `customer_locations` SET location='".$this->request->post['location']."' WHERE customer_id='".$this->request->post['customer_id']."'"); 
            $json['success']="Success";

        }

        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }

}