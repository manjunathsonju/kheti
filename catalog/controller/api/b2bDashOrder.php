<?php
class ControllerApiB2bDashOrder extends Controller {
	public function index() {
    $this->load->language('b2bDashboardComponents/components');
      if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $ordersinformation= $this->orderinfo($this->request->get['order_id']);
            $orderarrpro=$this->db->query("SELECT * FROM order_products_b2b WHERE order_id=".$this->request->get['order_id']);
            // var_dump($orderarrpro);
            foreach ($orderarrpro->rows as $result){
                $productname = $this->db->query("SELECT p.sku,p.model,pd.name FROM oc_product p JOIN oc_product_description pd ON (p.product_id=pd.product_id) WHERE p.product_id='".$result['product_id']."'");
              $orderproductarray[]=array(
                'order_product_id'=>$result['order_product_id'],
                'product_id'=>$result['product_id'],
                'quantity'=>$result['quantity'],
                'price'=>$result['price'],
                'total'=>$result['total'],
                'sku'=>$productname->row['sku'],
                'model'=>$productname->row['model'],
                'name'=>$productname->row['name']
              );
          }
          $cpnexits=$this->db->query("SELECT * FROM b2b_coupon_history WHERE order_id=".$this->request->get['order_id']);
          echo('<!doctype html>
          <html lang="en">
          <head>
          <title>Order</title>');
          echo($this->language->get('header_script'));              
          echo($this->language->get('style'));
          echo('
          <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
           <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
         <link rel="stylesheet" href="/resources/demos/style.css">
          </head>
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
                  
                      echo(' <div class="row">
                      <div class="col">
                                    <table class="table  table-striped  table-bordered border-primary" style="width: 400px; margin: 10px auto;">
                                        <thead class="table-light ">
                                            <tr>                                      
                                                <td style="background: #0db04b; color: #ffffff; font-weight: 700; text-transform: capitalize;"><i class="fa fa-shopping-basket" aria-hidden="true" style="padding: 5px;"></i>Order details</td>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>                                  
                                            <tr>                                       
                                                    <td> <i class="fa fa-shopping-cart" aria-hidden="true" style="padding:5px;"></i> kheti.food
                                                    <span style="border-left: 1px solid #ddd;
                                                    line-height: 10px;
                                                    padding: 13px;
                                                    margin-left: 15px;"><i class="fa fa-calendar" aria-hidden="true" style="padding: 5px;"></i>'.$ordersinformation[date_added].'</span>

                                            </tr>
                                            
                                            <tr><td>Payment Status:<span class="par">');
                                                
                                            if($ordersinformation["payment_status"]=='0'){
                                              echo(' Not Paid');
                                            }else{
                                              echo(' Paid('.$ordersinformation["payment_method"].')');
                                            }
                                            echo('</span>');
                                                                                    
                                            echo('<div id="unpaybtn"><div class="input-group mb-3" id="receipt_div">
                                            <div class="input-group-prepend" style="display: block;
                                            width: 100%;
                                            margin: 5px 0;">
                                              <span class="input-group-text" id="basic-addon3">Recipt no:<span id="update_text">'.$ordersinformation["receipt_no"].'</span>
                                              &nbsp;&nbsp;&nbsp;&nbsp;
                                              <button type="button" class="btn btn-info" id="updatebtnreceipt" style="position: absolute;right: 0;">Update</button>
                                            </div>
                                            <form id="myForm" style="width: 100%;display:none;"> 
                                            <input type="text" style="width: 76%;padding: 3px;" id="receipt_text" aria-describedby="basic-addon3"> <button type="button" class="btn btn-success" id="receipt_update"><i class="fa fa-save"></i></button>
                                            </form>
                                            <div class="input-group-prepend" style="display: block;width: 100%;margin: 5px 0;">
                                              <span class="input-group-text" id="basic-addon3">Actual payment:<span id="update_text_actual">'.$ordersinformation["actual_pay"].'</span>
                                              &nbsp;&nbsp;&nbsp;&nbsp;
                                              <button type="button" class="btn btn-info" id="updatebtnactualpay" style="position: absolute;right: 0;">Update</button>
                                              </div>
                                            <form id="myForm1" style="width: 100%;display:none;"> 
                                            <input type="text" style="width: 76%;padding: 3px;" id="receipt_text1" aria-describedby="basic-addon3"> <button type="button" class="btn btn-success" id="actual_update" ><i class="fa fa-save"></i></button>
                                            </form>

                                            <div class="input-group-prepend" style="display: block;width: 100%;margin: 5px 0;">
                                              <span class="input-group-text" id="basic-addon3">Description:<span id="update_text_desc">'.$ordersinformation["pay_description"].'</span>
                                              &nbsp;&nbsp;&nbsp;&nbsp;
                                              <button type="button" class="btn btn-info" id="updatebtndescription" style="position: absolute;right: 0;">Update</button>
                                              </div>
                                            <form id="myForm2" style="width: 100%;display:none;"> 
                                            <input type="text" style="width: 76%;padding: 3px;" id="receipt_text2" aria-describedby="basic-addon3"> <button type="button" class="btn btn-success" id="desc_update" ><i class="fa fa-save"></i></button>
                                            </form>


                                            <div class="input-group-prepend" style="display: block;width: 100%;margin: 5px 0;">
                                              <span class="input-group-text" id="basic-addon3">Payment Date:<span id="update_text_pay">'.$ordersinformation["pay_date"].'</span>
                                              &nbsp;&nbsp;&nbsp;&nbsp;
                                              <button type="button" class="btn btn-info" id="updatebtnpaymentdate" style="position: absolute;right: 0;">Update</button>
                                              </div>
                                            <form id="myForm3" style="width: 100%;display:none;"> 
                                            <input type="text" style="width: 76%;padding: 3px;" id="datetimepicker4" aria-describedby="basic-addon3" >
                                           

                                            <button type="button" class="btn btn-success" id="paydate_update" ><i class="fa fa-save"></i></button>
                                            </form>


                                            <button class="btnunpay" style="background-color:#dc3545;width: 24%;
                                            margin: 5px 0;
                                            float: left;"><i class="fa fa-money" aria-hidden="true"></i>Unpay</button></div>

                                            </div>
                                             <div id="paybtns">
                                             <button class="btnpay"><img src="https://khetifood.com/image/catalog/cod.png" style="width: 55px;"></button>
                                             <button class="btnpay1"><img src="image/catalog/cod1.png" style="width: 55px;"></button>
                                             <button class="btnpay2"><img src="image/catalog/payza.png" style="width: 55px;"></button>
                                             <button class="btnpay3"><img src="image/catalog/cod2.png" style="width: 55px;"></button>
                                             <button class="btnpay4"><img src="image/catalog/cellpay.png" style="width: 55px;"></button>
                                             </div>');
          
                                            echo('
                                            </td>
                                            </tr>
                                            <tr><td>Order Status: <span id="orderstatus">');
                                            if($ordersinformation["order_status_id"]=='1'){
                                              echo('Pending');
                                            }elseif($ordersinformation["order_status_id"]=='2'){
                                              echo('Processing');    
                                            }
                                            elseif($ordersinformation["order_status_id"]=='3'){
                                              echo('Complete');
    
                                            }
                                                echo('</span><br><button type="button" class="btn btn-info" id="processingbtn"><i class="fa fa-spinner" aria-hidden="true"></i>Processing</button> ');
                                                echo('<button type="button" class="btn btn-success" id="completebtn"><i class="fa fa-handshake-o" aria-hidden="true"></i>Complete</button>');
                                                echo('<button type="button" class="btn btn-success" id="cancelbtn"><i class="fa fa-stop-circle-o" aria-hidden="true"></i>Cancel</button>');

        
                                                echo('</td></tr>
                                        </tbody>
                                      </table>
                                </div>
                                <div class="  col">
                                    <table class="table  table-striped  table-bordered border-primary" style="width: 280px; margin: 10px auto;">
                                        <thead class="table-light ">
                                            <tr>
                                              
                                                    <td style="background: #0db04b; color: #ffffff; font-weight: 700; text-transform: capitalize;"><i class="fa fa-user" aria-hidden="true" style="padding: 5px;"></i>Customer details</td>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>                                  
                                            <tr>
                                                
                                                    <td> <i class="fa fa-user-circle" aria-hidden="true" style="padding:5px;"></i>'.$ordersinformation[name].'</td>
                                                
                                            </tr>
                                            <tr>
                                                
                                                    <td><i class="fa fa-users" aria-hidden="true" style="padding: 5px;"></i>B2B</td>
                                                
                                            </tr>
                                            <tr>
                                                
                                                    <td><i class="fa fa-envelope" aria-hidden="true" style="padding: 5px;"></i>'.$ordersinformation[email].'</td>
                                                
                                            </tr>
                                            <tr>
                                          <td><i class="fa fa-mobile" aria-hidden="true" style="padding: 5px;"></i>'.$ordersinformation[telephone].'</td>
                                        </tr>
                                        <tr>    
                                        <td>PAN no: '.$ordersinformation[pan_no].'</td>
                                    
                                </tr>
                                        </tbody>
                                      </table>
                                </div>
                                <div class="col" style="margin-top:10px;">
                    <a href="https://khetifood.com/index.php?route=api/b2bDashOrder/orderinvoice&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'" type="button" class="btn btn-warning" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Invoice</a>');
        
                    
                               echo(' </div><table class="table table-striped table-bordered" style="width: 100%; margin: 30px 10px;">
                                <thead>
                                  <tr>
                                  <th scope="col">Product id</th>
                                  <th scope="col">Product Name</th>
                                  <th scope="col">Model</th>
                                    <th scope="col">Sku</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Unit price</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Remove</th>
                                    
                                  </tr>
                                </thead>
                                <tbody>');
                                foreach ($orderproductarray as $orderproductarray1){
                                    echo(' <tr>
                                    <td>'.$orderproductarray1[product_id].'</td>
                                    <td>'.$orderproductarray1[name].'</td>
                                    <td>'.$orderproductarray1[model].'</td>
                                    <td>'.$orderproductarray1[sku].'</td>
                                   
                                    <td>'.'<input type="text" style="width: 76%;padding: 3px;" id="'.$orderproductarray1[order_product_id].'" value="'.$orderproductarray1[quantity].'" aria-describedby="basic-addon3" >
                                    <button type="button" class="btn btn-success" onclick="updatepro(document.getElementById(\''.$orderproductarray1[order_product_id].'\').value,'.$orderproductarray1[order_product_id].')" ><i class="fa fa-refresh"></i></button>'.'</td>
                                    
                                    <td>'.$orderproductarray1[price].'</td>
                                    <td>'.$orderproductarray1[total].'</td>
                                    <td><button type="button" class="btn btn-danger" onclick="removepro('.$orderproductarray1[order_product_id].')">x</button></td>

                                    </tr>');
                                  }
                                
                                  echo('<tr>
                                    <td></td>
                                    <td></td>
                                    <td> </td>
                                    <td></td>
                                    <td></td>
                                    <td>Sub Total</td>
                                    <td>'.$ordersinformation[total].'</td>  
                                    <td></td> 
                                  </tr>');
                                  if($cpnexits->num_rows!='0'){

                                  echo('<tr>
                                    <td></td>
                                    <td></td>
                                    <td> </td>
                                    <td></td>
                                    <td></td>
                                    <td>Coupon Discount</td>
                                    <td>Rs '.$cpnexits->row[discount_amount].'</td>   
                                    <td></td>
                                  </tr>');
                                  }
                                  if($cpnexits->num_rows!='0'){
                                    echo('<tr>
                                    <td></td>
                                    <td></td>
                                    <td> </td>
                                    <td></td>
                                    <td></td>
        
                                    <td>Total</td>
                                    <td>'.'Rs. '.($ordersinformation[total]-$cpnexits->row[discount_amount]).'</td>   
                                    <td></td>
                                  </tr>');
                                    
                                  }else{
                                    echo('<tr>
                                    <td></td>
                                    <td></td>
                                    <td> </td>
                                    <td></td>
                                    <td></td>
        
                                    <td>Total</td>
                                    <td>'.'Rs. '.$ordersinformation[total].'</td>   
                                    <td></td>
                                  </tr>');

                                  }
                                  echo('
                                </tbody>
                              </table>');
                              echo('
                              <div class="item_wrap" style="width:100%;">
                                <div class="item">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong1">
                                        Add Product
                                      </button>
                              <!-- start -->
                                      <div class="modal fade" id="exampleModalLong1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                          <div class="modal-header">
                                    
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                            
                                  <!-- dropdown -->
                            
                                  <div class="btn-group" style="width: 100%;">
                                    <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Category
                                   </button>
                                   <div class="dropdown-menu" style="width: 100%;">
                                   <h6 class="dropdown-header">Choose Category:</h6>
                                   <button class="dropdown-item" onclick="myFunction(240)">Vegetables</button>
                                   <button class="dropdown-item" onclick="myFunction(239)">Fruits</button>
                                   <button class="dropdown-item" onclick="myFunction(241)">Groceries</button>
                                   <button class="dropdown-item" onclick="myFunction(248)">Dairy</button>
                            
                                   </div> 
                                   </div>
                            
                                   <!-- products -->
                                   <div class="table-responsive">
                                    <table class="table">
                                      <thead>
                                        <tr>
                                          <th>Productname</th>
                                          <th>image</th>
                                          <th>price</th>
                                          <th>Sku</th>
                                          <th>Add</th>
                            
                                       
                                        </tr>
                                      </thead>
                                      <tbody id="table1">
                            
                                      </tbody>
                                    </table>
                                  </div>
                                   <!-- products end -->
                            
                                  <!-- dropdown end -->
                            
                                          </div></div></div> 
                                          <!-- end -->
                                </div>
                            </div>');
                    
                                  echo('<form>
                                    <div class="mb-3 row" style="margin: 5px;">
                                        <label for="exampleFormControlTextarea1" class="form-label">Customer Comment</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3">'.$ordersinformation[comment].'</textarea>
                                      </div>
                                  </form>


                                  </div>');
  //  var_dump();
   if($cpnexits->num_rows=='0'){
    echo('<div class="row">
       <div class="input-group mb-3">
    <span>Coupon </span> 
  <input type="text" class="form-control" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="basic-addon2" id="cpnname">
     <div class="input-group-append">
      <button class="btn btn-outline-secondary" type="button" id="cpnbtnapply">Apply</button>
        </div>
           </div>
             <div>');
   }



  echo('  <a href="https://khetifood.com/index.php?route=api/b2bDashOrder/delete&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'" type="button" class="btn btn-danger" id="dltbtn"><i class="fa fa-trash" aria-hidden="true"></i>Delete This Order</a>
  </div>
              </div>
          </body>
          </html>');
          
          echo($this->language->get('footer_script'));
          echo('<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>');

//for coupon
          echo('<script>document.querySelector(\'#cpnbtnapply\').addEventListener(\'click\', function () {
            let code=document.getElementById("cpnname").value;
            window.location.href = "https://khetifood.com/index.php?route=api/b2bCoupons/applyCoupon&id='.$this->request->get[id].'&tkn='.$this->request->get[tkn].'&order_id='.$this->request->get[order_id].'"+"&code="+code;


          });
            </script>');
            //remove products
            echo('<script>
            function removepro(order_pro_id) {
              var formData = new FormData();
          formData.append("key","21211919");
      formData.append("order_product_id", order_pro_id);
     
  
              $.ajax({
                  url: \'https://khetifood.com/index.php?route=api/b2bDashOrder/removeproduct\',
                  type: \'POST\',
                  data: formData,
                  async: false,
                  dataType: "json",
                  crossDomain: true, 
                  
  
                  success: function (data) {
                    location.reload();
                  },
                  error:function(data){
                      alert(data);
                  },
                  cache: false,
                  contentType: false,
                  processData: false
              });
  
              return false;

            }

            function updatepro(quantity,order_pro_id) {
              
              var formData = new FormData();
              formData.append("key","up21211919");
          formData.append("order_product_id", order_pro_id);
          formData.append("quantity", quantity);
      
                  $.ajax({
                      url: \'https://khetifood.com/index.php?route=api/b2bDashOrder/updateproduct\',
                      type: \'POST\',
                      data: formData,
                      async: false,
                      dataType: "json",
                      crossDomain: true, 
              
                      success: function (data) {
                        location.reload();
                      },
                      error:function(data){
                          alert(data);
                      },
                      cache: false,
                      contentType: false,
                      processData: false
                  });
      
                  return false;

            }
            </script>');

          //for update btn receipt
          echo('<script>
          document.querySelector(\'#updatebtnreceipt\').addEventListener(\'click\', function () {
            let myForm3= document.getElementById("myForm");
            let updatebtnreceipt= document.getElementById("updatebtnreceipt");
            updatebtnreceipt.style.display="none";
            myForm.style.display="block";

          });
            </script>');

           // set to processing complete JS
      echo('<script>
      let orderstatus= document.getElementById("orderstatus");
      let processingbtn= document.getElementById("processingbtn");
      let completebtn= document.getElementById("completebtn");
      let cancelbtn= document.getElementById("cancelbtn");

      document.querySelector(\'#cancelbtn\').addEventListener(\'click\', function () {
        fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/cancel&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')
          .then((response) => {
            console.log(response);
            return response.json();
          })
          .then((data) => {    
        if(data["success"]== 1){
          orderstatus.innerHTML = "Canceled";
          cancelbtn.style.display="none";
          completebtn.style.display="block";

      }
          })
          .catch(function(error)  {
              console.log(error);
          });
        });


      document.querySelector(\'#processingbtn\').addEventListener(\'click\', function () {
        fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/processing&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')
          .then((response) => {
            console.log(response);
            return response.json();
          })
          .then((data) => {    
        if(data["success"]== 1){
          orderstatus.innerHTML = "Processing";
          processingbtn.style.display="none";
          completebtn.style.display="block";

      }
          })
          .catch(function(error)  {
              console.log(error);
          });
        });

        document.querySelector(\'#completebtn\').addEventListener(\'click\', function () {
          fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/complete&&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')
            .then((response) => {
              console.log(response);
              return response.json();
            })
            .then((data) => {    
          if(data["success"]== 1){
            orderstatus.innerHTML = "Complete";
            completebtn.style.display="none";
            processingbtn.style.display="block";

        }
            })
            .catch(function(error)  {
                console.log(error);
            });
          });
        </script>');
        //for update btn description
 echo('<script>document.querySelector(\'#updatebtndescription\').addEventListener(\'click\', function () {
  let myForm2= document.getElementById("myForm2");
  let updatebtndescription= document.getElementById("updatebtndescription");
  updatebtndescription.style.display="none";
  myForm2.style.display="block";

});
  </script>');

         // description pay js
 echo('<script>      
 let update_text2= document.getElementById("update_text_desc");
 let myForm2= document.getElementById("myForm2");
 let updatebtndescription= document.getElementById("updatebtndescription");

 
 document.querySelector(\'#desc_update\').addEventListener(\'click\', function () {
   fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/submit_pay_description&pay_description=\'+document.getElementById("receipt_text2").value+\'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')

     .then((response) => {
       console.log(response);
       return response.json();
     })
     .then((data) => {    
   if(data["success"]== 1){
   
     update_text2.innerHTML =document.getElementById("receipt_text2").value;
     document.getElementById("myForm2").reset();
     updatebtndescription.style.display="block";
     myForm2.style.display="none";


 }
     })
     .catch(function(error)  {
         console.log(error);
     });
   });
   </script>');
    //for update btn pay_date
 echo('<script>document.querySelector(\'#updatebtnpaymentdate\').addEventListener(\'click\', function () {
  let myForm3= document.getElementById("myForm3");
  let updatebtnpaymentdate= document.getElementById("updatebtnpaymentdate");
  updatebtnpaymentdate.style.display="none";
  myForm3.style.display="block";

});
  </script>');

    // pay_date pay js
 echo('<script>      
 let update_text_pay= document.getElementById("update_text_pay"); 
 let myForm3= document.getElementById("myForm3");
  let updatebtnpaymentdate= document.getElementById("updatebtnpaymentdate");
 document.querySelector(\'#paydate_update\').addEventListener(\'click\', function () {
   fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/submit_pay_date&pay_date=\'+document.getElementById("datetimepicker4").value+\'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')

     .then((response) => {
       console.log(response);
       return response.json();
     })
     .then((data) => {    
   if(data["success"]== 1){
   
    update_text_pay.innerHTML =document.getElementById("datetimepicker4").value;
     document.getElementById("myForm3").reset();
     updatebtnpaymentdate.style.display="block";
  myForm3.style.display="none";


 }
     })
     .catch(function(error)  {
         console.log(error);
     });
   });
   </script>');

 //for update btn actual pay
 echo('<script>document.querySelector(\'#updatebtnactualpay\').addEventListener(\'click\', function () {
  let myForm1= document.getElementById("myForm1");
  let updatebtnactualpay= document.getElementById("updatebtnactualpay");
  updatebtnactualpay.style.display="none";
  myForm1.style.display="block";

});
  </script>');
 // actual pay js
 echo('<script>      
 let update_text1= document.getElementById("update_text_actual");
 let myForm1= document.getElementById("myForm1");
 let updatebtnactualpay= document.getElementById("updatebtnactualpay");
 
 document.querySelector(\'#actual_update\').addEventListener(\'click\', function () {
   fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/submit_actual_pay&actual_pay=\'+document.getElementById("receipt_text1").value+\'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')

     .then((response) => {
       console.log(response);
       return response.json();
     })
     .then((data) => {    
   if(data["success"]== 1){
   
     update_text1.innerHTML =document.getElementById("receipt_text1").value;
     document.getElementById("myForm1").reset();
     updatebtnactualpay.style.display="block";
     myForm1.style.display="none";


 }
     })
     .catch(function(error)  {
         console.log(error);
     });
   });
   </script>');

        // receipt js
      echo('<script>      
      let update_text= document.getElementById("update_text");
      let myForm= document.getElementById("myForm");
      let updatebtnreceipt= document.getElementById("updatebtnreceipt");

      
      document.querySelector(\'#receipt_update\').addEventListener(\'click\', function () {
        fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/submitrecipt&receipt_no=\'+document.getElementById("receipt_text").value+\'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')

          .then((response) => {
            console.log(response);
            return response.json();
          })
          .then((data) => {    
        if(data["success"]== 1){
        
          update_text.innerHTML =document.getElementById("receipt_text").value;
          document.getElementById("myForm").reset();
          myForm.style.display="none";
          updatebtnreceipt.style.display="block";

      }
          })
          .catch(function(error)  {
              console.log(error);
          });
        });
        </script>');
        
      // js for pay and unpay JS
          echo('<script>
          let buttonunpay= document.getElementById("btnunpay");
          
          let text=document.querySelector(\'.par\');
          let unpaybtnsdiv=document.getElementById("unpaybtn");
          let paybtnsdiv=document.getElementById("paybtns");

          
          document.querySelector(\'.btnpay4\').addEventListener(\'click\', function () {
            fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/pay&method=cellpay&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')
              .then((response) => {
                console.log(response);
                return response.json();
              })
              .then((data) => {    
            if(data["success"]== 1){
              text.innerHTML = "Paid(cellpay)";
              paybtnsdiv.style.display="none";
              unpaybtn.style.display="block";

          }
              })
              .catch(function(error)  {
                  console.log(error);
              });
            });

          document.querySelector(\'.btnpay3\').addEventListener(\'click\', function () {
            fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/pay&method=esewa&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')
              .then((response) => {
                console.log(response);
                return response.json();
              })
              .then((data) => {    
            if(data["success"]== 1){
              text.innerHTML = "Paid(esewa)";
              paybtnsdiv.style.display="none";
              unpaybtn.style.display="block";

          }
              })
              .catch(function(error)  {
                  console.log(error);
              });
            });


          document.querySelector(\'.btnpay2\').addEventListener(\'click\', function () {
            fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/pay&method=Connectips&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')
              .then((response) => {
                console.log(response);
                return response.json();
              })
              .then((data) => {    
            if(data["success"]== 1){
              text.innerHTML = "Paid(Connectips)";
              paybtnsdiv.style.display="none";
              unpaybtn.style.display="block";

          }
              })
              .catch(function(error)  {
                  console.log(error);
              });
            });

          document.querySelector(\'.btnpay1\').addEventListener(\'click\', function () {
            fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/pay&method=Fonepay&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')
              .then((response) => {
                console.log(response);
                return response.json();
              })
              .then((data) => {    
            if(data["success"]== 1){
              text.innerHTML = "Paid(Fonepay)";
              paybtnsdiv.style.display="none";
              unpaybtn.style.display="block";

          }
              })
              .catch(function(error)  {
                  console.log(error);
              });
            });

          
            document.querySelector(\'.btnpay\').addEventListener(\'click\', function () {
              fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/pay&method=Cash_on_delivery&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')
                .then((response) => {
                  console.log(response);
                  return response.json();
                })
                .then((data) => {    
              if(data["success"]== 1){
                text.innerHTML = "Paid(Cash_on_delivery)";
                paybtnsdiv.style.display="none";
                unpaybtn.style.display="block";

            }
                })
                .catch(function(error)  {
                    console.log(error);
                });
              });

              document.querySelector(\'.btnunpay\').addEventListener(\'click\', function () {
                fetch(\'https://khetifood.com/index.php?route=api/b2bDashOrder/unpay&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$this->request->get['order_id'].'\')
                  .then((response) => {
                    console.log(response);
                    return response.json();
                  })
                  .then((data) => {    
                if(data["success"]== 1){
                  text.innerHTML = "Unpaid";
                  paybtnsdiv.style.display="block";
                  unpaybtn.style.display="none";
  
              }
                  })
                  .catch(function(error)  {
                      console.log(error);
                  });
                });
      </script>');
      //for add products
      echo('<script type="text/javascript">
      function myFunction(Category_id) {
      
       $.ajax({
                     url: \'https://khetifood.com/index.php?route=api/getproductsb2b&secret=U7sw3jr69r13&category_id=\'+Category_id+\'&language_id=1&location=1\',
                     type: \'GET\',
                     async: false,
                     dataType: "json",
                     crossDomain: true, 
                     success: function (data) {
                       var response = JSON.parse(JSON.stringify(data));
                       console.log(response); 
                       $(\'#table1\').empty();
     
              for (let i = 0; i < response[\'products\'].length; i++) {
let link= "https://khetifood.com/index.php?route=api/b2bDashOrder/addproducttoexistingorder&key=12389077&customer_id='.$ordersinformation[customer_id].'&product_id="+response[\'products\'][i].product_id+"&quantity=1&order_id='.$this->request->get['order_id'].'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'";
console.log(link); 
              let string = \'<tr>\'+
                \'<td>\'+response[\'products\'][i].name +\'</td>\'+
                 \'<td>\'+\'<img src="\'+response[\'products\'][i].thumb+\'" style="width: 60px;">\'+\'</td>\'+
                 \'<td>\'+response[\'products\'][i].special +\'</td>\'+
                 \'<td>\'+response[\'products\'][i].sku +\'</td>\'+
                 \'<td>\'+\'<a type="button" class="btn btn-primary" href="\'+link+\'">ADD</a>\' +\'</td>\'+
                 \'</tr>\';
                 $(\'#table1\').append(string);
               }
     },
                     error:function(data){
                         alert(data);
                     },
                     cache: false,
                     contentType: false,
                     processData: false
                 });
       
     }
     </script>');
      // end add prodcyts


      // for calender

      echo('<script type="text/javascript"><!--
      $(\'#datetimepicker4\').datepicker({
	pickTime: false,
  dateFormat: \'yy-mm-dd\'
});
</script>
      ');
       // hide processing complete button
       if($ordersinformation["order_status_id"]=='2'){
        echo('<style>
        #processingbtn{
            display: none;
        }
        </style>
        ');

    }elseif($ordersinformation["order_status_id"]=='3'){
      echo('<style>
        #completebtn{
            display: none;
        }
        </style>');
    }

          // hide buttons
          if($ordersinformation["payment_status"]=='0'){
              echo('<style>
              #unpaybtn{
                  display: none;
              }
            }#receipt_div{
              display: none;
          }
              </style>
              ');

          }else{
            echo('<style>
              #paybtns{
                  display: none;
              }
              </style>');
          }
          $user_grp_id=$this->db->query("SELECT * FROM b2b_users WHERE user_id=".$this->request->get['id']);
          
          if($user_grp_id->row['user_group_id']==1||$user_grp_id->row['user_group_id']=='1'){
            echo('<style>
              #paybtns{
                  display: none;
              }
              #unpaybtn{
                display: none;
            }
            #processingbtn{
              display: none;
          }
          #completebtn{
            display: none;
        }
        
        #dltbtn{
          display: none;
      }
      </style>');
          }

        }else{header("Location: https://khetifood.com/business/");
          exit();
        }

   }

   public function delete() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      // set status 0 for deleted products
      $this->db->query("UPDATE order_b2b SET order_status_id=0 WHERE order_id='".$this->request->get['order_id']."'");
      header("Location: https://khetifood.com/index.php?route=api/b2bDash&order_status_id=1&id=".$this->request->get['id']."&tkn=".$this->request->get['tkn']);
    }else{
        echo("password mismatch");
    }

   }

   public function delete1() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      // set status 0 for deleted products
      $this->db->query("UPDATE order_b2b SET order_status_id=0 WHERE order_id='".$this->request->get['order_id']."'");
      $dt=strtotime( now);
      $dtm=(date("Y-m-d H:i:s", $dt));
      $sql="INSERT INTO `b2b_activity_log_dashboard` (activity_type,user,change_id,date) VALUES ('delete','".$this->request->get['id']."','".$this->request->get['order_id']."','".$dtm."')";

			$this->db->query($sql);
      header("Location: https://khetifood.com/index.php?route=b2b/orders&order_status_id=1&id=".$this->request->get['id']."&tkn=".$this->request->get['tkn']);
    }else{
        echo("password mismatch");
    }

   }
   public function pay() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $this->db->query("UPDATE order_b2b SET payment_status=1, payment_method='".$this->request->get['method']."' WHERE order_id='".$this->request->get['order_id']."'");
      $json['success']= 1;
        $this->response->setOutput(json_encode($json));

    }else{
        echo("password mismatch");
    }
   }

   public function unpay() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $this->db->query("UPDATE order_b2b SET payment_status=0 WHERE order_id='".$this->request->get['order_id']."'");
      $json['success']= 1;
        $this->response->setOutput(json_encode($json));}else{
        echo("password mismatch");
    }
   }

   public function cancel() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $this->db->query("UPDATE order_b2b SET order_status_id=7 WHERE order_id='".$this->request->get['order_id']."'");
      $json['success']= 1;
        $this->response->setOutput(json_encode($json));    }else{
        echo("password mismatch");
    }

   }

   public function processing() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $this->db->query("UPDATE order_b2b SET order_status_id=2 WHERE order_id='".$this->request->get['order_id']."'");
      $json['success']= 1;
        $this->response->setOutput(json_encode($json));    }else{
        echo("password mismatch");
    }
   }
   public function complete() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $this->db->query("UPDATE order_b2b SET order_status_id=3 WHERE order_id='".$this->request->get['order_id']."'");
      $json['success']= 1;
        $this->response->setOutput(json_encode($json));
    }else{
        echo("password mismatch");
    }

   }
   public function submitrecipt() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $this->db->query("UPDATE order_b2b SET receipt_no='".$this->request->get['receipt_no']."' WHERE order_id='".$this->request->get['order_id']."'");
      $json['success']= 1;
        $this->response->setOutput(json_encode($json));}else{
        echo("password mismatch");
    }
   }
   
   public function submit_actual_pay() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $this->db->query("UPDATE order_b2b SET actual_pay='".$this->request->get['actual_pay']."' WHERE order_id='".$this->request->get['order_id']."'");
      $json['success']= 1;
        $this->response->setOutput(json_encode($json));}else{
        echo("password mismatch");
    }
   }
   public function submit_pay_description() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $this->db->query("UPDATE order_b2b SET pay_description='".$this->request->get['pay_description']."' WHERE order_id='".$this->request->get['order_id']."'");
      $json['success']= 1;
        $this->response->setOutput(json_encode($json));}else{
        echo("password mismatch");
    }
   }

   public function submit_pay_date() {
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $this->db->query("UPDATE order_b2b SET pay_date='".$this->request->get['pay_date']."' WHERE order_id='".$this->request->get['order_id']."'");
      $json['success']= 1;
        $this->response->setOutput(json_encode($json));}else{
        echo("password mismatch");
    }
   }
   

   public function getLocation($customer_id){
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $userlocation=$this->db->query("SELECT * FROM `oc_address` WHERE customer_id=".$customer_id." LIMIT 1");
    if($userlocation==NULL){
      $userlocation=$this->db->query("SELECT * FROM `b2b_address` WHERE customer_id=".$customer_id." LIMIT 1");
      $useraddress= $userlocation->row['address'];
    }else{
      $useraddress= $userlocation->row['address_1'].','.$userlocation->row['address_2'].','.$userlocation->row['city'];
    }
    return $useraddress;
      }
  }

   public function orderinfo($order_id) {
      $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE order_id='".$order_id."'");
      foreach ($orderarr->rows as $result){
        $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$result['customer_id']."'");
        $panno = $this->load->controller('api/b2bCustomerDetails/getpanno',$result['customer_id']);
        $orderarray=array(
          'order_id'=>$result['order_id'],
          'customer_id'=>$result['customer_id'],
          'name'=>$customername->row['name'],
          'comment'=>$result['comment'],
          'total'=>$result['total'],
          'order_status_id'=>$result['order_status_id'],
          'payment_status'=>$result['payment_status'],
          'payment_method'=>$result['payment_method'],
          'receipt_no'=>$result['receipt_no'],
          'actual_pay'=>$result['actual_pay'],
          'pay_description'=>$result['pay_description'],
          'pay_date'=>$result['pay_date'],
          'date_added'=>$result['date_added'],
          'date_delivery'=>$result['date_delivery'],
          'email'=>$customername->row['email'],
          'telephone'=>$customername->row['telephone'],
          'delivery_address'=>$result['delivery_address'],
          'pan_no'=> $panno

        );
    }
    return($orderarray);
   }
   public function orderinvoice() {
    $this->load->language('b2bDashboardComponents/invoice');
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
      $ordersinformation= $this->orderinfo($this->request->get['order_id']);
      $orderarrpro=$this->db->query("SELECT * FROM order_products_b2b WHERE order_id=".$this->request->get['order_id']);
            foreach ($orderarrpro->rows as $result){
                $productname = $this->db->query("SELECT p.sku,p.model,pd.name FROM oc_product p JOIN oc_product_description pd ON (p.product_id=pd.product_id) WHERE p.product_id='".$result['product_id']."'");
              $orderproductarray[]=array(
                'product_id'=>$result['product_id'],
                'quantity'=>$result['quantity'],
                'price'=>$result['price'],
                'total'=>$result['total'],
                'sku'=>$productname->row['sku'],
                'model'=>$productname->row['model'],
                'name'=>$productname->row['name']
              );
          }
          echo($this->language->get('header'));
          $cpnexits=$this->db->query("SELECT * FROM b2b_coupon_history WHERE order_id=".$this->request->get['order_id']);

          echo('<div class="wrapper">        
             <div class="content" style="width: 100%; margin-left: 40px;">
              <h5 style="color: #666; margin-left: 10px;"><strong> Invoice #'.$this->request->get['order_id'].'<strong></h5>
              <div class="row">
                  <div class="col-6">
                  <table class="table table-bordered " >
            <thead>
            <tr>
          <td colspan="2">Order Details</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: 50%;font-size:13px;"><address>
            <strong> Kheti.Food</strong><br />
            <span>Kupondole, Lalitpur</span> </address>
            <b> Telephone</b><span> +977-1-5524142</span><br />
            <b>E-Mail</b><span> Kheti.Food@dvexcellus.com</span> <br/>
            <b>Web Site:</b> <a href="https://khetifood.com"><span>khetifood.com</span></a></td>');
            if($ordersinformation["date_delivery"]!==NULL){
              echo('<td style="width: 50%;font-size:13px;"><b>Date Added</b><span> '. $ordersinformation["date_delivery"].'</span><br />');
            }else{
              echo('<td style="width: 50%;font-size:13px;"><b>Date Added</b><span> '. $ordersinformation["date_added"].'</span><br />');
            }
            
          echo('<b>Order ID:</b><span> '.$this->request->get['order_id'].'</span><br />');
          if($ordersinformation["payment_status"]==0){
            echo('<b>Payment Method</b> <span> '.'Not Paid'.'</span><br />');

          }else{
            echo('<b>Payment Method</b> <span> '. $ordersinformation["payment_method"].'</span><br />
            ');
          }
          $delivery=$this->db->query("SELECT address FROM b2b_address WHERE id=".(int)$ordersinformation["delivery_address"]);


          echo('<b>Delivery Address</b><span> '. $delivery->row[address].'</span> <br/>          ');
          echo('</td>
        </tr>
      </tbody>
    </table><table class="table table-bordered" >
    <thead>
      <tr>
        <td colspan="2">Customer Details</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="width: 50%;font-size:13px;">
    <address>
      <strong>Name:</strong><span>  '. $ordersinformation["name"].'</span> <br />
      <strong>Email:</strong><span> '. $ordersinformation["email"].'</span> <br />
      <strong>Mobile:</strong><span>  '. $ordersinformation["telephone"].'</span> <br />

      <strong>Address:</strong><span>  '. $this->getLocation($ordersinformation['customer_id']).'</span> <br/>

          </address>
    </td>
      </tr>
    </tbody>Total
  </table></div>
                     <div class="col-6">
                      <div class="images" style="width: 100%; float: right;  padding:8px;">
                          <img src="https://khetifood.com/image/fonepay_Qr_ktm.gif" alt="" style="width: 250px; height: 295px; ">
                      </div>
                  </div>
                  </div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <td><b>Product</b></td>
        <td><b>Model</b></td>
        <td class="text-right"><b>Quantity</b></td>
        <td class="text-right"><b>SKU</b></td>
        <td class="text-right"><b>Price</b></td>
        <td class="text-right"><b>Total</b></td>
      </tr>
    </thead>
    <tbody>');
    foreach ($orderproductarray as $result){
       echo('<tr>
        <td style="font-size:13px;"><span> '.$result[name].'</span> 
        </td>
        <td style="font-size:13px;"><span> '.$result[model].'</td>
        <td class="text-right" style="font-size:13px;"><span> '.$result[quantity].'</span> </td>
        <td class="text-right" style="font-size:13px;"><span> '.$result[sku].'</span> </td>
        <td class="text-right" style="font-size:13px;"><span> '.$result[price].'</span> </td>
        <td class="text-right" style="font-size:13px;"><span> '.$result[total].'</span> </td>
      </tr>');
    }
      echo('
      <tr>
        <td class="text-right" colspan="4"><b>Sub-Total</b></td>
        <td class="text-right"><span>'.$ordersinformation["total"].'</span></td>
      </tr>
      <tr>
      <td class="text-right" colspan="4"><b>Shipping Charges</b></td>
      <td class="text-right"><span>'.'Rs. 0.00'.'</span></td>
    </tr>');
    if($cpnexits->num_rows!='0'){

      echo('<tr>
      <td class="text-right" colspan="4">Coupon Discount</td>
        <td class="text-right">Rs '.$cpnexits->row[discount_amount].'</td>   
      </tr>');
      }
      if($cpnexits->num_rows!='0'){
        echo('<tr><td class="text-right" colspan="4">Total</td>
        <td class="text-right">'.'Rs. '.($ordersinformation[total]-$cpnexits->row[discount_amount]).'</td>   
      </tr>');
        
      }else{
        echo('<tr><td class="text-right" colspan="4">Total</td>
        <td class="text-right">'.'Rs. '.$ordersinformation[total].'</td>   
      </tr>');
      }
     


    echo('
    </tbody>
  </table><table class="table table-bordered">
      <thead>
        <tr>
          <td><b>Customer Comment</b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span>'.$ordersinformation["comment"].'</span></td>
        </tr>
      </tbody>
    </table>');
    echo($this->language->get('footer'));

    }

   }       
   public function addproducttoexistingorder() {
    if($this->request->get['key']=='12389077'){
      $price=$this->db->query("SELECT ROUND(price, 2) AS price FROM `oc_product` WHERE product_id='".$this->request->get[product_id]."'");
      $total= ((float)$price->row['price'])*((int)$this->request->get[quantity]);
      $querypro = $this->db->query("INSERT INTO `order_products_b2b` set order_id ='".$this->request->get[order_id]."', product_id='". $this->request->get[product_id]."', quantity='".$this->request->get[quantity]."', price='".$price->row['price']."', total=".$total.", customer_id='".$this->request->get[customer_id]."'");

      $this->db->query("UPDATE `order_b2b` SET total=total+".$total." WHERE order_id='".$this->request->get[order_id]."'");
    }
    header('Location: https://khetifood.com/index.php?route=api/b2bDashOrder&id='.$this->request->get[id].'&tkn='.$this->request->get[tkn].'&order_id='.$this->request->get[order_id]);

   } 
   
   public function addproducttoexistingorder1() {
    if($this->request->get['key']=='12389077'){
      $price=$this->db->query("SELECT ROUND(price, 2) AS price FROM `oc_product` WHERE product_id='".$this->request->get[product_id]."'");
      $total= ((float)$price->row['price'])*((int)$this->request->get[quantity]);
      $querypro = $this->db->query("INSERT INTO `order_products_b2b` set order_id ='".$this->request->get[order_id]."', product_id='". $this->request->get[product_id]."', quantity='".$this->request->get[quantity]."', price='".$price->row['price']."', total=".$total.", customer_id='".$this->request->get[customer_id]."'");

      $this->db->query("UPDATE `order_b2b` SET total=total+".$total." WHERE order_id='".$this->request->get[order_id]."'");
    }
    header('Location: https://khetifood.com/index.php?route=b2b/orderdetails&id='.$this->request->get[id].'&tkn='.$this->request->get[tkn].'&order_id='.$this->request->get[order_id]);

   } 
   public function removeproduct() {
    if($this->request->post['key']=='21211919'){
      
      $productotal=$this->db->query("SELECT total,order_id FROM `order_products_b2b` WHERE order_product_id='".$this->request->post[order_product_id]."'");
      $this->db->query("UPDATE `order_b2b` SET total=total-".(float)$productotal->row[total]." WHERE order_id='".$productotal->row[order_id]."'");
      $this->db->query("DELETE FROM `order_products_b2b` WHERE order_product_id='".$this->request->post[order_product_id]."'");
      $json['success']=1;
    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: *');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));
   }          
   
   public function updateproduct() {
    if($this->request->post['key']=='up21211919'){
      // var_dump($this->request->post[quantity]);

      
      $productotal=$this->db->query("SELECT total,price,order_id FROM `order_products_b2b` WHERE order_product_id='".$this->request->post[order_product_id]."'");
      // var_dump((float)$productotal->row[total]);
      // var_dump($productotal->row[order_id]);

      $this->db->query("UPDATE `order_b2b` SET total=total-".(float)$productotal->row[total]." WHERE order_id='".$productotal->row[order_id]."'");
      $newtotal=(float)$productotal->row[price]*(float)$this->request->post[quantity];
      $sql="UPDATE order_products_b2b SET quantity='".$this->request->post[quantity]."', total='".$newtotal."' WHERE order_product_id='".$this->request->post[order_product_id]."'";
      // var_dump($sql);

      $this->db->query($sql);
      $this->db->query("UPDATE `order_b2b` SET total=total+".$newtotal." WHERE order_id='".$productotal->row[order_id]."'");
      $json['success']=1;
    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: *');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));
   }  
}