<?php
class ControllerApiB2bOmsOrderHistory extends Controller {
	public function index(){
        if($this->request->post['key']=='b2Bg3t@Ll0rd3r0ws'){
            if(isset($this->request->post['format'])&&($this->request->post['format']=='d')){
                $datetime=$this->request->post['date']." 00:00:00";
                // var_dump($datetime);

                $nextday= strtotime("+1 day", strtotime($datetime));
                $nextdayfinal=date("Y-m-d", $nextday);
                $datetimenexttime=$nextdayfinal." 00:00:00";
                $sql="SELECT * FROM b2b_oms_orders WHERE customer_id='".$this->request->post['customer_id']."' AND time_start>'".$datetime."' AND time_start<'".$datetimenexttime."' AND complete=1";
                $sql1="SELECT oc.category_id,oc.image,oc.category_name,SUM(op.quantity) AS total_quantity,SUM(op.total) AS total_sum FROM `b2b_oms_order_products` op JOIN b2b_oms_orders oo ON (oo.order_id=op.order_id) JOIN b2b_oms_products p ON (p.product_id=op.product_id)JOIN b2b_oms_category oc ON (oc.category_id=p.category_id) WHERE oc.customer_id='".$this->request->post['customer_id']."' AND oo.time_start>'".$datetime."' AND oo.time_start<'".$datetimenexttime."' AND oo.complete=1 GROUP BY oc.category_id";
                // SELECT * FROM `order_b2b` WHERE date_added>"2021-03-14 00:00:00" AND date_added<"2021-03-15 00:00:00" ORDER BY date_added
            }elseif(isset($this->request->post['format'])&&($this->request->post['format']=='m')){
                $today= strtotime("+1 day", strtotime($this->request->post['date']));
                $today1=date("Y-m-d", $today);
                $datetime=$today1." 00:00:00";

                $nextday= strtotime("-1 month", strtotime($this->request->post['date']));
                $nextdayfinal=date("Y-m-d", $nextday);
                $datetimenexttime=$nextdayfinal." 00:00:00";
                $sql="SELECT * FROM b2b_oms_orders WHERE customer_id='".$this->request->post['customer_id']."' AND time_start>'".$datetimenexttime."' AND time_start<'".$datetime."' AND complete=1";

                $sql1="SELECT oc.category_id,oc.image,oc.category_name,SUM(op.quantity) AS total_quantity,SUM(op.total) AS total_sum FROM `b2b_oms_order_products` op JOIN b2b_oms_orders oo ON (oo.order_id=op.order_id) JOIN b2b_oms_products p ON (p.product_id=op.product_id)JOIN b2b_oms_category oc ON (oc.category_id=p.category_id) WHERE oc.customer_id='".$this->request->post['customer_id']."' AND oo.time_start>'".$datetimenexttime."' AND oo.time_start<'".$datetime."' AND oo.complete=1 GROUP BY oc.category_id";

            }else{          
              $sql="SELECT * FROM b2b_oms_orders WHERE customer_id='".$this->request->post['customer_id']."' AND complete=1";
              $sql1="SELECT oc.category_id,oc.image,oc.category_name,SUM(op.quantity) AS total_quantity,SUM(op.total) AS total_sum FROM `b2b_oms_order_products` op JOIN b2b_oms_orders oo ON (oo.order_id=op.order_id) JOIN b2b_oms_products p ON (p.product_id=op.product_id)JOIN b2b_oms_category oc ON (oc.category_id=p.category_id) WHERE oc.customer_id='".$this->request->post['customer_id']."' AND oo.complete=1 GROUP BY oc.category_id";

            }
            $orderbycustomerid=$this->db->query($sql);
            $categorySummary=$this->db->query($sql1);
            // var_dump($categorySummary);
           


            if($orderbycustomerid->num_rows!=0){
                                // var_dump($orderbycustomerid);

                foreach ($orderbycustomerid->rows as $result){
                        $allordersarray[]=array(
                        'order_id'=>$result['order_id'],
                        'table_id'=>$result['table_id'],
                        'total'=>round($result['total'],2),
                        'time_start'=>$result['time_start'],
                        'time_finish'=>$result['time_finish'],
                        'status'=>($result['complete']==0)?"Running": "Complete"
                        
                    );
                
                    }
                        // cateogry summary
                    foreach ($categorySummary->rows as $result){
                        $category_summary[]=array(
                            'category_id'=>$result['category_id'],
                            'image'=>$result['image'],
                            'category_name'=>$result['category_name'],
                            'total_quantity'=>$result['total_quantity'],
                            'total_sum'=>$result['total_sum']                    
                        );
        
                    }

                    $json['orders']=$allordersarray;
                    $json['category_summary']=$category_summary;


            }else{
                $json['orders']=[];

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

    public function getRunningOrder(){
        if($this->request->post['key']=='b2Bg3tRun!g0rd3r0ws'){
            $sql="SELECT * FROM b2b_oms_orders WHERE table_id='".$this->request->post['table_id']."' AND complete=0";
            $runorder=$this->db->query($sql);

            if($runorder->num_rows!=0){
                $json['order_id']=$runorder->row['order_id'];
                $json['customer_id']=$runorder->row['customer_id'];
                $json['total']=$runorder->row['total'];
                $json['time_start']=$runorder->row['time_start'];
                $json['status']=($runorder->row['complete']==0)?"Running": "Complete";
                $sql="SELECT * FROM `b2b_oms_order_products` WHERE order_id='".$runorder->row['order_id']."'";
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

            }else{
                $json['error']='No Running order in this table';

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


    public function deleteProductFromOrder(){
        if($this->request->post['key']=='d3l3tpr0dctord3r5'){
            $sql="SELECT * FROM b2b_oms_orders WHERE order_id='".$this->request->post['order_id']."'";
            $running=$this->db->query($sql);
            $runningornot=$running->row['complete'];
            if($runningornot=='0'){

                //find if there is one or more produt
                $sql="SELECT count(*) as number FROM `b2b_oms_order_products` WHERE order_id='".$this->request->post['order_id']."'";
                $numberofpro=$this->db->query($sql);
                if($numberofpro->row['number']=='1'||$numberofpro->row['number']=='0'){
                    $sql="DELETE FROM `b2b_oms_order_products` WHERE order_product_id='".$this->request->post['order_product_id']."' AND order_id='".$this->request->post['order_id']."'";
                    $this->db->query($sql);
                    
                    $sql="DELETE FROM `b2b_oms_orders` WHERE order_id='".$this->request->post['order_id']."'";
                    $this->db->query($sql);

                }else{
                    // get total of the product
                $sql="SELECT total FROM `b2b_oms_order_products` WHERE order_product_id='".$this->request->post['order_product_id']."' AND order_id='".$this->request->post['order_id']."'";
                $thistotal=$this->db->query($sql);
                $product_total=(float)$thistotal->row['total'];

                $sql="DELETE FROM `b2b_oms_order_products` WHERE order_product_id='".$this->request->post['order_product_id']."' AND order_id='".$this->request->post['order_id']."'";
                $this->db->query($sql);
               
                $sql="SELECT total FROM `b2b_oms_orders` WHERE order_id='".$this->request->post['order_id']."'";
                $thisordertotal=$this->db->query($sql);
                $order_total=(float)$thisordertotal->row['total'];


                $sql="UPDATE `b2b_oms_orders` SET total='".($order_total-$product_total)."' WHERE order_id='".$this->request->post['order_id']."'";
                $this->db->query($sql);
                }
                $json['success']='done';  


            }elseif($runningornot!='0'||!($runningornot)){
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
    public function modifyProductFromOrder(){
        if($this->request->post['key']=='m0d!5ypr0dctord3r5'){
            $sql="SELECT complete , total FROM b2b_oms_orders WHERE order_id='".$this->request->post['order_id']."'";
            $runornot=$this->db->query($sql);
            $run=$runornot->row['complete'];
            $total=(float)$runornot->row['total'];

            if($run=='0'){
                $sql="SELECT * FROM `b2b_oms_order_products` WHERE order_product_id='".$this->request->post['order_product_id']."' AND order_id='".$this->request->post['order_id']."'";
                $thispro=$this->db->query($sql);
                $thisprice=(float)$thispro->row['price'];
                $thistotals=(float)$thispro->row['total'];


                // echo($thispro);
                $newtotal=(float)$thisprice*(float)$this->request->post['quantity'];
                $sql="UPDATE `b2b_oms_order_products` SET quantity='".$this->request->post['quantity']."', total='".$newtotal."' WHERE order_product_id='".$this->request->post['order_product_id']."' AND order_id='".$this->request->post['order_id']."'";
                $this->db->query($sql);
                //update in orders total
                $sql="UPDATE `b2b_oms_orders` SET total='".(($total-$thistotals)+$newtotal)."' WHERE order_id='".$this->request->post['order_id']."'";
                $this->db->query($sql);
                $json['success']='Success';

                // $json['product']=array(
                //     'order_product_id'=>$thispro->row['price'],
                //     'product_id'=>$thispro->row['product_id'],
                //     'quantity'=>$this->request->post['quantity'],
                //     'price'=>round($thispro->row['price'],2),
                //     'total'=>$newtotal,
                //     'order_id'=>$thispro->row['order_id']
                   
                // );
                // $json['total']=$newtotal;
                
            }else{
                $json['error']='You cannot modify this order';

            }

            // orderid,orderproductid , quantity
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type,Accept'); 
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));   
    }
    public function getproductsforchef(){
        if($this->request->post['key']=='93tpr0dctord3r5'){
            $sql="SELECT * FROM `b2b_oms_orders` WHERE complete=0 AND customer_id='".$this->request->post['customer_id']."'";
            $orderids=$this->db->query($sql);
                        // var_dump($orderids->num_rows);

            
            foreach ($orderids->rows as $result1){
                $sql="SELECT * FROM `b2b_oms_order_products` WHERE order_id='".$result1['order_id']."'";
                $pros=$this->db->query($sql);

                foreach ($pros->rows as $result){
                    $sql="SELECT * FROM `b2b_oms_products` WHERE product_id='".$result['product_id']."'";
                    $nameandimage=$this->db->query($sql);
                    $prods[]=array(
                        'order_product_id'=>$result['order_product_id'],
                        'product_id'=>$result['product_id'],
                        'quantity'=>$result['quantity'],
                        'price'=>round($result['price'],2),
                        'total'=>$result['total'],
                        'name'=>$nameandimage->row['product_name'],
                        'image'=>$nameandimage->row['image'],
                        'chef_check'=>$result['chef_check']
                    );
            }
            $sql ="SELECT * FROM `b2b_oms_tables` WHERE table_id='".$result1['table_id']."'"; //find table details
            $tablename=$this->db->query($sql);
            $table[]=array(
                'order_id'=>$result1['order_id'],
                'table_id'=>$result1['table_id'],
                'table_name'=>$tablename->row['table_name'],
                'products'=>$prods
            );
            unset($prods);
                                        // var_dump($prods);



        }
        $json['tables']=$table;

        
    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type,Accept'); 
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));  
    }

    public function chefcheck(){
        if($this->request->post['key']=='chefcheck'){
            $sql="UPDATE `b2b_oms_order_products` SET chef_check=1 WHERE order_product_id='".$this->request->post['order_product_id']."'";
            $this->db->query($sql);
            $json['success']='done';  


        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type,Accept'); 
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));  
    }
    public function chefuncheck(){
        if($this->request->post['key']=='chefcheck'){
            $sql="UPDATE `b2b_oms_order_products` SET chef_check=0 WHERE order_product_id='".$this->request->post['order_product_id']."'";
            $this->db->query($sql);
            $json['success']='done';  


        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type,Accept'); 
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));  
    }

}