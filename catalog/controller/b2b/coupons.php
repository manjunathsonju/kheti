<?php
class ControllerB2bCoupons extends Controller {
	public function index() {
        if($this->load->controller('b2b/home/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
            // var_dump("fuck");
            $couparr=$this->db->query("SELECT * FROM b2b_coupon");
            foreach ($couparr->rows as $result){
              $data['coupons'][]=array(
                'coupon_id'=>$result['coupon_id'],
                'name'=>$result['name'],
                'code'=>$result['code'],
                'type'=>$result['type'],
                'discount'=>$result['discount'],
                'uses_total'=>$result['uses_total']
              );
            }
            $data1['id']=$this->request->get['id'];
            $data1['tkn']=$this->request->get['tkn'];
            $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data1['fname']=$userdetails->row['firstname'];
            $data1['lname']=$userdetails->row['lastname'];
            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];
            $data['header'] = $this->load->controller('b2b/header');
            $data['nav'] = $this->load->controller('b2b/nav',$data1);
            $this->response->setOutput($this->load->view('b2b/coupons', $data));


        }
    }
}