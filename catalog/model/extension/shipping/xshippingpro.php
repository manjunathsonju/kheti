<?php 
class ModelExtensionShippingXshippingpro extends Model {
    public function __construct($registry) {
        parent::__construct($registry);
        $this->registry = $registry;
        $this->ocm = ($ocm = $this->registry->get('ocm_front')) ? $ocm : new OCM\Front($this->registry);
        $this->mtype = 'shipping';
        $this->ext_path = 'extension/shipping/xshippingpro';
    }
    function getQuote($address) {
        $this->load->language($this->ext_path);
        $language_id = $this->config->get('config_language_id');

        $address = $this->_replenishAddress($address);
        $compare_with = $this->_getCommonParams($address);
        $only_address_rule = isset($address['only_address_rule']) ? true : false;

        $method_data = array();
        $quote_data = array();
        $sort_data = array(); 

        $xshippingpro_heading = $this->ocm->getConfig('xshippingpro_heading', $this->mtype);
        $xshippingpro_debug = $this->ocm->getConfig('xshippingpro_debug', $this->mtype);
        $xshippingpro_sorting = $this->ocm->getConfig('xshippingpro_sorting', $this->mtype);
        $xshippingpro_sorting = ($xshippingpro_sorting)?(int)$xshippingpro_sorting:1;

        $currency_code = isset($this->session->data['currency']) ? $this->session->data['currency'] : $this->config->get('config_currency');

        $_vweight_cache = array();
        $debugging = array();
        $method_level_group = false;
       
        $xshippings = $this->getShippings();

        $xmethods = $xshippings['xmethods'];
        $xmeta = $xshippings['xmeta'];

        $geo_ids = array();
        if ($xmeta['geo']) {
            $geo_rows = $this->db->query("SELECT geo_zone_id FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')")->rows; 
            foreach ($geo_rows as $geo_row) {
                $geo_ids[] = $geo_row['geo_zone_id'];
            }
        }

        $cart_products = $this->cart->getProducts();
        $_cart_data =  $this->getProductProfile($cart_products, $xmeta);
        $compare_with['geo'] = $geo_ids;
    
        foreach($xmethods as $xshippingpro) {
            $rules = $xshippingpro['rules'];
            $rates = $xshippingpro['rates'];
            $tab_id = $xshippingpro['tab_id'];
            $debugging_message = array();

            $alive_or_dead = $this->_crucify($rules, $compare_with, false);
            if (!$alive_or_dead['status']) {
                $debugging_message = $alive_or_dead['debugging'];
                $debugging[] = array('name' => $xshippingpro['display'],'filter' => $debugging_message,'index' => $tab_id);
            } else {
                $status = true;
                $cost = 0;
                $percent_of = $_cart_data['total'];
                if ($rates['type'] == 'flat') {
                    $cost = $rates['percent'] ? ($rates['value'] * $percent_of) : $rates['value'];
                }
                else {
                    $target_value = $_cart_data[$rates['type']];
                    $price_result = $this->getPrice($rates, $target_value, $percent_of);
                    if (!$price_result['status']) {
                        $debugging_message[]='Shipping By - '.$rates['type'].' ('.$target_value.')';
                        $status = false;
                    } else {
                        $cost = $price_result['cost'];
                    }
                }

                if (!$status) {
                    $debugging[] = array('name' => $xshippingpro['display'],'filter' => $debugging_message,'index' => $tab_id);
                }

               if ($status) { 
                    $quote_data['xshippingpro'.$tab_id] = array(
                        'code'         => 'xshippingpro'.'.xshippingpro'.$tab_id,
                        'title'        => $xshippingpro['name'][$language_id],
                        'display'      => $xshippingpro['display'],
                        'cost'         => $cost,
                        'sort_order'   => $xshippingpro['sort_order'],
                        'tax_class_id' => $xshippingpro['tax_class_id'],
                        'text'         => $this->currency->format($this->tax->calculate($cost, $xshippingpro['tax_class_id'], $this->config->get('config_tax')),$currency_code)
                    );
                }
            } 
        }

        /*Sorting final methods */
        $sort_order = array();
        foreach ($quote_data as $key => $value) {
           $sort_order[$key] = $value['sort_order'];
        }
        array_multisort($sort_order, SORT_ASC, $quote_data);
        $xshippingpro_heading = isset($xshippingpro_heading[$language_id])?$xshippingpro_heading[$language_id] : '';
        $method_data = array(
            'code'       => 'xshippingpro',
            'title'      => $xshippingpro_heading,
            'quote'      => $quote_data,
            'sort_order' => $this->ocm->getConfig('xshippingpro_sort_order', $this->mtype),
            'error'      => false
        );
        if ($xshippingpro_debug) {
            $this->ocm->writeLog($debugging, 'xshippingpro');
        }
        return $quote_data ? $method_data : array();
    }

