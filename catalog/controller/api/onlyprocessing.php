<?php
Class ControllerApiOnlyprocessing extends Controller{
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
            $queryresult = $this->db->query("SELECT * from oc_order_product op  JOIN "."oc_order o ON (o.order_id=op.order_id) WHERE o.order_status_id ="."2"." AND op.order_id BETWEEN ".$_GET['from']." AND ".$_GET['to']." AND op.product_id=".$productid['product_id']);


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



// public function createCategory() {
//     if($this->request->post['key']=='cr3@t3c@t3g0r60ws'){
//       $target_dir1 = "image/oms/".$this->request->post['customer_id'];
//       if (!file_exists($target_dir1)) {
//           mkdir($target_dir1, 0777, true);
//       }
//       $already=0;

//       $target_dir = "image/oms/".$this->request->post['customer_id']."/";    
//       $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
//        $uploadOk = 1;
//        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

//         // Check if image file is a actual image or fake image
//         if(isset($_POST["submit"])) {
//           $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//           if($check !== false) {
//           $uploadOk = 1;
//           } else {
//             $json['error']= 'File is not image';
//             $uploadOk = 0;
//           }
//         }

//         // Check if file already exists
//         if (file_exists($target_file)) {
//           $already=1;
//           $uploadOk = 1;
//         }
        
//           // Check file size
//           if ($_FILES["fileToUpload"]["size"] > 500000) {
//               $json['error']= 'image too large';
//             $uploadOk = 0;
//           }

//         // Allow certain file formats
//         if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//         && $imageFileType != "gif" ) {
//             $json['error']= 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
//           $uploadOk = 0;
//         }
        
//         // Check if $uploadOk is set to 0 by an error
//         if ($uploadOk == 0) {
//             $json['error']= 'Sorry, your file was not uploaded.';
        
//           // if everything is ok, try to upload file
//         } else {
//             // var_dump($_FILES["fileToUpload"]["tmp_name"]);
//             //   var_dump($target_file);
//           if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
              
//             if($already){
//               $image_url= 'https://khetifood.com/image/khetilogo.png';
//             }else{
//               $image_url="http://khetifood.com/image/oms/".$this->request->post['customer_id']."/".htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
//             }
        
//             $product_name =$this->request->post['category_name'] ;
//           $customer_id =$this->request->post['customer_id'] ;
        
//           $this->db->query("INSERT INTO b2b_oms_category SET category_name= '" . $product_name . "', image = '" .$image_url. "', customer_id = '" . $customer_id ."'");

        
//             $json['success'] = 'file uploaded';
//             $json['image'] = $image_url;
//           } else {
//                $json['error']= 'Sorry, your file was not uploaded.';
//        }
//    }  
//     }
//     $this->response->addHeader('Content-Type: application/json');
//     $this->response->addHeader('Access-Control-Allow-Origin: *');
//     $this->response->addHeader('Access-Control-Allow-Headers: *');
//     $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
//     $this->response->addHeader('Access-Control-Max-Age: 600');
//     $this->response->setOutput(json_encode($json));

// }
