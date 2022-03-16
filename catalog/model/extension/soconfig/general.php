<?php
class ModelExtensionSoconfigGeneral extends Model {
	
    public function getDateEnd($product_id) {
        $query = $this->db->query("SELECT date_end, date_start FROM ".DB_PREFIX."product_special WHERE product_id='".filter_var($product_id, FILTER_SANITIZE_NUMBER_INT)."' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ");
		
        if ($query->num_rows) {
			$td=date("Y-m-d");
			$today = DateTime::createFromFormat("Y-m-d",$td);
			$check=false;
			foreach($query->rows as $date){
				$start_date=DateTime::createFromFormat("Y-m-d", $date['date_start']);
				$end_date=DateTime::createFromFormat("Y-m-d",$date['date_end']);
				if(($start_date <= $today) && ($end_date >= $today)){
					$special_date = $date['date_end'];
					$check=true;
				} 				
			}
			if(!$check) $special_date=false;        
        } else {
            $special_date = false;
        }
        return $special_date;
    }
	
    public function getUnitsSold($product_id) {
        $query = $this->db->query("SELECT SUM(op.quantity) AS total FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) WHERE o.order_status_id > '0' AND op.product_id = '" . (int)$product_id . "'"); 

        if ($query->row and $query->row['total'] !=null) {
            return $query->row['total'];
        } else {
            return FALSE;
        }
    } 

    public function getCategoryId($category_href) {
        if (isset($category_href)) {
            $parts = explode('=', (string)$category_href);
        } else {
            $parts = array();
        }
        $category_id = end($parts);

        if (is_numeric($category_id)) {
            $category_id = $category_id;
        } else {
            $parts = explode('/', (string)$category_id);
            $query = $this->db->query("SELECT query as query FROM ".DB_PREFIX."url_alias WHERE keyword='".$this->db->escape($parts)."'");

            if ($query->num_rows) {
                //$parts = explode('=', (string)$query->row['query']);
                $parts = explode('=', $query->row['query']);
                $category_id = end($parts);
            }

        }
        return $category_id;
    }

    public function getCategoryOption($category_id, $option) {
        $column_exists_option = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "category_description LIKE '".$option."' ");
        if ($column_exists_option->num_rows) {
            $query = $this->db->query("SELECT DISTINCT `".$option."` FROM " . DB_PREFIX . "category_description cd WHERE cd.category_id = '" . (int)$category_id . "' ");
            if ($query->rows) {
                $category_option = $query->row["$option"];
            } else {
                $category_option = '';
            }
        } else {
            $category_option = '';
        }
        return $category_option;
    }

    public function getProductOption($product_id, $option) {
        $column_exists_option = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_description LIKE '".$option."' ");
        if ($column_exists_option->num_rows) {
            $query = $this->db->query("SELECT DISTINCT `".$option."` FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ");
            if ($query->rows) {
                $custom1 = $query->row[$option];
                if ($option == 'html_product_right' || $option == 'html_product_tab'){
                    $custom = html_entity_decode($custom1, ENT_QUOTES, 'UTF-8');
                } else {
                    $custom = $custom1;
                }
            } else {
                $custom = '';
            }
        } else {
            $custom = '';
        }

        return $custom;
    }

    public function getCategoryName($category_id) {
        $query = $this->db->query("SELECT DISTINCT `name` FROM " . DB_PREFIX . "category_description cd WHERE cd.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ");

        if ($query->rows) {
            $category_name = $query->row["name"];
        } else {
            $category_name = '';
        }
        return $category_name;

    }

    public function getModuleSettings($module_code) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE code = '" . $module_code . "'");

        if ($query->row) {
            return unserialize($query->row['setting']);
        } else {
            return array();
        }
    }

    public function getSeoUrl($product_id) {

        $query = $this->db->query("SELECT keyword as keyword FROM ".DB_PREFIX."url_alias WHERE query='product_id=".$product_id."' ");

        if ($query->num_rows) {
            $product_link = '/'.$query->row['keyword'];
        } else {
            $product_link = 'index.php?route=product/product&product_id='.$product_id;
        }
        return $product_link;
    }


}