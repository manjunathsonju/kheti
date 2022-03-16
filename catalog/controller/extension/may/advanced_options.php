<?php
class ControllerExtensionMayAdvancedOptions extends Controller {
	public function vProductProductBefore($route, &$data) {
		if (!$this->config->get('may_advanced_options_status')) {
			return;
		}

		if (!isset($data['options'])) {
			return;
		}

		$may_advanced_options = array();

		$this->load->model('tool/image');

		$may_advanced_options_prefix = array();
		foreach ($data['options'] as $option_key => $option) {
			if (($option['type'] == 'select' || $option['type'] == 'radio') && $option['value'] != '') {
				$may_advanced_info = explode(':::', $option['value']);

				$tmp = isset($may_advanced_info[2]) ? json_decode($may_advanced_info[2], true) : array();
				
				$models = isset($tmp['model']) ? $tmp['model'] : array();
				$skus = isset($tmp['sku']) ? $tmp['sku'] : array();
				$upcs = isset($tmp['upc']) ? $tmp['upc'] : array();
				$eans = isset($tmp['ean']) ? $tmp['ean'] : array();
				$jans = isset($tmp['jan']) ? $tmp['jan'] : array();
				$isbns = isset($tmp['isbn']) ? $tmp['isbn'] : array();
				$mpns = isset($tmp['mpn']) ? $tmp['mpn'] : array();
				$locations = isset($tmp['location']) ? $tmp['location'] : array();
				$images = isset($tmp['image']) ? $tmp['image'] : array();
				$prices = isset($tmp['price']) ? $tmp['price'] : array();
				$price_prefixs = isset($tmp['price_prefix']) ? $tmp['price_prefix'] : array();
				$points = isset($tmp['point']) ? $tmp['point'] : array();
				$point_prefixs = isset($tmp['point_prefix']) ? $tmp['point_prefix'] : array();
				$weights = isset($tmp['weight']) ? $tmp['weight'] : array();
				$weight_prefixs = isset($tmp['weight_prefix']) ? $tmp['weight_prefix'] : array();
				$dimension_ls = isset($tmp['dimension_l']) ? $tmp['dimension_l'] : array();
				$dimension_ws = isset($tmp['dimension_w']) ? $tmp['dimension_w'] : array();
				$dimension_hs = isset($tmp['dimension_h']) ? $tmp['dimension_h'] : array();
				$hides = isset($tmp['hide']) ? $tmp['hide'] : array();
				$subtracts = isset($tmp['subtract']) ? $tmp['subtract'] : array();
				$quantities = isset($tmp['quantity']) ? $tmp['quantity'] : array();
				
				foreach ($option['product_option_value'] as $option_value_key => $option_value) {
					if (isset($hides[$option_value['option_value_id']]) && $hides[$option_value['option_value_id']]) {
						unset($option['product_option_value'][$option_value_key]);
						continue;
					}

					if (isset($models[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['model'] = $models[$option_value['option_value_id']];
					}

					if (isset($skus[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['sku'] = $skus[$option_value['option_value_id']];
					}

					if (isset($upcs[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['upc'] = $upcs[$option_value['option_value_id']];
					}

					if (isset($eans[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['ean'] = $eans[$option_value['option_value_id']];
					}

					if (isset($jans[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['jan'] = $jans[$option_value['option_value_id']];
					}

					if (isset($isbns[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['isbn'] = $isbns[$option_value['option_value_id']];
					}

					if (isset($mpns[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['mpn'] = $mpns[$option_value['option_value_id']];
					}

					if (isset($locations[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['location'] = $locations[$option_value['option_value_id']];
					}

					if (isset($prices[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['base_price'] = $this->currency->format($prices[$option_value['option_value_id']], $this->session->data['currency']);
					}

					if (isset($price_prefixs[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['price_prefix'] = $price_prefixs[$option_value['option_value_id']];
					}

					if (isset($points[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['point'] = $points[$option_value['option_value_id']];
					}

					if (isset($point_prefixs[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['point_prefix'] = $point_prefixs[$option_value['option_value_id']];
					}

					if (isset($weights[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['weight'] = $weights[$option_value['option_value_id']];
					}

					if (isset($weight_prefixs[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['weight_prefix'] = $weight_prefixs[$option_value['option_value_id']];
					}

					if (isset($dimension_ls[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['dimension_l'] = $dimension_ls[$option_value['option_value_id']];
					}

					if (isset($dimension_ws[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['dimension_w'] = $dimension_ws[$option_value['option_value_id']];
					}

					if (isset($dimension_hs[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['dimension_h'] = $dimension_hs[$option_value['option_value_id']];
					}

					if (isset($images[$option_value['option_value_id']]) && count(array_filter($images[$option_value['option_value_id']]))) {
						$option['product_option_value'][$option_value_key]['product_images'] = array();
						foreach ($images[$option_value['option_value_id']] as $image) {
							$option['product_option_value'][$option_value_key]['product_images'][] = array(
								'origin' => $image,
								'popup' => $this->model_tool_image->resize($image, $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
								'thumb' => $this->model_tool_image->resize($image, $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'))
							);
						}
					}

					if (isset($subtracts[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['subtract'] = $subtracts[$option_value['option_value_id']];
					}

					if (isset($quantities[$option_value['option_value_id']])) {
						$option['product_option_value'][$option_value_key]['quantity'] = $quantities[$option_value['option_value_id']];
					}
				}

				$option['advanced_option_id'] = isset($may_advanced_info[1]) ? $may_advanced_info[1] : '';

				if (isset($may_advanced_info[0])) {
					if (in_array($may_advanced_info[0], $may_advanced_options_prefix)) {
						$option['init_disable'] = 1;
					} else {
						$may_advanced_options_prefix[] = $may_advanced_info[0];
						$option['init_disable'] = 0;
					}
				} else {
					$option['init_disable'] = 0;
				}

				$may_advanced_options[] = $option;
				unset($data['options'][$option_key]);
			}
		}

		uasort($may_advanced_options, function($a, $b) {
			return ($a['product_option_id'] < $b['product_option_id']) ? -1 : 1;
		});

		if (count($may_advanced_options)) {
			$may_advanced_options = array_values($may_advanced_options);

			$init_visible_options = array();
			foreach ($may_advanced_options as $index => $option) {
				if (!in_array($option['option_id'], $init_visible_options)) {
					$may_advanced_options[$index]['init_visible'] = 1;
					$init_visible_options[] = $option['option_id'];
				} else {
					$may_advanced_options[$index]['init_visible'] = 0;
				}
			}

			$may_advanced_options[0]['init_disable'] = 0;
		}

		$currency = $this->session->data['currency'];
		$symbol_left = $this->currency->getSymbolLeft($currency);
		$symbol_right = $this->currency->getSymbolRight($currency);
		$data['currency'] = array(
			'code' => $currency,
			'symbol_position' => ($symbol_left !== "") ? "left" : "right",
			'symbol' => ($symbol_left !== "") ? $symbol_left : $symbol_right,
			'decimal_place' => $this->currency->getDecimalPlace($currency),
			'decimal_point' => $this->language->get('decimal_point'),
			'thousand_point' => $this->language->get('thousand_point')
		);
		$data['may_advanced_options'] = $may_advanced_options;

		if (!isset($data['may_advanced_options_config'])) {
			$data['may_advanced_options_config'] = array(
				'show_option_price' => $this->config->get('may_advanced_options_show_option_price'),
				'wrapper' => $this->config->get('may_advanced_options_wrapper'),
				'swatches' => $this->config->get('may_advanced_options_swatches'),
				'swatch_image' => isset($tmp['swatch_image']) ? $tmp['swatch_image'] : $this->config->get('may_advanced_options_swatch_image'),

				'hide_pp_sku' => $this->config->get('may_advanced_options_hide_pp_sku'),
				'hide_pp_upc' => $this->config->get('may_advanced_options_hide_pp_upc'),
				'hide_pp_ean' => $this->config->get('may_advanced_options_hide_pp_ean'),
				'hide_pp_jan' => $this->config->get('may_advanced_options_hide_pp_jan'),
				'hide_pp_isbn' => $this->config->get('may_advanced_options_hide_pp_isbn'),
				'hide_pp_mpn' => $this->config->get('may_advanced_options_hide_pp_mpn'),
				'hide_pp_location' => $this->config->get('may_advanced_options_hide_pp_location'),
				'hide_pp_dimension' => $this->config->get('may_advanced_options_hide_pp_dimension'),
		
				'swatch_style_shape' => $this->config->get('may_advanced_options_swatch_style_shape'),
				'swatch_style_size_width' => $this->config->get('may_advanced_options_swatch_style_size_width'),
				'swatch_style_size_height' => $this->config->get('may_advanced_options_swatch_style_size_height'),
				'swatch_style_size_radius' => $this->config->get('may_advanced_options_swatch_style_size_radius'),
				'swatch_style_border_width' => $this->config->get('may_advanced_options_swatch_style_border_width'),
				'swatch_style_border_color_selected' => $this->config->get('may_advanced_options_swatch_style_border_color_selected'),
				'swatch_style_border_color_default' => $this->config->get('may_advanced_options_swatch_style_border_color_default'),
				'swatch_style_space_padding' => $this->config->get('may_advanced_options_swatch_style_space_padding'),
		
				'swatch_css' => $this->config->get('may_advanced_options_swatch_css'),
				'theme' => ($this->config->get('config_theme') == 'default') ? $this->config->get('theme_default_directory') : $this->config->get('config_theme'),
			);
		}

		$this->load->language('extension/may/advanced_options');
		$data['may_advanced_options_language'] = array(
			'text_sku' => $this->language->get('text_sku'),
			'text_upc' => $this->language->get('text_upc'),
			'text_ean' => $this->language->get('text_ean'),
			'text_jan' => $this->language->get('text_jan'),
			'text_isbn' => $this->language->get('text_isbn'),
			'text_mpn' => $this->language->get('text_mpn'),
			'text_location' => $this->language->get('text_location'),
			'text_weight' => $this->language->get('text_weight'),
			'text_dimension' => $this->language->get('text_dimension'),

			'error_option_stock' => $this->language->get('error_option_stock'),
		);

		if (!in_array($data['may_advanced_options_config']['theme'], ['journal3', 'wokiee'])) {
			$view_template = 'product';
		} else {
			$view_template = 'product/' . $data['may_advanced_options_config']['theme'];
		}

		if (isset($this->session->data['may_advanced_option_product_' . $data['product_id']])) {
			$product_info = $this->session->data['may_advanced_option_product_' . $data['product_id']];

			if (!isset($data['weight'])) {
				$data['weight'] = $this->weight->format((float)$product_info['weight'], $product_info['weight_class_id']);
			}
			$data['weight_value'] = (float)$product_info['weight'];
			$data['weight_unit'] = $this->weight->getUnit($product_info['weight_class_id']);

			$data['dimension_l'] = (float)$product_info['length'];
			$data['dimension_w'] = (float)$product_info['width'];
			$data['dimension_h'] = (float)$product_info['height'];
			$data['length_unit'] = $this->length->getUnit($product_info['length_class_id']);

			unset($this->session->data['may_advanced_option_product_' . $data['product_id']]);
		}

		$data['footer'] .= $this->load->view('extension/may/advanced_options/product/' . $view_template, $data);
	}

	public function vProductCategoryBefore($route, &$data) {
		/* In Progress
		$this->load->model('catalog/product');

		foreach ($data['products'] as $product) {
			$options = $this->model_catalog_product->getProductOptions($product['product_id']);
		}
		*/
	}

	public function vCommonCartBefore($route, &$data) {
		if (!$this->config->get('may_advanced_options_status')) {
			return;
		}

		if (!isset($data['products'])) {
			return;
		}

		if (isset($this->session->data['may_advanced_options_config'])) {
			$may_advanced_options_config = $this->session->data['may_advanced_options_config'];
		} else {
			$may_advanced_options_config = array(
				'hide_cc_sku' => $this->config->get('may_advanced_options_hide_cc_sku'),
				'hide_cc_upc' => $this->config->get('may_advanced_options_hide_cc_upc'),
				'hide_cc_ean' => $this->config->get('may_advanced_options_hide_cc_ean'),
				'hide_cc_jan' => $this->config->get('may_advanced_options_hide_cc_jan'),
				'hide_cc_isbn' => $this->config->get('may_advanced_options_hide_cc_isbn'),
				'hide_cc_mpn' => $this->config->get('may_advanced_options_hide_cc_mpn'),
				'hide_cc_location' => $this->config->get('may_advanced_options_hide_cc_location'),
				'hide_cc_dimension' => $this->config->get('may_advanced_options_hide_cc_dimension'),
			);	
		}

		$this->load->model('extension/may/advanced_options');
		$cart_product_data = $this->model_extension_may_advanced_options->getCartProductVariableData();
		$cart_product_models = $cart_product_data['models'];
		$cart_product_skus = $cart_product_data['skus'];
		$cart_product_upcs = $cart_product_data['upcs'];
		$cart_product_eans = $cart_product_data['eans'];
		$cart_product_jans = $cart_product_data['jans'];
		$cart_product_isbns = $cart_product_data['isbns'];
		$cart_product_mpns = $cart_product_data['mpns'];
		$cart_product_locations = $cart_product_data['locations'];
		$cart_product_dimension_ls = $cart_product_data['dimension_ls'];
		$cart_product_dimension_ws = $cart_product_data['dimension_ws'];
		$cart_product_dimension_hs = $cart_product_data['dimension_hs'];
		$cart_product_length_units = $cart_product_data['length_units'];
		$cart_product_weights = $cart_product_data['weights'];
		$cart_product_weight_units = $cart_product_data['weight_units'];
		$cart_product_images = $cart_product_data['images'];

		$this->load->model('tool/image');
		$this->load->language('extension/may/advanced_options');

		$data['column_model'] = $this->language->get('text_product_details');

		foreach ($data['products'] as $key => $product) {
			$details = [];
			if (isset($cart_product_models[$product['cart_id']]) && !empty($cart_product_models[$product['cart_id']])) {
				$details[] = $this->language->get('text_model') . ': ' . $cart_product_models[$product['cart_id']];
			} else {
				$details[] = $this->language->get('text_model') . ': ' . $data['products'][$key]['model'];
			}
			if (isset($cart_product_skus[$product['cart_id']]) && !empty($cart_product_skus[$product['cart_id']]) && !$may_advanced_options_config['hide_cc_sku']) {
				$details[] = $this->language->get('text_sku') . ': ' . $cart_product_skus[$product['cart_id']];
			}
			if (isset($cart_product_upcs[$product['cart_id']]) && !empty($cart_product_upcs[$product['cart_id']]) && !$may_advanced_options_config['hide_cc_upc']) {
				$details[] = $this->language->get('text_upc') . ': ' . $cart_product_upcs[$product['cart_id']];
			}
			if (isset($cart_product_eans[$product['cart_id']]) && !empty($cart_product_eans[$product['cart_id']]) && !$may_advanced_options_config['hide_cc_ean']) {
				$details[] = $this->language->get('text_ean') . ': ' . $cart_product_eans[$product['cart_id']];
			}
			if (isset($cart_product_jans[$product['cart_id']]) && !empty($cart_product_jans[$product['cart_id']]) && !$may_advanced_options_config['hide_cc_jan']) {
				$details[] = $this->language->get('text_jan') . ': ' . $cart_product_jans[$product['cart_id']];
			}
			if (isset($cart_product_isbns[$product['cart_id']]) && !empty($cart_product_isbns[$product['cart_id']]) && !$may_advanced_options_config['hide_cc_isbn']) {
				$details[] = $this->language->get('text_isbn') . ': ' . $cart_product_isbns[$product['cart_id']];
			}
			if (isset($cart_product_mpns[$product['cart_id']]) && !empty($cart_product_mpns[$product['cart_id']]) && !$may_advanced_options_config['hide_cc_mpn']) {
				$details[] = $this->language->get('text_mpn') . ': ' . $cart_product_mpns[$product['cart_id']];
			}
			if (isset($cart_product_locations[$product['cart_id']]) && !empty($cart_product_locations[$product['cart_id']]) && !$may_advanced_options_config['hide_cc_location']) {
				$details[] = $this->language->get('text_location') . ': ' . $cart_product_locations[$product['cart_id']];
			}
			if (isset($cart_product_dimension_ls[$product['cart_id']]) && !empty($cart_product_dimension_ls[$product['cart_id']]) && !$may_advanced_options_config['hide_cc_dimension']) {
				$length_unit = isset($cart_product_length_units[$product['cart_id']]) ? $cart_product_length_units[$product['cart_id']] : '';

				$dimension = $cart_product_dimension_ls[$product['cart_id']] . $length_unit;

				if (isset($cart_product_dimension_ws[$product['cart_id']]) && !empty($cart_product_dimension_ws[$product['cart_id']])) {
					$dimension .= ' x ' . $cart_product_dimension_ws[$product['cart_id']] . $length_unit;
				}
				if (isset($cart_product_dimension_hs[$product['cart_id']]) && !empty($cart_product_dimension_hs[$product['cart_id']])) {
					$dimension .= ' x ' . $cart_product_dimension_hs[$product['cart_id']] . $length_unit;
				}
				$details[] = $this->language->get('text_dimension') . ': ' . $dimension;
			}
			if (isset($cart_product_weights[$product['cart_id']]) && !empty($cart_product_weights[$product['cart_id']]) && $cart_product_weights[$product['cart_id']] > 0) {
				$details[] = $this->language->get('text_weight') . ': ' . $cart_product_weights[$product['cart_id']] . (isset($cart_product_weight_units[$product['cart_id']]) ? $cart_product_weight_units[$product['cart_id']] : "");
			}

			$data['products'][$key]['model'] = implode('<br/>', $details);

			if (isset($cart_product_images[$product['cart_id']])) {
				$data['products'][$key]['thumb'] = $this->model_tool_image->resize($cart_product_images[$product['cart_id']], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
			}
		}
	}

	public function vCheckoutCartBefore($route, &$data) {
		if (!$this->config->get('may_advanced_options_status')) {
			return;
		}

		$this->vCommonCartBefore($route, $data);
	}

	public function mCheckoutOrderAddOrderBefore($route, &$args) {
		if (!$this->config->get('may_advanced_options_status')) {
			return;
		}

		$this->load->model('extension/may/advanced_options');
		$this->load->model('catalog/product');
		$this->load->language('extension/may/advanced_options');

		$may_advanced_options_config = array(
			'hide_oi_sku' => $this->config->get('may_advanced_options_hide_oi_sku'),
			'hide_oi_upc' => $this->config->get('may_advanced_options_hide_oi_upc'),
			'hide_oi_ean' => $this->config->get('may_advanced_options_hide_oi_ean'),
			'hide_oi_jan' => $this->config->get('may_advanced_options_hide_oi_jan'),
			'hide_oi_isbn' => $this->config->get('may_advanced_options_hide_oi_isbn'),
			'hide_oi_mpn' => $this->config->get('may_advanced_options_hide_oi_mpn'),
			'hide_oi_location' => $this->config->get('may_advanced_options_hide_oi_location'),
			'hide_oi_dimension' => $this->config->get('may_advanced_options_hide_oi_dimension'),
		);	

		foreach ($args[0]['products'] as $key => $product) {
			$product_option_value_ids = array();
			foreach ($product['option'] as $option) {
				$product_option_value_ids[] = $option['product_option_value_id'];
			}

			$option_value_data = array();
			if ($product_option_value_ids) {
				$option_value_data = $this->model_extension_may_advanced_options->getOrderProductOptionData($product_option_value_ids);
			}

			$details = array();
			if (isset($option_value_data['model']) && !empty($option_value_data['model'])) {
				$details[] = $this->language->get('text_model') . ': ' . $option_value_data['model'];
			} else {
				$details[] = $this->language->get('text_model') . ': ' . $args[0]['products'][$key]['model'];
			}
			if (isset($option_value_data['sku']) && !empty($option_value_data['sku']) && !$may_advanced_options_config['hide_oi_sku']) {
				$details[] = $this->language->get('text_sku') . ': ' . $option_value_data['sku'];
			}
			if (isset($option_value_data['upc']) && !empty($option_value_data['upc']) && !$may_advanced_options_config['hide_oi_upc']) {
				$details[] = $this->language->get('text_upc') . ': ' . $option_value_data['upc'];
			}
			if (isset($option_value_data['ean']) && !empty($option_value_data['ean']) && !$may_advanced_options_config['hide_oi_ean']) {
				$details[] = $this->language->get('text_ean') . ': ' . $option_value_data['ean'];
			}
			if (isset($option_value_data['jan']) && !empty($option_value_data['jan']) && !$may_advanced_options_config['hide_oi_jan']) {
				$details[] = $this->language->get('text_jan') . ': ' . $option_value_data['jan'];
			}
			if (isset($option_value_data['isbn']) && !empty($option_value_data['isbn']) && !$may_advanced_options_config['hide_oi_isbn']) {
				$details[] = $this->language->get('text_isbn') . ': ' . $option_value_data['isbn'];
			}
			if (isset($option_value_data['mpn']) && !empty($option_value_data['mpn']) && !$may_advanced_options_config['hide_oi_mpn']) {
				$details[] = $this->language->get('text_mpn') . ': ' . $option_value_data['mpn'];
			}
			if (isset($option_value_data['location']) && !empty($option_value_data['location']) && !$may_advanced_options_config['hide_oi_location']) {
				$details[] = $this->language->get('text_location') . ': ' . $option_value_data['location'];
			}

			$product_info = $this->model_catalog_product->getProduct($product['product_id']);
			$length_unit = $this->length->getUnit($product_info['length_class_id']);

			if (isset($option_value_data['dimension_l']) && !empty($option_value_data['dimension_l']) && !$may_advanced_options_config['hide_oi_dimension']) {
				$dimension = $option_value_data['dimension_l'] . $length_unit;
				if (isset($option_value_data['dimension_w']) && !empty($option_value_data['dimension_w'])) {
					$dimension .= ' x ' . $option_value_data['dimension_w'] . $length_unit;
				}
				if (isset($option_value_data['dimension_h']) && !empty($option_value_data['dimension_h'])) {
					$dimension .= ' x ' . $option_value_data['dimension_h'] . $length_unit;
				}

				$details[] = $this->language->get('text_dimension') . ': ' . $dimension;
			}

			$args[0]['products'][$key]['model'] = implode('<br/>', $details);
		}
	}

	public function vCheckoutConfirmBefore($route, &$data) {
		if (!$this->config->get('may_advanced_options_status')) {
			return;
		}

		$this->vCommonCartBefore($route, $data);
	}

	public function mCatalogProductGetProductAfter(&$route, &$args, &$output) {
		if (!isset($this->session->data['may_advanced_option_product_' . $args[0]])) {
			$this->session->data['may_advanced_option_product_' . $args[0]] = $output;
		}
	}
}
