<?php
class ControllerApiB2bAddress extends Controller {
	public function index() {
        if($this->request->get['address']){
            $this->db->query("INSERT INTO b2b_address SET customer_id = '" . $this->request->get['customer_id']."' , address= '".$this->request->get['address']."' , contact_number= '".$this->request->get['contact_number']."' , detail_address= '".$this->request->get['detail_address']."'");
            $json['success']= 1;
            
        }else{
            $json['error']= 'failed';
        }
            $this->response->addHeader('Content-Type: application/json');
            $this->response->addHeader('Access-Control-Allow-Origin: *');
            $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
            $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
            $this->response->addHeader('Access-Control-Max-Age: 600');
            $this->response->setOutput(json_encode($json));
    }
    public function getAddress() {
        $adresult=$this->db->query("SELECT * FROM b2b_address where customer_id = '". $this->request->get['customer_id']."' ORDER BY id DESC");
        $id=0;

        foreach ($adresult->rows as $result){
             $addressarray[]=array(
              'address'=>$result['address'],
              'contact_number'=>$result['contact_number'],
              'detail_address'=>$result['detail_address'],
                'id'=> $result['id']
            );
        }
        if(!($adresult->rows)){
            $addressarray=array(
               );
        }
        $json['addresses']= $addressarray;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }
    public function deleteAddress() {
        $adresult=$this->db->query("DELETE FROM b2b_address where id = '". $this->request->get['id']."'");
        $json['success']= 1;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }
    public function editAddress() {
        if(isset($this->request->get['address'])||isset($this->request->get['contact_number'])||isset($this->request->get['detail_address'])){
            $sql="UPDATE b2b_address SET edit=1";
            if(isset($this->request->get['address'])){
                $sql=$sql.", address='".$this->request->get['address']."'";
            } if(isset($this->request->get['contact_number'])){
                $sql=$sql.", contact_number='".$this->request->get['contact_number']."'";
            } if(isset($this->request->get['detail_address'])){
                $sql=$sql.", detail_address='".$this->request->get['detail_address']."'";
            }
            $sql=$sql." where id = '". $this->request->get['id']."'";
            $adresult=$this->db->query($sql);
            $json['success']= 1;
        }else{
            $json['error']= 'No edit';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }
}