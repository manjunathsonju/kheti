<?php
Class ControllerApiCompiledTotalb2b extends Controller{ 
    public function index(){       
      
    $productidarr=$this->db->query("SELECT DISTINCT op.product_id, cd.name from oc_order_product op JOIN oc_product p ON (op.product_id=p.product_id) JOIN oc_product_to_category ptc ON (op.product_id=ptc.product_id) JOIN oc_category_description cd ON (ptc.category_id=cd.category_id) JOIN oc_order ord ON (ord.order_id=op.order_id) where op.order_id BETWEEN ".$_GET['from'] ." AND ".$_GET['to']." AND p.location='ktm' AND cd.language_id='1' AND ord.customer_group_id='3' AND ord.order_status_id!='5'AND ord.order_status_id!='7' ORDER BY cd.category_id ");
    foreach ($productidarr->rows as $result){
        $productidarray[]=array(
            'product_id'=>$result['product_id'],
            'name'=>$result['name']
        );
    }
//        var_dump($productidarray);
// var_dump(sizeof($productidarray) );
 
// table

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
        <div class="container"><h2>Order accumulation (B2B only,kathmandu only,canceled and complete orders excluded) from order id '.$_GET['from']. " to ". $_GET['to'].'</h2>
        <button type="button" class="btn btn-danger" id="button-export">Export</button>
<hr>
          <table class="table table-striped" id="orderits">
              <thead>
                <tr>
                  <th scope="col">Product Id</th>
                  <th scope="col">Product Category</th>
                  <th scope="col">Product Name</th>
                  <th scope="col">Sku</th>
                  <th scope="col">Total Quantity</th>
                  <th scope="col">Order ids</th>

                </tr>
              </thead>
              <tbody>');

              foreach ($productidarray as $productid){
                echo ('<tr>');
                echo '<td>'.$productid['product_id'].'</td>';
                echo '<td>'.$productid['name'].'</td>';
                $productname= $this->db->query("SELECT name from oc_product_description WHERE product_id=".$productid['product_id']);
                echo '<td>'.$productname->row['name'].'</td>';
                $productsku=$this->db->query("SELECT sku from oc_product WHERE product_id=".$productid['product_id']);
                echo '<td>'.$productsku->row['sku'].'</td>';
                $queryresult = $this->db->query("SELECT * from oc_order_product op  JOIN "."oc_order o ON (o.order_id=op.order_id) WHERE o.order_status_id !="."0"." o.order_status_id !="."5"." AND o.order_status_id !="."7"." AND o.customer_group_id="."3"." AND op.order_id BETWEEN ".$_GET['from']." AND ".$_GET['to']." AND op.product_id=".$productid['product_id']);
                $total=0; 
                $orderids='';
                foreach ($queryresult->rows as $result1) {  
                  $orderids = $orderids . "," .$result1['order_id'];
                    $total = $total + $result1['quantity'];
                }
                echo '<td>'.$total.'</td>';
                echo '<td>'.$orderids.'</td>';

                echo '</tr>';
                unset($total);                   
                unset($orderids);

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
     var productids=[];
     var productcategory=[];
     var productname=[];
     var sku=[];var productlocation=[];var totalquntity=[]; var orderidss=[];
     

     $(\'#orderits tbody tr td:nth-child(1)\').each(function () {
        productids.push($(this).text());
     })

     $(\'#orderits tbody tr td:nth-child(2)\').each(function () {
        productcategory.push($(this).text());
     })

      $(\'#orderits tbody tr td:nth-child(3)\').each(function () {
        productname.push($(this).text());
     })
      $(\'#orderits tbody tr td:nth-child(4)\').each(function () {
        sku.push($(this).text());
     })
     
      $(\'#orderits tbody tr td:nth-child(5)\').each(function () {
        totalquntity.push($(this).text());
     })
     $(\'#orderits tbody tr td:nth-child(6)\').each(function () {
      orderidss.push($(this).text());
   })


     var arraytotalreport=[[\'Product ID\',\'Product Category\',\'Product Name\',\'Sku\',\'total quantity\',\'Order ids\']];
     for(let i=0; i<'.sizeof($productidarray).';i++){
       var arraypush=[productids[i],productcategory[i],productname[i],sku[i],totalquntity[i],orderidss[i]];
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
saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), \'Orders_compiled_Report.xlsx\');

 });
</script>');

// end of export

}
}
