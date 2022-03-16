<?php
class ControllerApiB2bGetOrder extends Controller {
	public function index() {
        $ordersinformation= $this->orderinfo($this->request->get['order_id']);
        $orderarrpro=$this->db->query("SELECT * FROM order_products_b2b WHERE order_id=".$this->request->get['order_id']);
        foreach ($orderarrpro->rows as $result){
            $productname = $this->db->query("SELECT p.sku,p.model,pd.name,p.image FROM oc_product p JOIN oc_product_description pd ON (p.product_id=pd.product_id) WHERE p.product_id='".$result['product_id']."'");
          $orderproductarray[]=array(
            'product_id'=>$result['product_id'],
            'quantity'=>$result['quantity'],
            'price'=>$result['price'],
            'total'=>$result['total'],
            'sku'=>$productname->row['sku'],
            'model'=>$productname->row['model'],
            'name'=>$productname->row['name'],
            'thumb'=>"https://khetifood.com/image/".$productname->row['image']

          );
      }
      $json['order_details'] = $ordersinformation;
      $json['order_products'] = $orderproductarray;
      $this->response->addHeader('Content-Type: application/json');
      $this->response->addHeader('Access-Control-Allow-Origin: *');
     $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
     $this->response->addHeader('Access-Control-Max-Age: 600');
      $this->response->setOutput(json_encode($json));


     }
     public function orderinfo($order_id) {
        $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE order_id='".$order_id."'");
        foreach ($orderarr->rows as $result){
          $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$result['customer_id']."'");
          $orderarray=array(
            'order_id'=>$result['order_id'],
            'customer_id'=>$result['customer_id'],
            'name'=>$customername->row['name'],
            'comment'=>$result['comment'],
            'total'=>$result['total'],
            'order_status_id'=>$result['order_status_id'],
            'payment_status'=>$result['payment_status'],
            'payment_method'=>$result['payment_method'],
            'date_added'=>$result['date_added'],
            'email'=>$customername->row['email'],
            'telephone'=>$customername->row['telephone'],
            'shipping_charge'=>'0.00'

          );
      }
      return($orderarray);  
     }

}
