<?php
class ControllerApiUserlocation extends Controller {
	public function index() {
       
        $loc = $this->db->query("SELECT shipping_zone_id FROM `oc_order` WHERE order_id=(SELECT MAX(order_id) FROM oc_order WHERE customer_id=288 AND shipping_zone_id IS NOT NULL)");
        var_dump(
            $loc
        );
        if((int)$loc->row['shipping_zone_id']==2318){
            // 2315
            var_dump("pkr");
        }else{
            var_dump("krm");

        }
        }
}

// $userid=$this->session->data['user_id'];
// 					$loc = $this->db->query("SELECT shipping_zone_id FROM `oc_order` WHERE order_id=(SELECT MAX(order_id) FROM oc_order WHERE customer_id='.$userid.' AND shipping_zone_id IS NOT NULL)");
// 					if((int)$loc->row['shipping_zone_id']==2318){
// 						// 2315 ktm
// 						$data['userlocation'] ="pkr";
// 						setCookie('locationCookie', 'pkr');
// 					}else{
// 						$data['userlocation'] ="ktm";
// 						setCookie('locationCookie', 'ktm');

			
// 					}