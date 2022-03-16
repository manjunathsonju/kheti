<?php
class ControllerApiGetCategories extends controller{
    public function index(){
        if($_GET['secret'] == "U7sw3jr69r13"){    

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '" .  $_GET['store_id'] . "' AND cd.language_id = '" . '1' . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

        // var_dump($query );
        foreach($query->rows as $result){
            $categoryarray['category'][]=array(
                'category_id'=> $result['category_id'],
                'name'=> $result['name'],
                'image'=>$result['image'],
                'parent_id'=> $result['parent_id'],
                'top'=> $result['top'],
                'column'=> $result['column'],
                'sort_order'=> $result['sort_order'],
                'status'=> $result['status'],
                'language_id'=> $result['language_id'],
                'description'=> $result['description'],
                'meta_title'=> $result['meta_title'],
                'meta_description'=> $result['meta_description'],
                'meta_keyword'=> $result['meta_keyword'],
                
            );
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($categoryarray));
        }
    }
}