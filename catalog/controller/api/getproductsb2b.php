<?php
class ControllerApiGetproductsb2b extends Controller
{
    public function index()
    {
        if($_GET['secret'] == "U7sw3jr69r13"){    
            if($_GET['category_id']){
                if($_GET['category_id']=='171'){
                    $category_id='240'; // veg
                }elseif($_GET['category_id']=='172'){
                    $category_id='239'; // fruit

                }elseif($_GET['category_id']=='215'){
                    $category_id='241'; //groceries

                }else{
                    $category_id=$_GET['category_id'];
                }
                if($_GET['location']){
                    $location=($_GET['location']=='1') ? "ktm" : "pkr";
                    $sql="SELECT p.product_id,pd.name,p.sku,p.image, ROUND(p.price, 2) AS price FROM oc_product p JOIN oc_product_description pd ON (pd.product_id=p.product_id) JOIN oc_product_to_category pc ON (pc.product_id=p.product_id) WHERE pc.category_id='".$category_id."' AND pd.language_id=".$_GET['language_id']." AND p.location IN('".$location."','ktm,pkr')";

                }else{
                    $sql="SELECT p.product_id,pd.name,p.sku,p.image, ROUND(p.price, 2) AS price FROM oc_product p JOIN oc_product_description pd ON (pd.product_id=p.product_id) JOIN oc_product_to_category pc ON (pc.product_id=p.product_id) WHERE pc.category_id='".$category_id."' AND pd.language_id=".$_GET['language_id']." AND p.location IN ('ktm','ktm,pkr')";

                }
                        // var_dump($sql);

                $query = $this->db->query($sql);
                foreach ($query->rows as $result){
                    $products[]=array(
                        'product_id'=>$result['product_id'],
                        'name'=>$result['name'],
                        'sku'=>$result['sku'],
                        'thumb'=>"https://khetifood.com/image/".$result['image'],
                        'special'=>"Rs. ".$result['price']
                    );
                }


            }else{
                $json['error']='no category id';
            }
    
    }else{
        $json['error']='wrong password';

    }

    
    $json['products'] = $products;
    $this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
$this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));
  }
}