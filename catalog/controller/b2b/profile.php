<?php
class ControllerB2bProfile extends Controller {
	public function index() {
        if($this->load->controller('b2b/home/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){

           
            $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data['fname']=$userdetails->row['firstname'];
            $data['lname']=$userdetails->row['lastname'];
            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];
            $data['header'] = $this->load->controller('b2b/header');
            $data['nav'] = $this->load->controller('b2b/nav',$data);
            $data['a']='';
            $this->response->setOutput($this->load->view('b2b/profile', $data));

        }
    }

    public function reset() {
        if($this->load->controller('b2b/home/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){

            $simple_string = $this->request->get['pass'];
            //  cipher method 
            $ciphering = "AES-128-CTR"; 
            // Use OpenSSl Encryption method 
            $iv_length = openssl_cipher_iv_length($ciphering); 
            $options = 0; 
            // Non-NULL Initialization Vector for encryption 
            $encryption_iv = '1236667891011121'; 
            $encryption_key = "satanrajiv"; 
            // Use openssl_encrypt() function to encrypt the data 
            $encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv); 
            // $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");

            $this->db->query("UPDATE `b2b_users` SET firstname='".$this->request->get['fname']."',lastname='".$this->request->get['lname']."',password='".$encryption."' WHERE user_id='".$this->request->get['id']."'");
           
           
            


           
           
            $data['fname']=$this->request->get['fname'];
            $data['lname']=$this->request->get['lname'];
            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];
            $data['header'] = $this->load->controller('b2b/header');
            $data['nav'] = $this->load->controller('b2b/nav',$data);
            $data['reset']=1;
            $this->response->setOutput($this->load->view('b2b/profile', $data));

        }
    }


}