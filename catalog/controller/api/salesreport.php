<?php
Class ControllerApiSalesreport extends Controller{ 
    public function index(){
      // var_dump($_GET['datestart']);
      // var_dump($_GET['passrd']);
      // var_dump($_GET['customer_group_id']);
      if($_GET['passrd']=='21kheti'){
        $sql ="SELECT op.order_id,op.product_id,op.name,op.model,op.quantity,op.price,op.total,o.firstname,o.lastname,o.date_added FROM oc_order o JOIN oc_order_product op ON (op.order_id=o.order_id) WHERE";
        $sql .= " o.order_status_id IN (1,2,5)";
        if($_GET['customer_group_id']!=9){ 
          $sql .= " AND o.customer_group_id=".$_GET['customer_group_id'];
         }

         if($_GET['store_id']!=2){ 
          $sql .= " AND o.store_id=".$_GET['store_id'];
         }

         if($_GET['locationstore']!='both'){ 
          $sql .= " AND o.shipping_zone='".$_GET['locationstore']."'";
         }
         
         if(isset($_GET['datestart'])){ 
          $sql .= " AND o.date_added>=".$_GET['datestart'];
         }
  
         if(isset($_GET['dateend'])){ 
          $sql .= " AND o.date_added<='".$_GET['dateend']." 23:59:59'";
         }

         $sql .= "ORDER BY op.product_id";

         // run query and put in array
         $querys = $this->db->query($sql);
         foreach ($querys->rows as $result){
           $ordersidarray[]=array(
               'order_id'=>$result['order_id'],
               'firstname'=>$result['firstname'],
               'lastname'=>$result['lastname'],

               'product_id'=>$result['product_id'],
               'name'=>$result['name'],
               'model'=>$result['model'],
               'quantity'=>$result['quantity'],
               'price'=>$result['price'],
               'total'=>$result['total'],
               'date_added'=>$result['date_added']
           );
          //  var_dump($ordersidarray);
       }
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
          <th scope="col">Firstname</th>
          <th scope="col">lastname</th>

          <th scope="col">product id</th>
          <th scope="col">name</th>

          <th scope="col">model</th>
          <th scope="col">quantity</th>
          <th scope="col">price</th>

          <th scope="col">total</th>
          <th scope="col">date_added</th>


        </tr>
      </thead>
      <tbody>');

      foreach ($ordersidarray as $query){
        echo ('<tr>');
        echo '<td>'.$query['order_id'].'</td>';
        echo '<td>'.$query['firstname'].'</td>';
        echo '<td>'.$query['lastname'].'</td>';

        echo '<td>'.$query['product_id'].'</td>';
        echo '<td>'.$query['name'].'</td>';

        echo '<td>'.$query['model'].'</td>';
        echo '<td>'.$query['quantity'].'</td>';
        echo '<td>'.$query['price'].'</td>';

      
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
        
         var firstnames=[];
         var lastnames=[];

         var product_ids=[];
         var names=[];
         var models=[];
         var quantitys=[];
         var price=[];
        
         var totals=[];


         $(\'#orderits tbody tr td:nth-child(1)\').each(function () {
          orderids.push($(this).text());
         })

     
          $(\'#orderits tbody tr td:nth-child(2)\').each(function () {
            firstnames.push($(this).text());
         })
         
          $(\'#orderits tbody tr td:nth-child(3)\').each(function () {
            lastnames.push($(this).text());
         })
         $(\'#orderits tbody tr td:nth-child(4)\').each(function () {
          product_ids.push($(this).text());
       }) 
       $(\'#orderits tbody tr td:nth-child(5)\').each(function () {
        names.push($(this).text());
     })
     $(\'#orderits tbody tr td:nth-child(6)\').each(function () {
      models.push($(this).text());
   })
   $(\'#orderits tbody tr td:nth-child(7)\').each(function () {
    quantitys.push($(this).text());
 })
 $(\'#orderits tbody tr td:nth-child(8)\').each(function () {
  price.push($(this).text());
})
$(\'#orderits tbody tr td:nth-child(9)\').each(function () {
  totals.push($(this).text());
})

var arraytotalreport=[[\'order_id\',\'firstname\',\'lastname\',\'product id\',\'name\',\'model\',\'quantity\',\'price\',\'total\']];
for(let i=0; i<'.sizeof($ordersidarray).';i++){
           var arraypush=[orderids[i],firstnames[i],lastnames[i],product_ids[i],names[i],models[i],quantitys[i],price[i],totals[i]];
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
  saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), \'Sales_Report.xlsx\');
   
     });
 </script>');

 // end of export


         

      }else{
        echo('wrong password');
      }

    }
}
