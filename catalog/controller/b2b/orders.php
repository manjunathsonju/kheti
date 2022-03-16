<?php
class ControllerB2bOrders extends Controller {
	public function index() {
        if($this->load->controller('b2b/home/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){

            if($this->request->get['location']){
              // $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE order_status_id='".$this->request->get['order_status_id']."' ORDER BY order_id DESC");
              if($this->request->get['location']==1){
                if($this->request->get['filter_date']){
                  $date=$this->request->get['filter_date'].' 00:00:00';
                  $date1=$this->request->get['filter_date'].' 59:59:59';

                  $orderarr=$this->db->query("SELECT o.order_id as order_id, o.customer_id as customer_id , o.total as total, o.order_status_id as order_status_id, o.payment_status as payment_status,o.date_added as date_added FROM order_b2b o LEFT JOIN customer_locations c ON (c.customer_id=o.customer_id) WHERE o.order_status_id='".$this->request->get['order_status_id']."' AND (c.location IS NULL OR c.location=1) AND o.date_added>'".$date."' AND o.date_added<'".$date1."' ORDER BY o.order_id DESC");

                }else{
                  $orderarr=$this->db->query("SELECT o.order_id as order_id, o.customer_id as customer_id , o.total as total, o.order_status_id as order_status_id, o.payment_status as payment_status,o.date_added as date_added FROM order_b2b o LEFT JOIN customer_locations c ON (c.customer_id=o.customer_id) WHERE o.order_status_id='".$this->request->get['order_status_id']."' AND (c.location IS NULL OR c.location=1) ORDER BY o.order_id DESC");

                }
              }else{

                if($this->request->get['filter_date']){
                  $date=$this->request->get['filter_date'].' 00:00:00';
                  $date1=$this->request->get['filter_date'].' 59:59:59';

                  $orderarr=$this->db->query("SELECT * FROM order_b2b o JOIN customer_locations c ON (c.customer_id=o.customer_id) WHERE o.order_status_id='".$this->request->get['order_status_id']."' AND c.location='".$this->request->get[location]."' AND o.date_added>'".$date."' AND o.date_added<'".$date1."' ORDER BY o.order_id DESC");

                }else{
                  $orderarr=$this->db->query("SELECT * FROM order_b2b o JOIN customer_locations c ON (c.customer_id=o.customer_id) WHERE o.order_status_id='".$this->request->get['order_status_id']."' AND c.location='".$this->request->get[location]."' ORDER BY o.order_id DESC");
                }
              }
            }else{

              if($this->request->get['filter_date']){
                $date=$this->request->get['filter_date'].' 00:00:00';
                $date1=$this->request->get['filter_date'].' 59:59:59';

                $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE order_status_id='".$this->request->get['order_status_id']."' AND date_added>'".$date."' AND date_added<'".$date1."' ORDER BY order_id DESC");

              }else{
              $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE order_status_id='".$this->request->get['order_status_id']."' ORDER BY order_id DESC");
              }
            }

              foreach ($orderarr->rows as $result){
                $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$result['customer_id']."'");
                $orderarray[]=array(
                  'order_id'=>$result['order_id'],
                  'customer_id'=>$result['customer_id'],
                  'name'=>$customername->row['name'],
                  'comment'=>$result['comment'],
                  'total'=>$result['total'],
                  'order_status_id'=>$result['order_status_id'],
                  'payment_status'=>$result['payment_status'],
                  'date_added'=>$result['date_added'],
                  'email'=>$customername->row['email'],
                  'telephone'=>$customername->row['telephone']
                );
            }
            $data['orders']=$orderarray;

            $data['header'] = $this->load->controller('b2b/header');
            
            $data['order_status_id']=$this->request->get['order_status_id'];


            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];




            $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data1['fname']=$userdetails->row['firstname'];
            $data1['lname']=$userdetails->row['lastname'];

            $data1['id']=$this->request->get['id'];
            $data1['tkn']=$this->request->get['tkn'];

            $data['nav'] = $this->load->controller('b2b/nav',$data1);
            // var_dump($data['header']);

            $this->response->setOutput($this->load->view('b2b/orders', $data));


          }else{header("Location: https://khetifood.com/business/");
            exit();
          }
    
    }
}