    private function getPrice($rates, $target_value, $percent_of) {
        $ranges = $rates['ranges'];
        $status = false;
        $cost = 0;
        $block = 0;
        $end = 0;
        $cumulative = 0;
        $target_value = round($target_value, 3);
        foreach($ranges as $range) {
            $start = $range['start'];
            $end = $range['end'];
            if ($start && !$end) {
                $end = PHP_INT_MAX;
            }
            $cost = $range['percent'] ? ($range['value'] * $percent_of) : $range['value'];
            if ($start <= $target_value && $target_value <= $end) {
                $status = true; 
                $end = $target_value;
            }
            $block = $range['block'];
            $partial = $range['partial'];
            if ($block > 0) {
                /* round to complete block for iteration purpose. 
                  For negetive value, round to previous round and for positive value round to next round.
                */
                if (!$partial) {
                    if(is_float($end) && fmod($end, $block) != 0) {
                        $end = $cost < 0 ? ($end - fmod($end, $block)) : ($end - fmod($end, $block)) + $block;
                    }
                    else if($block >= 1 && ($end % $block) != 0) {
                       $end =  $cost < 0 ? ($end - ($end % $block)) : ($end - ($end % $block)) + $block; 
                    }
                }
                $no_of_blocks = 0;
                if ($start == 0 && !$partial) {
                    $start = 1;
                }
                while($start <= $end) {
                    if ($partial) {
                        $no_of_blocks =  ($end-$start) >= $block ? ($no_of_blocks + 1) : ($no_of_blocks + ($end - $start) / $block);
                    } else {
                        $no_of_blocks++;
                    }
                    $start += $block;
                }
                $cost = ($no_of_blocks * $cost);
            }
            $cumulative += $cost;
            if ($status) break;
        }

        return array(
            'cost' => $status ? $cost : 0,
            'cumulative' => $cumulative,
            'status' => $status
        );
    }

