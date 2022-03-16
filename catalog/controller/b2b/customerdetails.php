<?php
class Controllerb2bcustomerdetails extends Controller {
	public function index() {
        if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){

            $data['totalall']=0;  
            $data['totalallunpaid']=0;          
        
            $data['totalnoofsales']=0;
            $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE customer_id='".$this->request->get['customer_id']."' AND order_status_id!=0");
            foreach ($orderarr->rows as $result){
              if($result['payment_status']==1){
                $data['totalall']=$data['totalall']+$result['total'];
              }elseif($result['payment_status']==0){
                $data['totalallunpaid']=$data['totalallunpaid']+$result['total'];
              }
              $data['totalnoofsales']=$data['totalnoofsales']+1;
              $orderarray[]=array(
                'order_id'=>$result['order_id'],
                'total'=>$result['total'],
                'order_status_id'=>$result['order_status_id'],
                'payment_status'=>$result['payment_status'],
                'date_added'=>$result['date_added']
              );
          }

          $data['orders']=$orderarray;
          $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$this->request->get['customer_id']."'");
          $data['name']= $customername->row['name'];
          $data['email']= $customername->row['email'];
          $data['telephone']= $customername->row['telephone'];
          $data['customer_id']= $this->request->get['customer_id'];


          $data['header'] = $this->load->controller('b2b/header');
          $data1['id']=$this->request->get['id'];
          $data1['tkn']=$this->request->get['tkn'];
          $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data1['fname']=$userdetails->row['firstname'];
            $data1['lname']=$userdetails->row['lastname'];
          $data['id']=$this->request->get['id'];
          $data['tkn']=$this->request->get['tkn'];
        
          $data['nav'] = $this->load->controller('b2b/nav',$data1);
          $data['pan_no']=$this->getpanno($this->request->get['customer_id']);

          $this->response->setOutput($this->load->view('b2b/customerdetails', $data));

        }



    }

    public function getpanno($id){
        $noo=$this->db->query("SELECT pan_no FROM b2b_PAN WHERE customer_id='".$id."'");
        if($noo){
          return  $noo->row['pan_no'];
        }
       }

    public function setpanno(){
        if($this->request->get['pass']='7823av'){
         $noo1=$this->db->query("SELECT pan_no FROM b2b_PAN WHERE customer_id='".$this->request->get['customer_id']."'");
         if($noo1->row['pan_no']==NULL){
           $sql="INSERT INTO b2b_PAN (customer_id,pan_no) VALUES ('".$this->request->get['customer_id']."','".$this->request->get['no']."')";
           $noo=$this->db->query($sql);
         }else{

           $noo=$this->db->query("UPDATE b2b_PAN SET pan_no='".$this->request->get['no']."' WHERE customer_id='".$this->request->get['customer_id']."'");       
         }
         if($noo){
           $json['success']= 1;
           $this->response->setOutput(json_encode($json));
         }
        }
      }
}