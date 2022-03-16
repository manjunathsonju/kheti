<?php
class ModelExtensionMayAdvancedOptions extends Model {
	public function getCartProductVariableData() {
		$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		$cart_product_models = array();
		$cart_product_skus = array();
		$cart_product_upcs = array();
		$cart_product_eans = array();
		$cart_product_jans = array();
		$cart_product_isbns = array();
		$cart_product_mpns = array();
		$cart_product_locations = array();
		$cart_product_dimension_ls = array();
		$cart_product_dimension_ws = array();
		$cart_product_dimension_hs = array();
		$cart_product_length_units = array();
		$cart_product_weights = array();
		$cart_product_weight_units = array();
		$cart_product_images = array();

		foreach ($cart_query->rows as $cart) {
			$cart_option = json_decode($cart['option'], true);

			$cart_product_models[$cart['cart_id']] = "";
			if (isset($cart_option['model']) && $cart_option['model'] != '') {
				$cart_product_models[$cart['cart_id']] = $cart_option['model'];
			}

			if (isset($cart_option['sku']) && $cart_option['sku'] != '') {
				$cart_product_skus[$cart['cart_id']] = $cart_option['sku'];
			}

			if (isset($cart_option['upc']) && $cart_option['upc'] != '') {
				$cart_product_upcs[$cart['cart_id']] = $cart_option['upc'];
			}

			if (isset($cart_option['ean']) && $cart_option['ean'] != '') {
				$cart_product_eans[$cart['cart_id']] = $cart_option['ean'];
			}

			if (isset($cart_option['jan']) && $cart_option['jan'] != '') {
				$cart_product_jans[$cart['cart_id']] = $cart_option['jan'];
			}

			if (isset($cart_option['isbn']) && $cart_option['isbn'] != '') {
				$cart_product_isbns[$cart['cart_id']] = $cart_option['isbn'];
			}

			if (isset($cart_option['mpn']) && $cart_option['mpn'] != '') {
				$cart_product_mpns[$cart['cart_id']] = $cart_option['mpn'];
			}

			if (isset($cart_option['location']) && $cart_option['location'] != '') {
				$cart_product_locations[$cart['cart_id']] = $cart_option['location'];
			}

			if (isset($cart_option['dimension_l']) && $cart_option['dimension_l'] != '') {
				$cart_product_dimension_ls[$cart['cart_id']] = $cart_option['dimension_l'];
			}

			if (isset($cart_option['dimension_w']) && $cart_option['dimension_w'] != '') {
				$cart_product_dimension_ws[$cart['cart_id']] = $cart_option['dimension_w'];
			}

			if (isset($cart_option['dimension_h']) && $cart_option['dimension_h'] != '') {
				$cart_product_dimension_hs[$cart['cart_id']] = $cart_option['dimension_h'];
			}

			if (isset($cart_option['length_unit']) && $cart_option['length_unit'] != '') {
				$cart_product_length_units[$cart['cart_id']] = $cart_option['length_unit'];
			}

			if (isset($cart_option['weight']) && $cart_option['weight'] != '') {
				$cart_product_weights[$cart['cart_id']] = $cart_option['weight'];
			}

			if (isset($cart_option['weight_unit']) && $cart_option['weight_unit'] != '') {
				$cart_product_weight_units[$cart['cart_id']] = $cart_option['weight_unit'];
			}

			if (isset($cart_option['image']) && $cart_option['image'] != '') {
				$cart_product_images[$cart['cart_id']] = $cart_option['image'];
			}
		}

		return array(
			'models' => $cart_product_models,
			'skus' => $cart_product_skus,
			'upcs' => $cart_product_upcs,
			'eans' => $cart_product_eans,
			'jans' => $cart_product_jans,
			'isbns' => $cart_product_isbns,
			'locations' => $cart_product_locations,
			'mpns' => $cart_product_mpns,
			'dimension_ls' => $cart_product_dimension_ls,
			'dimension_ws' => $cart_product_dimension_ws,
			'dimension_hs' => $cart_product_dimension_hs,
			'length_units' => $cart_product_length_units,
			'weights' => $cart_product_weights,
			'weight_units' => $cart_product_weight_units,
			'images' => $cart_product_images
		);
	}

