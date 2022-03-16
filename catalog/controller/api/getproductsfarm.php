<?php

class ControllerApiGetproductsfarm extends Controller{
    public function index(){
        $query = $this->db->query("SELECT pd.product_id,pd.name from oc_product_description pd LEFT JOIN oc_product_to_store ps ON(pd.product_id=ps.product_id) WHERE ps.store_id='0' AND pd.language_id='1'");
        foreach($query->rows as $result){
            $productarray['products'][]=array(
                'product_id'=> $result['product_id'],
                'name'=>$result['name']
            );
        }

        header('Content-Type: application/json');
        $this->response->setOutput(json_encode($productarray['products']));
    }
}