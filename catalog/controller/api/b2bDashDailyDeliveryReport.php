<?php
class ControllerApiB2bDashDailyDeliveryReport extends Controller {
	public function index() {
    $this->load->language('b2bDashboardComponents/components');
    if($this->load->controller('api/b2bDashHome/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){

      $orderarr=$this->db->query("SELECT * FROM b2b_daily_delivery_report");
      foreach ($orderarr->rows as $result){
        $orderinfo = $this->db->query("SELECT * from order_b2b where order_id='".$result['order_id']."'");

        $orderinfoaddress = $this->db->query("SELECT * from b2b_address where id='".$orderinfo->row['delivery_address']."'");
        $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$orderinfo->row['customer_id']."'");

        $orderarray[]=array(
          'order_id'=>$result['order_id'],
          'customer_'=>$orderinfo->row['customer_id'],
          'name'=>$customername->row['name'],
          'total'=>$orderinfo->row['total'],
          'telephone'=>$customername->row['telephone'],
          'date_added'=>$orderinfo->row['date_added'],
          'date_delivery'=>$orderinfo->row['date_delivery'],
          'address'=>$orderinfoaddress->row['address'].',',$orderinfoaddress->row['detail_address'],
          'contact_number'=>$orderinfoaddress->row['contact_number']
        );
    }
    // var_dump($orderarray);

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
        echo('<a class="btn btn-danger" href="https://khetifood.com/index.php?route=api/b2bDashDailyDeliveryReport/clearallOrder&key=@d0rd34&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'" role="button">Clear this list</a>');
        echo('<button type="button" id="button-export" class="btn btn-info" >Export</button>');

        if(sizeof($orderarray)==0||sizeof($orderarray)=='0'){
          echo('<h4 style="    margin-left: 40%;
          ">Sorry, no orders here.</h4>');
        }else{
          echo('<table id="example" style=" max-width: 100%;">
          <thead>
              <tr>
                <th scope="col">Order Id</th>
                <th scope="col">Customer name</th>
                <th scope="col">telephone</th>
                <th scope="col">contact_number</th>
                <th scope="col">Total</th>
                <th scope="col">date_delivery</th>
                <th scope="col">Date Added</th>
                <th scope="col">address</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>');

            foreach ($orderarray as $orderarray1){
              echo(' <tr>
              <td>'.$orderarray1[order_id].'</td>
              <td>'.$orderarray1[name].'</td>
              <td>'.$orderarray1[telephone].'</td>
              <td>'.$orderarray1[contact_number].'</td>
              <td>'.$orderarray1[total].'</td>
              <td>'.$orderarray1[date_delivery].'</td>
              <td>'.$orderarray1[date_added].'</td>
              <td>'.$orderarray1[address].'</td>
              <td><a class="btn btn-danger" href="https://khetifood.com/index.php?route=api/b2bDashDailyDeliveryReport/delOrder&key=@d0rd34&order_id='.$orderarray1[order_id].'&id='.$this->request->get['id'].'&tkn='.$this->request->get['tkn'].'" role="button">Remove</a></td>

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
      // remove js
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
  //  <!--for export in excel-->
   echo('
 <script lang="javascript" src="./xlsx.full.min.js"></script>
<script lang="javascript" src="./FileSaver.min.js"></script>
    <script type="text/javascript">
        $(\'#button-export\').on(\'click\', function() {
            var orderids=[];
            var customerids=[];
            var telephones=[];

            var contactnums=[];
            var totals=[];
            var datedeliverys=[];

            var dateaddeds=[];
            var addresses=[];

            

            $(\'#example tbody tr td:nth-child(1)\').each(function () {
                orderids.push($(this).text());
            })
            $(\'#example tbody tr td:nth-child(2)\').each(function () {
              customerids.push($(this).text());
          })
          $(\'#example tbody tr td:nth-child(3)\').each(function () {
            telephones.push($(this).text());
        })
        $(\'#example tbody tr td:nth-child(4)\').each(function () {
          contactnums.push($(this).text());
      })
      $(\'#example tbody tr td:nth-child(5)\').each(function () {
        totals.push($(this).text());
    })
    $(\'#example tbody tr td:nth-child(6)\').each(function () {
      datedeliverys.push($(this).text());
  })
  $(\'#example tbody tr td:nth-child(7)\').each(function () {
    dateaddeds.push($(this).text());
})
$(\'#example tbody tr td:nth-child(8)\').each(function () {
  addresses.push($(this).text());
})

            
           
            var arraytotalreport=[[\'Order ID\',\'Customer name\',\'Telephone\',\'Contact number\',\'total\',\'date added\',\'delivery date\',\'address\']];
            for(let i=0; i<orderids.length;i++){
              var arraypush=[orderids[i],customerids[i],telephones[i],contactnums[i],totals[i],datedeliverys[i],dateaddeds[i],addresses[i]];
                arraytotalreport.push(arraypush);

            }


            var wb = XLSX.utils.book_new();
         wb.Props = {
                Title: "report",
                Subject: "Test",
                Author: "Rajiv",
                CreatedDate: new Date(2021,01,19)
        };
        
        wb.SheetNames.push("Test Sheet");
        var ws = XLSX.utils.aoa_to_sheet(arraytotalreport);
        wb.Sheets["Test Sheet"] = ws;
        var wbout = XLSX.write(wb, {bookType:\'xlsx\',  type: \'binary\'});
        function s2ab(s) {
  
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
                
        }
      
     saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), \'Daily_Delivery_Report.xlsx\');
     


           
        });
    </script>');

   

    }else{header("Location: https://khetifood.com/business/");
          exit();
        }


    }

    public function addOrder() {
        if($this->request->get['key']=='@d0rd34'){
          $sql ="SELECT * FROM `b2b_daily_delivery_report` WHERE order_id='".$this->request->get['order_id']."'";
          $exists=$this->db->query($sql);
          if($exists->num_rows==0){
            $sql ="INSERT INTO b2b_daily_delivery_report (order_id) VALUE ('".$this->request->get['order_id']."')";
            $this->db->query($sql);
            $json['success']= 1;
            $this->response->setOutput(json_encode($json));

          }
        }

    }
    public function delOrder() {
      if($this->request->get['key']=='@d0rd34'){
        $sql ="DELETE FROM `b2b_daily_delivery_report` WHERE  order_id='".$this->request->get['order_id']."'";
        $this->db->query($sql);
        header("Location: https://khetifood.com/index.php?route=api/b2bDashDailyDeliveryReport&id=".$this->request->get['id']."&tkn=".$this->request->get['tkn']);
        exit();
        // header('Location: '.$url);

       

      }
    }

    public function clearallOrder() {
      if($this->request->get['key']=='@d0rd34'){
        $sql ="DELETE FROM `b2b_daily_delivery_report` WHERE 1";
        $this->db->query($sql);
        header("Location: https://khetifood.com/index.php?route=api/b2bDashDailyDeliveryReport&id=".$this->request->get['id']."&tkn=".$this->request->get['tkn']);
        exit();

      }
    }

}