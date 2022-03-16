<?php
class ControllerApiUpload extends Controller
{
    public function index()
    {
      $already=0;

        $target_dir = "image/";
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
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    if($already){
      $image_url= 'https://khetifood.com/image/khetilogo.png';
    }else{
      $image_url='http://khetifood.com/image/'.htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
    }

    $product_name =$this->request->post['product_name'] ;
    $customer_id =$this->request->post['customer_id'] ;

    $this->db->query("INSERT INTO products_upload_b2b SET product_name= '" . $product_name . "', image = '" .$image_url. "', customer_id = '" . $customer_id ."'");

    $json['success'] = 'file uploaded';
  } else {
    $json['error']= 'Sorry, your file was not uploaded.';
  }
}


$this->response->addHeader('Content-Type: application/json');
$this->response->addHeader('Access-Control-Allow-Origin: *');
$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
$this->response->addHeader('Access-Control-Max-Age: 600');
$this->response->setOutput(json_encode($json));

    }
}