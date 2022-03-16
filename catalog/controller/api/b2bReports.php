<?php
class ControllerApiB2bReports extends Controller {
	public function index() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
        $salesper=$this->db->query("SELECT DISTINCT(user_id) FROM b2b_salesperson_to_customer ");
        foreach ($salesper->rows as $result){
            $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name from b2b_users where user_id='".$result['user_id']."'");
            $salesperson[]=array(
              'user_id'=>$result['user_id'],
              'name'=>$customername->row['name']
            );
        }

        $customeridarray = array();

        if($this->request->get['user_id']){
          $salespertocustomer = $this->db->query("SELECT customer_id from b2b_salesperson_to_customer where user_id='".$this->request->get['user_id']."'");
          foreach ($salespertocustomer->rows as $result){
          $customernamm = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name from oc_customer where customer_id='".$result['customer_id']."'");

          $salespersontocustomers[]=array(
            'customer_id'=>$result['customer_id'],
            'name'=>$customernamm->row['name']

          );
          $customeridarray[] = $result['customer_id'];  
        }
      }
      // var_dump($customeridarray);

        // if($this->request->get['user_id']){
        //   $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,customer_id from oc_customer where user_id='".$result['user_id']."'");

        // }
         

    $this->load->language('b2bDashboardComponents/components');
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
        echo('<!doctype html>
    <html lang="en">
    <head>
    <title>Reports</title>');
    echo($this->language->get('header_script')); echo($this->language->get('style'));
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
                // var_dump($salesperson);
                echo('<h4>Sales Report by Sales person</h4>');
                echo('<div class="btn-group" style="width: 230px;">
                <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Sales Person
                </button>
                <div class="dropdown-menu" style="width: 230px;">');
                foreach($salesperson as $person){
                  echo('<a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bReports&user_id='.$person[user_id].'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">'.$person[name].'</a>');
                  
                }
               
                echo('</div></div>'); //dropdown
              echo('<hr><div class="container">
              <div class="row">
                <div class="col-sm">
                 

                <div class="card mb-4">
                <div class="card-header">
                    Customers
                </div>
                <div class="card-body">');
                foreach($salespersontocustomers as $sptc){
                echo('<i class="fa fa-user-o">'.' <a href="https://khetifood.com/index.php?route=api/b2bCustomerDetails&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&customer_id='.$sptc[customer_id].'"> '.$sptc[name].'</a>'.'</i><br>');
              }
               
                echo('</div>
            </div>
                </div>
                <div class="col-sm">
                  

                <div class="card mb-4">
                <div class="card-header">
                    Sales summary
                </div>
                <div class="card-body">');
                $abc=$this->get_total_customers_salesperson($customeridarray);

                echo('</div>
            </div>


            

                </div>
              </div>
            </div>');
      
     
    echo('</div></div></body></html>');
  echo($this->language->get('footer_script'));
    }
  }else{
      header("Location: https://khetifood.com/business/");
      exit();
    }

}
public function get_total_customers_salesperson($arrayofcustomerid){
  if(sizeof($arrayofcustomerid)==0){
    return 0;
  }
  $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE customer_id IN (". implode(',', $arrayofcustomerid).") AND order_status_id!=0");
  $totalallpaid=0;  
  $totalallall=0;
  $totalallunpaid=0;          
  $totalnoofsales=0;
  foreach ($orderarr->rows as $result){
    $totalallall=$totalallall+$result['total'];
    if($result['payment_status']==1){
      $totalallpaid=$totalallpaid+$result['total'];
    }elseif($result['payment_status']==0){
      $totalallunpaid=$totalallunpaid+$result['total'];
    }
      $totalnoofsales=$totalnoofsales+1;

  }
  // $json['totalallall']= $totalallall;
  // $json['totalall']= $totalall;
  // $json['totalallunpaid']= $totalallunpaid;
  // $json['totalnoofsales']= $totalnoofsales;
  // $this->response->setOutput(json_encode($json));
    echo('<span>Total Orders: '.$totalnoofsales.'</span><br>
    <span>Total Transactions: Rs. '.$totalallall.'</span><br>
    <span>Total Paid: '.$totalallpaid.'</span><br>
    <span>Total Unpaid: '.$totalallunpaid.'</span><br>
    ');

 }
}