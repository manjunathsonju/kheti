<?php
Class ControllerApiB2bDashHome extends Controller{ 
    public function index(){
        $this->load->language('b2bDashboardComponents/components');
        if($this->checklogin(array($this->request->get['id'],$this->request->get['tkn']))){
            echo('<!doctype html>
          <html lang="en">
          <head>
          <title>Dashboard</title>
          ');
          echo($this->language->get('header_script'));
          echo($this->language->get('style'));
          
          echo('<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
          <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
          <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
          <link rel="stylesheet" href="/resources/demos/style.css"></head> 
                   <body>
              <div class="wrapper"> ');
            //   echo($this->language->get('sidebar'));
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
              echo('<h4>Dashboard</h4>');
              echo('<div class="row">');
            // total no of customers

              echo('<div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2" style="border-left: .25rem solid #4e73df!important;">
                  <div class="card-body">
                      <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="font-size: 15px;">
                                  <a href="https://khetifood.com/index.php?route=api/b2bDashCustomers&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">Total Customers</a></div>
                              <div class="h5 mb-0 font-weight-bold text-gray-800">'.$this->noOfCustomers().'</div>
                          </div>
                          <div class="col-auto">
                              <i class="fa fa-users" style="font-size: 35px;"></i>
                          </div>
                      </div>
                  </div>
              </div>
          </div>');
          // total orders
          echo('<div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-success shadow h-100 py-2" style="border-left: .25rem solid #36b9cc!important;">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Orders</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">'.$this->noOfOrders().'</div>
                      </div>
                      <div class="col-auto">
                          <i class="fa fa-shopping-cart" style="font-size: 35px;"></i>
                      </div>
                  </div>
              </div>
          </div>
      </div>');
       // pending orders
       echo('<div class="col-xl-3 col-md-6 mb-4">
       <div class="card border-left-warning shadow h-100 py-2" style="border-left: .25rem solid #f6c23e!important;">
           <div class="card-body">
               <div class="row no-gutters align-items-center">
                   <div class="col mr-2">
                       <div class="text-xs font-weight-bold text-warning text-uppercase mb-1" style="font-size: 15px;">
                           Pending Orders</div>
                       <div class="h5 mb-0 font-weight-bold text-gray-800">'.$this->noOfPendingOrders().'</div>
                   </div>
                   <div class="col-auto">
                       <i class="fa fa-shopping-basket" style="font-size: 35px;"></i>
                   </div>
               </div>
           </div>
       </div>
   </div>');
    // transaction sum
    echo('<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2" style="border-left: .25rem solid #28a745!important;">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Transaction sum</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rs.'.round($this->totalsum()/1000,2).'k</div>
                </div>
                <div class="col-auto">
                    <i class="fa fa-money" style="font-size: 35px;"></i>
                </div>
            </div>
        </div>
    </div>
</div>');
          echo('</div>');
          echo('<div class="row">');
          echo('<div class="card shadow col-xl-8 col-md-6 mb-4">
          <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
          </div>
          <div class="card-body">
              <h4 class="small font-weight-bold"><a href="https://khetifood.com/index.php?route=api/b2bDash&order_status_id=1&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">Pending Orders</a><span
                      class="float-right">'. round(($this->noOfPendingOrders()/$this->noOfOrders())*100).'%</span></h4>
              <div class="progress mb-4">
                  <div class="progress-bar bg-danger" role="progressbar" style="width: '. round(($this->noOfPendingOrders()/$this->noOfOrders())*100).'%"
                      aria-valuenow="'. round(($this->noOfPendingOrders()/$this->noOfOrders())*100).'" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <h4 class="small font-weight-bold"><a href="https://khetifood.com/index.php?route=api/b2bDash&order_status_id=2&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">Processing Orders</a><span
                      class="float-right">'. round(($this->noOfProcessingOrders()/$this->noOfOrders())*100).'%</span></h4>
              <div class="progress mb-4">
                  <div class="progress-bar bg-warning" role="progressbar" style="width: '. round(($this->noOfProcessingOrders()/$this->noOfOrders())*100).'%"
                      aria-valuenow="'. round(($this->noOfProcessingOrders()/$this->noOfOrders())*100).'" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <h4 class="small font-weight-bold"><a href="https://khetifood.com/index.php?route=api/b2bDash&order_status_id=3&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">Completed Orders</a><span
                      class="float-right">'. round(($this->noOfCompletedOrders()/$this->noOfOrders())*100).'%</span></h4>
              <div class="progress mb-4">
                  <div class="progress-bar" role="progressbar" style="width: '. round(($this->noOfCompletedOrders()/$this->noOfOrders())*100).'%"
                      aria-valuenow="'. round(($this->noOfCompletedOrders()/$this->noOfOrders())*100).'" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              
          </div>
      </div>');
      echo('<div class="card shadow col-xl-3 col-md-5 mb-3" style="margin-left: 25px;">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sum paid
                      </div>
                      <div class="row no-gutters align-items-center">
                          <div class="col-auto">
                              <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">'.round(($this->findpaidsum()/$this->totalsum())*100).'%</div>
                          </div>
                          <div class="col">
                              <div class="progress progress-sm mr-2">
                                  <div class="progress-bar bg-info" role="progressbar"
                                      style="width: '.round(($this->findpaidsum()/$this->totalsum())*100).'%" aria-valuenow="'.round(($this->findpaidsum()/$this->totalsum())*100).'" aria-valuemin="0"
                                      aria-valuemax="100"></div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                  </div>
              </div>
              <hr>

              <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Sum Unpaid
                  </div>
                  <div class="row no-gutters align-items-center">
                      <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">'.round(($this->findunpaidsum()/$this->totalsum())*100).'%</div>
                      </div>
                      <div class="col">
                          <div class="progress progress-sm mr-2">
                              <div class="progress-bar bg-danger" role="progressbar"
                                  style="width: '.round(($this->findunpaidsum()/$this->totalsum())*100).'%" aria-valuenow="'.round(($this->findunpaidsum()/$this->totalsum())*100).'" aria-valuemin="0"
                                  aria-valuemax="100"></div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-auto">
                  <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
              </div>
          </div>
          <hr>
          <h4 class="small font-weight-bold">
          Paid: Rs. '.$this->findpaidsum().'
          <br><br>
          Unpaid: Rs. '.$this->findunpaidsum().' 
          </h4>
          <span> (Unpaid order since '.$this->findunpaiddate().')</span>
          
           </div>
  </div>');
 
      echo('</div>');
      echo('<div class="card shadow mb-4">
      <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Orders Payment Report</h6>
      </div>
      <div class="card-body">
      <div class="row">
      <div class="col mr-2">
      <div class="input-group">
      <h4 class="small font-weight-bold">Date From</h4>
      <input type="text" style="width: 75%;padding: 3px;" id="datetimepicker4" aria-describedby="basic-addon3" >
      </div></div>

    <div class="col mr-2">
    <div class="input-group">
    <h4 class="small font-weight-bold">Date To</h4>
    <input type="text" style="width: 75%;padding: 3px;" id="datetimepicker3" aria-describedby="basic-addon3" >
      </div>
    </div>
    <button type="button" class="btn btn-success" id="weekpay"><i class="fa fa-level-down"></i></button>
    </div>

      </div> 
    

      <div class="input-group-prepend" style="display: block;width: 100%;margin: 5px 0;">
      <span class="input-group-text" id="basic-addon3"><h6 class="m-0 font-weight-bold text-primary" style="float:right;">Total Paid Amount: </h6>Rs. <span id="paid_txt"> </span>
    </div>
    <div class="input-group-prepend" style="display: block;width: 100%;margin: 5px 0;">
      <span class="input-group-text" id="basic-addon3"><h6 class="m-0 font-weight-bold text-primary" style="float:right;">Paid Order Ids:</h6><span id="paid_orders"> </span>
    </div>     
      

      <div class="input-group-prepend" style="display: block;width: 100%;margin: 5px 0;">
      <span class="input-group-text" id="basic-addon3"><h6 class="m-0 font-weight-bold text-primary">Total Unpaid Amount: </h6>Rs. <span id="unpaid_txt"> </span>
    </div>
    <div class="input-group-prepend" style="display: block;width: 100%;margin: 5px 0;">
      <span class="input-group-text" id="basic-addon3"><h6 class="m-0 font-weight-bold text-primary">UnPaid Order Ids:</h6><span id="unpaid_orders"> </span>
    </div>


  </div>');
          echo('<style>
          .border-left-primary {
            border-left: .25rem solid #4e73df!important;
        }</style>');
        echo('<script type="text/javascript">
          $(\'#datetimepicker4\').datepicker({
        pickTime: false,
        dateFormat: \'yy-mm-dd\'
    });
    </script>');
    echo('<script type="text/javascript">
    $(\'#datetimepicker3\').datepicker({
  pickTime: false,
  dateFormat: \'yy-mm-dd\'
});
</script>');
          echo($this->language->get('footer_script'));
      // js for weekly payment report
     echo('<script> 
    let update_text_paid= document.getElementById("paid_txt");
    let update_text_unpaid= document.getElementById("unpaid_txt");
    let paidpaid= document.getElementById("paid_orders");
    let unpaidunpaid= document.getElementById("unpaid_orders");


    document.querySelector(\'#weekpay\').addEventListener(\'click\', function () {
    fetch(\'https://khetifood.com/index.php?route=api/b2bDashHome/weeklyPaymentStatus&date_start=\'+document.getElementById("datetimepicker4").value+\'&date_end=\'+document.getElementById("datetimepicker3").value+\'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'\')

     .then((response) => {
       console.log(response);
       return response.json();
     })
     .then((data) => { 
        update_text_paid.innerHTML =data["paid_total"];
        update_text_unpaid.innerHTML =data["unpaid_total"];
        let text="<textarea style=\"height: 30px;width: 900px;\">";
        let text1="<textarea style=\"height: 30px;width: 900px;\">";
        for (i = 0; i < data["array_paid"].length; i++) {
            text =text+data[\'array_paid\'][i][\'order_id\']+",";
          }
          text =text+"</textarea>";

          paidpaid.innerHTML =text;
          for (i = 0; i < data["array_unpaid"].length; i++) {
            text1 =text1+data[\'array_unpaid\'][i][\'order_id\']+",";
          }
          text1 =text1+"</textarea>";
          unpaidunpaid.innerHTML =text1;
     })
     .catch(function(error)  {
         console.log(error);
     });
   });
   </script>');
        }else{
            header("Location: https://khetifood.com/business/");
            exit();
        }
   }

   public function noOfCustomers(){
    if($this->checklogin(array($this->request->get['id'],$this->request->get['tkn']))){
        $number_of_customers=$this->db->query("SELECT COUNT(DISTINCT(customer_id)) as number FROM order_b2b");
        return $number_of_customers->row['number'];
    }
   }
   public function noOfOrders(){
    if($this->checklogin(array($this->request->get['id'],$this->request->get['tkn']))){
        $number_of_orders=$this->db->query("SELECT COUNT(order_id) as number FROM order_b2b WHERE order_status_id!=0");
        return $number_of_orders->row['number'];
    }
   }
   public function noOfPendingOrders(){
    if($this->checklogin(array($this->request->get['id'],$this->request->get['tkn']))){
        $number_of_orders=$this->db->query("SELECT COUNT(order_id) as number FROM order_b2b WHERE order_status_id=1");
        return $number_of_orders->row['number'];
    }
   }
   public function noOfProcessingOrders(){
    if($this->checklogin(array($this->request->get['id'],$this->request->get['tkn']))){
        $number_of_orders=$this->db->query("SELECT COUNT(order_id) as number FROM order_b2b WHERE order_status_id=2");
        return $number_of_orders->row['number'];
    }
   } 
   public function noOfCompletedOrders(){
    if($this->checklogin(array($this->request->get['id'],$this->request->get['tkn']))){
        $number_of_orders=$this->db->query("SELECT COUNT(order_id) as number FROM order_b2b WHERE order_status_id=3");
        return $number_of_orders->row['number'];
    }
   }
   public function totalsum(){
    if($this->checklogin(array($this->request->get['id'],$this->request->get['tkn']))){
        $number_of_orders=$this->db->query("SELECT round(sum(total)) as number FROM order_b2b WHERE order_status_id!=0");
        return $number_of_orders->row['number'];
    }
   }
   public function findpaidsum(){
    if($this->checklogin(array($this->request->get['id'],$this->request->get['tkn']))){
        $number_of_orders=$this->db->query("SELECT round(sum(total)) as number FROM order_b2b WHERE payment_status=1 AND order_status_id!=0");
        return $number_of_orders->row['number'];
    }
   }
   public function findunpaidsum(){
    if($this->checklogin(array($this->request->get['id'],$this->request->get['tkn']))){
        $number_of_orders=$this->db->query("SELECT round(sum(total)) as number FROM order_b2b WHERE payment_status!=1 AND order_status_id!=0");
        return $number_of_orders->row['number'];
    }
   }
   
   public function findunpaiddate(){
        $number_of_orders=$this->db->query("SELECT MIN(date_added) as date FROM `order_b2b` WHERE order_status_id!=0 AND payment_status=0 ORDER BY date_added");
        // var_dump( $number_of_orders->row['date']);

        $date1=(date("jS F Y", strtotime($number_of_orders->row['date'])));
        return $date1;
   }

   

   public function checklogin($useridarray) {
    if($useridarray[0]==NULL||$useridarray[1]==NULL){
        return FALSE;
    }
        $hash=$this->db->query("SELECT * FROM b2b_users where user_id='".$useridarray[0]."'");
   
        if($hash->row['token']==$useridarray[1]){
            return TRUE;
        }else{
            return FALSE;
        } 
}
    public function weeklyPaymentStatus() { // api
    
        if($this->request->get['date_start']){
            $preday=$this->request->get['date_start'];
                    }

            if($this->request->get['date_end']){
                $datestinge=$this->request->get['date_end'];
                $nextdayw=strftime("%Y-%m-%d", strtotime("$datestinge +1 day"));
                
            }
        $sqlunpaid="SELECT round(sum(total)) as number FROM order_b2b WHERE payment_status!=1 AND order_status_id!=0";
        if($this->request->get['date_start']){
            $sqlunpaid= $sqlunpaid. " AND date_added>'".$preday."'";
        }
        if($this->request->get['date_end']){
            $sqlunpaid= $sqlunpaid. " AND date_added<'".$nextdayw."'";
        }
        $unpaid_total=$this->db->query($sqlunpaid);
        $json['unpaid_total']=$unpaid_total->row[number];

        $sqlpaid="SELECT round(sum(total)) as number FROM order_b2b WHERE payment_status=1 AND order_status_id!=0";
        if($this->request->get['date_start']){
            $sqlpaid= $sqlpaid. " AND date_added>'".$preday."'";
        }
        if($this->request->get['date_end']){
            $sqlpaid= $sqlpaid. " AND date_added<'".$nextdayw."'";
        }

        $paid_total=$this->db->query($sqlpaid);
        $json['paid_total']=$paid_total->row[number];


        $unpaid_sum= $unpaid_total->row['number']; 
        $paid_sum= $paid_total->row['number'];

        //for list of orders
        $sqlunpaidorders="SELECT order_id FROM order_b2b WHERE payment_status!=1 AND order_status_id!=0";
        if($this->request->get['date_start']){
            
            $sqlunpaidorders= $sqlunpaidorders. " AND date_added>'".$preday ." 00:00:00"."'";
        }
        if($this->request->get['date_end']){
            $sqlunpaidorders= $sqlunpaidorders. " AND date_added<'".$nextdayw."'";
        }
        $unpaid_total_orders=$this->db->query($sqlunpaidorders);
                // var_dump($unpaid_total_orders);


        foreach ($unpaid_total_orders->rows as $result){
            $orderarrayunpaid[]=array(
               'order_id'=>$result['order_id'],
            );
         }
        //  var_dump($orderarrayunpaid);
        $sqlpaidorders="SELECT order_id FROM order_b2b WHERE payment_status=1 AND order_status_id!=0";
        if($this->request->get['date_start']){
            $sqlpaidorders= $sqlpaidorders. " AND date_added>'".$preday ." 00:00:00"."'";
        }
        if($this->request->get['date_end']){
            $sqlpaidorders= $sqlpaidorders. " AND date_added<'".$nextdayw."'";
        }
        $paid_total_orders=$this->db->query($sqlpaidorders); //list of paid orders id
        foreach ($paid_total_orders->rows as $result){
            $orderarraypaid[]=array(
               'order_id'=>$result['order_id'],
            );
         }
        
        $json['array_paid']= $orderarraypaid;
        $json['array_unpaid']= $orderarrayunpaid;

        $this->response->setOutput(json_encode($json));

}


}