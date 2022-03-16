<?php
class ControllerApiB2bMasterApi extends Controller {
	public function index() {
    
            $categoryarray[]=array(
                'Vegetables'=>'https://khetifood.com/index.php?route=api/getproductsb2b&category_id=240&secret=U7sw3jr69r13&language_id=1',
                'Fruits'=>'https://khetifood.com/index.php?route=api/getproductsb2b&category_id=239&secret=U7sw3jr69r13&language_id=1',
                'Staff Vegetable'=>'https://khetifood.com/index.php?route=api/getproductsb2b&category_id=243&secret=U7sw3jr69r13&language_id=1'
    
              );
              $json['category_array'] = $categoryarray;
              $this->response->addHeader('Content-Type: application/json');
              $this->response->addHeader('Access-Control-Allow-Origin: *');
          $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
          $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
          $this->response->addHeader('Access-Control-Max-Age: 600');
              $this->response->setOutput(json_encode($json));
        }
       
        
    }
}
