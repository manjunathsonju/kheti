<?php
Class ControllerApiB2bMenu extends Controller{ 
    public function index(){
        $menu_no= $this->db->query("SELECT MAX(menu_id) as menuid FROM b2bmenu ");
         $menu_no1=  ($menu_no->row['menuid'])+1;
         
         $array = explode(",", substr($_POST['product_id'], 1, -1));
        //  var_dump($array);
         for($i=0;$i<sizeof($array);$i++){
         $this->db->query("INSERT INTO b2bmenu SET customer_id = '" . $this->request->post['customer_id'] . "', menu_id = '" . $menu_no1 . "', product_id = " .(int)$array[$i] ); 

          }

// $this->db->query("INSERT INTO b2bmenu SET customer_id = '" . $this->request->post['customer_id'] . "', menu_id = '" . '1' . "', product_id = '" . $this->request->post['product_id']  . "', description = '". 'null'."'"); 

$json['success'] = '1';
 
$this->response->addHeader('Content-Type: application/json');
$this->response->addHeader('Access-Control-Allow-Origin: *');
$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
$this->response->addHeader('Access-Control-Max-Age: 600');
$this->response->setOutput(json_encode($json));
}

public function getproducts(){
    $this->load->model('catalog/product');

    $getmenu=$this->db->query("SELECT * FROM b2bmenu WHERE customer_id ='". $this->request->post['customer_id']."'");

    foreach ($getmenu->rows as $result){
        $productidarray[]=array(
            'product_id'=>$result['product_id']
                   );
    }
   

    for($i=0;$i<sizeof($productidarray);$i++){
       
      $name= $this->db->query("SELECT name FROM oc_product_description WHERE product_id=".$productidarray[$i]['product_id'] ." and language_id='". 1 ."'");
      $image= $this->db->query("SELECT image FROM oc_product WHERE product_id=".$productidarray[$i]['product_id']);
      $image_url ="https://khetifood.com/image/".$image->row['image'];

    //   if($this->db->query("SELECT ROUND(price, 2) AS price FROM oc_product_special WHERE product_id=".$productidarray[$i]['product_id']." AND customer_group_id=3")){
    //       $price=$this->db->query("SELECT ROUND(price, 2) AS price FROM oc_product_special WHERE product_id=".$productidarray[$i]['product_id']." AND customer_group_id=3");
    //   }else{
         $price= $this->db->query("SELECT ROUND(price, 2) AS price FROM oc_product WHERE product_id=".$productidarray[$i]['product_id']);
    //   }

      $sku= $this->db->query("SELECT sku FROM oc_product WHERE product_id=".$productidarray[$i]['product_id']);


    $productsarray[]=array(
        'product_id'=> $productidarray[$i]['product_id'],
        'thumb'=> $image_url,
        'name'=> $name->row['name'],
        'special'=> "Rs. ".$price->row['price'],
        'sku'=> $sku->row['sku'],

               );
    }

    // var_dump($productsarray[0]['product_id']);
    // var_dump($productsarray[1]['product_id']);



    $json['products'] =   $productsarray;
    $this->response->addHeader('Content-Type: application/jsson');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
$this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));

}
public function deleteproduct(){
    $this->db->query("DELETE FROM b2bmenu WHERE customer_id='".$this->request->get['customer_id']."'"." AND product_id='".$this->request->get['product_id']."'");
        $json['success'] = '1';
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
        
}
}
