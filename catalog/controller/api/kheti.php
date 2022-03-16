<?php
class ControllerApiKheti extends Controller {
    public function index(){    
        if($this->request->post['key']=='G3tpr0@p!Kf' && $this->request->post['category_id']){
            // var_dump("fuck");

        $this->load->language('api/cart');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $category_id= $this->request->post['category_id'];
        if($this->request->post['location']){
            $location= $this->request->post['location'];

        }else{
            $location='ktm';
        }
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

       
        $results = $this->model_catalog_product->getProducts($filter_data);
		
        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $price = false;
            }
            if ((float) $result['special']) {
                $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
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
				$special = $price;
				$price  = "";
			}
			
            $href = $this->url->link('product/product', 'product_id=' . $result['product_id']);
            if ((float)$result['special']) {
                $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                $discount = '-'.round((($result['price'] - $result['special'])/$result['price'])*100, 0).'%';
            } else {
                // $special = false;
                $discount = false;
            }
			
            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'location'  		=> $result['location'],
                'sku' =>$result['sku'],
                'thumb' => $image,
                'name' => $result['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' => $price,
                'special' => $special,
                'tax' => $tax,
                'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating' => $result['rating'],
                'href' => str_replace("amp;", "", $href),
                'discount' => $discount,
                'quantity' => $result['quantity'],
            );
        }
        
            $json['products'] = $data['products'];
            $this->response->addHeader('Content-Type: application/json');
            $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }
}
}