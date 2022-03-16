<?php
class ControllerApiOms extends Controller {
	public function index() {
		$this->load->language('api/login');
		$json = array();
        $this->load->model('account/api');
        //login for b2b
        $sql="SELECT * FROM `oc_customer` WHERE email='".$this->request->post['email']."'";
        $customer_info=$this->db->query($sql);     

        $group_id=(int)$customer_info->row['customer_group_id'];
        if($group_id==3){

        if(($this->customer->login($this->request->post['email'], $this->request->post['password']))){
                // produce a token
                
                // insert token and id in table , table made
                $sql="SELECT * FROM `b2b_oms_token` WHERE customer_id='".$customer_info->row['customer_id']."'";
                $tokn=$this->db->query($sql);
                $token=token(10);

                if($tokn->num_rows!='0'){
                    // $json['in-table']=1; // table ma xa
                    $sql="UPDATE `b2b_oms_token` SET token='".$token."' WHERE customer_id='".$customer_info->row['customer_id']."'";
                    $tokn=$this->db->query($sql);
                }else{
                    // $json['in-table']=0; //xaina
                  
                    $sql="INSERT INTO `b2b_oms_token` (customer_id,token) VALUES ('".$customer_info->row['customer_id']."','". $token."')";
                    $tokn=$this->db->query($sql);
                }
                $json['token']=$token;
                $json['i']=$customer_info->row['customer_id'];

                $json['login']= 1;
        }else{
            $json['error']= 'login Failed';
        }

        }else{
            $json['error']= 'You are not Authorized';
        }

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

    }
}