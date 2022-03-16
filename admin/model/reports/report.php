<?php
class ModelReportsReport extends Model {
	public function getsalesreport($Product_id,$date_from,$date_to) {
        $sql="SELECT op.order_id,op.product_id,op.name,op.model,op.quantity,op.price,op.total FROM oc_order o JOIN oc_order_product op ON (op.order_id=o.order_id) WHERE o.order_status_id IN (2,5)";
        if($date_from!=NULL){
            $sql = $sql. " AND o.date_added>'".$date_from."'";
        }

        if($date_to!=NULL){
            $sql = $sql. " AND o.date_added<'".$date_to."'";
        }
        $sql = $sql. " AND op.product_id='".$Product_id."'";
        $query = $this->db->query($sql);
		return $query->rows;

    }

    public function getsalesreportB2B($Product_id,$date_from,$date_to) {
        $sql="SELECT op.product_id,pd.name,p.model,p.sku,o.order_id,op.quantity,op.price,op.total FROM order_products_b2b op JOIN order_b2b o ON (o.order_id=op.order_id) JOIN oc_product_description pd ON (pd.product_id=op.product_id) JOIN oc_product p ON (p.product_id=op.product_id) WHERE o.order_status_id=3 AND pd.language_id=1";

        if($date_from!=NULL){
            $sql = $sql. " AND o.date_added>'".$date_from."'";
        }

        if($date_to!=NULL){
            $sql = $sql. " AND o.date_added<'".$date_to."'";
        }
        $sql = $sql. " AND op.product_id='".$Product_id."'";
        $query = $this->db->query($sql);
		return $query->rows;

    }
}