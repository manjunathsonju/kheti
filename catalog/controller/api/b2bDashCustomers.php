<?php
class ControllerApiB2bDashCustomers extends Controller {
	public function index() {

    $this->load->language('b2bDashboardComponents/components');
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
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
    <body>
        <div class="wrapper">');
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
                    <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bDashCustomers&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&location=1">Kathmandu</a>
                    <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bDashCustomers&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&location=2">Pokhara</a>
                    <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bDashCustomers&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">Both</a>
                 
                </div> </div>
                
               
                 </div>
             </div>
         </div>');
            // filter end
          if($this->request->get['location']){
            $location=$this->request->get['location'];
          }else{
            $location=0;
          }
        $customers=$this->getAllCustomers($location);
       
        echo('<table id="example" style="max-width: 100%;">            
        <thead>
        <tr> 
          <th scope="col">Customer Id</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Telephone</th>
          <th scope="col">Action</th>
        </tr>
      </thead><tbody>');
      foreach ($customers as $customer){
          echo(' <tr><td>'.$customer[customer_id].'</td>
          <td>'.$customer[name].'</td>
          <td>'.$customer[email].'</td>
          <td>'.$customer[telephone].'</td>
          <td>'.'<a href="https://khetifood.com/index.php?route=api/b2bCustomerDetails&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&customer_id='.$customer[customer_id].'"><button type="button" class="btn btn-primary">View</button></a>'.'</td>

          </tr>');

      }
    echo('</tbody></table></div>
        </div>
    </body>
    </html>');
  
  echo($this->language->get('footer_script'));  
  echo('<script>$(document).ready(function() {
    $(\'#example\').DataTable();
} );</script>');
    }else{
        header("Location: https://khetifood.com/business/");
            exit();
    }
}
public function getAllCustomers(int $location) {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
        if($this->request->get['location']=='2'){
            $customerarr=$this->db->query("SELECT DISTINCT(o.customer_id) as customer_ids FROM order_b2b o LEFT JOIN customer_locations c ON (c.customer_id=o.customer_id) WHERE c.location='".$location."'");
  
        }elseif($this->request->get['location']=='1'){
            $customerarr=$this->db->query("SELECT DISTINCT(o.customer_id) as customer_ids FROM order_b2b o LEFT JOIN customer_locations c ON (c.customer_id=o.customer_id) WHERE (c.location IS NULL OR c.location=1)");

        }else{
            $customerarr=$this->db->query("SELECT DISTINCT(customer_id) as customer_ids FROM order_b2b");

        }
         foreach ($customerarr->rows as $result){
            $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$result['customer_ids']."'");
            $customerarray[]=array(
                'customer_id'=>$result['customer_ids'],
                'name'=>$customername->row['name'],
                'email'=>$customername->row['email'],
                'telephone'=>$customername->row['telephone']
            );

        }
        return($customerarray);
    }
}


}