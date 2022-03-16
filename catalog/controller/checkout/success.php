<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');
		$this->load->model('catalog/product');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');


        if (isset($this->session->data['order_id'])) {
			
            $products = $this->cart->getProducts();
			
            $address=$this->session->data['shipping_address'];
						
            // $orderarray=$this->model_checkout_order->getOrder($this->session->data['order_id']);
            $messageString="";
            if ($this->config->get('config_store_id')==1){
                $storeName="Kheti Food";
            }else{
                $storeName="Kheti Farm";
            }
            $messageString=$messageString. $storeName. "\n"."\nOrder Id: ".$this->session->data['order_id']."\nDate: ".date("Y/m/d")."\nComment: ".$this->model_checkout_order->getOrderComment($this->session->data['order_id']);;
            foreach($products as $product3) {
                $productInfo=$this->model_catalog_product->getProduct($product3["product_id"]);
                $productCategory=$this->model_catalog_product->getProductCategory($product3["product_id"]);
                $messageString=$messageString ."\n \nProduct id: ".$product3["product_id"]. "\n" ."Product Name: ". $product3["name"]."\n"."Product price: ". $product3["price"]."\n"."Quantity: ".$product3["quantity"]."\n";
                unset($productInfo);
                unset($productCategory);
            }
            $customerGroupName=$this->model_account_customer->getCustomerGroup($this->customer->getId());
			$sql="SELECT * FROM `oc_order` WHERE order_id='".$this->session->data['order_id']."'";
			$order_info=$this->db->query($sql);

            $messageString=$messageString."\n\n". "Customer "."\n"."First name: ".$order_info->row['firstname']."\n"."Last Name: ".$order_info->row['lastname'];

            $url = 'https://kheti.pythonanywhere.com/stockInfo';
            $ch = curl_init($url);
            $payload = ( $messageString);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            //var_dump($result);
            curl_close($ch);
			//for donation
			$don=$this->db->query("SELECT * FROM `donation_covid` WHERE order_id IS NULL AND customer_id='".$this->customer->getId()."' LIMIT 1");
			
			// var_dump($don);

			$sql="UPDATE oc_order SET total=total+".(float)$don->row['amount']." WHERE order_id='".$this->session->data['order_id']."'";
            $this->db->query($sql);

			$sql="UPDATE oc_order_total SET value=value+".(float)$don->row['amount']." WHERE order_id='".$this->session->data['order_id']."' AND code='total'";
            $this->db->query($sql);

			$sql="UPDATE donation_covid SET status=1,order_id='".$this->session->data['order_id']."' WHERE donation_id='".(int)$don->row['donation_id']."'";
			$this->db->query($sql);
			// end donation
			

            unset($customerGroupName);
            unset($messageString);
            unset($storeName);
            $this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}