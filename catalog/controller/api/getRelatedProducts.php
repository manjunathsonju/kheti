<?php
class ControllerApiGetRelatedProducts extends Controller
{
    public function index()
    {
        $sql="SELECT related_id FROM `oc_product_related` WHERE product_id='".$this->request->get['product_id']."'";
        $query = $this->db->query($sql);
        foreach ($query->rows as $result){
        // echo($result[related_id]);
        // echo(' ');
        $name= $this->db->query("SELECT name FROM oc_product_description WHERE product_id=".$result[related_id]." and language_id='". 1 ."'");
        $image= $this->db->query("SELECT image FROM oc_product WHERE product_id=".$result[related_id]);
        $image_url ="https://khetifood.com/image/".$image->row['image'];
        $price= $this->db->query("SELECT ROUND(price, 2) AS price FROM oc_product WHERE product_id=".$result[related_id]);
        $sku= $this->db->query("SELECT sku FROM oc_product WHERE product_id=".$result[related_id]);
        $productrelatedarray[]=array(
            'product_id'=>$result[related_id],
            'name'=>$name->row['name'],
            'thumb'=>$image_url,
            'price'=>$price->row['price'],
            'sku'=>$sku->row['sku']);
        }
        // var_dump($productrelatedarray);
        $json['products'] = $productrelatedarray;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));

    }
}