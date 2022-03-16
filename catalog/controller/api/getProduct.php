<?php
class ControllerApiGetProduct extends Controller
{
    public function index()
    {
        if($_GET['secret'] == "U7sw3jr69r13"){    

        $this->load->language('api/cart');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $product_id= $_GET['product_id'];
        $json = array();
        $result = $this->model_catalog_product->getProduct($_GET['product_id']);
		
        $json['product'] = $result;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
        }
    }
}
