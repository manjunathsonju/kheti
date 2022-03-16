<?php

include('phpexcel/Classes/PHPExcel/IOFactory.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer as Writer;

class ControllerApiExport extends Controller{
    public function index(){

        $items=$_REQUEST['items'];
        $items1=implode(',',$items);
        $order_result = $this->db->query("SELECT o.order_id,o.invoice_no,o.invoice_prefix,o.store_id,o.firstname,o.lastname,o.commission,o.email,o.telephone,o.payment_firstname,o.payment_lastname,o.payment_company,o.payment_address_1,o.payment_address_2,o.payment_city,o.payment_postcode,o.payment_country,o.payment_country_id,o.payment_zone,o.payment_zone_id,o.payment_address_format,o.payment_custom_field,o.payment_method,o.payment_code,o.shipping_firstname,o.shipping_lastname,o.shipping_company,o.shipping_address_1,o.shipping_address_2,o.shipping_city,o.shipping_postcode,o.shipping_country,o.shipping_country_id,o.shipping_zone,o.shipping_zone_id,o.shipping_address_format,o.shipping_custom_field,o.shipping_method,o.shipping_code,o.comment,o.total,o.order_status_id,o.language_id,o.currency_id,o.currency_code,o.currency_value,o.date_added, op.product_id,op.name,op.quantity,op.price,op.total,op.tax,op.reward, pd.price as discount FROM " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id=op.order_id) LEFT JOIN " .DB_PREFIX. "product_discount pd ON(pd.product_id=op.product_id) WHERE o.order_id IN (" . $items1. ") ");
       foreach($order_result->rows as $result){
           $orders[]=array(
                'order_id'                => $result['order_id'],
                'invoice_no'              => $result['invoice_no'],
             'invoice_prefix'             => $result['invoice_prefix'],
                'store_id'                => $result['store_id'],
               'firstname' =>$result['firstname'],
               'lastname'=>$result['lastname'],
           'email'=>$result['email'],
            'telephone'=>$result['telephone'],
           'payment_firstname'=>$result['payment_firstname'],
           'payment_lastname'=>$result['payment_lastname'],
           'payment_company'=>$result['payment_company'],
           'payment_address_1'=>$result['payment_address_1'],
           'payment_address_2'=>$result['payment_address_2'],
           'payment_city'=>$result['payment_city'],
           'payment_postcode'=>$result['payment_postcode'],
           'payment_country'=>$result['payment_country'],
           'payment_country_id'=>$result['payment_country_id'],
           'payment_zone'=>$result['payment_zone'],
           'payment_method'=>$result['payment_method'],
           'shipping_firstname'=>$result['shipping_firstname'],
           'shipping_lastname'=>$result['shipping_lastname'],
           'shipping_company'=>$result['shipping_company'],
           'shipping_address_1'=>$result['shipping_address_1'],
           'shipping_city'=>$result['shipping_city'],
           'shipping_postcode'=>$result['shipping_postcode'],
           'shipping_country'=>$result['shipping_country'],
           'shipping_zone'=>$result['shipping_zone'],
           'shipping_method'=>$result['shipping_method'],
           'comment'=>$result['comment'],
           'commission'=>$result['commission'],
           'currency_id'=>$result['currency_id'],
           'currency_code'=>$result['currency_code'],
           'date_added'=>$result['date_added'],
                'name'=>$result['name'],
               'price'=>$result['price'],
               'total'=>$result['total'],
               'quantity'=>$result['quantity'],
               'tax'=>$result['tax'],
               'reward'=>$result['reward'],
               'product_id'=>$result['product_id'],
               'discount'=>$result['discount']
           );
        }
       $this->generate($orders);

    }
    public function generate( $orders){
        //var_dump($orders);
        require 'vendor/autoload.php';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'orders id');
        $sheet->setCellValue('B1', 'invoice_no');
        $sheet->setCellValue('C1', 'invoice_prefix');
        $sheet->setCellValue('D1', 'store_id');
        $sheet->setCellValue('E1', 'firstname');
        $sheet->setCellValue('F1', 'Lastname');
        $sheet->setCellValue('G1', 'email');
        $sheet->setCellValue('H1', 'telephone');
        $sheet->setCellValue('I1', 'payment_firstname');
        $sheet->setCellValue('J1', 'payment_lastname');
        $sheet->setCellValue('K1', 'payment_company');
        $sheet->setCellValue('L1', 'payment_address_1');
        $sheet->setCellValue('M1', 'payment_address_2');
        $sheet->setCellValue('N1', 'payment_city');
        $sheet->setCellValue('O1', 'payment_postcode');
        $sheet->setCellValue('P1', 'shipping_country');
        $sheet->setCellValue('Q1', 'shipping_zone');
        $sheet->setCellValue('R1', 'shipping_method');
        $sheet->setCellValue('S1', 'comment');
        //$sheet->setCellValue('T1', 'total');
        $sheet->setCellValue('T1', 'commission');
        $sheet->setCellValue('U1', 'currency_id');
        $sheet->setCellValue('V1', 'currency_code');
        $sheet->setCellValue('W1', 'date_added');
        $sheet->setCellValue('X1', 'Products');
        $sheet->setCellValue('Y1', 'price');
        $sheet->setCellValue('Z1', 'quantity');
        $sheet->setCellValue('AA1', 'total');
        $sheet->setCellValue('AB1', 'reward');
        $sheet->setCellValue('AC1', 'discount');

        for($i=2;$i<= (sizeof($orders)+1);$i++){
            $productString=Null;
            $order_products=(array) null;
            $order_p=(array) null;

            $a="A". (int)$i;
            $b="B".(int) $i;
            $c="C".(int)$i;
            $d="D".(int)$i;
            $e="E".(int)$i;
            $f="F".(int)$i;
            $g="G".(int)$i;
            $h="H".(int)$i;
            $ii="I".(int)$i;
            $jj="J".(int)$i;
            $k="K".(int)$i;
            $l="L".(int)$i;
            $m="M".(int)$i;
            $n="N".(int)$i;
            $o="O".(int)$i;
            $p="P".(int)$i;
            $q="Q".(int)$i;
            $r="R".(int)$i;
            $s="S".(int)$i;
            $t="T".(int)$i;
            $u="U".(int)$i;
            $v="V".(int)$i;
            $w="W".(int)$i;
            $x="X".(int)$i;
            $y="Y".(int)$i;
            $zz="Z".(int)$i;
            $aa="AA".(int)$i;
            $ab="AB".(int)$i;
            $ac="AC".(int)$i;


            $j=$i-2;

             $sheet->setCellValue($a , $orders[$j]["order_id"]);
            $sheet->setCellValue($b, $orders[$j]["invoice_no"]);
            $sheet->setCellValue($c, $orders[$j]['invoice_prefix']);
            $sheet->setCellValue($d, $orders[$j]['store_id']);
            $sheet->setCellValue($e, $orders[$j]["firstname"]);
            $sheet->setCellValue($f, $orders[$j]["lastname"]);
            $sheet->setCellValue($g, $orders[$j]['email']);
            $sheet->setCellValue($h, $orders[$j]['telephone']);
            $sheet->setCellValue($ii, $orders[$j]['payment_firstname']);
            $sheet->setCellValue($jj, $orders[$j]['payment_lastname']);
            $sheet->setCellValue($k, $orders[$j]['payment_company']);
            $sheet->setCellValue($l, $orders[$j]['payment_address_1']);
            $sheet->setCellValue($m, $orders[$j]['payment_address_2']);
            $sheet->setCellValue($n, $orders[$j]['payment_city']);
            $sheet->setCellValue($o, $orders[$j]['payment_postcode']);
            $sheet->setCellValue($p, $orders[$j]['shipping_country']);
            $sheet->setCellValue($q, $orders[$j]['shipping_zone']);
            $sheet->setCellValue($r, $orders[$j]['shipping_method']);
            $sheet->setCellValue($s, $orders[$j]['comment']);
            //$sheet->setCellValue($t, $orders[$j]['total']);
            $sheet->setCellValue($t, $orders[$j]['commission']);
            $sheet->setCellValue($u, $orders[$j]['currency_id']);
            $sheet->setCellValue($v, $orders[$j]['currency_code']);
            $sheet->setCellValue($w, $orders[$j]['date_added']);
            $sheet->setCellValue($x, $orders[$j]['name']);
            $sheet->setCellValue($y, $orders[$j]['price']);
            $sheet->setCellValue($zz, $orders[$j]['quantity']);
            $sheet->setCellValue($aa, $orders[$j]['total']);
            $sheet->setCellValue($ab, $orders[$j]['reward']);
            $sheet->setCellValue($ac, $orders[$j]['discount']);
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('../hello world.xlsx');
       // $file_url = '../hello world.xlsx';
//        header('Content-Type: application/octet-stream');
//        header("Content-Transfer-Encoding: Binary");
//        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
//        readfile($file_url);

//        header('Location: /path/to/hello.xlsx');
//        header('Content-disposition: attachment; filename=path/to/hello.xlsx');
//        $path= 'sftp://root@157.230.249.112/var/www/hello%20world.xlsx';
//        $data= file_get_contents($path);
//        $fp = fopen($path,'wb');
//        if($fp === FALSE) echo  "error";
//        $result = fwrite($fp,$data);
//        if($result ===false) echo "roor write";
//        $result= fclose($fp);

    }
    }
