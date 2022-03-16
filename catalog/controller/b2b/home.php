<?php
class ControllerB2bHome extends Controller {
	public function index() {
        if($this->checklogin(array($this->request->get['id'],$this->request->get['tkn']))){

            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];

            $numbers=$this->db->query("SELECT COUNT(DISTINCT(customer_id)) as number_of_customer FROM order_b2b");
            $data['number_of_customers']=$numbers->row['number_of_customer'];

            $numbers10=$this->db->query("SELECT COUNT(order_id) as number FROM order_b2b WHERE order_status_id=1");
            $data['number_of_pending_orders']=$numbers10->row['number'];

            $numbers2=$this->db->query("SELECT COUNT(order_id) as number FROM order_b2b WHERE order_status_id=2");
            $data['number_of_processing_orders']=$numbers2->row['number'];

            $numbers3=$this->db->query("SELECT COUNT(order_id) as number FROM order_b2b WHERE order_status_id=3");
            $data['number_of_complete_orders']=$numbers3->row['number'];

            $data['number_of_orders']=(int)$data['number_of_pending_orders']+(int)$data['number_of_processing_orders']+(int)$data['number_of_complete_orders'];
            $data['percentage_of_pending_orders']=round(((int)$numbers10->row['number']/(int)$data['number_of_orders'])*100);
            $data['percentage_of_processing_orders']=round(((int)$numbers2->row['number']/(int)$data['number_of_orders'])*100);
            $data['percentage_of_complete_orders']=round(((int)$numbers3->row['number']/(int)$data['number_of_orders'])*100);

            
            $paid=$this->db->query("SELECT round(sum(total)) as number FROM order_b2b WHERE payment_status=1 AND order_status_id!=0");
            $data['paidsum']=$paid->row['number'];

            $unpaid=$this->db->query("SELECT round(sum(total)) as number FROM order_b2b WHERE payment_status!=1 AND order_status_id!=0");
            $data['unpaidsum']=$unpaid->row['number'];

            $data['totalsum']=round(((float)$data['paidsum']+(float)$data['unpaidsum'])/1000,2);

            $data['paidper']=round(((int)$paid->row['number']/(((float)$data['paidsum']+(float)$data['unpaidsum'])) )*100);
            $data['unpaidper']=round(((float)$unpaid->row['number']/(((float)$data['paidsum']+(float)$data['unpaidsum'])) )*100);


            $date=$this->db->query("SELECT MIN(date_added) as date FROM `order_b2b` WHERE order_status_id!=0 AND payment_status=0 AND date_added!='0000-00-00 00:00:00' ORDER BY date_added");
            $data['date1']=(date("jS F Y", strtotime($date->row['date'])));
            $data['header'] = $this->load->controller('b2b/header');
            $data1['id']=$this->request->get['id'];
            $data1['tkn']=$this->request->get['tkn'];
            $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data1['fname']=$userdetails->row['firstname'];
            $data1['lname']=$userdetails->row['lastname'];
          
            $data['nav'] = $this->load->controller('b2b/nav',$data1);
            // var_dump($data['header']);

            $this->response->setOutput($this->load->view('b2b/home', $data));
        }else{
            header("Location: https://khetifood.com/business/");
            exit();

        }

    }
       
       
    
       public function checklogin($useridarray) {
        if($useridarray[0]==NULL||$useridarray[1]==NULL){
            return FALSE;
        }
            $hash=$this->db->query("SELECT * FROM b2b_users where user_id='".$useridarray[0]."'");
       
            if($hash->row['token']==$useridarray[1]){
                return TRUE;
            }else{
                return FALSE;
            } 
    }
        public function weeklyPaymentStatus() { // api
        
            if($this->request->get['date_start']){
                $preday=$this->request->get['date_start'];
                        }
    
                if($this->request->get['date_end']){
                    $datestinge=$this->request->get['date_end'];
                    $nextdayw=strftime("%Y-%m-%d", strtotime("$datestinge +1 day"));
                    
                }
            $sqlunpaid="SELECT round(sum(total)) as number FROM order_b2b WHERE payment_status!=1 AND order_status_id!=0";
            if($this->request->get['date_start']){
                $sqlunpaid= $sqlunpaid. " AND date_added>'".$preday."'";
            }
            if($this->request->get['date_end']){
                $sqlunpaid= $sqlunpaid. " AND date_added<'".$nextdayw."'";
            }
            $unpaid_total=$this->db->query($sqlunpaid);
            $json['unpaid_total']=$unpaid_total->row[number];
    
            $sqlpaid="SELECT round(sum(total)) as number FROM order_b2b WHERE payment_status=1 AND order_status_id!=0";
            if($this->request->get['date_start']){
                $sqlpaid= $sqlpaid. " AND date_added>'".$preday."'";
            }
            if($this->request->get['date_end']){
                $sqlpaid= $sqlpaid. " AND date_added<'".$nextdayw."'";
            }
    
            $paid_total=$this->db->query($sqlpaid);
            $json['paid_total']=$paid_total->row[number];
    
    
            $unpaid_sum= $unpaid_total->row['number']; 
            $paid_sum= $paid_total->row['number'];
    
            //for list of orders
            $sqlunpaidorders="SELECT order_id FROM order_b2b WHERE payment_status!=1 AND order_status_id!=0";
            if($this->request->get['date_start']){
                
                $sqlunpaidorders= $sqlunpaidorders. " AND date_added>'".$preday ." 00:00:00"."'";
            }
            if($this->request->get['date_end']){
                $sqlunpaidorders= $sqlunpaidorders. " AND date_added<'".$nextdayw."'";
            }
            $unpaid_total_orders=$this->db->query($sqlunpaidorders);
                    // var_dump($unpaid_total_orders);
    
    
            foreach ($unpaid_total_orders->rows as $result){
                $orderarrayunpaid[]=array(
                   'order_id'=>$result['order_id'],
                );
             }
            //  var_dump($orderarrayunpaid);
            $sqlpaidorders="SELECT order_id FROM order_b2b WHERE payment_status=1 AND order_status_id!=0";
            if($this->request->get['date_start']){
                $sqlpaidorders= $sqlpaidorders. " AND date_added>'".$preday ." 00:00:00"."'";
            }
            if($this->request->get['date_end']){
                $sqlpaidorders= $sqlpaidorders. " AND date_added<'".$nextdayw."'";
            }
            $paid_total_orders=$this->db->query($sqlpaidorders); //list of paid orders id
            foreach ($paid_total_orders->rows as $result){
                $orderarraypaid[]=array(
                   'order_id'=>$result['order_id'],
                );
             }
            
            $json['array_paid']= $orderarraypaid;
            $json['array_unpaid']= $orderarrayunpaid;
    
            $this->response->setOutput(json_encode($json));
    
    }
    
}