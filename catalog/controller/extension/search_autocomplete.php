<?php
	class ControllerExtensionSearchAutocomplete extends Controller {
		
	public function index() {
			$keyword = trim($_GET['keyword']);
			$json11 = array();
			$json1 = array();
			$json22 = array();
			$json2 = array();
			$this->load->model('extension/search_autocomplete');
			$products = $this->model_extension_search_autocomplete->getProducts($keyword);
			$categories = $this->model_extension_search_autocomplete->getCategories($keyword);
			if(!empty($products)) {
				$this->load->model('tool/image');
				
				foreach ($products as $product) {
				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 40, 40);
				}
				$price = '';
				if ((float)$product['special']) {
					$price = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				}
				else {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				}
				$json11[] = array(
					'name' => strip_tags(html_entity_decode($product['product_name'], ENT_QUOTES, 'UTF-8')),
					'image'  => $image,
					'price'	 =>	$price,
					'option_count' =>	$product['option_count'],
					'href'	 => $this->url->link('product/product&product_id='.$product['product_id']),
					'id' => $product['product_id']
				);
				}
				$json1[] = array('products' => $json11);
				
		}
		else {
			$json1 = array(); 
		}
		if(!empty($categories)) {
			$this->load->model('tool/image');
				foreach ($categories as $category) {
				if ($category['image']) {
					$image = $this->model_tool_image->resize($category['image'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 40, 40);
				}
				$json22[] = array(
					'name' => strip_tags(html_entity_decode($category['category_name'], ENT_QUOTES, 'UTF-8')),
					'image'	 => $image,
					'href'	 => $this->url->link('product/category&path='.$category['category_id']),
					'id' => $category['category_id']
				);
				}
				$json2[] = array('categories' => $json22);
				
		}
		else {
			$json2 = array(); 
		}
		
		 $json = array_merge($json1, $json2);
		if(!empty($json)) {
			
			echo json_encode($json);
		}
		
		else {
			echo json_encode($json);
		} 
		
	}
}