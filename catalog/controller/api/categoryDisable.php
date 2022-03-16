<?php
class ControllerApiCategoryDisable extends Controller {
	public function index() {
        $products=$this->db->query("SELECT * FROM `oc_product_to_category` WHERE category_id='".216."'");
        foreach ($products->rows as $result){
            // $productarray[]=array(
            //     'product_id'=>$result['product_id']);
            $this->db->query("UPDATE oc_product SET status=0 WHERE product_id='".$result['product_id']."'");
        }
        // var_dump($productarray);
        
        $json['success']=1;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));

    }
    public function categoryEnable(){
        $products=$this->db->query("SELECT * FROM `oc_product_to_category` WHERE category_id='".216."'");
        foreach ($products->rows as $result){
            // $productarray[]=array(
            //     'product_id'=>$result['product_id']);
            $this->db->query("UPDATE oc_product SET status=1 WHERE product_id='".$result['product_id']."'");
        }
        // var_dump($productarray);
        
        $json['success']=1;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));

    }
}