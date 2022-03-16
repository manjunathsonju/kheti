<?php
class ControllerApiAccounting extends Controller
{
	public function index()
    {
        $this->load->model('account/order');
        $this->load->model('catalog/product');
        $this->load->model('tool/upload');
        $products = $this->model_account_order->getOrderProducts($this->request->post['order_id']);

        foreach ($products as $product) {
            $option_data = array();

            $options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

            foreach ($options as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            $product_info = $this->model_catalog_product->getProduct2($product['product_id']);	
            $sql="SELECT * FROM `product_to_code` WHERE product_id='".$product['product_id']."'";
            $code=$this->db->query($sql);	
            
            if($product_info['sku']=='KG'||$product_info['sku']=='kg'||$product_info['sku']=='Kg'){
                $quantity1=$product['quantity'];
                $sku='KG';
            }
            elseif($product_info['sku']=='mutha'||$product_info['sku']=='Mutha'){
                $quantity1=$product['quantity'];
                $sku='Mutha';
            }
            elseif($product_info['sku']=='500 gm'||$product_info['sku']=='0.5 kg'||$product_info['sku']=='500 gram'){
                $quantity1=(float)$product['quantity']/2;
                $sku='KG';
            }
            elseif($product_info['sku']=='200 gram'||$product_info['sku']=='200 gm'||$product_info['sku']=='200 Gram'){
                $quantity1=(float)$product['quantity']/5;
                $sku='KG';
            }
            elseif($product_info['sku']=='piece'||$product_info['sku']=='Piece'||$product_info['sku']=='pc'){
                $quantity1=$product['quantity'];
                $sku='Piece';
            }
            elseif($product_info['sku']=='dozen'||$product_info['sku']=='Dozen'){
                $quantity1=$product['quantity'];
                $sku='Dozen';
            }
            elseif($product_info['sku']=='Half dozen'){
                $quantity1=(float)$product['quantity']/2;
                $sku='Dozen';

            }
            elseif($product_info['sku']=='300 gm'){
                $quantity1=((float)$product['quantity']*3)/10;
                $sku='KG';

            }

            elseif($product_info['sku']=='250 gm'||$product_info['sku']=='250 gram'||$product_info['sku']=='250 Gram'){
                $quantity1=(float)$product['quantity']/4;
                $sku='KG';

            }

            elseif($product_info['sku']=='2.5 kg'){
                $quantity1=(float)$product['quantity']*2.5;
                $sku='KG';

            }

            else{
                $quantity1=$product['quantity'];
                $sku=$product_info['sku'];
            }

            $data['products'][] = array(
                'product_id'     => $product['product_id'], //op
                'product_code'   => $code->row['code'],
                'product_name_product'   => $product['name'],
                'product_name_code'   =>  $code->row['name'],

                'original_sku'   => $product_info['sku'],   //op
                'converted_sku'   => $sku,   //op

                'quantity'   => $product['quantity'],//op
                'quantity_in_kg'   => $quantity1,//op

                // 'unit'           => $product['quantity'],
                // 'buy_rate'       => NULL,
                // 'sales_rate'     => (float)$product['price'],
                'mrp' =>  (float)$product['total'],

            );
        }

        $json['order_id']=$this->request->post['order_id'];
        $json['products']=$data['products'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }
}