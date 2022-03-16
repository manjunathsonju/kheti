<?php
class ControllerApiB2bOmsCategory extends Controller {
	public function index() {
        if($this->request->post['key']=='G3tc@t3g0r60ws'){
        $sql ="SELECT * FROM b2b_oms_category WHERE customer_id='".$this->request->post[customer_id]."'";
        $Categoryarr=$this->db->query($sql);
        // var_dump($Categoryarr);
        foreach ($Categoryarr->rows as $result){
            $Categoryarray[]=array(
                'category_id'=>$result['category_id'],
                'category_name'=>$result['category_name'],
                'image'=>$result['image'],
                'customer_id'=>$result['customer_id']
            );
        
        }
        $json['Categoryarray']=$Categoryarray;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Origin: *'); //allow everybody
        // header('Access-Control-Allow-Origin: http://localhost:3000');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }

    }


    public function createCategory() {
        if($this->request->post['key']=='cr3@t3c@t3g0r60ws'){
          $target_dir1 = "image/oms/".$this->request->post['customer_id'];
          if (!file_exists($target_dir1)) {
              mkdir($target_dir1, 0777, true);
          }
          $already=0;
    
          $target_dir = "image/oms/".$this->request->post['customer_id']."/";    
          $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
           $uploadOk = 1;
           $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
              $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
              if($check !== false) {
              $uploadOk = 1;
              } else {
                $json['error']= 'File is not image';
                $uploadOk = 0;
              }
            }
    
            // Check if file already exists
            if (file_exists($target_file)) {
              $already=1;
              $uploadOk = 1;
            }
            
              // Check file size
              if ($_FILES["fileToUpload"]["size"] > 500000) {
                  $json['error']= 'image too large';
                $uploadOk = 0;
              }
    
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $json['error']= 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
              $uploadOk = 0;
            }
            
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                // $json['error']= 'Sorry, your file was not uploaded.';
            
              // if everything is ok, try to upload file
            } else {
                // var_dump($_FILES["fileToUpload"]["tmp_name"]);
                //   var_dump($target_file);
                // $json['hello'] = $uploadOk;
                // var_dump();
                $json['size']=$_FILES["fileToUpload"];
              if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $json['bye'] = $already;
                if($already){
                  $image_url= 'https://khetifood.com/image/khetilogo.png';
                }else{
                  $image_url="http://khetifood.com/image/oms/".$this->request->post['customer_id']."/".htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
                }
            
                $product_name =$this->request->post['category_name'] ;
              $customer_id =$this->request->post['customer_id'] ;
            
              $this->db->query("INSERT INTO b2b_oms_category SET category_name= '" . $product_name . "', image = '" .$image_url. "', customer_id = '" . $customer_id ."'");

            
                $json['success'] = 'file uploaded';
                $json['image'] = $image_url;
              } else {
                   $json['error']= 'Sorry, your file was not uploaded.';
           }
       }
                 
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }


    public function deleteCategory() {
      if($this->request->post['key']=='0m$d3ltek@t'){
        $sql ="DELETE FROM `b2b_oms_category` WHERE category_id='".$this->request->post['category_id']."'";
        $delete=$this->db->query($sql);
        $json['success']="Success";
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));

      }
    }

    public function deleteProduct() {
      if($this->request->post['key']=='0m$d3ltePr0'){
        $sql ="DELETE FROM `b2b_oms_products` WHERE product_id='".$this->request->post['product_id']."'";
        $delete=$this->db->query($sql);
        $json['success']="Success";
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');        
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));


      }
    }




    public function getProducts() {
        if($this->request->post['key']=='G3tC@tPr00ws'){
        $sql ="SELECT * FROM b2b_oms_products WHERE category_id='".$this->request->post[category_id]."'";
        $productarr=$this->db->query($sql);
        // var_dump($Categoryarr);
        foreach ($productarr->rows as $result){
            $productarray[]=array(
                'product_id'=>$result['product_id'],
                'product_name'=>$result['product_name'],
                'image'=>$result['image'],
                'price'=>$result['price'],
                'sku'=>$result['sku'],
                'category_id'=>$result['category_id'],
                'customer_id'=>$result['customer_id'],

            );
        
        }
        $json['productarray']=$productarray;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }

    }

    public function createCategoryProduct() {
        if($this->request->post['key']=='cr3@t3c@t3g0rp60ws'){
            $target_dir1 = "image/oms-products/".$this->request->post['customer_id'];
            if (!file_exists($target_dir1)) {
                mkdir($target_dir1, 0777, true);
            }
            $already=0;

            $target_dir = "image/oms-products/".$this->request->post['customer_id']."/";    
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                  $uploadOk = 1;
                  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                  
                  // Check if image file is a actual image or fake image
                  if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                    if($check !== false) {
                    $uploadOk = 1;
                    } else {
                      $json['error']= 'File is not image';
                      $uploadOk = 0;
                    }
                  }
                  
                  // Check if file already exists
                  if (file_exists($target_file)) {
                    $already=1;
                    $uploadOk = 1;
                  }
                  
                  // Check file size
                  if ($_FILES["fileToUpload"]["size"] > 500000) {
                      $json['error']= 'image too large';
                    $uploadOk = 0;
                  }
                  
                  // Allow certain file formats
                  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                  && $imageFileType != "gif" ) {
                      $json['error']= 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
                    $uploadOk = 0;
                  }
                  
                  // Check if $uploadOk is set to 0 by an error
                  if ($uploadOk == 0) {
                      $json['error']= 'Sorry, your file was not uploaded.';
                  
                    // if everything is ok, try to upload file
                  } else {
                      // var_dump($_FILES["fileToUpload"]["tmp_name"]);
                      //   var_dump($target_file);
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        
                      if($already){
                        $image_url= 'https://khetifood.com/image/khetilogo.png';
                      }else{
                        $image_url="http://khetifood.com/image/oms-products/".$this->request->post['customer_id']."/".htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
                      }
                  
                  
                      $sql="INSERT INTO b2b_oms_products (product_name,image,price,sku,category_id,customer_id) VALUES ('".$this->request->post['product_name']."','".$image_url."','".$this->request->post['price']."','".$this->request->post['sku']."','".$this->request->post['category_id']."','".$this->request->post['customer_id']."')";
                  
                      $this->db->query($sql);
                      $json['success'] = 'file uploaded';
                    } else {
                      $json['error']= 'failed';
                    }
                  }            
                  $this->response->addHeader('Content-Type: application/json');
                  $this->response->addHeader('Access-Control-Allow-Origin: *');             
                  $this->response->addHeader('Access-Control-Allow-Headers: *');
                  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
                  $this->response->addHeader('Access-Control-Max-Age: 600');
                  $this->response->setOutput(json_encode($json));
        
        }

    }
}