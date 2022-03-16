<?php
Class ControllerApiCompiledTotalProcessing extends Controller{
    public function index(){

        echo"Calculating from ".$_GET['from']. " to ". $_GET['to']."</br>";
        $productidarr=$this->db->query("SELECT DISTINCT op.product_id, cd.name from oc_order_product op JOIN oc_product p ON (op.product_id=p.product_id) JOIN oc_product_to_category ptc ON (op.product_id=ptc.product_id) JOIN oc_category_description cd ON (ptc.category_id=cd.category_id) where op.order_id BETWEEN ".$_GET['from'] ." AND ".$_GET['to']." AND p.location='ktm' AND cd.language_id='1' ORDER BY cd.category_id ");
        foreach ($productidarr->rows as $result){
            $productidarray[]=array(
                'product_id'=>$result['product_id'],
                'name'=>$result['name']
            );
        }
//        var_dump($productidarray);
        foreach ($productidarray as $productid){
            echo "</br>"."</br>"."Product ID: ". $productid['product_id']."</br>";
            echo "Product category: " . $productid['name']."</br>";
            $productname= $this->db->query("SELECT name from oc_product_description WHERE product_id=".$productid['product_id']);
            echo "Name: ".$productname->row['name']."</br>";
            $productsku=$this->db->query("SELECT sku from oc_product WHERE product_id=".$productid['product_id']);
            echo "SKU: ".$productsku->row['sku']."</br>";
            $productlocation=$this->db->query("SELECT location from oc_product WHERE product_id=".$productid['product_id']);
            echo "Product Location: ".$productlocation->row['location']."</br>";
//            $queryresult = $this->db->query("SELECT * from oc_order_product op where op.order_id BETWEEN ".$_GET['from']." AND ".$_GET['to']." AND op.product_id=".$productid." JOIN "."oc_order o ON (o.order_id=op.order_id) WHERE o.order_status_id !="."7");
//            var_dump($queryresult);
            $queryresult = $this->db->query("SELECT * from oc_order_product op  JOIN "."oc_order o ON (o.order_id=op.order_id) WHERE o.order_status_id = "."2"." AND op.order_id BETWEEN ".$_GET['from']." AND ".$_GET['to']." AND op.product_id=".$productid['product_id']);


            $total=0;
            echo "The product is in following orders id:";
            foreach ($queryresult->rows as $result1) {
                echo($result1['order_id']."\t");
                $total = $total + $result1['quantity'];
            }
            echo "</br>"."Total Quantity=".($total);
            unset($total);
        }

    }
}
