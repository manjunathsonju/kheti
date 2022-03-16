<?php
class ControllerB2bOrderdetails extends Controller {
	public function index() {
        if($this->load->controller('b2b/home/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
            $data['order']= $this->orderinfo($this->request->get['order_id']);
            $orderarrpro=$this->db->query("SELECT * FROM order_products_b2b WHERE order_id=".$this->request->get['order_id']);
            foreach ($orderarrpro->rows as $result){
                $productname = $this->db->query("SELECT p.sku,p.model,pd.name FROM oc_product p JOIN oc_product_description pd ON (p.product_id=pd.product_id) WHERE p.product_id='".$result['product_id']."'");
              $orderproductarray[]=array(
                'order_product_id'=>$result['order_product_id'],
                'product_id'=>$result['product_id'],
                'quantity'=>$result['quantity'],
                'price'=>$result['price'],
                'total'=>$result['total'],
                'sku'=>$productname->row['sku'],
                'model'=>$productname->row['model'],
                'name'=>$productname->row['name']
              );
          }
          $data['products']=$orderproductarray;

          $data1['id']=$this->request->get['id'];
          $data1['tkn']=$this->request->get['tkn'];
          $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
          $data1['fname']=$userdetails->row['firstname'];
          $data1['lname']=$userdetails->row['lastname'];
          $data['id']=$this->request->get['id'];
          $data['tkn']=$this->request->get['tkn'];
          $data['header'] = $this->load->controller('b2b/header');
          $data['nav'] = $this->load->controller('b2b/nav',$data1);

          $cpnexits=$this->db->query("SELECT * FROM b2b_coupon_history WHERE order_id=".$this->request->get['order_id']);
          if($cpnexits->num_rows!='0'){
            $data['coupon']=1;
            $data['coupon_discount']=$cpnexits->row['discount_amount'];
            $data['totalafterdiscount']=$data['order']['total']-$data['coupon_discount'];
          }

            // var_dump($data['order']);

          $this->response->setOutput($this->load->view('b2b/orderdetails', $data));


        }
    }

    public function orderinfo($order_id) {
        $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE order_id='".$order_id."'");
        foreach ($orderarr->rows as $result){
          $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$result['customer_id']."'");
          $panno = $this->load->controller('api/b2bCustomerDetails/getpanno',$result['customer_id']);
          $orderarray=array(
            'order_id'=>$result['order_id'],
            'customer_id'=>$result['customer_id'],
            'name'=>$customername->row['name'],
            'comment'=>$result['comment'],
            'total'=>$result['total'],
            'order_status_id'=>$result['order_status_id'],
            'payment_status'=>$result['payment_status'],
            'payment_method'=>$result['payment_method'],
            'date_delivery'=>$result['date_delivery'],
            'email'=>$customername->row['email'],
            'telephone'=>$customername->row['telephone'],
            'delivery_address'=>$result['delivery_address'],
            'date_added'=>$result['date_added'],
            'receipt_no'=>$result['receipt_no'],
            'actual_pay'=>$result['actual_pay'],
            'pay_description'=>$result['pay_description'],
            'pay_date'=>$result['pay_date'],

            
            'pan_no'=> $panno
  
          );
      }
      return($orderarray);
     }
}