<?php
class ControllerApiTest extends Controller {
   
public function index(){
	// if(($this->request->post['customer_id'])&&($this->request->post['token'])){

		var_dump($this->request->get['order_id']);

		$sql="SELECT * FROM `oc_customer` WHERE customer_id='".$this->request->get['customer_id']."'";
		$customer_info=$this->db->query($sql);
		// if($customer_info->row['token_app']==$this->request->post['token']){
		$this->load->language('account/order');

	if (isset($this->request->get['order_id'])) {
		$order_id = $this->request->get['order_id'];
	} else {
		$order_id = 0;
	}
	if (isset($this->request->get['customer_id'])) {
		$customer_id = $this->request->get['customer_id'];
	} else {
		$customer_id = 0;
	}

	$this->load->model('account/order');

	$order_info = $this->model_account_order->getOrderinfos($order_id,$customer_id);

	if ($order_info) {
		$this->document->setTitle($this->language->get('text_order'));
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if ($order_info['invoice_no']) {
			$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
		} else {
			$data['invoice_no'] = '';
		}

		$data['order_id'] = $this->request->post['order_id'];
		$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

		if ($order_info['payment_address_format']) {
			$format = $order_info['payment_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}


		$replace = array(
			'firstname' => $order_info['payment_firstname'],
			'lastname'  => $order_info['payment_lastname'],
			'company'   => $order_info['payment_company'],
			'address_1' => $order_info['payment_address_1'],
			'address_2' => $order_info['payment_address_2'],
			'city'      => $order_info['payment_city'],
			'postcode'  => $order_info['payment_postcode'],
			'zone'      => $order_info['payment_zone'],
			'zone_code' => $order_info['payment_zone_code'],
			'country'   => $order_info['payment_country']
		);

		$data['payment_address'] = $replace;
		$data['payment_method'] = $order_info['payment_method'];

		if ($order_info['shipping_address_format']) {
			$format = $order_info['shipping_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = array(
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{zone_code}',
			'{country}'
		);

		$replace = array(
			'firstname' => $order_info['shipping_firstname'],
			'lastname'  => $order_info['shipping_lastname'],
			'company'   => $order_info['shipping_company'],
			'address_1' => $order_info['shipping_address_1'],
			'address_2' => $order_info['shipping_address_2'],
			'city'      => $order_info['shipping_city'],
			'postcode'  => $order_info['shipping_postcode'],
			'zone'      => $order_info['shipping_zone'],
			'zone_code' => $order_info['shipping_zone_code'],
			'country'   => $order_info['shipping_country']
		);

		$data['shipping_address'] = $replace; 
		$data['shipping_method'] = $order_info['shipping_method'];

		$this->load->model('catalog/product');
		$this->load->model('tool/upload');

		// Products
		$data['products'] = array();

		$products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

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

			$product_info = $this->model_catalog_product->getProduct($product['product_id']);				

			$data['products'][] = array(
				'name'     => $product['name'],
				'model'    => $product['model'],
				'option'   => $option_data,
				'quantity' => $product['quantity'],
				'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				// 'reorder'  => $reorder,
				// 'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], true)
			);
		}

		// Voucher
		$data['vouchers'] = array();
		$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);
		foreach ($vouchers as $voucher) {
			$data['vouchers'][] = array(
				'description' => $voucher['description'],
				'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
			);
		}

		// Totals
		$data['totals'] = array();

		$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

		foreach ($totals as $total) {
			$data['totals'][] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}

		$data['comment'] = nl2br($order_info['comment']);

		// History
		$data['histories'] = array();

		$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'status'     => $result['status'],
				'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
			);
		}
	} 
	var_dump($this->request->get['customer_id']);

	var_dump($data);
	$this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));
}
}