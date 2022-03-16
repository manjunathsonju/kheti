<?php
Class ControllerApiB2bCustomerDetails extends Controller{ 
    public function index(){
      $this->load->language('b2bDashboardComponents/components');

      if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
        $totalall=0;  
            $totalallunpaid=0;          
        
            $totalnoofsales=0;
            $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE customer_id='".$this->request->get['customer_id']."' AND order_status_id!=0");
            foreach ($orderarr->rows as $result){
              if($result['payment_status']==1){
                $totalall=$totalall+$result['total'];
              }elseif($result['payment_status']==0){
                $totalallunpaid=$totalallunpaid+$result['total'];
              }
                $totalnoofsales=$totalnoofsales+1;
              $orderarray[]=array(
                'order_id'=>$result['order_id'],
                'total'=>$result['total'],
                'order_status_id'=>$result['order_status_id'],
                'payment_status'=>$result['payment_status'],
                'date_added'=>$result['date_added']
              );
          }
          $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$this->request->get['customer_id']."'");
          $name= $customername->row['name'];
          $email= $customername->row['email'];
          $telephone= $customername->row['telephone'];
     
          echo('<!doctype html>
          <html lang="en">
          <head>
          <title>Customers</title>');
          echo($this->language->get('header_script'));
          echo($this->language->get('style'));
          echo('<style>td{border-style: solid;
            border-width: 0.5px;}
            </style>');
          echo('</head>
          <body><div class="wrapper">');
          echo($this->language->get('nav1')); 
          echo('<a href="https://khetifood.com/index.php?route=api/b2bDashHome&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-dashboard fw" aria-hidden="true" style="padding:5px;"></i>Dashboard</a>
            </li>
            <li class="active">
                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle"><i class="fa fa-shopping-cart" aria-hidden="true" style="padding:5px;"></i>Orders</a>
                <ul class="collapse list-unstyled active" id="homeSubmenu">
                    <li>
                        <a href="https://khetifood.com/index.php?route=api/b2bDash&order_status_id=1&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-hourglass-1" aria-hidden="true" style="padding:5px;"></i>Pending Orders</a>
                    </li>
                    <li>
                        <a href="https://khetifood.com/index.php?route=api/b2bDash&order_status_id=2&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-spinner" aria-hidden="true" style="padding:5px;"></i>Processing Orders</a>
                    </li>
                    <li>
                        <a href="https://khetifood.com/index.php?route=api/b2bDash&order_status_id=3&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-handshake-o" aria-hidden="true" style="padding:5px;"></i>Completed Orders</a>
                    </li>
                    <li>
                        <a href="https://khetifood.com/index.php?route=api/b2bDash&order_status_id=7&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-stop-circle-o" aria-hidden="true" style="padding:5px;"></i>Canceled Orders</a>
                    </li>
                </ul>
            </li>
            <li>
            <a href="https://khetifood.com/index.php?route=api/b2bCompileOrders&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-bar-chart" aria-hidden="true" style="padding:5px;"></i>Orders Accumulation</a>
            </li>
        
            <li>
            <a href="https://khetifood.com/index.php?route=api/b2bDashCustomers&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-users" aria-hidden="true" style="padding:5px;"></i>Customers</a>
            </li>
            <li class="active">
    <a href="#homereport" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle"><i class="fa fa-file-text" aria-hidden="true" style="padding:5px;"></i>Reports</a>
    <ul class="collapse list-unstyled active" id="homereport">
        <li>
            <a href="https://khetifood.com/index.php?route=api/b2bReports&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-user" aria-hidden="true" style="padding:5px;"></i>SalesPerson report</a>
        </li>
        
    </ul>
</li> 

            <li>
            <a href="https://khetifood.com/index.php?route=api/b2bCoupons&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-diamond" aria-hidden="true" style="padding:5px;"></i> Coupons</a>
            </li>
            ');
            echo($this->language->get('nav2'));
                echo('<table class="table table-bordered" style="width:100%; margin:20px auto;">
              <thead>
                <tr>
                 <th>Customer Details</th>
                 <td><i class="fa fa-address-card" aria-hidden="true" style="padding: 5px;"></i>Customer Id: '.$this->request->get['customer_id'].'</td>                  
                 
                </tr>
              </thead>
              <tbody>
                <tr>
                  
                  <td> <i class="fa fa-user" aria-hidden="true" style="padding:5px;"></i>'.$name.'</td>
                  <td>Total number of orders: '.$totalnoofsales.'</td>

                </tr>
                <tr>
                <td><i class="fa fa-mobile" aria-hidden="true" style="padding: 5px;"></i>'.$telephone.'</td>   
                <td>Total Paid Transaction Amount: Rs.'.$totalall.'</td>

                </tr>
                <tr>
                  <td scope="row"><i class="fa fa-envelope-o" aria-hidden="true" style="padding: 5px;"></i>'.$email.'</td>
                  <td>Total UnPaid Transaction Amount: Rs.'.$totalallunpaid.'</td>

                  
                </tr>');
                $pan_no=$this->getpanno($this->request->get['customer_id']);

                

                echo('<tr><td><div class="input-group-prepend" style="display: block;width: 100%;margin: 5px 0;">
                <span class="input-group-text" id="basic-addon3">PAN no:<span id="update_text_pan">'.$pan_no.'</span>

                &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-info" id="updatebtnpan" >Update</button>

              </div>
              <form id="myForm3" style="width: 100%;display:none;"> 
              <input type="text" style="width: 76%;padding: 3px;" id="pan_text" aria-describedby="basic-addon3" >
              <button type="button" class="btn btn-success" id="pan_save" ><i class="fa fa-save"></i></button>
              </form></td></tr>        
              </tbody>
            </table>
          
            <!-- customer details -->
            <!-- tables -->');
            echo('<table id="example" style=" max-width: 100%;">

              
                      <thead>
                      <tr>
                      <th scope="col">Order Id</th>
                      <th scope="col">Status</th>
                      <th scope="col">Total</th>
                      <th scope="col">Payment status</th>
                      <th scope="col">Date Added</th>
                      <th scope="col">Action</th>
                    </tr>
                      </thead>
                      <tbody>');

                        foreach ($orderarray as $orderarray1){

                        echo('<tr>
                          <td>'.'<a href="https://khetifood.com/index.php?route=api/b2bDashOrder&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$orderarray1[order_id].'">'.$orderarray1[order_id].'</a>'.'</td>');
                          
                          echo('<td>'.($this->orderstatus($orderarray1[order_status_id])).'</td>');
                    
                          echo('<td>'.'Rs. '.$orderarray1[total].'</td>');
                          if($orderarray1[payment_status]=='1'){
                            echo('<td><span class="input-group-text" style="background-color: #33ff70;">Paid</span></td>');
    
                          }else{
                            echo('<td><span class="input-group-text" style="background-color: #ff3352;">Unpaid</span></td>');
                          }
                          echo('<td>'.$orderarray1[date_added].'</td>');
                          echo('<td><a href="https://khetifood.com/index.php?route=api/b2bDashOrder&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$orderarray1[order_id].'"><button type="button" class="btn btn-primary">View</button></a></td>');
                          echo('</tr>');
                        }
                        
                      echo('</tbody>
                    </table>
                  </div>
              </div>
          </body>
          
          </html>');
          
          
         
          echo($this->language->get('footer_script'));
          echo('<script>$(document).ready(function() {
            $(\'#example\').DataTable();
        } );</script>');
          //for update button
          echo('<script>document.querySelector(\'#updatebtnpan\').addEventListener(\'click\', function () {
            let myForm3= document.getElementById("myForm3");
            let updatebtnpan= document.getElementById("updatebtnpan");

            updatebtnpan.style.display="none";
            myForm3.style.display="block";

          });
            </script>');
               // panno js
 echo('<script> 
 let update_text_pan= document.getElementById("update_text_pan");
 let myForm3= document.getElementById("myForm3");
 let updatebtnpan= document.getElementById("updatebtnpan");

 document.querySelector(\'#pan_save\').addEventListener(\'click\', function () {
  fetch(\'https://khetifood.com/index.php?route=api/b2bCustomerDetails/setpanno&no=\'+document.getElementById("pan_text").value+\'&customer_id='.$this->request->get['customer_id'].'&pass='.'7823av'.'\')

     .then((response) => {
       console.log(response);
       return response.json();
     })
     .then((data) => {    
   if(data["success"]== 1){
    update_text_pan.innerHTML =document.getElementById("pan_text").value;
     document.getElementById("myForm3").reset();
     myForm3.style.display="none";
     updatebtnpan.style.display="block";

    }
     }) 
     .catch(function(error)  {
         console.log(error);
     });
   });
   </script>');

        }  else{
          header("Location: https://khetifood.com/business/");
            exit();
        } 
   }
   public function orderstatus($order_status_id) {
    if($order_status_id=='1'){
      return 'Pending';
    }elseif($order_status_id=='2'){
     return 'Processing';
  }elseif($order_status_id=='3'){
   return 'Completed';
  }
 }
   public function customerdetails(){
    if($this->request->get['password']=='kheti'){
      $totalall=0;  
    $totalallunpaid=0;          
    $totalnoofsales=0;
    $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE customer_id='".$this->request->get['customer_id']."' AND order_status_id!=0");
    foreach ($orderarr->rows as $result){
      if($result['payment_status']==1){
        $totalall=$totalall+$result['total'];
      }elseif($result['payment_status']==0){
        $totalallunpaid=$totalallunpaid+$result['total'];
      }
        $totalnoofsales=$totalnoofsales+1;
      $orderarray[]=array(
        'order_id'=>$result['order_id'],
        'total'=>$result['total'],
        'order_status_id'=>$result['order_status_id'],
        'payment_status'=>$result['payment_status'],
        'date_added'=>$result['date_added']
      );
  }
  $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$this->request->get['customer_id']."'");
  $json['name']= $customername->row['name'];
  $json['email']= $customername->row['email'];
  $json['telephone']= $customername->row['telephone'];
  $json['orders']=$orderarray;
  $json['total_number_of_sales']=$totalnoofsales;
  $json['total_paid_amount']=$totalall;
  $json['total_unpaid_amount']=$totalallunpaid;
  $noo1=$this->db->query("SELECT logo FROM b2b_logo WHERE customer_id='".$this->request->get['customer_id']."'");
     
        $json['logo']= $noo1->row['logo'];  
  
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');
  $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
    }

   }
   public function getpanno($id){
    $noo=$this->db->query("SELECT pan_no FROM b2b_PAN WHERE customer_id='".$id."'");
    if($noo){
      return  $noo->row['pan_no'];
    }
   }
   public function setpanno(){
     if($this->request->get['pass']='7823av'){
      $noo1=$this->db->query("SELECT pan_no FROM b2b_PAN WHERE customer_id='".$this->request->get['customer_id']."'");
      if($noo1->row['pan_no']==NULL){
        $sql="INSERT INTO b2b_PAN (customer_id,pan_no) VALUES ('".$this->request->get['customer_id']."','".$this->request->get['no']."')";
        $noo=$this->db->query($sql);
      }else{
        $noo=$this->db->query("UPDATE b2b_PAN SET pan_no='".$this->request->get['no']."' WHERE customer_id='".$this->request->get['customer_id']."'");       
      }
      if($noo){
        $json['success']= 1;
        $this->response->setOutput(json_encode($json));
      }
     }
   }

   public function edituserinfo(){
    if($this->request->post['key']='3d1tus3r!n40'){
      if($this->request->post['firstname']){
       $this->db->query("UPDATE oc_customer SET firstname='".$this->request->post['firstname']."' WHERE customer_id='".$this->request->post['customer_id']."'");

      }
      if($this->request->post['lastname']){
        $this->db->query("UPDATE oc_customer SET lastname='".$this->request->post['lastname']."' WHERE customer_id='".$this->request->post['customer_id']."'");
 
       }
       if($this->request->post['email']){
        $this->db->query("UPDATE oc_customer SET email='".$this->request->post['email']."' WHERE customer_id='".$this->request->post['customer_id']."'");
 
       }
       if($this->request->post['telephone']){
        $this->db->query("UPDATE oc_customer SET telephone='".$this->request->post['telephone']."' WHERE customer_id='".$this->request->post['customer_id']."'");
 
       }

       if($this->request->post['pan_no']){
        $noo1=$this->db->query("SELECT pan_no FROM b2b_PAN WHERE customer_id='".$this->request->post['customer_id']."'");
        if($noo1->row['pan_no']==NULL){
          $sql="INSERT INTO b2b_PAN (customer_id,pan_no) VALUES ('".$this->request->post['customer_id']."','".$this->request->post['pan_no']."')";
          $noo=$this->db->query($sql);
        }else{
          $noo=$this->db->query("UPDATE b2b_PAN SET pan_no='".$this->request->post['pan_no']."' WHERE customer_id='".$this->request->post['customer_id']."'");       
        }
 
       }
       $json['success']="Success";
       $this->response->addHeader('Content-Type: application/json');
       $this->response->addHeader('Access-Control-Allow-Origin: *');
       $this->response->addHeader('Access-Control-Allow-Headers: *');
       $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
       $this->response->addHeader('Access-Control-Max-Age: 600');
       $this->response->setOutput(json_encode($json));

    }
  }

  public function setPanApi(){
    if($this->request->post['pass']='7823av'){
     $noo1=$this->db->query("SELECT pan_no FROM b2b_PAN WHERE customer_id='".$this->request->post['customer_id']."'");
     if($noo1->row['pan_no']==NULL){
       $sql="INSERT INTO b2b_PAN (customer_id,pan_no) VALUES ('".$this->request->post['customer_id']."','".$this->request->post['pan_no']."')";
       $noo=$this->db->query($sql);
     }else{
       $noo=$this->db->query("UPDATE b2b_PAN SET pan_no='".$this->request->post['pan_no']."' WHERE customer_id='".$this->request->post['customer_id']."'");       
     }
     if($noo){
      $json['success']="Success";
      $this->response->addHeader('Content-Type: application/json');
      $this->response->addHeader('Access-Control-Allow-Origin: *');
      $this->response->addHeader('Access-Control-Allow-Headers: *');
      $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
      $this->response->addHeader('Access-Control-Max-Age: 600');
      $this->response->setOutput(json_encode($json));
     }
    }
  }

  
  public function getalluserinfo(){
    if($this->request->post['key']='93t@llus3r!n4'){
      // var_dump('fuk');
      $customername = $this->db->query("SELECT firstname,lastname,email,telephone from oc_customer where customer_id='".$this->request->post['customer_id']."'");
      $json['firstname']= $customername->row['firstname'];
      $json['lastname']= $customername->row['lastname'];
      $json['email']= $customername->row['email'];
      $json['telephone']= $customername->row['telephone'];
      $pan=$this->db->query("SELECT pan_no FROM b2b_PAN WHERE customer_id='".$this->request->post[customer_id]."'");
      $json['pan_no']= $pan->row['pan_no'];
      
      $noo1=$this->db->query("SELECT logo FROM b2b_logo WHERE customer_id='".$this->request->post['customer_id']."'");
     
        $json['logo']= $noo1->row['logo'];    
       
   
  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->addHeader('Access-Control-Allow-Origin: *');
  $this->response->addHeader('Access-Control-Allow-Headers: *');
  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  $this->response->addHeader('Access-Control-Max-Age: 600');
  $this->response->setOutput(json_encode($json));
}

  public function logoupload(){
    if($this->request->post['key']='l090upl0@d828'){

      $target_dir1 = "image/logob2b/".$this->request->post['customer_id'];
      if (!file_exists($target_dir1)) {
          mkdir($target_dir1, 0777, true);
      }
      $already=0;

      $target_dir = "image/logob2b/".$this->request->post['customer_id']."/";    
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
              $image_url="http://khetifood.com/image/logob2b/".$this->request->post['customer_id']."/".htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
            }
        
            // $product_name =$this->request->post['category_name'] ;
            $customer_id =$this->request->post['customer_id'] ;
        
           
            $noo1=$this->db->query("SELECT logo FROM b2b_logo WHERE customer_id='".$this->request->post['customer_id']."'");
      if($noo1->row['logo']==NULL){
        $this->db->query("INSERT INTO b2b_logo SET customer_id= '" . $customer_id . "', logo = '" .$image_url. "'");
      }else{
        $this->db->query("UPDATE b2b_logo SET logo='".$image_url."' WHERE customer_id='".$this->request->post['customer_id']."'");       
      }
            //
        
            $json['success'] = 'file uploaded';
            $json['logo'] = $image_url;
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
  
  
}