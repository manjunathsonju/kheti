<?php
class ControllerApiB2bCoupons extends Controller {
	public function index() {
        $this->load->language('b2bDashboardComponents/components');

        if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
            echo('<!doctype html>
            <html lang="en">
            <head>
            <title>Coupons</title>');
            echo($this->language->get('header_script')); echo($this->language->get('style'));
            echo('<style>td{border-style: solid;border-width: 0.5px;}
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
                $couparr=$this->db->query("SELECT * FROM b2b_coupon");
                foreach ($couparr->rows as $result){
                  $couponarray[]=array(
                    'coupon_id'=>$result['coupon_id'],
                    'name'=>$result['name'],
                    'code'=>$result['code'],
                    'type'=>$result['type'],
                    'discount'=>$result['discount'],
                    'uses_total'=>$result['uses_total'],
                    'status'=>$result['status']   );
              }
              echo('<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong1">
              Add new coupon
            </button><hr>');
              echo(' <div class="modal fade" id="exampleModalLong1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
          
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
 

      <label>Name</label>
      <div class="input-group mb-3" style="padding: 0px 10px 0px 10px;">
      <div class="input-group-prepend">
  </div>
  <input type="text" class="form-control" id="cpnname" aria-describedby="basic-addon3">
</div> 

<label>Code</label>
<div class="input-group mb-3" style="padding: 0px 10px 0px 10px;">
<div class="input-group-prepend">
  </div>
  <input type="text" class="form-control" id="cpncode" aria-describedby="basic-addon3">
</div> 

<div class="form-group" style="padding: 0px 10px 0px 10px;"> 
<label for="exampleFormControlSelect1">Discount Types</label>
<select class="form-control" id="discounttype">
  <option value="p">Percentage</option>
  <option value="f">Fixed Amount</option>
  
</select>
</div>

<label>Discount</label>
<div class="input-group mb-3" style="padding: 0px 10px 0px 10px;">
<div class="input-group-prepend">
  </div>
  <input type="text" class="form-control" id="discount" aria-describedby="basic-addon3">
</div> 

<label>Total uses</label>
<div class="input-group mb-3" style="padding: 0px 10px 0px 10px;">
<div class="input-group-prepend">
  </div>
  <input type="text" class="form-control" id="uses_total" aria-describedby="basic-addon3">
</div> 

<div class="form-group" style="padding: 0px 10px 0px 10px;">
<label for="exampleFormControlSelect1">Status</label>
<select class="form-control" id="status">
  <option value="1">Active</option>
  <option value="0">Inactive</option>
  
</select>
</div>
<button type="button" class="btn btn-primary" id="createnewcpnbtn"> 
              Submit
            </button>
                            </div></div></div> 
                ');

              echo('<table id="example" style="width: 90%;">
                <thead>
                    <tr>
                      <th scope="col">Coupon id</th>
                      <th scope="col">Name</th>
                      <th scope="col">Code</th>
                      <th scope="col">Type</th>
                      <th scope="col">Discount</th>
                      <th scope="col">Total uses</th>
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>');
                  foreach ($couponarray as $couponarray1){
                    echo(' <tr>
                    <td>'.$couponarray1[coupon_id].'</td>
                    <td>'.$couponarray1[name].'</td>
                    <td>'.$couponarray1[code].'</td><td>');
                    if($couponarray1[type]=='p'){
                      echo('Percentage');

                    }else{
                        echo('Fixed');
                    }

                    echo('</td><td>'.$couponarray1[discount].'</td>
                    <td>'.$couponarray1[uses_total].'</td><td>');
                    if($couponarray1[status]=='1'){
                        echo('Active');
  
                      }else{
                          echo('Inactive');
                      }
                    echo('</td>
                    <td><a href="https://khetifood.com/index.php?route=api/b2bCoupons/Viewcoupon&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&coupon_id='.$couponarray1[coupon_id].'"><button type="button" class="btn btn-primary">Edit</button></a></td>
                    
                    </tr>');
                  }

                  echo('</tbody>
                  </table>');  
        echo('</div>
                </div>
                  </body>
                    </html>');
          echo($this->language->get('footer_script'));
          echo('<script>$(document).ready(function() {
            $(\'#example\').DataTable();
        } );</script>');
        // js to submit create new coupons
        echo('<script type="text/javascript">
        $(\'#createnewcpnbtn\').on(\'click\', function() {
          window.location.href = "https://khetifood.com/index.php?route=api/b2bCoupons/addCoupon&key=addcopnxwq1189&name="+document.getElementById("cpnname").value+"&code="+document.getElementById("cpncode").value+"&type="+$("#discounttype").val()+"&discount="+document.getElementById("discount").value+"&uses_total="+document.getElementById("uses_total").value+"&status="+$("#status").val()+"&tkn='.$this->request->get['tkn'].'&id='.$this->request->get['id'].'";

        });
      </script>');

        }   else{
            header("Location: https://khetifood.com/business/");
            exit();
        }
   }
  

   public function ViewCoupon() {
    $this->load->language('b2bDashboardComponents/components');

    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      if($this->request->get['coupon_id']){
        $sql="SELECT * FROM b2b_coupon WHERE coupon_id='".$this->request->get['coupon_id']."'";
        $coupdetails=$this->db->query($sql);
        // var_dump($coupdetails->row['name']);
        
      }
        echo('<!doctype html>
        <html lang="en">
        <head>
        <title>Coupons</title>');
        echo($this->language->get('header_script')); echo($this->language->get('style'));
        echo('<style>td{border-style: solid;
            border-width: 0.5px;}
            </style>');
        echo('
        </head>
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
            echo('<br><a type="button" class="btn btn-danger" style="float: right;" id="deletebtncpn"><i class="fa fa-trash" aria-hidden="true"></i>Delete</a>');

            echo(' <br><div tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div role="document">
                <div>
                <div>
        </div>
 

      <label>Name</label>
      <div class="input-group mb-3" style="padding: 0px 10px 0px 10px;">
      <div class="input-group-prepend">
  </div>
  <input type="text" class="form-control" id="cpnname" aria-describedby="basic-addon3" value="'.$coupdetails->row['name'].'">
</div> 

<label>Code</label>
<div class="input-group mb-3" style="padding: 0px 10px 0px 10px;">
<div class="input-group-prepend">
  </div>
  <input type="text" class="form-control" id="cpncode" aria-describedby="basic-addon3" value="'.$coupdetails->row['code'].'">
</div> 

<div class="form-group" style="padding: 0px 10px 0px 10px;"> 
<label for="exampleFormControlSelect1">Discount Types</label>
<select class="form-control" id="discounttype">
  <option value="p">Percentage</option>
  <option value="f">Fixed Amount</option>
  
</select>
</div>

<label>Discount</label>
<div class="input-group mb-3" style="padding: 0px 10px 0px 10px;">
<div class="input-group-prepend">
  </div>
  <input type="text" class="form-control" id="discount" aria-describedby="basic-addon3" value="'.$coupdetails->row['discount'].'">
</div> 

<label>Total uses</label>
<div class="input-group mb-3" style="padding: 0px 10px 0px 10px;">
<div class="input-group-prepend">
  </div>
  <input type="text" class="form-control" id="uses_total" aria-describedby="basic-addon3" value="'.$coupdetails->row['uses_total'].'">
</div> 

<div class="form-group" style="padding: 0px 10px 0px 10px;">
<label for="exampleFormControlSelect1">Status</label>
<select class="form-control" id="status">
  <option value="1">Active</option>
  <option value="0">Inactive</option>
  
</select>
</div>
<button type="button" class="btn btn-primary" id="savecpnbtn" style="width:100%;">  
              Save
            </button>
                            </div></div></div> 
                ');
            

    echo('</div>
            </div>
              </body>
                </html>');
      echo($this->language->get('footer_script'));
      echo('<script>$(document).ready(function() {
        $(\'#example\').DataTable();
    } );</script>');
    // js to submit create new coupons
    echo('<script type="text/javascript">
    $(\'#savecpnbtn\').on(\'click\', function() {
      window.location.href = "https://khetifood.com/index.php?route=api/b2bCoupons/updateCoupon&key=upcopnxwq1189&name="+document.getElementById("cpnname").value+"&code="+document.getElementById("cpncode").value+"&type="+$("#discounttype").val()+"&discount="+document.getElementById("discount").value+"&uses_total="+document.getElementById("uses_total").value+"&status="+$("#status").val()+"&tkn='.$this->request->get['tkn'].'&coupon_id='.$this->request->get['coupon_id'].'&id='.$this->request->get['id'].'";
      
    });
  </script>');
  echo('<script type="text/javascript">
  $(\'#deletebtncpn\').on(\'click\', function() {
    window.location.href = "https://khetifood.com/index.php?route=api/b2bCoupons/deleteCoupon&key=deltecopnxwq1189&tkn='.$this->request->get['tkn'].'&coupon_id='.$this->request->get['coupon_id'].'&id='.$this->request->get['id'].'";
    
  });
</script>');
    }   else{
        header("Location: https://khetifood.com/business/");
        exit();
    }

   }
   public function addCoupon() {
    if($this->request->get['key']=="addcopnxwq1189"){
     $cpn=$this->db->query("INSERT INTO b2b_coupon (name, code, type,discount,uses_total,status) values ('".$this->request->get['name']."','".$this->request->get['code']."','".$this->request->get['type']."','".$this->request->get['discount']."','".$this->request->get['uses_total']."','".$this->request->get['status']."')");
     // $url="https://khetifood.com/index.php?route=api/b2bCoupons&id=".$this->request->get[id]."&tkn=".$this->request->get[tkn];
     // var_dump($url);
     header("Location: https://khetifood.com/index.php?route=api/b2bCoupons&id=".$this->request->get[id]."&tkn=".$this->request->get[tkn]);
    }

  }

  public function addCoupon1() {
    if($this->request->get['key']=="addcopnxwq1189"){
     $cpn=$this->db->query("INSERT INTO b2b_coupon (name, code, type,discount,uses_total,status) values ('".$this->request->get['name']."','".$this->request->get['code']."','".$this->request->get['type']."','".$this->request->get['discount']."','".$this->request->get['uses_total']."','".$this->request->get['status']."')");
     // $url="https://khetifood.com/index.php?route=api/b2bCoupons&id=".$this->request->get[id]."&tkn=".$this->request->get[tkn];
     // var_dump($url);
     header("Location: https://khetifood.com/index.php?route=b2b/coupons&id=".$this->request->get[id]."&tkn=".$this->request->get[tkn]);
    }

  }
  public function updateCoupon() {
    if($this->request->get['key']=="upcopnxwq1189"){
      $sql="UPDATE b2b_coupon SET name='".$this->request->get['name']."', code='".$this->request->get['code']."', type='".$this->request->get['type']."', discount='".$this->request->get['discount']."',uses_total='".$this->request->get['uses_total']."',status='".$this->request->get['status']."' WHERE coupon_id='".$this->request->get['coupon_id']."'";
     
     $cpn=$this->db->query($sql);
     header("Location: https://khetifood.com/index.php?route=api/b2bCoupons&id=".$this->request->get[id]."&tkn=".$this->request->get[tkn]);
    }

  }
 public function deleteCoupon() { 
    if($this->request->get['key']=="deltecopnxwq1189"){
      $sql="DELETE FROM b2b_coupon WHERE coupon_id='".$this->request->get['coupon_id']."'";
     $cpn=$this->db->query($sql);
     header("Location: https://khetifood.com/index.php?route=api/b2bCoupons&id=".$this->request->get[id]."&tkn=".$this->request->get[tkn]);
    }

  }

  public function updateCoupon1() {
    if($this->request->get['key']=="upcopnxwq1189"){
      $sql="UPDATE b2b_coupon SET name='".$this->request->get['name']."', code='".$this->request->get['code']."', type='".$this->request->get['type']."', discount='".$this->request->get['discount']."',uses_total='".$this->request->get['uses_total']."',status='".$this->request->get['status']."' WHERE coupon_id='".$this->request->get['coupon_id']."'";
     
     $cpn=$this->db->query($sql);
     header("Location: https://khetifood.com/index.php?route=b2b/coupons&id=".$this->request->get[id]."&tkn=".$this->request->get[tkn]);
    }

  }
 public function deleteCoupon1() { 
    if($this->request->get['key']=="deltecopnxwq1189"){
      $sql="DELETE FROM b2b_coupon WHERE coupon_id='".$this->request->get['coupon_id']."'";
     $cpn=$this->db->query($sql);
     header("Location: https://khetifood.com/index.php?route=b2b/coupons&id=".$this->request->get[id]."&tkn=".$this->request->get[tkn]);
    }

  }

  public function applyCoupon() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){

           $cpn=$this->db->query("SELECT * FROM b2b_coupon WHERE code='".$this->request->get['code']."'");
           $orderdetail=$this->db->query("SELECT * FROM order_b2b WHERE order_id='".$this->request->get['order_id']."'");
          //  var_dump($orderdetail->row[total]);


           if($cpn->row[type]=="p"){
             $discount=round((((float)$orderdetail->row[total])*(int)($cpn->row[discount]))/100);

           }elseif($cpn->row[type]=="f"){
            $discount=$cpn->row[discount];

           }

           
           $dt=strtotime( now);
           $dtm=(date("Y-m-d", $dt));
          //  var_dump($cpn->num_rows);



           if(($cpn->num_rows!='0')&&((int)$cpn->row[uses_total]>0)&&($cpn->row[status]==1)){
             $sql="INSERT INTO b2b_coupon_history (order_id,coupon_id,coupon_name,discount_amount,date_added) VALUES ('".$this->request->get[order_id]."','".$cpn->row[coupon_id]."','".$cpn->row[name]."','".$discount."','".$dtm."')";

             $cpn1=$this->db->query($sql);
            $totalUse=(int)($cpn->row[uses_total]);
            $newUse=$totalUse-1;
           
           $this->db->query("UPDATE b2b_coupon SET uses_total='".$newUse."' WHERE coupon_id='".$cpn->row[coupon_id]."'");

          }   
    
    }
    header("Location: https://khetifood.com/index.php?route=api/b2bDashOrder&id=".$this->request->get[id]."&tkn=".$this->request->get[tkn]."&order_id=".$this->request->get[order_id]);


  }

  public function useCoupon() {
    if($this->request->get['key']=="usecopnxwq1189"){
      $cpn=$this->db->query("SELECT * FROM b2b_coupon WHERE code='".$this->request->get['code']."'");
      // var_dump($cpn);
      if(($cpn->num_rows=='0')||($cpn->row['status']=='0')||((int)($cpn->row['uses_total'])<1)){
        $json['coupon_validate']=0;

      }else{
        $json['coupon_validate']=1;
        $json['coupon_id']=$cpn->row['coupon_id'];
        if($cpn->row[type]=="p"){
          $json['discount']=round((((float)$this->request->get['total'])*(int)($cpn->row[discount]))/100);

        }elseif($cpn->row[type]=="f"){
          $json['discount']=$cpn->row[discount];

        }
      }
    }else{
      $json['coupon_validate']=0;


    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');  
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));

  }
   
}