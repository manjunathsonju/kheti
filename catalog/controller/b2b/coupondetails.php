<?php
class ControllerB2bCoupondetails extends Controller {
	public function index() {
        if($this->load->controller('b2b/home/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){

            $sql="SELECT * FROM b2b_coupon WHERE coupon_id='".$this->request->get['coupon_id']."'";
            $coupdetails=$this->db->query($sql);
            if($coupdetails){
                $data['name']=$coupdetails->row['name'];
                $data['code']=$coupdetails->row['code'];
                $data['type']=$coupdetails->row['type'];
                $data['discount']=$coupdetails->row['discount'];
                $data['uses_total']=$coupdetails->row['uses_total'];
                $data['status']=$coupdetails->row['status'];

            }
            $data['coupon_id']=$this->request->get['coupon_id'];

            $data1['id']=$this->request->get['id'];
            $data1['tkn']=$this->request->get['tkn'];
            $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data1['fname']=$userdetails->row['firstname'];
            $data1['lname']=$userdetails->row['lastname'];
            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];
            $data['header'] = $this->load->controller('b2b/header');
            $data['nav'] = $this->load->controller('b2b/nav',$data1);
            $this->response->setOutput($this->load->view('b2b/coupondetails', $data));

        }
    }
}