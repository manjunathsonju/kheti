<?php
class ModelExtensionSearchAutocomplete extends Model {
	
	public function getProducts($keyword) 
	{	
		$sql = "SELECT pd.name as product_name, p.image, p.product_id, p.price, p.tax_class_id, (SELECT COUNT(DISTINCT po.option_id) FROM " . DB_PREFIX . "product_option po WHERE po.product_id=p.product_id) AS option_count, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product_description pd JOIN " . DB_PREFIX . "product p on pd.product_id = p.product_id AND pd.name LIKE '%" .$keyword. "%' AND p.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'  LIMIT 4";
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getCategories($keyword) 
	{	
		$sql = "SELECT cd.name as category_name, cd.category_id, c.image FROM " . DB_PREFIX . "category_description cd JOIN " . DB_PREFIX . "category c on cd.category_id = c.category_id AND cd.name LIKE '%" .$keyword. "%' OR cd.description LIKE '%" .$keyword. "%' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status = '1' GROUP BY category_name LIMIT 4";
		$query = $this->db->query($sql);
		return $query->rows;
	}
}
?>