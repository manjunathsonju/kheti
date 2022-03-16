<?php
class ControllerApiB2bOmsCheckout extends Controller {
	public function index(){
        $data = json_decode(file_get_contents('php://input'), true);
        if($data[key]=='ch3Ck0nTb2B0ws'){
            $products=$data[products];
            //select from table where table_id=id,,if status=0 then ignore all code above and throw error
            $sql ="SELECT * FROM b2b_oms_tables WHERE table_id='".$data[table_id]."'"; 
            $tablestatus=$this->db->query($sql);
            if($tablestatus->row['status']==0){
                $json['error']='Inactive table';

            }else{
                if($this->checkOccupied($data[table_id],$data[customer_id])){//occupied
                    //get order id
                    $sql1="SELECT order_id,total FROM b2b_oms_orders WHERE table_id='".$data[table_id]."' AND complete='0' AND customer_id='".$data[customer_id]."'";
                  
                    $orderid=$this->db->query($sql1);
                    $order_id=$orderid->row['order_id'];
                    $totaltotal=$orderid->row['total'];
    
                    // add products to table
                    foreach($products as $product){
                        //check if the product is already added
                        $sql="SELECT * FROM `b2b_oms_order_products` WHERE product_id='".$product[product_id]."' AND order_id='".$order_id."'";
                        $product_exits = $this->db->query($sql);
                        $product_quantity=$product_exits->row['quantity'];
                        $product_total=$product_exits->row['total'];
                        $product_order_id=$product_exits->row['order_id'];
                        $new_quantity=(int)$product_quantity+(int)$product[quantity];
    
                        $new_total=(int)$product_total+(int)$product[total];
    
                        if($product_exits->num_rows!=0){
                            // var_dump('already');
                            $sql="UPDATE `b2b_oms_order_products` SET quantity='".$new_quantity."' , total='".$new_total."' WHERE product_id='".$product[product_id]."' AND order_id='".$order_id."'";
    
                            $querypro = $this->db->query($sql);
                            // var_dump($sql);
    
                            $totaltotal=$totaltotal+$product[total];
                        }else{
                            $sql="INSERT INTO `b2b_oms_order_products` (product_id,quantity,price,total,order_id) values ('".$product[product_id]."','".$product[quantity]."','".$product[price]."','".$product[total]."','".$order_id."')";
                            $querypro = $this->db->query($sql);
                            $totaltotal=$totaltotal+$product[total];
                        }
                    
                    }
                    $sql2="UPDATE b2b_oms_orders SET total='".$totaltotal."' where order_id='".$order_id."'";
                    $this->db->query($sql2);
                    $json['status']="Added to your existing order";
                }else{
                    // no occupied
                    date_default_timezone_set('Asia/Kathmandu');
                    $dt=strtotime(now);
                    $dtm=(date("Y-m-d H:i:s", $dt)); //date time ,current,ktm,ntpa
                    $sql="INSERT INTO b2b_oms_orders (customer_id,table_id,time_start,time_finish) VALUES ('".$data[customer_id]."','".$data[table_id]."','".$dtm."','".$dtm."')";
                    $this->db->query($sql);
                    // find order id
                    $sql1="SELECT order_id FROM b2b_oms_orders WHERE table_id='".$data[table_id]."' AND complete='0' AND customer_id='".$data[customer_id]."'";
                    $orderid=$this->db->query($sql1);
                    $order_id=$orderid->row['order_id'];
                    // add products to table
                    $totaltotal=0;
                    foreach($products as $product){
                      $sql="INSERT INTO `b2b_oms_order_products` (product_id,quantity,price,total,order_id) values ('".$product[product_id]."','".$product[quantity]."','".$product[price]."','".$product[total]."','".$order_id."')";
                      $querypro = $this->db->query($sql);
                      $totaltotal=$totaltotal+$product[total];
                      }
                      $sql2="UPDATE b2b_oms_orders SET total='".$totaltotal."' where order_id='".$order_id."'";
                      $this->db->query($sql2);
                    $json['success']=1;
                }

            }

            

        }else{
            $json['error']='Invalid Api Key';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type,Accept'); 
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));

    } 
    public function checkOccupied($table_id,$customer_id){
            // $ordersinformation= $this->orderinfo($this->request->get['order_id']);
            $sql="SELECT * FROM b2b_oms_orders WHERE table_id='".$table_id."' AND complete=0 AND customer_id='".$customer_id."'";
            $orderarr=$this->db->query($sql);
            // select from table where table_id=id 
            
            if($orderarr->num_rows!='0'){
                return true;
            }else{
                return false;
            }
    }
    public function finishOrder(){
        if($this->request->post['key']=='f!n!sHoDrb2B0ws'){
            $sql="SELECT * FROM b2b_oms_orders WHERE table_id='".$this->request->post['table_id']."' AND complete='0' AND customer_id='".$this->request->post['customer_id']."'";
            $runorder=$this->db->query($sql);

            if($runorder->num_rows!=0){
            date_default_timezone_set('Asia/Kathmandu');
            $dt=strtotime(now);
            $dtm=(date("Y-m-d H:i:s", $dt)); //update time of finish
            $sql="UPDATE `b2b_oms_orders` SET time_finish='".$dtm."' WHERE table_id='".$this->request->post['table_id']."' AND complete='0' AND customer_id='".$this->request->post['customer_id']."'";
            $this->db->query($sql);
            //for invoice
            $sql="SELECT * FROM b2b_oms_orders WHERE table_id='".$this->request->post['table_id']."' AND complete='0' AND customer_id='".$this->request->post['customer_id']."'";
            $thisorderinfo=$this->db->query($sql);
            $sql="SELECT * FROM `oc_customer` WHERE customer_id='".$thisorderinfo->row['customer_id']."'";
            $thisCustomerInfo=$this->db->query($sql);
           $json['customer_name'] = $thisCustomerInfo->row['firstname'].' '.$thisCustomerInfo->row['lastname'];
                               $json['order_info'] = array(
                                      'order_id' => $thisorderinfo->row['order_id'],
                                      'total' => $thisorderinfo->row['total'],
                                      'time_start' => $thisorderinfo->row['time_start'],
                                      'time_finish' => $thisorderinfo->row['time_finish']
                                      
                              );
                              $sql="SELECT * FROM `b2b_oms_order_products` WHERE order_id='".$thisorderinfo->row['order_id']."'";
                              $runorderproducts=$this->db->query($sql);
                              foreach ($runorderproducts->rows as $result){
                  
                              $sql="SELECT * FROM `b2b_oms_products` WHERE product_id='".$result['product_id']."'";
                              $nameandimage=$this->db->query($sql);
                              $runorderproductsarray[]=array(
                                  'order_product_id'=>$result['order_product_id'],
                                  'product_id'=>$result['product_id'],
                                  'quantity'=>$result['quantity'],
                                  'price'=>round($result['price'],2),
                                  'total'=>$result['total'],
                                  'name'=>$nameandimage->row['product_name'],
                                  'image'=>$nameandimage->row['image']
                              );
                          
                              }
                              $json['products']=$runorderproductsarray;  
                              //for invoice end


            // set to complete
            $sql="UPDATE `b2b_oms_orders` SET complete=1 WHERE table_id='".$this->request->post['table_id']."' AND complete='0' AND customer_id='".$this->request->post['customer_id']."'";
            $this->db->query($sql);

            // set table_id to 1 , check on merge_table , get merge_table_id , delete row where table_id=table_id , set merge_table_id to 1
            // DELETE FROM `b2b_oms_table_merge` WHERE table_id=1
            
            $sql ="SELECT * FROM `b2b_oms_table_merge` WHERE table_id='".$this->request->post[table_id]."'";
            $mergetables=$this->db->query($sql);
            if($mergetables->num_rows!='0'){
                $sql ="UPDATE b2b_oms_tables SET status=1 WHERE table_id='".$this->request->post[table_id]."'";
            $this->db->query($sql);

            $sql ="UPDATE b2b_oms_tables SET status=1 WHERE table_id='".$mergetables->row['merge_table_id']."'";
            $this->db->query($sql);
            $sql ="DELETE FROM `b2b_oms_table_merge` WHERE table_id='".$this->request->post[table_id]."'";
            $this->db->query($sql);

            }
          
            $json['success']='Done';

            }else{
                $json['error']='This order cannot be modified';


            }


            
        }else{
            $json['error']='Invalid Api Key';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type,Accept'); 
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));

    }

    public function cancelOrder(){
        if($this->request->post['key']=='c@nc3loDrb2B0ws'){
            // var_dump('yo');
            $sql="SELECT order_id FROM b2b_oms_orders WHERE table_id='".$this->request->post['table_id']."' AND complete='0' AND customer_id='".$this->request->post['customer_id']."'";
            $runorder=$this->db->query($sql);

            if($runorder->num_rows!=0){
                $sql="DELETE FROM `b2b_oms_order_products` WHERE order_id='".$runorder->row['order_id']."'";
                $this->db->query($sql);
                
                $sql="DELETE FROM `b2b_oms_orders` WHERE order_id='".$runorder->row['order_id']."'";
                $this->db->query($sql);

                $json['success']='done';  

            }else{
                $json['error']='You cannot modify this order';

            }

        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type,Accept'); 
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }
}