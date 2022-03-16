<?php
class ControllerApiB2bDash extends Controller {
	public function index() {
        $this->load->language('b2bDashboardComponents/components');
        // $info =$this->load->controller('api/b2bDashHome/checklogin');

        if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
          if($this->request->get['location']){
            // $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE order_status_id='".$this->request->get['order_status_id']."' ORDER BY order_id DESC");
            if($this->request->get['location']==1){
              $orderarr=$this->db->query("SELECT o.order_id as order_id, o.customer_id as customer_id , o.total as total, o.order_status_id as order_status_id, o.payment_status as payment_status,o.date_added as date_added FROM order_b2b o LEFT JOIN customer_locations c ON (c.customer_id=o.customer_id) WHERE o.order_status_id='".$this->request->get['order_status_id']."' AND (c.location IS NULL OR c.location=1) ORDER BY o.order_id DESC");

            }else{
              $orderarr=$this->db->query("SELECT * FROM order_b2b o JOIN customer_locations c ON (c.customer_id=o.customer_id) WHERE o.order_status_id='".$this->request->get['order_status_id']."' AND c.location='".$this->request->get[location]."' ORDER BY o.order_id DESC");

            }
          }else{
            $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE order_status_id='".$this->request->get['order_status_id']."' ORDER BY order_id DESC");

          }
            foreach ($orderarr->rows as $result){
              $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$result['customer_id']."'");
              $orderarray[]=array(
                'order_id'=>$result['order_id'],
                'customer_id'=>$result['customer_id'],
                'name'=>$customername->row['name'],
                'comment'=>$result['comment'],
                'total'=>$result['total'],
                'order_status_id'=>$result['order_status_id'],
                'payment_status'=>$result['payment_status'],
                'date_added'=>$result['date_added'],
                'email'=>$customername->row['email'],
                'telephone'=>$customername->row['telephone']
              );
          }
          //  var_dump(sizeof($orderarray));
          
          echo('<!doctype html>
          <html lang="en">
          <head>
          <title>Orders</title>');
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
          <li>
          <a href="https://khetifood.com/index.php?route=api/b2bDashDailyDeliveryReport&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-user" aria-hidden="true" style="padding:5px;"></i>Daily Delivery Report</a>
      </li>
          
      </ul>
  </li> 
  
              <li>
              <a href="https://khetifood.com/index.php?route=api/b2bCoupons&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'"><i class="fa fa-diamond" aria-hidden="true" style="padding:5px;"></i> Coupons</a>
              </li>
              ');
              echo($this->language->get('nav2'));
              // filter
              echo(' <div class="card shadow mb-4">
              <!-- Card Header - Accordion -->
              <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
                  role="button" aria-expanded="true" aria-controls="collapseCardExample">
                  <h6 class="m-0 font-weight-bold text-primary">Filter</h6>
              </a>
              <!-- Card Content - Collapse -->
              <div class="collapse show" id="collapseCardExample">
                  <div class="card-body">

                  <div class="btn-group" style="width: 165px;">
                  <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Location
                 </button>
                 <div class="dropdown-menu" style="width: 165px;">
                 <h6 class="dropdown-header">Choose location:</h6>
                     <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bDash&order_status_id='.$this->request->get['order_status_id'].'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&location=1">Kathmandu</a>
                     <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bDash&order_status_id='.$this->request->get['order_status_id'].'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&location=2">Pokhara</a>
                     <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bDash&order_status_id='.$this->request->get['order_status_id'].'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">Both</a>
                  
                 </div> </div>
                 
                
                  </div>
              </div>
          </div>');
             // filter end

              if(sizeof($orderarray)==0||sizeof($orderarray)=='0'){
                echo('<h4 style="    margin-left: 40%;
                ">Sorry, no orders here.</h4>');
              }else{
                echo('
                
                <table id="example" style=" max-width: 100%;">
                <thead>
                    <tr>
                      <th scope="col">Order Id</th>
                      <th scope="col">Customer Id</th>
                      <th scope="col">Customer</th>
                      <th scope="col">Status</th>
                      <th scope="col">Total</th>
                      <th scope="col">Payment status</th>
                      <th scope="col">Date Added</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>');
                    foreach ($orderarray as $orderarray1){
                      echo(' <tr>
                      <td>'.$orderarray1[order_id].'</td>
                      <td>'.$orderarray1[customer_id].'</td>
                      <td>'.'<a href="https://khetifood.com/index.php?route=api/b2bCustomerDetails&customer_id='.$orderarray1[customer_id].'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">'.$orderarray1[name].'</a>'.'</td><td>');
  
                      echo(($this->orderstatus($orderarray1[order_status_id])));
                      echo('</td><td>'.'Rs. '.$orderarray1[total].'</td><td>');
                      if($orderarray1[payment_status]=='1'){
                        echo('<span class="input-group-text" style="background-color: #33ff70;">Paid</span>');

                      }else{
                        echo('<span class="input-group-text" style="background-color: #ff3352;">Unpaid</span>');
                      }

                      echo('</td><td>'.$orderarray1[date_added].'</td>
                      <td><a href="https://khetifood.com/index.php?route=api/b2bDashOrder&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$orderarray1[order_id].'"><button type="button" class="btn btn-primary">View</button></a>
                      
                      <button onclick="myFunction('.$orderarray1[order_id].')" id="'.$orderarray1[order_id].'" style="display:block;">delivery report</button>
                      </td>
                    </tr>');
                    }
              
                   echo('
                    </tbody>
                  </table>');  
                
              }
                  echo('</div>
              </div>
          </body>
          </html>');
          echo($this->language->get('footer_script'));
          echo('<script>$(document).ready(function() {
            $(\'#example\').DataTable();
        } );</script>');

        // add to delivery reporrt js
 echo('<script> 
 function myFunction(order_id){
  let hide= document.getElementById(order_id);

   console.log(order_id);
   fetch(\'https://khetifood.com/index.php?route=api/b2bDashDailyDeliveryReport/addOrder&key=@d0rd34&order_id=\'+order_id)

   .then((response) => {
     console.log(response);
     return response.json();

   })
   .then((data) => { 
    hide.style.display="none";

   })
 }

   </script>');
         
        }else{header("Location: https://khetifood.com/business/");
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
   elseif($order_status_id=='7'){
    return 'Canceled';
   }
  }

}