<?php
class ModelExtensionMaximum extends Model {
    public function getMaximum($product_id) {
      if($this->config->get('module_maximum_status')){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "maximum WHERE product_id = '" . (int)$product_id . "'");
       if(isset($query->row['maximum'])&& $query->row['maximum']){
        return $query->row['maximum'];
       }}
       else{
           return "";
       }
    }

    public function getProductid($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "maximum WHERE product_id = '" . (int)$product_id . "'");
        if(isset($query->row['product_id'])&& $query->row['product_id']){
            return $query->row['product_id'];
        }
        else{
            return "";
        }
    }

}
