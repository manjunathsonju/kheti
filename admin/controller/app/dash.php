<?php
class ControllerAppDash extends Controller {
	public function index() {
		if (isset($this->request->get['user_token']) && isset($this->session->data['user_token']) && ($this->request->get['user_token'] == $this->session->data['user_token'])) {
            $data['location']=(string)$this->request->get['location'];
            $data['location_text']=($data['location']=='ktm')?('Kathmandu'):('pokhara');
            $data['tkn']=(string)$this->request->get['user_token'];
            $data['kathmandu']='https://store.kheti.farm/admin/index.php?route=app/dash&location=ktm&user_token='.$this->request->get['user_token'];
            $data['pokhara']='https://store.kheti.farm/admin/index.php?route=app/dash&location=pkr&user_token='.$this->request->get['user_token'];

            $sql="SELECT * FROM `app_B2C_home` WHERE location='".$data['location']."'";
            $tokn=$this->db->query($sql);
            foreach($tokn->rows as $result){
                $data['components'][] = array(
                    'component_id'   => $result['component_id'],
					'component_name'   => $result['component_name'],
					'status'   => $result['status'],
					'image'   => $result['image'],
					'type'   => $result['type'],
					'href'   => $result['href'],
					'filter'   => $result['filter'],
                    'location'   => $result['location'],

                );
            }


            $sql="SELECT a.*,cd.name FROM `app_categories` a JOIN oc_category c ON (c.category_id=a.category_id) JOIN oc_category_description cd ON (cd.category_id=c.category_id) WHERE a.location='".$data['location']."' AND cd.language_id=1 AND c.status=1";
            $cats=$this->db->query($sql);
            foreach($cats->rows as $result){
                $data['cats'][] = array(
                    'category_id'   => $result['category_id'],
					'status'   => $result['status'],	
                    'name'   => $result['name'],
                );
            }


            $sql="SELECT c.category_id,cd.name FROM oc_category c JOIN oc_category_description cd ON (cd.category_id=c.category_id) JOIN oc_category_to_store cs ON (cs.category_id=c.category_id) WHERE cs.store_id=1 AND c.status=1 AND cd.language_id=1 AND c.category_id NOT IN (SELECT category_id FROM `app_categories` WHERE location='".$data['location']."') ";
            $alcats=$this->db->query($sql);
            foreach($alcats->rows as $result){
                $data['allcats'][] = array(
                    'category_id'   => $result['category_id'],
                    'name'   => $result['name'],
                );
            }

            $sql="SELECT a.*,cd.name FROM `app_vendors` a JOIN oc_category c ON (c.category_id=a.category_id) JOIN oc_category_description cd ON (cd.category_id=c.category_id) WHERE a.location='".$data['location']."' AND cd.language_id=1";
            $vendors=$this->db->query($sql);
            foreach($vendors->rows as $result){
                $data['vendors'][] = array(
                    'category_id'   => $result['category_id'],
					'status'   => $result['status'],	
                    'name'   => $result['name'],
                );
            }

            $sql="SELECT * FROM `app_B2C_home` WHERE location='popup'";
            $pop=$this->db->query($sql);
       
                $data['popup'] = array(
					'component_name'   => $pop->row['component_name'],
					'status'   => $pop->row['status'],
					'image'   => $pop->row['image'],
					'type'   => $pop->row['type'],
					'href'   => $pop->row['href'],
                );

            // var_dump(  $data['popup']);
            // $data['image'] = $tokn->row['image'];
            $this->response->setOutput($this->load->view('app/dash', $data));


        }

    }
}