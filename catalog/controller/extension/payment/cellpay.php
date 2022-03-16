<?php
class ControllerExtensionPaymentCellPay extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		
		$orderId = $this->session->data['order_id'];
		
		$order_info = $this->model_checkout_order->getOrder($orderId);

		$data['action'] = 'https://secure.cellpay.com/checkout';

		$data['ap_merchant'] = $this->config->get('payment_cellpay_merchant');
		$data['ap_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['ap_orderId'] = $orderId;
		$data['ap_currency'] = $order_info['currency_code'];
		$data['ap_purchasetype'] = 'Item';
		$data['ap_itemname'] = $this->config->get('config_name') . ' - #' . $this->session->data['order_id'];
		$data['ap_itemcode'] = $this->session->data['order_id'];
		$data['ap_returnurl'] = $this->url->link('checkout/success');
		$data['ap_cancelurl'] = $this->url->link('checkout/checkout', '', true);

		return $this->load->view('extension/payment/cellpay', $data);
	}

	public function callback() {
		if (isset($this->request->post['ap_securitycode']) && ($this->request->post['ap_securitycode'] == $this->config->get('payment_cellpay_security'))) {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->request->post['ap_itemcode'], $this->config->get('payment_cellpay_order_status_id'));
		}
	}
}