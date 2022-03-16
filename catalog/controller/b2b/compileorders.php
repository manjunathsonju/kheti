<?php
class ControllerB2bCompileorders extends Controller {
	public function index() {
        if($this->load->controller('b2b/home/checklogin',array($this->request->get['id'],$this->request->get['tkn']))){
            $data1['id']=$this->request->get['id'];
            $data1['tkn']=$this->request->get['tkn'];
            $userdetails=$this->db->query("SELECT * FROM `b2b_users` WHERE user_id='".$this->request->get['id']."'");
            $data1['fname']=$userdetails->row['firstname'];
            $data1['lname']=$userdetails->row['lastname'];
  
            $data['id']=$this->request->get['id'];
            $data['tkn']=$this->request->get['tkn'];
            $data['header'] = $this->load->controller('b2b/header');
            $data['nav'] = $this->load->controller('b2b/nav',$data1);

            if($this->request->get['location']){
                if($this->request->get['location']==1){

            $productsarrayfromprocessin=$this->db->query("SELECT DISTINCT(op.product_id) FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) LEFT JOIN customer_locations c ON (c.customer_id=ob.customer_id) WHERE ob.order_status_id=2 AND (c.location IS NULL OR c.location=1)");
                }else{
             $productsarrayfromprocessin=$this->db->query("SELECT DISTINCT(op.product_id) FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) LEFT JOIN customer_locations c ON (c.customer_id=ob.customer_id) WHERE ob.order_status_id=2 AND c.location=2");

                }
            }else{
            $productsarrayfromprocessin=$this->db->query("SELECT DISTINCT(op.product_id) FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) WHERE ob.order_status_id=2");

            }

            // $data['products'] = $productsarrayfromprocessin;
            // var_dump($data['products']);


            foreach ($productsarrayfromprocessin->rows as $result){
                if($this->request->get['language_id']){
                    $productname = $this->db->query("SELECT p.sku,p.model,pd.name FROM oc_product p JOIN oc_product_description pd ON (p.product_id=pd.product_id) WHERE p.product_id='".$result['product_id']."' AND pd.language_id='".$this->request->get['language_id']."'");

                }else{
                    $productname = $this->db->query("SELECT p.sku,p.model,pd.name FROM oc_product p JOIN oc_product_description pd ON (p.product_id=pd.product_id) WHERE p.product_id='".$result['product_id']."'");

                }

                if($this->request->get['location']){
                    if($this->request->get['location']==1){
                        $productorderquantity=$this->db->query("SELECT op.order_id,op.quantity FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) LEFT JOIN customer_locations c ON (c.customer_id=ob.customer_id) WHERE ob.order_status_id=2 AND op.product_id='".$result[product_id]."' AND (c.location IS NULL OR c.location=1)");

                    }else{
                        $productorderquantity=$this->db->query("SELECT op.order_id,op.quantity FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) LEFT JOIN customer_locations c ON (c.customer_id=ob.customer_id) WHERE ob.order_status_id=2 AND op.product_id='".$result[product_id]."' AND c.location=2");

                    }

                }else{
                    $productorderquantity=$this->db->query("SELECT op.order_id,op.quantity FROM order_b2b ob JOIN order_products_b2b op ON (ob.order_id=op.order_id) WHERE ob.order_status_id=2 AND op.product_id='".$result[product_id]."'");

                }  
                $totalamount=0;
                foreach ($productorderquantity->rows as $result){
                 
                   $totalamount=$totalamount+(float)$result['quantity'];
                }

                $data['products'][]=array(
                    'product_id'=> $result['product_id'],
                    'name'=> $productname->row['name'],
                    'model'=> $productname->row['model'],
                    'sku'=> $productname->row['sku'],
                    'total_quantity'=> $totalamount                    
                );
            }

            // var_dump($data['products']);
            $this->response->setOutput($this->load->view('b2b/compileorders', $data));

        }
    }
}
