<?php
Class ControllerApiB2bCompileOrders extends Controller{ 
    public function index(){
        $this->load->language('b2bDashboardComponents/components');

        if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
            echo('<!doctype html>
            <html lang="en">
            <head>
            <title>Compile Orders</title>');
            echo($this->language->get('header_script'));
            echo($this->language->get('style'));
            echo('</head>            <body><div class="wrapper">');
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
                     <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bCompileOrders&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&location=1">Kathmandu</a>
                     <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bCompileOrders&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&location=2">Pokhara</a>
                     <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bCompileOrders&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">Both</a>
                  
                 </div> 
                 </div>
                 
                 <div class="btn-group" style="width: 170px;">
                    <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Language
                   </button>
                   <div class="dropdown-menu" style="width: 165px;">
                   <h6 class="dropdown-header">Choose Language:</h6>
                       <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bCompileOrders&language_id=2&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">Nepali</a>
                       <a class="dropdown-item" href="https://khetifood.com/index.php?route=api/b2bCompileOrders&language_id=1&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'">English</a>
                    
                   </div> 
                   </div>
                
           </div></div>
          </div>');
             // filter end

                       echo('
                        <table class="table table-bordered" style="width: 100%; margin: 20px auto;font-size: 14px;">
                        <thead>
                        <tr>
                            <th>product id</th>
                            <th>product Name</th>
                            <th>Model</th>
                            <th>Sku</th>
                            <th>Order id</th>
                            <th>Quantity(total)</th>
                        </tr>
                              </thead>
                           <tbody>
                        ');
                // sql to find products in processing
                if($this->request->get['location']){
                    if($this->request->get['location']==1){

                $productsarrayfromprocessin=$this->db->query("SELECT DISTINCT(op.product_id) FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) LEFT JOIN customer_locations c ON (c.customer_id=ob.customer_id) WHERE ob.order_status_id=2 AND (c.location IS NULL OR c.location=1)");
                    }else{
                 $productsarrayfromprocessin=$this->db->query("SELECT DISTINCT(op.product_id) FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) LEFT JOIN customer_locations c ON (c.customer_id=ob.customer_id) WHERE ob.order_status_id=2 AND c.location=2");

                    }
                }else{
                $productsarrayfromprocessin=$this->db->query("SELECT DISTINCT(op.product_id) FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) WHERE ob.order_status_id=2");

                }


                foreach ($productsarrayfromprocessin->rows as $result){
                    if($this->request->get['language_id']){
                        $productname = $this->db->query("SELECT p.sku,p.model,pd.name FROM oc_product p JOIN oc_product_description pd ON (p.product_id=pd.product_id) WHERE p.product_id='".$result['product_id']."' AND pd.language_id='".$this->request->get['language_id']."'");

                    }else{
                        $productname = $this->db->query("SELECT p.sku,p.model,pd.name FROM oc_product p JOIN oc_product_description pd ON (p.product_id=pd.product_id) WHERE p.product_id='".$result['product_id']."'");

                    }
    
                    echo('
                    <tr>
                        <td>'.$result['product_id'].'</td>
                        <td>'.$productname->row['name'].' </td>
                        <td>'.$productname->row['model'].'</td>
                        <td>'.$productname->row['sku'].'</td>');
                        if($this->request->get['location']){
                            if($this->request->get['location']==1){
                                $productorderquantity=$this->db->query("SELECT op.order_id,op.quantity FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) LEFT JOIN customer_locations c ON (c.customer_id=ob.customer_id) WHERE ob.order_status_id=2 AND op.product_id='".$result[product_id]."' AND (c.location IS NULL OR c.location=1)");

                            }else{
                                $productorderquantity=$this->db->query("SELECT op.order_id,op.quantity FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) LEFT JOIN customer_locations c ON (c.customer_id=ob.customer_id) WHERE ob.order_status_id=2 AND op.product_id='".$result[product_id]."' AND c.location=2");

                            }

                        }else{
                            $productorderquantity=$this->db->query("SELECT op.order_id,op.quantity FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) WHERE ob.order_status_id=2 AND op.product_id='".$result[product_id]."'");

                        }  
                $totalamount=0;
                echo('<td>');
                foreach ($productorderquantity->rows as $result){
                    echo('Order id: <a href="https://khetifood.com/index.php?route=api/b2bDashOrder&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'&order_id='.$result[order_id].'">'.$result[order_id].'</a>');
                    echo('(Quantity: '.$result[quantity].')'.',');
                   $totalamount=$totalamount+(float)$result['quantity'];
                }
                echo('</td>');
                echo('<td>'.$totalamount.'</td>');
                
                echo('</tr>');
                }
    echo(' </tbody>
    </table>
                    </div>
                </div>
            </body>
            </html>');
          echo($this->language->get('footer_script'));

  

        }   else{
            header("Location: https://khetifood.com/business/");
            exit();
        }
   }
   
}