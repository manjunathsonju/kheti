<?php
class ControllerApiKhetifarmmasterapi extends Controller {
	public function index() {
        // $json['payment_methods']=array(
        //     array('name' => 'Cash On Delivery'),
        //     array('name' => 'Esewa'),
        //     array('name' => 'Fonepay')

        // );
        // $json['shipping_methods']=array(
        //     array('name' => 'Shipping Charges',
        //           'description' => 'Shipping Charges -  Rs. 0.00 (Rs. 70 shipping charge for orders above Rs. 500 and free shipping for orders above Rs. 1500)',
        // ));   
        // $json['contacts']=array(
        //     'contact_ktm' => 'Kupondole - 01, Lalitpur 9802393170',
        //     'contact_pkr' => 'RastraBank Marg, Pokhara - 17 (061-460415)',

        // );      

        $json['farm_api_key']= "https://khetifarm.com";

        // $json['start_popup']= array('component_name'=>'Dashain Dhamaka','image'=>'https://khetifood.com/image/test/onionoff.jpeg','href'=>'251','type'=>'category');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));

    }


    public function getProducts(){    
        if($this->request->post['category_id']){
        $this->load->language('api/cart');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $category_id= $this->request->post['category_id'];
       
        if($this->request->post['language_id']){
            $language_id = $this->request->post['language_id'];

        }else{
            $language_id = 1;
        }
        $json = array();
        $json['products'] = array();

            $filter_data = array(
                'filter_category_id' => $category_id,
                'location' => $location,
                'language_id' => $language_id
            );

       
        $results = $this->model_catalog_product->getProducts2($filter_data);
		
        foreach ($results as $result) {
            if($result['name']){
                $name=$result['name'];
            }
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = strval((float)$result['price']);
            } else {
                $price = false;
            }
            if ((float) $result['special']) {
                $special = $result['special'];
            } else {
                $special = false;
            }
            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
            } else {
                $tax = false;
            }
            if ($this->config->get('config_review_status')) {
                $rating = (int) $result['rating'];
            } else {
                $rating = false;
            }
			//handle special price for flutter app api consumption
			if($special == false) {
				$special = strval((float)$price);
				$price  = $price;
			}
			
            $href = $this->url->link('product/product', 'product_id=' . $result['product_id']);
            if ((float)$result['special']) {
                $special = strval((float)$result['special']);
                $discount = '-'.round((($result['price'] - $result['special'])/$result['price'])*100, 0).'%';
            } else {
                // $special = false;
                $discount = false;
            }
            $option=null;

             //for options
             $sql="SELECT * FROM product_to_option op JOIN product_options p ON (op.option_group_id=p.option_group_id) WHERE op.product_id='". $result['product_id']."' ORDER BY p.option_id";
             $optionss=$this->db->query($sql);
 
             if($optionss->num_rows==0){
                 $option=NULL;
             }else{
                 foreach ($optionss->rows as $result2){
                     $option[]=array(
                         'option_id' => $result2['option_id'],
                         'option_name' => $result2['option_name'],
                         'price' => strval((float)$result2['price']),
                     );
                 }
             }
             if($result['location']==NULL){
                $result['location']='ktm,pkr';

             }
 
             // end for options
            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'location'  => $result['location'],
                'sku' =>$result['sku'],
                'thumb' => $image,
                'name' => $name,
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' =>($price),
                'special' => $special,
                'tax' => $tax,
                'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating' => $result['rating'],
                'href' => str_replace("amp;", "", $href),
                'discount' => $discount,
                'quantity' => $result['quantity'],
                'option' =>$option,
                'viewed' => $result['viewed'],                


            );
        }

              
        $json['products'] = $data['products'];
        // $json['banner']=NULL;
        $json['banner']= array(array('component_name'=>'Kaushi Kheti','image'=>'https://khetifood.com/image/test/kausi.jpeg','href'=>'220','type'=>'category'),array('component_name'=>'Kaushi Kheti','image'=>'https://khetifood.com/image/test/kausi.jpeg','href'=>'220','type'=>'category'));

        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
        }
    }

    public function getProductInfo(){   
        if($this->request->post['product_id']){
            $this->load->model('kheti/products'); 
    
            $this->load->model('catalog/product');
    
            $product_info = $this->model_catalog_product->getProduct1($this->request->post['product_id']);
            // $this->model_kheti_products->increaseview($this->request->post['product_id']);
            //  var_dump(($product_info['image']));
                if(strlen($product_info['image'])!=0){
                    $image='https://khetifood.com/image/'.$product_info['image'];
                }else{
                    $image="https://khetifood.com/image/no_image.png";
                }
               
                $this->load->model('tool/image');
                $data['image']=array();
                array_push($data['image'], array(
                    'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
                    'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'))
                ));
                $results = $this->model_catalog_product->getProductImages($this->request->post['product_id']);
                foreach ($results as $result) {
                    $data['image'][] = array(
                        'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
                        'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'))
                    );
                }
                if($product_info['special']==NULL||$product_info['special']==FALSE||$product_info['special']==''){
                    $special_price=(float)$product_info['price'];
                }else{
                    $special_price=(float)$product_info['special'];
                }
                $price1=(float)$product_info['price'];
                $nbsp = "&nbsp;";
               
    
                if($product_info['special']!=NULL&&$product_info['special']!=FALSE&&$product_info['special']!=''&&($product_info['special']!=$price1)){
                    $discount = '-'.round((((float)$price1 - (float)$product_info['special'])/(float)$price1)*100, 0).'%';
                } else {
                    // $special = false;
                    $discount = false;
                }
                if($product_info['price']==NULL&&$product_info['price']==FALSE||$product_info['price']==''){
                    $price1=$special_price;
                    $discount = false;
               }
            //    $price1=$special_price;
    
                $productInfo=array(
                    'product_id'=>$product_info['product_id'],
                    'name'=>$product_info['name'],
                    // 'description'=>NULL,
                  
                    'description'=> str_replace($nbsp, "",utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0)),
                    'transparency'=>$product_info['transparency'],
                    'nutritional_value'=>$product_info['nutritional_value'],
                    'meta_title'=>$product_info['meta_title'],
                    'meta_description'=>$product_info['meta_description'],
                    'tag'=>$product_info['tag'],
                    'model'=>$product_info['model'],
                    'sku'=>$product_info['sku'],
                    'quantity'=>$product_info['quantity'],
                    'stock_status'=>$product_info['stock_status'],
                    'location'=>$product_info['location'],
                    'image'=>$data['image'],
                    'tax'=>NULL,
                    'manufacturer'=>$product_info['manufacturer'],
                    'price'=>strval($price1),
                    'special'=> strval($special_price),
                    
                    'discount'=> $discount,
    
                    'points'=>$product_info['points'],
                    'date_available'=>$product_info['date_available'],
                    'viewed'=>$product_info['viewed']
                );
    
                if($this->request->post['location']){
                $location = $this->request->post['location'];
    
                 }else{
                 $location = 'ktm';
                }
    
                $results = $this->model_catalog_product->getProductRelated1($this->request->post['product_id'],$location);
                $this->load->model('tool/image');
    
                foreach ($results as $result) {
                    // var_dump(($result['image']));
                    if ($result['image']) {
                        // $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
                        $image = "https://khetifood.com/image/".$result['image'];
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
                    }
                    // var_dump($image);
    
                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price = $result['price'];
                    } else {
                        $price = false;
                    }
    
                    if ((float)$result['special']) {
                        $special =$this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $special = false;
                    }
    
                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
                    } else {
                        $tax = false;
                    }
    
                    if ($this->config->get('config_review_status')) {
                        $rating = (int)$result['rating'];
                    } else {
                        $rating = false;
                    }
    
                      //handle special price for flutter app api consumption
            if($special == false) {
                $special = $price;
                $price  =  $price;
                $discount=false;
            }
            if($special!=NULL&&$special!=FALSE&&$special!=''&&($special!=$price)){
                $discount = '-'.round((((float)$price - (float)$special)/(float)$price)*100, 0).'%';
            } else {
                // $special = false;
                $discount = false;
            }
             $price=$special ;
                    $data['related_products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'       => $image,
                        'name'        => $result['name'],
                        // str_replace($nbsp, "", $s);
                        'description' => str_replace($nbsp, "",utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..'),
                        'price'       => strval(round($price,2)),
                        'sku'     =>$result['sku'],
                        'special'     => strval($special),
                        'discount'=> $discount,
                        'tax'         => $tax,
                        'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                        'viewed'  => $result['viewed'],
    
                    );
                }
    
        }
        $json['product_info'] = $productInfo;
        $json['related_products']=$data['related_products'];
    
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
        
    }
}