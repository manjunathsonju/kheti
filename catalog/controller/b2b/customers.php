<?php
class ControllerB2bCustomers extends Controller {
	public function index() {
        if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){

            if($this->request->get['location']){
                $location=$this->request->get['location'];
              }else{
                $location=0;
              }
            $data['customers']=$this->getAllCustomers($location);
            $data['header'] = $this->load->controller('b2b/header');


            $data1['id']=$this->request->get['id'];
            $data1['tkn']=$this->request->get['tkn'];
            $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data1['fname']=$userdetails->row['firstname'];
            $data1['lname']=$userdetails->row['lastname'];
            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];
          
            $data['nav'] = $this->load->controller('b2b/nav',$data1);
            $this->response->setOutput($this->load->view('b2b/customers', $data));


        }



    }

    public function getAllCustomers(int $location) {
        if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
            if($this->request->get['location']=='2'){
                $customerarr=$this->db->query("SELECT DISTINCT(o.customer_id) as customer_ids FROM order_b2b o LEFT JOIN customer_locations c ON (c.customer_id=o.customer_id) WHERE c.location='".$location."'");
      
            }elseif($this->request->get['location']=='1'){
                $customerarr=$this->db->query("SELECT DISTINCT(o.customer_id) as customer_ids FROM order_b2b o LEFT JOIN customer_locations c ON (c.customer_id=o.customer_id) WHERE (c.location IS NULL OR c.location=1)");
    
            }else{
                $customerarr=$this->db->query("SELECT DISTINCT(customer_id) as customer_ids FROM order_b2b");
    
            }
             foreach ($customerarr->rows as $result){
                $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$result['customer_ids']."'");
                $customerarray[]=array(
                    'customer_id'=>$result['customer_ids'],
                    'name'=>$customername->row['name'],
                    'email'=>$customername->row['email'],
                    'telephone'=>$customername->row['telephone']
                );
    
            }
            return($customerarray);
        }
    }
}