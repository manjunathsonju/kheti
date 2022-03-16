
<?php
class ControllerApiCheckoutb2b extends Controller
{
    public function index()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $products=$data[products];
        // $errormessage='';

        // foreach($products as $product){
        //   $this->load->model('catalog/product');
        //   $product_info = $this->model_catalog_product->getProduct($product[product_id]);
        //   if($product_info['quantity']=='0'||$product_info['stock_status']=="Out Of Stock"){
        //       $stock_status_flag=1;
        //       $errormessage=$errormessage.' '.$product_info['name'].',';
        //   }
        // }
        // $errormessage=$errormessage.'not in stock, please remove';

        if(sizeof($products)<1){
            $json['error']='Cart Empty';

        }
        // elseif($stock_status_flag){
        //   $json['error']=$errormessage;
        // }
        else{

             // order information
            $sqlg="SELECT * FROM `oc_customer` WHERE customer_id='".$data[customer_id]."'";
       $customer_grp_id = $this->db->query($sqlg);
       $customer_group_id=$customer_grp_id->row['customer_group_id'];

         if(isset($data[customer_id]) && isset($data[total]) && isset($data[date_added]) &&($customer_group_id=='3') ){
            //find the order id for this order

          $sql ="SELECT MAX(order_id) FROM order_b2b";
          $querys = $this->db->query($sql);
          $order_id_for_this_order=$querys->row['MAX(order_id)']+1;
            
          // if($data[comment]){
          //     $comment=$data[comment];
          // }else{
          //     $comment='null';
          // }
          // if($data[date_delivery]){
          //   $data[date_added]=date('y-m-d h:i:s');
          // }
  
  
          $sql1 ="INSERT INTO `order_b2b` set order_id ='".$order_id_for_this_order."', customer_id='". $data[customer_id]."', total='".$data[total]."', order_status_id=".'1'.", payment_status=".'0'.", date_added='".$data[date_added]."', date_delivery='".$data[date_delivery]."'";
          if($data[delivery_address]){
            $sql1 =$sql1 .", delivery_address='".$data[delivery_address]."'";
          }
          if($data[comment]){
            $sql1 =$sql1 .", comment='".$data[comment]."'";
          }
          $querys = $this->db->query($sql1);
          
          //order product information
     
          foreach($products as $product){
              $querypro = $this->db->query("INSERT INTO `order_products_b2b` set order_id ='".$order_id_for_this_order."', product_id='". $product[product_id]."', quantity='".$product[quantity]."', price='".$product[price]."', total=".$product[total].", customer_id='".$data[customer_id]."'");
              $this->db->query("UPDATE `oc_product` set	quantity=quantity-1 WHERE product_id='".$product[product_id]."'");
            }
           if(($data[coupon_validate]==TRUE)||($data[coupon_validate]=='TRUE')||($data[coupon_validate]=='true')){
            $cpn=$this->db->query("SELECT * FROM b2b_coupon WHERE coupon_id='".$data[coupon_id]."'");

             if($cpn->row[type]=="p"){
          $discount=round((($data[total])*(int)($cpn->row[discount]))/100);

        }elseif($cpn->row[type]=="f"){
          $discount=$cpn->row[discount];

        }
        $dt=strtotime( now);
        $dtm=(date("Y-m-d", $dt));

            // $cpntableinsert = $this->db->query("INSERT INTO `b2b_coupon_history` set order_id ='".$order_id_for_this_order."', coupon_id='". $data[coupon_id]."', coupon_name='".$cpn->row[coupon_name]."', discount_amount='".$discount."', date_added=".$dtm."'");
            $sql="INSERT INTO b2b_coupon_history (order_id,coupon_id,coupon_name,discount_amount,date_added) VALUES ('".$order_id_for_this_order."','".$data[coupon_id]."','".$cpn->row[name]."','".$discount."','".$dtm."')";

 $cpntableinsert = $this->db->query($sql);

 $totalUse=(int)($cpn->row[uses_total]);
 $newUse=$totalUse-1;


$this->db->query("UPDATE b2b_coupon SET uses_total='".$newUse."' WHERE coupon_id='".$cpn->row[coupon_id]."'");
           }
    
          $json['success'] = 'order placed';
          //viber notify
          $messageString="";
          $messageString=$messageString."Kheti B2b". "\n"."\nOrder Id: ".$order_id_for_this_order."\nDate: ".date("Y/m/d")."\nComment: ".$data[comment];
          foreach($products as $product){
            $productname = $this->db->query("SELECT p.sku,p.model,pd.name FROM oc_product p JOIN oc_product_description pd ON (p.product_id=pd.product_id) WHERE p.product_id='".$product[product_id]."'");
            
            $messageString=$messageString ."\n \nProduct id: ".$product[product_id]."\n"."Name: ".$productname->row['name']."\n"."Model: ".$productname->row['model']."\n"."Sku: ".$productname->row['sku']."\n"."Quantity: ".$product[quantity]."\n" ."Price: ".$product[price] . "\n"."Total: ". $product[total];

          }
          //customer info
          $ordersinformation= $this->orderinfo($order_id_for_this_order);
                    
        $messageString=$messageString."\n\n". "Customer "."\n"."Customer_id: " .$data[customer_id]."\n"."Name: ".$ordersinformation[name]."\n"."Email: ".$ordersinformation[email] ."\n"."Telephone: ".$ordersinformation[telephone] ."\n"."Total: ".$data[total];
            $url = 'https://kheti.pythonanywhere.com/khetib2bnotification';
            $ch = curl_init($url);
            $payload = ( $messageString);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            //var_dump($result);
            curl_close($ch);

      }else{
          $json['error']='customer id , date or total missing';
      }
        }
$this->response->addHeader('Content-Type: application/json');
$this->response->addHeader('Access-Control-Allow-Origin: *');
$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
$this->response->addHeader('Access-Control-Max-Age: 600');
$this->response->setOutput(json_encode($json));
       
    }
    public function orderinfo($order_id) {
        $orderarr=$this->db->query("SELECT * FROM order_b2b WHERE order_id='".$order_id."'");
        foreach ($orderarr->rows as $result){
          $customername = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) As name,email,telephone from oc_customer where customer_id='".$result['customer_id']."'");
          $orderarray=array(
            'order_id'=>$result['order_id'],
            'customer_id'=>$result['customer_id'],
            'name'=>$customername->row['name'],
            'comment'=>$result['comment'],
            'total'=>$result['total'],
            'order_status_id'=>$result['order_status_id'],
            'payment_status'=>$result['payment_status'],
            'payment_method'=>$result['payment_method'],
            'date_added'=>$result['date_added'],
            'email'=>$customername->row['email'],
            'telephone'=>$customername->row['telephone']
          );
      }
      return($orderarray);
  
     }
     

}

