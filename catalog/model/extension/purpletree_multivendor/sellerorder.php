<?php 
class ModelExtensionPurpletreeMultivendorSellerorder extends Model{
		public function getProductCategory($productid){
		
		$sql = "SELECT category_id FROM " . DB_PREFIX . "product_to_category where 	product_id = '".(int)$productid."'";
		
		  $query = $this->db->query($sql);
		  
		  return $query->rows;
		}
	public function getTotalSellerOrders($data= array()){
		$sql = "SELECT COUNT(DISTINCT(pvo.order_id)) AS total FROM `" . DB_PREFIX . "order` o JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=o.order_id)";
		
		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "pvo.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE pvo.order_status_id > '0'";
		}
		if (isset($data['filter_admin_order_status'])) {
			$implode1 = array();

			$order_statuses1 = explode(',', $data['filter_admin_order_status']);

			foreach ($order_statuses1 as $order_status_id) {
				$implode1[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode1) {
				$sql .= " AND (" . implode(" OR ", $implode1) . ")";
			}
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
		
		if(!empty($data['seller_id'])){
			$sql .= " AND pvo.seller_id ='".(int)$data['seller_id']."'";
		}
		
		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}
		if(!isset($data['filter_date_from']) && !isset($data['filter_date_to'])){
			$end_date = date('Y-m-d', strtotime("-30 days"));
			$sql .= " AND DATE(o.date_added) >= '".$end_date."'";
			$sql .= " AND DATE(o.date_added) <= '".date('Y-m-d')."'";
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getSellerOrders($data = array()) {
		$sql = "SELECT pvo.order_status_id AS seller_order_status_idd,pvo.seen, o.order_status_id AS admin_order_status_idd, o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = pvo.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS admin_order_status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=o.order_id)";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "pvo.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE pvo.order_status_id > '0'";
		}
		if (isset($data['filter_admin_order_status'])) {
			$implode1 = array();

			$order_statuses1 = explode(',', $data['filter_admin_order_status']);

			foreach ($order_statuses1 as $order_status_id) {
				$implode1[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode1) {
				$sql .= " AND (" . implode(" OR ", $implode1) . ")";
			}
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
		
		if(!empty($data['seller_id'])){
			$sql .= " AND pvo.seller_id ='".(int)$data['seller_id']."'";
		}
		
		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(pvo.created_at) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(pvo.created_at) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}
		if(empty($data['filter_date_from']) && empty($data['filter_date_to'])){
			$end_date = date('Y-m-d', strtotime("-30 days"));
			$sql .= " AND DATE(pvo.created_at) >= '".$end_date."'";
			$sql .= " AND DATE(pvo.created_at) <= '".date('Y-m-d')."'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'order_status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);
		
		$sql .= " group by o.order_id";
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $this->db->escape($data['sort']);
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " DESC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getSellerOrdersProductTotal($seller_id,$order_id){
		$query = $this->db->query("SELECT SUM(total_price) AS total, SUM(shipping) AS total_shipping  FROM " . DB_PREFIX . "purpletree_vendor_orders WHERE seller_id = '".(int)$seller_id."' AND order_id = '".(int)$order_id."'");

		return $query->rows;
	}
	
	public function getSellerOrdersTotal($seller_id,$order_id){
		$query = $this->db->query("SELECT value AS total  FROM " . DB_PREFIX . "purpletree_order_total WHERE seller_id = '".(int)$seller_id."' AND order_id = '".(int)$order_id."' AND code='sub_total'");

		return $query->row;
	}
	public function getTotalllseller($seller_id,$order_id){
		$query = $this->db->query("SELECT value AS total  FROM " . DB_PREFIX . "purpletree_order_total WHERE seller_id = '".(int)$seller_id."' AND order_id = '".(int)$order_id."' AND code='sub_total'");

		return $query->row;
	}
	
	public function getOrder($order_id,$seller_id){
		 $sql = "SELECT *,o.order_status_id AS admin_order_status_id, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status FROM `" . DB_PREFIX . "order` o JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=o.order_id) WHERE o.order_id = '" . (int)$order_id . "'";
		if(!empty($seller_id)){
			$sql .=" AND pvo.seller_id = '".(int)$seller_id."'";
		}
		$order_query = $this->db->query($sql);
		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}
			
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

	

			$affiliate_info = '';

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => json_decode($order_query->row['custom_field'], true),
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_custom_field'    => json_decode($order_query->row['payment_custom_field'], true),
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => json_decode($order_query->row['shipping_custom_field'], true),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'admin_order_status_id'         => $order_query->row['admin_order_status_id'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified']
			);
		} else {
			return;
		}
	}
	
	public function getSellerOrderProducts($order_id,$seller_id){
		$query = $this->db->query("SELECT op.* ,(SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = pvo.seller_id) as seller_name, pvo.seller_id, pvo.shipping FROM " . DB_PREFIX . "order_product op JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=op.order_id AND pvo.product_id=op.product_id) WHERE op.order_id = '" . (int)$order_id . "' AND pvo.seller_id = '".(int)$seller_id."'");
		return $query->rows;
	}
	
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}
	
	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}
	
	public function getTotalCustomerRewardsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "' AND points > 0");

		return $query->row['total'];
	}
	
	public function addOrderHistory($order_id, $seller_id, $order_status_id, $comment = '', $notify = false, $override = false) {
		$order_info = $this->getOrder($order_id,$seller_id);
		
		if ($order_info) { 
			// Fraud Detection
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

			if ($customer_info && $customer_info['safe']) {
				$safe = true;
			} else {
				$safe = false;
			}

			// Only do the fraud check if the customer is not on the safe list and the order status is changing into the complete or process order status
			if (!$safe && !$override && in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Anti-Fraud
				$this->load->model('setting/extension');

				$extensions = $this->model_setting_extension->getExtensions('fraud');

				foreach ($extensions as $extension) {
					if ($this->config->get($extension['code'] . '_status')) {
						$this->load->model('extension/fraud/' . $extension['code']);

						if (property_exists($this->{'model_extension_fraud_' . $extension['code']}, 'check')) {
							$fraud_status_id = $this->{'model_extension_fraud_' . $extension['code']}->check($order_info);
	
							if ($fraud_status_id) {
								$order_status_id = $fraud_status_id;
							}
						}
					}
				}
			}

			// If current order status is not processing or complete but new status is processing or complete then commence completing the order
			if (!in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Redeem coupon, vouchers and reward points
				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

				foreach ($order_total_query->rows as $order_total) {
					if($order_total['code']!="seller_shipping"){
						$this->load->model('extension/total/' . $order_total['code']);
					
					if (property_exists($this->{'model_extension_total_' . $order_total['code']}, 'confirm')) {
						// Confirm coupon, vouchers and reward points
						$fraud_status_id = $this->{'model_extension_total_' . $order_total['code']}->confirm($order_info, $order_total);
						
						// If the balance on the coupon, vouchers and reward points is not enough to cover the transaction or has already been used then the fraud order status is returned.
						if ($fraud_status_id) {
							$order_status_id = $fraud_status_id;
						}
					}
					}
				}

				// Add commission if sale is linked to affiliate referral.
				if ($order_info['affiliate_id'] && $this->config->get('config_affiliate_auto')) {
					$this->load->model('affiliate/affiliate');

					$this->model_affiliate_affiliate->addTransaction((int)$order_info['affiliate_id'], (int)$order_info['commission'], $order_id);
				}

				// Stock subtraction
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

				foreach ($order_product_query->rows as $order_product) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");

					foreach ($order_option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}

			// Update the DB with the new statuses
			$this->db->query("UPDATE `" . DB_PREFIX . "purpletree_vendor_orders` SET order_status_id = '" . (int)$order_status_id . "', updated_at = NOW() WHERE order_id = '" . (int)$order_id . "' AND seller_id='".(int)$seller_id."'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "purpletree_vendor_orders_history SET order_id = '" . (int)$order_id . "', seller_id ='". (int)$seller_id ."', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', created_at = NOW()");

			/* $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "purpletree_vendor_commissions WHERE order_id = '" . (int)$order_id . "'");
				foreach ($order_product_query->rows as $order_product) {

            if($order_status_id == 7 || $order_status_id == 8 || $order_status_id == 9 || $order_status_id == 10 || $order_status_id == 11 || $order_status_id == 12 || $order_status_id == 13 || $order_status_id == 14 || $order_status_id == 16) {
				
                    $this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_commissions SET commission = '0', status = 'Order Cancelled', updated_at = NOW() WHERE order_id = '" . (int)$order_id . "'  AND product_id = '" . (int)$order_product['product_id'] . "'");
                } else {
				$seller_id = $this->db->query("SELECT pvp.seller_id,pvs.store_shipping_charge,pvs.store_commission, p.tax_class_id FROM " . DB_PREFIX . "purpletree_vendor_products pvp JOIN " . DB_PREFIX . "purpletree_vendor_stores pvs ON(pvs.seller_id=pvp.seller_id) JOIN " . DB_PREFIX . "product p ON(p.product_id=pvp.product_id) WHERE pvp.product_id='".(int)$order_product['product_id']."' AND pvp.is_approved=1")->row;
					if(!empty($seller_id['seller_id'])) {
						//category_commission
				        $productid = $order_product['product_id'];	
						$catids =$this->getProductCategory($productid );
						$commission_cat = array();
						$catttt = array();
						if(!empty($catids)){
							foreach($catids as $cat) {
								$sql = "SELECT commission FROM " . DB_PREFIX . "purpletree_vendor_categories_commission where 	category_id = '".(int)$cat['category_id']."'";
								$query = $this->db->query($sql);
								$commission_cat[] = $query->row;
							}
								
						}	
						if(!empty($commission_cat)) {
						 foreach($commission_cat as $catt) {
							 if(isset($catt['commission'])) {
							$catttt[] = $catt['commission'];
							 }
						   }
						}	
						if(!empty($catttt)) {
						$final_cat_commison = max($catttt);
						}
						if(isset($final_cat_commison)) {
							$commission = ($order_product['total']*$final_cat_commison)/100;
						}
						//category_commission
						elseif($seller_id['store_commission'] > 0){
							$commission = ($order_product['total']*$seller_id['store_commission'])/100;
						} else {
							$commission = ($order_product['total']*$this->config->get('module_purpletree_multivendor_commission'))/100;
						}
						$this->db->query("INSERT INTO " . DB_PREFIX . "purpletree_vendor_commissions SET order_id = '" . (int)$order_id . "', product_id ='".(int)$order_product['product_id']."', seller_id = '" . (int)$seller_id['seller_id'] . "', commission = '" . (float)$commission . "', status = 'Pending', created_at = NOW(), updated_at = NOW()");
					}
				}
				} */
				
			// If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
			if (in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && !in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Restock
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

				foreach($product_query->rows as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

					$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

					foreach ($option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}

				// Remove coupon, vouchers and reward points history
				$this->load->model('account/order');

				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

				foreach ($order_total_query->rows as $order_total) {
					if($order_total['code']!="seller_shipping"){
						$this->load->model('extension/total/' . $order_total['code']);

						if (property_exists($this->{'model_extension_total_' . $order_total['code']}, 'unconfirm')) {
							$this->{'model_extension_total_' . $order_total['code']}->unconfirm($order_id);
						}
					}
				}

				// Remove commission if sale is linked to affiliate referral.
				if ($order_info['affiliate_id']) {
					$this->load->model('affiliate/affiliate');

					$this->model_affiliate_affiliate->deleteTransaction($order_id);
				}
			}

			$this->cache->delete('product');
			
			// If order status is 0 then becomes greater than 0 send main html email
			if (!$order_info['order_status_id'] && $order_status_id) {
				// Check for any downloadable products
				$download_status = false;
	
				//$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
				$order_product_query = $this->db->query("SELECT op.* ,(SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = pvo.seller_id) as seller_name, pvo.seller_id, pvo.shipping FROM " . DB_PREFIX . "order_product op JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=op.order_id AND pvo.product_id=op.product_id) WHERE op.order_id = '" . (int)$order_id . "' AND pvo.seller_id = '".(int)$seller_id."'");
				
				foreach ($order_product_query->rows as $order_product) {
					// Check if there are any linked downloads
					$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");
	
					if ($product_download_query->row['total']) {
						$download_status = true;
					}
				}
	
				// Load the language for any mails that might be required to be sent out
				$language = new Language($order_info['language_code']);
				$language->load($order_info['language_code']);
				$language->load('mail/order_alert');
				$language->load('mail/order_edit');
				$language->load('mail/order_add');
	
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
	
				if ($order_status_query->num_rows) {
					$order_status = $order_status_query->row['name'];
				} else {
					$order_status = '';
				}
	
				$subject = sprintf($language->get('text_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), (int)$order_id);
	
				// HTML Mail
				$data = array();
	
				$data['title'] = sprintf($language->get('text_subject'), $order_info['store_name'], (int)$order_id);
	
				$data['text_greeting'] = sprintf($language->get('text_greeting'), $order_info['store_name']);
				$data['text_link'] = $language->get('text_link');
				$data['text_download'] = $language->get('text_download');
				$data['text_order_detail'] = $language->get('text_order_detail');
				$data['text_instruction'] = $language->get('text_instruction');
				$data['text_order_id'] = $language->get('text_order_id');
				$data['text_date_added'] = $language->get('text_date_added');
				$data['text_payment_method'] = $language->get('text_payment_method');
				$data['text_shipping_method'] = $language->get('text_shipping_method');
				$data['text_email'] = $language->get('text_email');
				$data['text_telephone'] = $language->get('text_telephone');
				$data['text_ip'] = $language->get('text_ip');
				$data['text_order_status'] = $language->get('text_order_status');
				$data['text_payment_address'] = $language->get('text_payment_address');
				$data['text_shipping_address'] = $language->get('text_shipping_address');
				$data['text_product'] = $language->get('text_product');
				$data['text_model'] = $language->get('text_model');
				$data['text_quantity'] = $language->get('text_quantity');
				$data['text_price'] = $language->get('text_price');
				$data['text_total'] = $language->get('text_total');
				$data['text_footer'] = $language->get('text_footer');
	
				$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
				$data['store_name'] = $order_info['store_name'];
				$data['store_url'] = $order_info['store_url'];
				$data['customer_id'] = $order_info['customer_id'];
				$data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
	
				if ($download_status) {
					$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
				} else {
					$data['download'] = '';
				}
	
				$data['order_id'] = $order_id;
				$data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
				$data['payment_method'] = $order_info['payment_method'];
				$data['shipping_method'] = $order_info['shipping_method'];
				$data['email'] = $order_info['email'];
				$data['telephone'] = $order_info['telephone'];
				$data['ip'] = $order_info['ip'];
				$data['order_status'] = $order_status;
				
				if ($comment && $notify) {
					$data['comment'] = nl2br($comment);
				} else {
					$data['comment'] = '';
				}
				
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
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
	
				$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	
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
	
				$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	
				$this->load->model('tool/upload');
	
				// Products
				$data['products'] = array();
				
				$total_shipping = 0;
				$product_total = 0;
				foreach ($order_product_query->rows as $product) {
					$option_data = array();
					$total_shipping += $product['shipping'];
				
					$product_total += $product['total'];
					
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
	
					foreach ($order_option_query->rows as $option) {
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
	
					$data['products'][] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}
	
				// Vouchers
				$data['vouchers'] = array();
	
				$order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
	
				foreach ($order_voucher_query->rows as $voucher) {
					$data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
	
				// Order Totals
				$data['totals'] = array();
				
				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "purpletree_order_total` WHERE order_id = '" . (int)$order_id . "' AND seller_id = '".(int)$seller_id."' ORDER BY sort_order ASC");
	
				 foreach ($order_total_query->rows as $total) {
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
	
				// Text Mail
				$text  = sprintf($language->get('text_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				$text .= $language->get('text_order_id') . ' ' . (int)$order_id . "\n";
				$text .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
				$text .= $language->get('text_order_status') . ' ' . (int)$order_status . "\n\n";
	
				if ($comment && $notify) {
					$text .= $language->get('text_instruction') . "\n\n";
					$text .= $comment . "\n\n";
				}
	
				// Products
				$text .= $language->get('text_products') . "\n";
	
				foreach ($order_product_query->rows as $product) {
					$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
	
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
	
					foreach ($order_option_query->rows as $option) {
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
	
						$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
					}
				}
	
				foreach ($order_voucher_query->rows as $voucher) {
					$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
				}
	
				$text .= "\n";
	
				$text .= $language->get('text_order_total') . "\n";
	
				foreach ($order_total_query->rows as $total) {
					$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				}
	
				$text .= "\n";
	
				if ($order_info['customer_id']) {
					$text .= $language->get('text_link') . "\n";
					$text .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
				}
	
				if ($download_status) {
					$text .= $language->get('text_download') . "\n";
					$text .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
				}
	
				// Comment
				if ($order_info['comment']) {
					$text .= $language->get('text_comment') . "\n\n";
					$text .= $order_info['comment'] . "\n\n";
				}
	
				$text .= $language->get('text_footer') . "\n\n";
	
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
				$mail->setTo($order_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($this->load->view('mail/order', $data));
				$mail->setText($text);
				$mail->send();
				
				// Seller mail
				$seller_detail = $this->db->query("SELECT email FROM " .DB_PREFIX."customer WHERE customer_id = '".(int)$seller_id."'")->row;
				if(isset($seller_detail['email'])){
					$language = new Language($order_info['language_code']);

				$language->load($order_info['language_code']);

				$language->load('mail/order_alert');
				$language->load('mail/order_edit');
					$subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), (int)$order_id);
	
					// HTML Mail
					$data['text_greeting'] = $language->get('text_received');
	
					if ($comment) {
						if ($order_info['comment']) {
							$data['comment'] = nl2br($comment) . '<br/><br/>' . $order_info['comment'];
						} else {
							$data['comment'] = nl2br($comment);
						}
					} else {
						if ($order_info['comment']) {
							$data['comment'] = $order_info['comment'];
						} else {
							$data['comment'] = '';
						}
					}
	
					$data['text_download'] = '';
	
					$data['text_footer'] = '';
	
					$data['text_link'] = '';
					$data['link'] = '';
					$data['download'] = '';
	
					// Text
					$text  = $language->get('text_received') . "\n\n";
					$text .= $language->get('text_order_id') . ' ' . $order_id . "\n";
					$text .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
					$text .= $language->get('text_order_status') . ' ' . $order_status . "\n\n";
					$text .= $language->get('text_product') . "\n";
	
					foreach ($order_product_query->rows as $product) {
						$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
	
						$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");
	
						foreach ($order_option_query->rows as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
							} else {
								$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
							}
	
							$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
						}
					}
	
					foreach ($order_voucher_query->rows as $voucher) {
						$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
					}
	
					$text .= "\n";
	
					$text .= $language->get('text_total') . "\n";
	
					foreach ($order_total_query->rows as $total) {
						$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
					}
	
					$text .= "\n";
	
					if ($order_info['comment']) {
						$text .= $language->get('text_comment') . "\n\n";
						$text .= $order_info['comment'] . "\n\n";
					}
	
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
					$mail->setTo($seller_detail['email']);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($this->load->view('mail/order', $data));
					$mail->setText($text);
					$mail->send();
				}
				
				// Admin Alert Mail
				if (in_array('order', (array)$this->config->get('config_mail_alert'))) {
					$language = new Language($order_info['language_code']);
					$language->load($order_info['language_code']);
					$language->load('mail/order_alert');
					$language->load('mail/order_edit');
					$language->load('mail/order_add');
					$subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);
	
					// HTML Mail
					$data['text_greeting'] = $language->get('text_received');
	
					if ($comment) {
						if ($order_info['comment']) {
							$data['comment'] = nl2br($comment) . '<br/><br/>' . $order_info['comment'];
						} else {
							$data['comment'] = nl2br($comment);
						}
					} else {
						if ($order_info['comment']) {
							$data['comment'] = $order_info['comment'];
						} else {
							$data['comment'] = '';
						}
					}
	
					$data['text_download'] = '';
	
					$data['text_footer'] = '';
	
					$data['text_link'] = '';
					$data['link'] = '';
					$data['download'] = '';
	
					// Text
					$text  = $language->get('text_received') . "\n\n";
					$text .= $language->get('text_order_id') . ' ' . $order_id . "\n";
					$text .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
					$text .= $language->get('text_order_status') . ' ' . $order_status . "\n\n";
					$text .= $language->get('text_products') . "\n";
	
					foreach ($order_product_query->rows as $product) {
						$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
	
						$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
	
						foreach ($order_option_query->rows as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
							} else {
								$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
							}
	
							$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
						}
					}
	
					foreach ($order_voucher_query->rows as $voucher) {
						$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
					}
	
					$text .= "\n";
	
					$text .= $language->get('text_order_total') . "\n";
	
					foreach ($order_total_query->rows as $total) {
						$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
					}
	
					$text .= "\n";
	
					if ($order_info['comment']) {
						$text .= $language->get('text_comment') . "\n\n";
						$text .= $order_info['comment'] . "\n\n";
					}
	
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
					$mail->setTo($this->config->get('config_email'));
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($this->load->view('mail/order', $data));
					$mail->setText($text);
					$mail->send();
	
					// Send to additional alert emails
					$emails = explode(',', $this->config->get('config_alert_email'));
	
					foreach ($emails as $email) {
						if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$mail->setTo($email);
							$mail->send();
						}
					}
				}
		
	}
			// If order status is not 0 then send update text email
			if ((int)$order_info['order_status_id'] && (int)$order_status_id && $notify) {
				$language = new Language($order_info['language_code']);
				$language->load($order_info['language_code']);
				$language->load('mail/order_alert');
				$language->load('mail/order_edit');
	
				$subject = sprintf($language->get('text_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);
	
				$message  = $language->get('text_order_id') . ' ' . $order_id . "\n";
				$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";
	
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
	
				if ($order_status_query->num_rows) {
					$message .= $language->get('text_order_status') . "\n\n";
					$message .= $order_status_query->row['name'] . "\n\n";
				}
				
				$message .= $language->get('text_product') . "\n";
				
				$order_product_query = $this->db->query("SELECT op.* ,(SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = pvo.seller_id) as seller_name, pvo.seller_id, pvo.shipping FROM " . DB_PREFIX . "order_product op JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=op.order_id AND pvo.product_id=op.product_id) WHERE op.order_id = '" . (int)$order_id . "' AND pvo.seller_id = '".(int)$seller_id."'");
				
				foreach ($order_product_query->rows as $product) {
					$message .= $product['name'] . "\n";
				}
				$message .="\n";
	
				if ($order_info['customer_id']) {
					$message .= $language->get('text_link') . "\n";
					$message .= $this->db->escape($order_info['store_url']) . 'index.php?route=account/order/info&order_id=' . (int)$order_id . "\n\n";
				}
	
				if ($comment) {
					$message .= $language->get('text_comment') . "\n\n";
					$message .= strip_tags($comment) . "\n\n";
				}
	
				$message .= $language->get('text_footer');
	
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
				$mail->setTo($order_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($message);
				$mail->send();
			}
			
			///// For admin when seller update the order status
			
			if ($order_info['order_status_id'] && $order_status_id) {
				
				$language = new Language($order_info['language_code']);
				$language->load($order_info['language_code']);
				$language->load('mail/order_alert');
				$language->load('mail/order_edit');
				$language->load('purpletree_multivendor/sellerorder');
				$subject = sprintf($language->get('text_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), (int)$order_id);
	
				$message  = $language->get('text_order_id') . ' ' . (int)$order_id . "\n";
				$message .= $language->get('text_date_added') . ' ' . date('d-m-Y', strtotime($this->db->escape($order_info['date_added']))) . "\n\n";
	
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
	
				if ($order_status_query->num_rows) {
					$message .= $language->get('text_seller_order_status');
					$message .= ": ".$order_status_query->row['name'].".\n\n" ;
				}
	
				if ($comment) {
					$message .= $language->get('text_comment') . "\n\n";
					$message .= strip_tags($comment) . "\n\n";
				}
	
				$message .= $language->get('text_seller_update_footer');
	
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->db->escape($order_info['store_name']), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($message);
				$mail->send();
			}
		}
	}
	
	public function getOrderHistories($order_id, $seller_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.created_at, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "purpletree_vendor_orders_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND oh.seller_id= '".(int)$seller_id."' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.created_at ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	
	public function getTotalOrderHistories($order_id,$seller_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purpletree_vendor_orders_history WHERE order_id = '" . (int)$order_id . "' AND seller_id='".(int)$seller_id."'");

		return $query->row['total'];
	}
	
	public function getOrderTotals($order_id,$seller_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purpletree_order_total WHERE order_id = '" . (int)$order_id . "' AND seller_id = '".(int)$seller_id."' ORDER BY sort_order");

		return $query->rows;
	}
	
	public function getSellerOrdersCommissionTotal($order_id,$seller_id=NULL){
		
		$sql = "SELECT SUM(commission) AS total_commission  FROM " . DB_PREFIX . "purpletree_vendor_commissions WHERE order_id = '".(int)$order_id."'";
		
		if(!empty($seller_id)){
			$sql .= " AND seller_id = '".(int)$seller_id."'";
		}
		
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function getProductOptionValue($product_id, $product_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function getOrderSeller($order_id){
		$query = $this->db->query("SELECT seller_id FROM " . DB_PREFIX . "purpletree_vendor_orders WHERE order_id='".(int)$order_id."'");
		return $query->rows;
	}
	public function getsellerorderunique($order_id,$seller_id){
		$query = $this->db->query("SELECT seller_id FROM " . DB_PREFIX . "purpletree_vendor_orders WHERE order_id='".(int)$order_id."' AND seller_id !=".$seller_id);
		if($query->num_rows) {
			return $query->rows;
		}
	}
		public function updateSeenOrders($order_id,$seller_id){
		$this->db->query("UPDATE `" . DB_PREFIX . "purpletree_vendor_orders` SET seen = '0' WHERE seller_id = '" . (int)$seller_id . "' AND order_id =".(int)$order_id);
	}
}
?>