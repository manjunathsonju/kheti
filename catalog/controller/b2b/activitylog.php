<?php
class ControllerB2bActivitylog extends Controller {
	public function index() {
        if($this->load->controller('b2b/home/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
            $data1['id']=$this->request->get['id'];
            $data1['tkn']=$this->request->get['tkn'];
            $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data1['fname']=$userdetails->row['firstname'];
            $data1['lname']=$userdetails->row['lastname'];
  
            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];
            $data['header'] = $this->load->controller('b2b/header');
            $data['nav'] = $this->load->controller('b2b/nav',$data1);

            $activities=$this->db->query("SELECT a.*,b.firstname,b.lastname FROM `b2b_activity_log_dashboard` a JOIN `b2b_users` b ON (a.user=b.user_id) ORDER BY a.activity_id");
            var_dump($activities);


            $this->response->setOutput($this->load->view('b2b/activitylog', $data));


        }
    }
}