<?php
class ControllerApiAppdash extends Controller {   
  public function index() {
    if($this->request->post['key']=='A@pC0mp0n3nt'){
      $target_dir1 = "image/app/".$this->request->post['component_id'];
      if (!file_exists($target_dir1)) {
          mkdir($target_dir1, 0777, true);
      }
      $already=0;

      $target_dir = "image/app/".$this->request->post['component_id']."/";    
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
                  $image_url="http://khetifood.com/image/app/".$this->request->post['component_id']."/".htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
               
                 $this->db->query("UPDATE `app_B2C_home` SET component_name='".$this->request->post['component_name']."', image='".$image_url."', href='".$this->request->post['href']."', type='".$this->request->post['type']."', filter='".$this->request->post['filter']."' WHERE component_id='".$this->request->post['component_id']."' AND location='".$this->request->post['location']."'");
              
                $json['success'] = 'file uploaded';
                $json['image_url'] = $image_url;

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

public function popop() {
  if($this->request->post['key']=='A@pC0mp0n3nt'){
    $target_dir1 = "image/app/popup";
    if (!file_exists($target_dir1)) {
        mkdir($target_dir1, 0777, true);
    }
    $already=0;

    $target_dir = "image/app/popup/";    
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
                $image_url="http://khetifood.com/image/app/popup/".basename( $_FILES["fileToUpload"]["name"]);
             
               $this->db->query("UPDATE `app_B2C_home` SET component_name='".$this->request->post['component_name']."', image='".$image_url."', href='".$this->request->post['href']."', type='".$this->request->post['type']."' WHERE location='popup'");
            
              $json['success'] = 'file uploaded';
              $json['image_url'] = $image_url;

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

public function disable() {
  if($this->request->post['key']=='A@pC0mp0n3ntenB'){
    $this->db->query("UPDATE `app_B2C_home` SET status=0 WHERE component_id='".$this->request->post['component_id']."' AND location='".$this->request->post['location']."'");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}

public function enable() {
  if($this->request->post['key']=='A@pC0mp0n3ntenB'){
    $this->db->query("UPDATE `app_B2C_home` SET status=1 WHERE component_id='".$this->request->post['component_id']."' AND location='".$this->request->post['location']."'");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}

public function disablepopup() {
  if($this->request->post['key']=='P0pupp'){
    $this->db->query("UPDATE `app_B2C_home` SET status=0 WHERE location='popup'");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}


public function enablepopup() {
  if($this->request->post['key']=='P0pupp'){
    $this->db->query("UPDATE `app_B2C_home` SET status=1 WHERE location='popup'");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}


public function disablecats() {
  if($this->request->post['key']=='A@pc@ts'){
    $this->db->query("UPDATE `app_categories` SET status=0 WHERE category_id='".$this->request->post['category_id']."' AND location='".$this->request->post['location']."'");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}


public function enablecats() {
  if($this->request->post['key']=='A@pcaTss'){
    $this->db->query("UPDATE `app_categories` SET status=1 WHERE category_id='".$this->request->post['category_id']."' AND location='".$this->request->post['location']."'");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}


public function disablevendors() {
  if($this->request->post['key']=='A@pc@ts'){
    $this->db->query("UPDATE `app_vendors` SET status=0 WHERE category_id='".$this->request->post['category_id']."' AND location='".$this->request->post['location']."'");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}


public function enablevendors() {
  if($this->request->post['key']=='A@pcaTss'){
    $this->db->query("UPDATE `app_vendors` SET status=1 WHERE category_id='".$this->request->post['category_id']."' AND location='".$this->request->post['location']."'");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}



public function getcomponentinfo() {
  if($this->request->post['key']=='g3TC0mp0n3ntenB'){
    $helodoc=$this->db->query("SELECT * FROM `app_B2C_home` WHERE component_id='".$this->request->post['component_id']."' AND location='".$this->request->post['location']."'");
    $json['info'] =array(
      "component_name"=>  $helodoc->row['component_name'],
      "status"=>  $helodoc->row['status'],
      "image"=>  $helodoc->row['image'],
      "href"=>  $helodoc->row['href'],
      "type"=>  $helodoc->row['type'],
      "filter"=>  $helodoc->row['filter'],
      "location"=>  $helodoc->row['location']
    );

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}

public function addcategory() {
  if($this->request->post['key']=='A@dcaTss'){

    $this->db->query("INSERT INTO `app_categories` (category_id,status,location) VALUES ('".$this->request->post['category_id']."','1','".$this->request->post['location']."')");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}

public function addbrand() {
  if($this->request->post['key']=='A@dcaTss'){

    $this->db->query("INSERT INTO `app_vendors` (category_id,status,location) VALUES ('".$this->request->post['category_id']."','1','".$this->request->post['location']."')");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}


public function deletevendor() {
  if($this->request->post['key']=='A@dcaTss'){
    $this->db->query("DELETE FROM `app_vendors` WHERE category_id='".$this->request->post['category_id']."' AND location='".$this->request->post['location']."'");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}


public function deletecategory() {
  if($this->request->post['key']=='A@dcaTss'){
    $this->db->query("DELETE FROM `app_categories` WHERE category_id='".$this->request->post['category_id']."' AND location='".$this->request->post['location']."'");
    $json['success'] =1;

  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');             
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}


}