	public function getOrderProductOptionData($product_option_value_ids) {
		if (!is_array($product_option_value_ids)) {
			$product_option_value_ids = array($product_option_value_ids);
		}

		$option_value_ids = array();
		$product_option_ids = array();
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_value` WHERE product_option_value_id IN (" . implode(',', $product_option_value_ids) . ")");
		foreach ($query->rows as $row) {
			$option_value_ids[$row['product_option_id']] = $row['option_value_id'];
			$product_option_ids[] = $row['product_option_id'];
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` WHERE product_option_id IN (" . implode(',', $product_option_ids) . ") ORDER BY product_option_id DESC");

		$data = array();
		foreach ($query->rows as $row) {
			$values = explode(':::', $row['value']);
			if (!isset($values[2])) {
				continue;
			}

			$option_values = json_decode($values[2], true);

			if (!isset($data['model']) && isset($option_values['model']) && !empty($option_values['model'][$option_value_ids[$row['product_option_id']]])) {
				$data['model'] = $option_values['model'][$option_value_ids[$row['product_option_id']]];
			}
			if (!isset($data['sku']) && isset($option_values['sku']) && !empty($option_values['sku'][$option_value_ids[$row['product_option_id']]])) {
				$data['sku'] = $option_values['sku'][$option_value_ids[$row['product_option_id']]];
			}
			if (!isset($data['upc']) && isset($option_values['upc']) && !empty($option_values['upc'][$option_value_ids[$row['product_option_id']]])) {
				$data['upc'] = $option_values['upc'][$option_value_ids[$row['product_option_id']]];
			}
			if (!isset($data['ean']) && isset($option_values['ean']) && !empty($option_values['ean'][$option_value_ids[$row['product_option_id']]])) {
				$data['ean'] = $option_values['ean'][$option_value_ids[$row['product_option_id']]];
			}
			if (!isset($data['jan']) && isset($option_values['jan']) && !empty($option_values['jan'][$option_value_ids[$row['product_option_id']]])) {
				$data['jan'] = $option_values['jan'][$option_value_ids[$row['product_option_id']]];
			}
			if (!isset($data['isbn']) && isset($option_values['isbn']) && !empty($option_values['isbn'][$option_value_ids[$row['product_option_id']]])) {
				$data['isbn'] = $option_values['isbn'][$option_value_ids[$row['product_option_id']]];
			}
			if (!isset($data['mpn']) && isset($option_values['mpn']) && !empty($option_values['mpn'][$option_value_ids[$row['product_option_id']]])) {
				$data['mpn'] = $option_values['mpn'][$option_value_ids[$row['product_option_id']]];
			}
			if (!isset($data['location']) && isset($option_values['location']) && !empty($option_values['location'][$option_value_ids[$row['product_option_id']]])) {
				$data['location'] = $option_values['location'][$option_value_ids[$row['product_option_id']]];
			}
			if (!isset($data['dimension_l']) && isset($option_values['dimension_l']) && !empty($option_values['dimension_l'][$option_value_ids[$row['product_option_id']]])) {
				$data['dimension_l'] = $option_values['dimension_l'][$option_value_ids[$row['product_option_id']]];
			}
			if (!isset($data['dimension_w']) && isset($option_values['dimension_w']) && !empty($option_values['dimension_w'][$option_value_ids[$row['product_option_id']]])) {
				$data['dimension_w'] = $option_values['dimension_w'][$option_value_ids[$row['product_option_id']]];
			}
			if (!isset($data['dimension_h']) && isset($option_values['dimension_h']) && !empty($option_values['dimension_h'][$option_value_ids[$row['product_option_id']]])) {
				$data['dimension_h'] = $option_values['dimension_h'][$option_value_ids[$row['product_option_id']]];
			}
		}

		return $data;
	}
}
