<?php
Class ControllerApiCustomerTotal extends Controller{
    public function index(){
        $customeridarr=$this->db->query("SELECT DISTINCT customer_id from oc_order where store_id=1 AND order_status_id !="."7"." AND order_status_id !="."0"." AND order_status_id !="."5"." AND order_status_id !="."8");
        foreach ($customeridarr->rows as $result){
            $customeridarray[]=array(
                'customerid'=>$result['customer_id'],
            );
        }
//        var_dump($customeridarray);
        foreach($customeridarray as $customerids){
            echo '</br>'.'</br>'.'Customer id: '.$customerids["customerid"].'</br>';
            $customername= $this->db->query("SELECT firstname , lastname ,email , telephone FROM oc_customer WHERE customer_id =".$customerids["customerid"]);
            echo "Name: ".$customername->row['firstname']. " ".$customername->row['lastname']."</br>";
            echo "Email: ".$customername->row['email']."</br>";
                        echo "Telephone: ".$customername->row['telephone']."</br>";

            $ordermoney= $this->db->query("SELECT total , order_id from oc_order WHERE customer_id= ". $customerids["customerid"]." AND store_id=1 AND order_status_id !="."7"." AND order_status_id !="."0"." AND order_status_id !="."5"." AND order_status_id !="."8");
            $total1= 0;
            echo 'Order ids of this customer:' ;
            foreach ($ordermoney->rows as $result1){
                echo $result1['order_id']."\t" ;
                $total1= $total1 + $result1['total'];
            }
            echo '</br>'.'Total money spent ='. $total1;
            unset($total1);
        }

    }
}

