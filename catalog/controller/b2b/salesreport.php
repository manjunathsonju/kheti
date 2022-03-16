<?php
class ControllerB2bSalesreport extends Controller {
	public function index() {

                    // var_dump("asdf");

        if($this->load->controller('b2b/home/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
           
            $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data['fname']=$userdetails->row['firstname'];
            $data['lname']=$userdetails->row['lastname'];
            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];
            $data['header'] = $this->load->controller('b2b/header');
            $data['nav'] = $this->load->controller('b2b/nav',$data);
            $data['a']='';
            // var_dump($data);

            // SELECT * FROM `order_b2b` WHERE date_added>'2021-12-01 00:00:00' AND date_added<'2022-01-01 00:00:00' AND order_status_id=3 AND customer_id=5328



            $this->response->setOutput($this->load->view('b2b/salesreport',$data));

        }

    }

    public function filter() {

        if($this->load->controller('b2b/home/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
           
            $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data['fname']=$userdetails->row['firstname'];
            $data['lname']=$userdetails->row['lastname'];
            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];
            $data['header'] = $this->load->controller('b2b/header');
            $data['nav'] = $this->load->controller('b2b/nav',$data);
            $datefrom=$this->request->get['filter_date'].' 00:00:00';
            $dateto=$this->request->get['filter_date1'].' 00:00:00';

            $data['datefrom']=$this->request->get['filter_date'];
            $data['dateto']=$this->request->get['filter_date1'];
            $data['customer_id']=$this->request->get['filter_id'];

          

            if($this->request->get['filter_id']){
                $sql="SELECT order_id FROM `order_b2b` WHERE order_status_id IN (2,3) AND customer_id='".$this->request->get['filter_id']."'";
                if($this->request->get['filter_date']){
                    $sql=$sql." AND date_added>'".$datefrom."'";
                }
                if($this->request->get['filter_date1']){
                    $sql=$sql." AND date_added<'".$dateto."'";
                }
            

            $orders=$this->db->query($sql);
         
            $sql1="SELECT DISTINCT(product_id) FROM order_products_b2b WHERE order_id IN (".$sql.")  ORDER BY product_id ASC";
            $products=$this->db->query($sql1);

            $data['table']='<table id="example" style=" max-width: 100%;">
            <thead>
                <tr>
                  <th scope="col">Order Id</th>';

            foreach($products->rows as $result){
                $data['table']=$data['table'].'<th scope="col">'.$result['product_id'].'</th>';

            }

            $data['table']=$data['table'].'</tr>
            </thead>
            <tbody>';

            foreach($orders->rows as $result1){
                $data['table']=$data['table'].'<tr>
                <td>'.$result1['order_id'].'</td>';

                foreach($products->rows as $result){
                    $sql="SELECT quantity FROM order_products_b2b WHERE order_id='".$result1['order_id']."' AND product_id='".$result['product_id']."'";
                    $productsquantity=$this->db->query($sql);

                    $data['table']=$data['table'].'<td>'.$productsquantity->row['quantity'].'</td>';
                }
                $data['table']=$data['table'].'</tr>';


            }
            $data['table']=$data['table'].'</tbody></table>';
        }


                        // var_dump($data['table']);

            $jskvar='var orderids=[];';
            $jskdata='
            $(\'#example tbody tr td:nth-child(1)\').each(function () {
                orderids.push($(this).text());
            })';
            $i=2;
            $jsktitle='
            var arraytotalreport=[[\'order_id\'';

            $jskdataarray='
            for(let i=0; i<orderids.length;i++){
                var arraypush=[orderids[i]';
            foreach($products->rows as $result){
                $jskvar=$jskvar.'var p'.$result['product_id'].'=[];';
                $jskdata=$jskdata.'
                $(\'#example tbody tr td:nth-child('.$i.')\').each(function () {
                    p'.$result['product_id'].'.push($(this).text());
                })';
                $i=$i+1;
                $jsktitle=$jsktitle.',\''.$result['product_id'].'\'';

                $jskdataarray=$jskdataarray.',p'.$result['product_id'].'[i]';
            }

            $jsktitle=$jsktitle.']];';
            $jskdataarray=$jskdataarray.'];
            arraytotalreport.push(arraypush);

        }


      var wb = XLSX.utils.book_new();
     wb.Props = {
            Title: "report",
            Subject: "Dvexcellus",
            Author: "bhairab",
            CreatedDate: new Date(2021,12,19)
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
  
 saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), \'Product_sales_Report_b2b.xlsx\');';

            

             $data['jsk']=$jskvar.$jskdata.$jsktitle.$jskdataarray;

             $data['jskk']='var orderids=[];var productids=[];var names=[];var models=[];var quantitys=[];var prices=[];var totals=[];            

             $(\'#prosalesreport tbody tr td:nth-child(1)\').each(function () {
                 orderids.push($(this).text());
             })
 
             $(\'#prosalesreport tbody tr td:nth-child(2)\').each(function () {
                 //add item to array
                 productids.push($(this).text());
             })
 
              $(\'#prosalesreport tbody tr td:nth-child(3)\').each(function () {
                 //add item to array
                 names.push($(this).text());
             })
              $(\'#prosalesreport tbody tr td:nth-child(4)\'.each(function () {
                 models.push($(this).text());
             })
              $(\'#prosalesreport tbody tr td:nth-child(5)\').each(function () {
                 quantitys.push($(this).text());
             })
              $(\'#prosalesreport tbody tr td:nth-child(6)\').each(function () {
                 prices.push($(this).text());
             })
             $(\'#prosalesreport tbody tr td:nth-child(7)\').each(function () {
                 totals.push($(this).text());
             })
             
            
             var arraytotalreport=[[\'order_id\',\'product_id\',\'name\',\'model\',\'quantity\',\'price\',\'total\']];
             for(let i=0; i<orderids.length;i++){
               var arraypush=[orderids[i],productids[i],names[i],models[i],quantitys[i],prices[i],totals[i],];
                 arraytotalreport.push(arraypush);
 
             }
 
 
           var wb = XLSX.utils.book_new();
          wb.Props = {
                 Title: "report",
                 Subject: "Dvexcellus",
                 Author: "bhairab",
                 CreatedDate: new Date(2021,12,19)
         };
         
         wb.SheetNames.push("Test Sheet");
         var ws = XLSX.utils.aoa_to_sheet(arraytotalreport);
         wb.Sheets["Test Sheet"] = ws;
         var wbout = XLSX.write(wb, {bookType:"xlsx",  type: "binary"});
         function s2ab(s) {
   
                 var buf = new ArrayBuffer(s.length);
                 var view = new Uint8Array(buf);
                 for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                 return buf;
                 
         }
       
      saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), \'Product_sales_Report.xlsx\');
            
         });';



            $this->response->setOutput($this->load->view('b2b/salesreport',$data));

        }

    }
}
