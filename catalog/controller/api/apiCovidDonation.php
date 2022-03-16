<?php
class ControllerApiApiCovidDonation extends Controller {
	public function index() {
        if($this->request->post['key']=='opnxwq1189'){
            // 
            // $abc=$this->db->query("SELECT * FROM `box_subscription` WHERE customer_id='".$this->request->post['customer_id']."'");
            // if($abc->num_rows=='0'){
                // $this->db->query("INSERT INTO `box_subscription` SET customer_id='".$this->request->post['customer_id']."'");
                $this->db->query("INSERT INTO `donation_covid` (customer_id,amount) VALUE (".$this->request->post['customer_id'].",".$this->request->post['amount'].")");
                // $sql="UPDATE oc_order SET total=total+".$this->request->post['amount']." WHERE order_id='".$this->request->post['order_id']."'";
                // $this->db->query($sql);
                // var_dump($sql);

            // }
            $json['success']=1;

        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));

    }
}