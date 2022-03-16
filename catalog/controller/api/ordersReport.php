<?php
Class ControllerApiOrdersReport extends Controller{
    public function index(){    
      // var_dump($_GET['customer_group_id']);
        if($_GET['passrd']=='21kheti'){
      $sql ="SELECT order_id, store_name, customer_id,firstname,lastname,email,telephone, payment_city, payment_method, shipping_company, shipping_address_1,shipping_city,shipping_zone,comment,total,date_added FROM oc_order WHERE";
      $sql .= " order_status_id IN (1,2,5)";


      if($_GET['customer_group_id']!=9){ 
        $sql .= " AND customer_group_id=".$_GET['customer_group_id'];
       }

      if($_GET['store_id']!=2){ 
        $sql .= " AND store_id=".$_GET['store_id'];
       }
       if($_GET['locationstore']!='both'){ 
        $sql .= " AND shipping_zone='".$_GET['locationstore']."'";
       }
       
       if(isset($_GET['datestart'])){ 
        $sql .= " AND date_added>='".$_GET['datestart']."'";
       }

       if(isset($_GET['dateend'])){ 
        $sql .= " AND date_added<='".$_GET['dateend']." 23:59:59'";
       }
       $sql .= "ORDER BY date_added";
      //  var_dump($sql);

      $querys = $this->db->query($sql);
      foreach ($querys->rows as $result){
        $ordersidarray[]=array(
            'order_id'=>$result['order_id'],
            'store_name'=>$result['store_name'],
            'customer_id'=>$result['customer_id'],
            'firstname'=>$result['firstname'],
            'lastname'=>$result['lastname'],
            'email'=>$result['email'],
            'telephone'=>$result['telephone'],
            'payment_city'=>$result['payment_city'],
            'payment_method'=>$result['payment_method'],
            'shipping_company'=>$result['shipping_company'],
            'shipping_address_1'=>$result['shipping_address_1'],
            'shipping_city'=>$result['shipping_city'],
            'shipping_zone'=>$result['shipping_zone'],
            'comment'=>$result['comment'],
            'total'=> round((float)$result['total'],2),
            'date_added'=>$result['date_added']

           
        );
    }
      // var_dump($query->rows);

//table
echo('<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sales Compiled</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container"><h2>Orders Report</h2>
<button type="button" class="btn btn-danger" id="button-export">Export</button>
<hr>
  <table class="table table-striped" id="orderits">
      <thead>
        <tr>
          <th scope="col">order_id</th>
          <th scope="col">store_name</th>
          <th scope="col">customer_id</th>

          <th scope="col">firstname</th>
          <th scope="col">lastname</th>
          <th scope="col">email</th>

          <th scope="col">telephone</th>
          <th scope="col">payment_city</th>
          <th scope="col">payment_method</th>

          <th scope="col">shipping_company</th>
          <th scope="col">shipping_address_1</th>
          <th scope="col">shipping_city</th>

          <th scope="col">shipping_zone</th>
          <th scope="col">comment</th>
          <th scope="col">total</th>

          <th scope="col">date_added</th>

        </tr>
      </thead>
      <tbody>');

      foreach ($ordersidarray as $query){
        echo ('<tr>');
        echo '<td>'.$query['order_id'].'</td>';
        echo '<td>'.$query['store_name'].'</td>';
        echo '<td>'.$query['customer_id'].'</td>';

        echo '<td>'.$query['firstname'].'</td>';
        echo '<td>'.$query['lastname'].'</td>';
        echo '<td>'.$query['email'].'</td>';

        echo '<td>'.$query['telephone'].'</td>';
        echo '<td>'.$query['payment_city'].'</td>';
        echo '<td>'.$query['payment_method'].'</td>';

        echo '<td>'.$query['shipping_company'].'</td>';
        echo '<td>'.$query['shipping_address_1'].'</td>';
        echo '<td>'.$query['shipping_city'].'</td>';

        echo '<td>'.$query['shipping_zone'].'</td>';
        echo '<td>'.$query['comment'].'</td>';
        echo '<td>'.$query['total'].'</td>';

        echo '<td>'.$query['date_added'].'</td>';
        



        echo '</tr>';
       

    }                   
        echo(' 
        </tbody>
      </table>
</body>
</html>');
//end table

//export

echo ( '
<script lang="javascript" src="./xlsx.full.min.js"></script>
<script lang="javascript" src="./FileSaver.min.js"></script>
 <script type="text/javascript">
     $(\'#button-export\').on(\'click\', function() {

         var orderids=[];
         var storenames=[];
         var customerids=[];

         var firstnames=[];
         var lastnames=[];
         var emails=[]; 

         var telephones=[];
         var paymentcitys=[];
         var paymentmethds=[];

         var shippingcompanys=[];
         var shippingaddresses=[];
         var shippingcitys=[];

         var shippingzones=[];
         var comments=[];
         var totals=[];

         var dates=[];
        

         $(\'#orderits tbody tr td:nth-child(1)\').each(function () {
          orderids.push($(this).text());
         })

         $(\'#orderits tbody tr td:nth-child(2)\').each(function () {
          storenames.push($(this).text());
       })
        

          $(\'#orderits tbody tr td:nth-child(3)\').each(function () {
            customerids.push($(this).text());
         })
          $(\'#orderits tbody tr td:nth-child(4)\').each(function () {
            firstnames.push($(this).text());
         })
         
          $(\'#orderits tbody tr td:nth-child(5)\').each(function () {
            lastnames.push($(this).text());
         })
         $(\'#orderits tbody tr td:nth-child(6)\').each(function () {
          emails.push($(this).text());
       }) 
       $(\'#orderits tbody tr td:nth-child(7)\').each(function () {
        telephones.push($(this).text());
     })
     $(\'#orderits tbody tr td:nth-child(8)\').each(function () {
      paymentcitys.push($(this).text());
   })
   $(\'#orderits tbody tr td:nth-child(9)\').each(function () {
    paymentmethds.push($(this).text());
 })
 $(\'#orderits tbody tr td:nth-child(10)\').each(function () {
  shippingcompanys.push($(this).text());
})
$(\'#orderits tbody tr td:nth-child(11)\').each(function () {
  shippingaddresses.push($(this).text());
})
$(\'#orderits tbody tr td:nth-child(12)\').each(function () {
  shippingcitys.push($(this).text());
})
$(\'#orderits tbody tr td:nth-child(13)\').each(function () {
  shippingzones.push($(this).text());
})
$(\'#orderits tbody tr td:nth-child(14)\').each(function () {
  comments.push($(this).text());
})
$(\'#orderits tbody tr td:nth-child(15)\').each(function () {
  totals.push($(this).text());
})
$(\'#orderits tbody tr td:nth-child(16)\').each(function () {
  dates.push($(this).text());
})

var arraytotalreport=[[\'order_id\',\'store_name\',\'customer_id\',\'firstname\',\'lastname\',\'email\',\'telephone\',\'payment_city\',\'payment_method\',\'shipping_company\',\'shipping_address\',\'shipping_city\',\'shipping_zone\',\'comment\',\'total\',\'date_added\']];
for(let i=0; i<'.sizeof($ordersidarray).';i++){
           var arraypush=[orderids[i],storenames[i],customerids[i],firstnames[i],lastnames[i],emails[i],telephones[i],paymentcitys[i],paymentmethds[i],shippingcompanys[i],shippingaddresses[i],shippingcitys[i],shippingzones[i],comments[i],totals[i],dates[i]];
             arraytotalreport.push(arraypush);

         }
         var wb = XLSX.utils.book_new();
      wb.Props = {
             Title: "report",
             Subject: "report",
             Author: "Rajiv shrestha",
             CreatedDate: new Date(2020,12,19)
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
  saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), \'Orders_Report.xlsx\');
   
     });
 </script>');

 // end of export


        }else{
          echo("wrong input or password");
        }

       
}
}