    private function getProductProfile($cart_products, $xmeta) {
            $cart_categories = array();
            $cart_product_ids = array();
            $cart_manufacturers = array();
            $cart_options = array();
            $cart_locations = array();
            $cart_volume = 0;
            $cart_quantity = 0;
            $cart_weight = 0;
            $cart_sub = 0;
            $cart_total = 0;
            $cart_ean = 0;
            $cart_jan = 0;
            $multi_category = false;
            $tax_data = array();

            foreach($cart_products as $inc=>$product) {
                $cart_product_ids[] = $product['product_id']; 
                $cart_products[$inc]['product'] = $product['product_id']; /* Use same key for all places */
                $tax_class_id = isset($product['tax_class_id']) ? $product['tax_class_id'] : 0;
                $total_with_tax = $this->tax->calculate($product['price'], $tax_class_id, $this->config->get('config_tax')) * $product['quantity'];

                $weight_class_id = $product['weight_class_id'] ? $product['weight_class_id'] : $this->config->get('config_weight_class_id');
                $weight = $this->weight->convert($product['weight'], $weight_class_id, $this->config->get('config_weight_class_id'));

                $cart_products[$inc]['category'] = array();

                if ($xmeta['category_query']) {
                    $product_categories = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product['product_id'] . "'")->rows;
                    if ($product_categories) {
                        if (count($product_categories)>1) $multi_category = true;
                        foreach($product_categories as $category) {
                            $cart_categories[]=$category['category_id'];  
                            $cart_products[$inc]['category'][]=$category['category_id']; //store for future use 
                        } 
                    }
                }

                $length_class_id = $product['length_class_id'] ? $product['length_class_id'] : $this->config->get('config_length_class_id');
                $length = $this->length->convert($product['length'], $length_class_id, $this->config->get('config_length_class_id'));
                $width = $this->length->convert($product['width'], $length_class_id, $this->config->get('config_length_class_id'));
                $height = $this->length->convert($product['height'], $length_class_id, $this->config->get('config_length_class_id'));

                $volume = ($width * $height * $length);
                $cart_volume += ($volume * $product['quantity']);
                $cart_quantity += $product['quantity'];
                $cart_sub += $product['total'];
                $cart_total += $total_with_tax;
                $cart_weight += $weight;

                $cart_products[$inc]['length'] = $product['length'] * $product['quantity'];
                $cart_products[$inc]['width'] = $product['width'] * $product['quantity'];
                $cart_products[$inc]['height'] = $product['height'] * $product['quantity'];
                $cart_products[$inc]['total_with_tax'] = $total_with_tax;
                $cart_products[$inc]['volume'] = $volume * $product['quantity'];
                $cart_products[$inc]['weight'] = $weight;
                $cart_products[$inc]['length_self'] = $length;
                $cart_products[$inc]['width_self'] = $width;
                $cart_products[$inc]['height_self'] = $height;
                $cart_products[$inc]['volume_self'] = $volume; 
                $cart_products[$inc]['weight_self'] = ($weight / $product['quantity']);
                $cart_products[$inc]['price_self'] = $product['price'];

                if ($xmeta['product_query']) {
                    $product_info = $this->db->query("SELECT manufacturer_id, location, jan, ean FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product['product_id'] . "'")->row;
                    if ($product_info){
                        $cart_ean += (float)$product_info['ean'] * $product['quantity'];
                        $cart_jan += (float)$product_info['jan'] * $product['quantity'];

                        $cart_manufacturers[] = $product_info['manufacturer_id'];
                        $cart_products[$inc]['manufacturer'] = $product_info['manufacturer_id']; //store for future use
                        $location = trim(strtolower($product_info['location']));
                        if ($location) {
                            $cart_products[$inc]['location'] = $location; //store for future use
                            $cart_locations[] = $location;
                        }
                    }
                }
                
                $cart_products[$inc]['option'] = array();
                if (isset($product['option']) && $product['option'] && is_array($product['option'])) {
                    foreach($product['option'] as $option) {
                        if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox') {
                            $cart_options[] = $option['option_value_id'];  
                            $cart_products[$inc]['option'][] = $option['option_value_id']; //store for future use 
                        }
                    }
                }

                /* Tax Data */
                if ($product['tax_class_id']) {
                    $tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);
                    foreach ($tax_rates as $tax_rate) {
                        if (!isset($tax_data[$tax_rate['tax_rate_id']])) $tax_data[$tax_rate['tax_rate_id']] = 0;
                        $tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
                    }
                }
            } 

            $cart_categories = array_unique($cart_categories);
            $cart_product_ids = array_unique($cart_product_ids);
            $cart_manufacturers = array_unique($cart_manufacturers);
            $cart_options = array_unique($cart_options);
            $cart_locations = array_unique($cart_locations);

            return array(
                'products' => $cart_products,
                'category' => $cart_categories,
                'product' => $cart_product_ids,
                'manufacturer' => $cart_manufacturers,
                'option' => $cart_options,
                'location' => $cart_locations,
                'volume' => $cart_volume,
                'multi_category' => $multi_category,
                'no_category' => count($cart_categories),
                'no_manufacturer' => count($cart_manufacturers),
                'no_location' => count($cart_locations),
                'quantity' => $cart_quantity,
                'weight' => $cart_weight,
                'total' => $cart_total,
                'sub' => $cart_sub,
                'grand' => $cart_total,
                'grand_shipping' => $cart_total,
                'jan' => $cart_jan,
                'ean' => $cart_ean,
                'coupon' => 0,
                'reward' => 0,
                'distance' => 0,
                'tax_data' => $tax_data,
                'xfeepro' => array()
            );
    }

    private function getShippings() {
        $xshippingpro = $this->cache->get('ocm.xshippingpro');
        if (!$xshippingpro) {
            $language_id = $this->config->get('config_language_id');
            $xmethods = array();
            $xmeta = array(
                'grand' => false,
                'coupon' => false,
                'geo' => false,
                'category_query' => false,
                'product_query' => false,
                'payment_rule' => false,
                'distance' => false
            );
            $xshippingpro_rows = $this->db->query("SELECT * FROM `" . DB_PREFIX . "xshippingpro` order by `sort_order` asc")->rows;
            foreach($xshippingpro_rows as $xshippingpro_row) {
                $method_data = $xshippingpro_row['method_data'];
                $method_data = json_decode($method_data, true);
                /* cache only valid shipping */
                if ($method_data && is_array($method_data) && $method_data['status']) {
                    $method_data =  $this->_resetEmptyRule($method_data);
                    $rules = $this->_findValidRules($method_data);
                    $rates = $this->_findRawRate($method_data);
                    
                    if ($method_data['geo_zone_all'] != 1) {
                        $xmeta['geo'] = true;
                    }
                    $have_product_specified = false;
                    $xmethods[] = array(
                       'tab_id' => (int)$xshippingpro_row['tab_id'],
                       'name' => $method_data['name'],
                       'display' => $method_data['display'],
                       'rules' => $rules,
                       'rates' => $rates,
                       'tax_class_id' => (int)$method_data['tax_class_id'],
                       'sort_order' => (int)$method_data['sort_order']
                    );
                }
            }
            $xshippingpro = array('xmeta' => $xmeta, 'xmethods' => $xmethods);
            $this->cache->set('ocm.xshippingpro', $xshippingpro);
        }
        return $xshippingpro;
   }

    private function _resetEmptyRule($data) {
        $rules = array(
            'store' => 'store_all',
            'geo_zone' => 'geo_zone_all',
        );
        
        foreach ($rules as $key => $value) {
            if (!isset($data[$value])) {
                $data[$value] = '';
            }
            if (!isset($data[$key]) || !$data[$key]) {
                $data[$value] = 1;
            }
        }

    
        /* reset cost params  */ 
        if (!isset($data['ranges'])) $data['ranges'] = array();
        /* checkboxes */

        /* Reset other */
        if (!isset($data['name']) || !is_array($data['name'])) $data['name']=array();
        if (!isset($data['display']) || !$data['display']) $data['display'] = 'Untitled Method';
        return $data;
    }

    private function _findValidRules($data) {
        $rules = array();
        if ($data['store_all'] != 1) {
            $rules['store'] = array(
                'type' => 'in_array',
                'product_rule' => false,
                'address_rule' => false,
                'value' => $data['store'],
                'compare_with' => 'store_id',
                'false_value' => false
            );
        }
        if ($data['geo_zone_all'] != 1) {
            $rules['geo_zone'] = array(
                'type' => 'intersect',
                'product_rule' => false,
                'address_rule' => true,
                'value' => $data['geo_zone'],
                'compare_with' => 'geo',
                'false_value' => false
            );
        }
        return $rules;
    }
    private function _findRawRate($data) {
        $operators= array('+','-','/','*');
        $rates = array();
        $rates['type'] = $data['rate_type'];
      
        /* Shipping Cost */
        if ($data['rate_type'] == 'flat') {
            $cost = trim($data['cost']);
            if (substr($cost, -1) == '%') {
                $cost = rtrim($cost,'%');
                $rates['percent'] = true;
                $rates['value'] = (float)$cost / 100;
            } else {
                $rates['percent'] = false;
                $rates['value'] = (float)$cost;
            }
        } else {
           $ranges = array();
           foreach($data['ranges'] as $range) {
               $start = (float)$range['start'];
               $end = (float)$range['end'];
               $cost = trim(trim($range['cost']), '-');
               $block = (float)$range['block'];
               $partial = (int)$range['partial'];
               $product_id = (int)$range['product_id'];
               $type = $range['type'];
               if (substr($cost, -1) == '%') {
                    $cost = rtrim($cost,'%');
                    $percent = true;
                    $value = (float)$cost / 100;
                } else {
                    $percent = false;
                    $value = (float)$cost;
                }
                $ranges[] = array('start' => round($start, 3), 'end' => round($end, 3), 'percent' => $percent, 'value' => $value, 'block' => 0, 'partial' => 0);
            }
            $rates['ranges'] = $ranges;
        }
        return $rates;
    }

    private function _crucify($rules, $data, $product_and_or, $ingore_product_rule = false, $only_address_rule = false) {
            $status = true;
            $product_status = false;
            $product_rules = array();
            $debugging = array();

            foreach ($rules as $name => $rule) {
                if ($only_address_rule && !$rule['address_rule']) {
                  continue;
                }
                if ($ingore_product_rule && $rule['product_rule']) {
                  continue;
                }

                $_debug_hint = $rule['compare_with'] !== 'products' ? $data[$rule['compare_with']] : $rule['value'];
                $debug_value = is_array($_debug_hint) ? implode(',', $_debug_hint) : $_debug_hint;

                if ($rule['type'] == 'in_array') {
                    if (in_array($data[$rule['compare_with']], $rule['value']) === (boolean)$rule['false_value']) {
                        $debugging[] = $name . '('.$debug_value.')';
                        $status = false;
                        break;
                    }
                }
                if ($rule['type'] == 'intersect') {
                    if ((boolean)$this->array_intersect_faster($data[$rule['compare_with']], $rule['value']) === (boolean)$rule['false_value']) {
                        $debugging[] = $name . '('.$debug_value.')';
                        $status = false;
                        break;
                    }
                }
                if ($rule['type'] == 'in_between') {
                    if ($data[$rule['compare_with']] < $rule['start'] ||  $data[$rule['compare_with']] > $rule['end']) {
                        $debugging[] = $name . '('.$debug_value.')';
                        $status = false;
                        break;
                    }
                }
                if ($rule['type'] == 'in_array_not_equal') {
                    if ($data[$rule['not_equal_with']] == $rule['not_equal_value'] && in_array($data[$rule['compare_with']], $rule['value']) === (boolean)$rule['false_value']) {
                        $debugging[] = $name . '('.$debug_value.')';
                        $status = false;
                        break;
                    }
                }
                if ($rule['type'] == 'function') {
                    $_return = $this->{$rule['func']}($rule['value'], $data[$rule['compare_with']], $rule['rule_type']);
                    if ($rule['product_rule'] && $product_and_or) {
                        $product_status |= $_return;
                        $product_rules[$name] = $_return;
                    } else {
                        if ($_return === (boolean)$rule['false_value']) {
                            $debugging[] = $name . '('.$debug_value.')';
                            $status = false;
                            break;
                        }
                    }
                }
            }
            /* check or_mode for product rules */
            if ($product_and_or && $product_rules && !$product_status) {
                $status = false;
                foreach ($product_rules as $key => $value) {
                    if (!$value) {
                        $debugging[] = $key;
                    }
                }
            }

            return array(
              'status' => $status,
              'debugging' => $debugging
            );
    }
    private function _replenishAddress($address) {
        if (!isset($address['zone_id'])) $address['zone_id'] = '';
        if (!isset($address['country_id'])) $address['country_id'] = '';
        if (!isset($address['city'])) $address['city'] = '';
        if (!isset($address['postcode'])) $address['postcode'] = '';

        $fields = array('zone_id', 'country_id', 'city', 'postcode');
        /* Xshippingpro estimator */
        if (isset($this->request->post['_xestimator'])) {
            $_xestimator = $this->request->post['_xestimator'];
            foreach ($fields as $field) {
                if (!$address[$field]
                  && isset($_xestimator[$field])
                  && $_xestimator[$field]) {
                     $address[$field] = $_xestimator[$field];
                }
            }
        }

        $sessions = array('shipping_address', 'payment_address');
        foreach ($sessions as $key) {
            foreach ($fields as $field) {
                if (!$address[$field]
                  && isset($this->session->data[$key])
                  && isset($this->session->data[$key][$field])
                  && $this->session->data[$key][$field]) {
                     $address[$field] = $this->session->data[$key][$field];
                }
            }
        }

        /* Still country emptry, set default one */
        if (!$address['country_id']) {
            $address['country_id'] = $this->config->get('config_country_id');
        }
        /* all option has failed for postal and city, lets fetch from address book */
        if (!$address['postcode'] && !$address['city'] && $this->customer->isLogged()) {
            $this->load->model('account/address');
            $customer_address = $this->model_account_address->getAddress($this->customer->getAddressId());
            if ($customer_address) {
                $address['postcode'] = $customer_address['postcode'];
                $address['city'] = $customer_address['city'];
            }
        }
        $address['city'] = strtolower(trim($address['city']));
        $address['postcode'] = strtolower(trim($address['postcode']));
        return $address;
    }

    private function _getCommonParams($address) {
        $param = array();
        if (isset($_POST['customer_group_id']) && $_POST['customer_group_id']) {
            $customer_group_id = $_POST['customer_group_id'];
        }
        elseif (isset($_GET['customer_group_id']) && $_GET['customer_group_id']) {
            $customer_group_id = $_GET['customer_group_id'];
        }
        elseif ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getGroupId();
        } elseif (isset($this->session->data['customer']) && isset($this->session->data['customer']['customer_group_id']) && $this->session->data['customer']['customer_group_id']) {
            $customer_group_id = $this->session->data['customer']['customer_group_id'];     
        } else {
            $customer_group_id = 0;
        }

        $store_id = $this->config->get('config_store_id');
        $store_id = isset($this->request->post['store_id']) ? $this->request->post['store_id'] : $store_id;
        $store_id = isset($this->request->get['store_id']) ? $this->request->get['store_id'] : $store_id;

        $payment_method = isset($this->session->data['payment_method']['code'])?$this->session->data['payment_method']['code']:'';
        if(isset($this->session->data['default']['payment_method']['code'])) $payment_method = $this->session->data['default']['payment_method']['code'];


        /* currency */
        $currency_code = isset($this->session->data['currency']) ? $this->session->data['currency'] : $this->config->get('config_currency');
        $currency_id = $this->currency->getId($currency_code);

        /* Coupon code */
        $coupon_code = '';
        if (isset($this->session->data['default']['coupon']) && $this->session->data['default']['coupon']) {
            $coupon_code = $this->session->data['default']['coupon'];
        }
        if (isset($this->session->data['coupon']) && $this->session->data['coupon']) {
            $coupon_code = $this->session->data['coupon'];
        }
        if ($coupon_code) {
            $coupon_code = strtolower($coupon_code);
        }
        $param['customer_id'] = $this->customer->getId();
        $param['store_id'] = $store_id;
        $param['customer_group_id'] = $customer_group_id;
        $param['payment_method'] = $payment_method;
        $param['coupon_code'] = $coupon_code;
        $param['city'] = $address['city'];
        $param['country_id'] = $address['country_id'];
        $param['zone_id'] = $address['zone_id'];
        $param['postcode'] = $address['postcode'];
        $param['currency_id'] = $currency_id;
        $param['time'] = date('G');
        $param['date'] = date('Y-m-d');
        $param['day'] = date('w');
        return $param;
    }
    private function array_intersect_faster($array1, $array2) {
        $is_found = false;
        foreach ($array1 as $key) {
           if (in_array($key, $array2)) {
                $is_found = true;
                break;
            }
        }
        return $is_found;
    }
    public function getScript() {
        return '';
    }
}