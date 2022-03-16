<?php
Class ControllerApiCompiledTotalfarm extends Controller{
    public function index(){

        echo"Calculating from ".$_GET['from']. " to ". $_GET['to']."</br>";
        echo "FOR KHETIFARM ONLY";
        $productidarr=$this->db->query("SELECT DISTINCT op.product_id, cd.name from oc_order_product op JOIN oc_product p ON (op.product_id=p.product_id) JOIN oc_product_to_category ptc ON (op.product_id=ptc.product_id) JOIN oc_category_description cd ON (ptc.category_id=cd.category_id) JOIN oc_product_to_store pts ON (p.product_id= pts.product_id) where pts.store_id='0' AND op.order_id BETWEEN ".$_GET['from'] ." AND ".$_GET['to']." AND p.location='ktm' AND cd.language_id='1' ORDER BY cd.category_id ");
        foreach ($productidarr->rows as $result){
            $productidarray[]=array(
                'product_id'=>$result['product_id'],
                'name'=>$result['name']
            );
        }
//        var_dump($productidarray);
//         foreach ($productidarray as $productid){
//             echo "</br>"."</br>"."Product ID: ". $productid['product_id']."</br>";
//             echo "Product category: " . $productid['name']."</br>";
//             $productname= $this->db->query("SELECT name from oc_product_description WHERE product_id=".$productid['product_id']);
//             echo "Name: ".$productname->row['name']."</br>";
//             $productsku=$this->db->query("SELECT sku from oc_product WHERE product_id=".$productid['product_id']);
//             echo "SKU: ".$productsku->row['sku']."</br>";
//             $productlocation=$this->db->query("SELECT location from oc_product WHERE product_id=".$productid['product_id']);
//             echo "Product Location: ".$productlocation->row['location']."</br>";
// //            $queryresult = $this->db->query("SELECT * from oc_order_product op where op.order_id BETWEEN ".$_GET['from']." AND ".$_GET['to']." AND op.product_id=".$productid." JOIN "."oc_order o ON (o.order_id=op.order_id) WHERE o.order_status_id !="."7");
// //            var_dump($queryresult);
//             $queryresult = $this->db->query("SELECT * from oc_order_product op  JOIN "."oc_order o ON (o.order_id=op.order_id) WHERE o.order_status_id !="."7"." AND o.order_status_id !="."0"." AND o.order_status_id !="."5"." AND o.order_status_id !="."8"." AND op.order_id BETWEEN ".$_GET['from']." AND ".$_GET['to']." AND op.product_id=".$productid['product_id']);


//             $total=0;
//             echo "The product is in following orders id:";
//             foreach ($queryresult->rows as $result1) {
//                 echo($result1['order_id']."\t");
//                 $total = $total + $result1['quantity'];
//             }
//             echo "</br>"."Total Quantity=".($total);
//             unset($total);
//         }
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
            <div class="container"><h2>Order accumulation (processing only,kathmandu only ) from order id '.$_GET['from']. " to ". $_GET['to'].'</h2>
            <button type="button" class="btn btn-danger" id="button-export">Export</button>
<hr>
              <table class="table table-striped" id="orderits">
                  <thead>
                    <tr>
                      <th scope="col">Product Id</th>
                      <th scope="col">model</th>
                      <th scope="col">Product Name</th>
                      <th scope="col">Sku</th>
                      <th scope="col">Total Quantity</th>
                      <th scope="col">Total by weight</th>

                      <th scope="col">Order ids</th>

                    </tr>
                  </thead>
                  <tbody>');

                  foreach ($productidarray as $productid){

                     $queryresult = $this->db->query("SELECT * from oc_order_product op  JOIN "."oc_order o ON (o.order_id=op.order_id) WHERE o.order_status_id ="."2"." AND op.order_id BETWEEN ".$_GET['from']." AND ".$_GET['to']." AND op.product_id=".$productid['product_id']);
                    $total=0; 
                    $orderids='';
                    foreach ($queryresult->rows as $result1) {  
                      $orderids = $orderids . "," .$result1['order_id'];
                        $total = $total + $result1['quantity'];
                    }
                    if($total!=0){
// var_dump($productsku->row['sku']);

                      echo ('<tr>');
                      echo '<td>'.$productid['product_id'].'</td>';
  
                      $productsku=$this->db->query("SELECT model from oc_product WHERE product_id=".$productid['product_id']);
                      echo '<td>'.$productsku->row['model'].'</td>';
                      
                      $productname= $this->db->query("SELECT name from oc_product_description WHERE product_id=".$productid['product_id'] ." AND language_id='".$_GET['language']."'");
                      echo '<td>'.$productname->row['name'].'</td>';
                      $productsku=$this->db->query("SELECT sku from oc_product WHERE product_id=".$productid['product_id']);
                      echo '<td>'.$productsku->row['sku'].'</td>';
                      // $queryresult = $this->db->query("SELECT * from oc_order_product op  JOIN "."oc_order o ON (o.order_id=op.order_id) WHERE o.order_status_id ="."2"." AND op.order_id BETWEEN ".$_GET['from']." AND ".$_GET['to']." AND op.product_id=".$productid['product_id']);
                      // $total=0; 
                      // $orderids='';
                      // foreach ($queryresult->rows as $result1) {  
                      //   $orderids = $orderids . "," .$result1['order_id'];
                      //     $total = $total + $result1['quantity'];
                      // }
                      echo '<td>'.$total.'</td>';
                      echo '<td>';

                      if($productsku->row['sku']=='KG'||$productsku->row['sku']=='kg'||$productsku->row['sku']=='Kg'){
                        echo($total.' kg');

                      }elseif($productsku->row['sku']=='mutha'||$productsku->row['sku']=='Mutha'){
                        echo($total.' mutha');

                      }
                      elseif($productsku->row['sku']=='500 gm'||$productsku->row['sku']=='0.5 kg'||$productsku->row['sku']=='500 gram'){
                        echo($total/2 .'kg');
                      }
                      elseif($productsku->row['sku']=='200 gram'||$productsku->row['sku']=='200 gm'||$productsku->row['sku']=='200 Gram'){
                        echo($total/5 .'kg');
                      }
                      elseif($productsku->row['sku']=='piece'||$productsku->row['sku']=='Piece'||$productsku->row['sku']=='pc'){
                        echo($total.' piece');

                      }
                      elseif($productsku->row['sku']=='dozen'||$productsku->row['sku']=='Dozen'){
                        echo($total.' piece');

                      }
                      elseif($productsku->row['sku']=='Half dozen'){
                        echo($total/2 .' dozen');
                      }
                      elseif($productsku->row['sku']=='300 gm'){
                        echo(($total*3)/10 .' kg');
                      }
                      elseif($productsku->row['sku']=='250 gm'||$productsku->row['sku']=='250 gram'||$productsku->row['sku']=='250 Gram'){
                        echo(($total)/4 .' kg');
                      }
                      elseif($productsku->row['sku']=='2.5 kg'){
                        echo(($total*2.5) .' kg');
                      }
                     
                      echo '</td>';

                      echo '<td>'.$orderids.'</td>';
  
                      echo '</tr>';

                    }

                 
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
         var productmodel=[];
         var productname=[];
         var sku=[];var productlocation=[];var totalquntity=[]; var orderidss=[];
         

         $(\'#orderits tbody tr td:nth-child(1)\').each(function () {
            productids.push($(this).text());
         })

         $(\'#orderits tbody tr td:nth-child(2)\').each(function () {
          productmodel.push($(this).text());
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


         var arraytotalreport=[[\'Product ID\',\'model\',\'Product Name\',\'Sku\',\'total quantity\',\'Order ids\']];
         for(let i=0; i<'.sizeof($productidarray).';i++){
           var arraypush=[productids[i],productmodel[i],productname[i],sku[i],totalquntity[i],orderidss[i]];
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

    }
}
