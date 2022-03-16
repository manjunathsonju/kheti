<?php
class ControllerApiGetOrdersHistoryb2b extends Controller {
	public function index() {
    $orderarrayhistory = (array) null; 
    $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name from oc_customer where customer_id='".$this->request->get['customer_id']."'");

        $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE customer_id='".$this->request->get['customer_id']."' AND order_status_id!=0 ORDER BY order_id DESC");
        foreach ($orderarr->rows as $result){
            $orderarrayhistory[]=array(
              'customer_name'=>$customername->row['name'],
              'order_id'=>$result['order_id'],
              'customer_id'=>$result['customer_id'],
            //   'name'=>$customername->row['name'],
              'comment'=>$result['comment'],
              'total'=>$result['total'],
              'order_status_id'=>$result['order_status_id'],
              'payment_status'=>$result['payment_status'],
              'date_added'=>$result['date_added'],
            //   'email'=>$customername->row['email'],
            //   'telephone'=>$customername->row['telephone']
            );
        }
        // var_dump($);
        // $json['name']=$customername->row['name'];

        $json['orders'] = $orderarrayhistory;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));

    }
}
