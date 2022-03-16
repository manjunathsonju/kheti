<?php

class ControllerApiGetcategoryproducts extends Controller{
    public function index(){
        $query = $this->db->query("SELECT p.product_id,name, p.image from oc_product p LEFT JOIN oc_product_to_category pc ON(p.product_id=pc.product_id) LEFT JOIN oc_product_description pd ON(p.product_id=pd.product_id) WHERE pc.category_id=".$_GET["category_id"]." AND pd.language_id=1");


            if($query->rows){
                foreach($query->rows as $result){
                    $productarray['products'][]=array(
                        'product_id'=> $result['product_id'],
                        'name'=>$result['name'],
                        'image'=> $result['image']
                    );
                }
            }else{
                $productarray['products'][]=NULL;
            }
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *'); //allow everybody
        header('Access-Control-Allow-Origin: http://codesheet.org'); //allow just one domain
        $this->response->setOutput(json_encode($productarray['products']));
    }
}