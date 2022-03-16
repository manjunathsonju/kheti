<?php
class ControllerApiTest1 extends Controller {
   
public function index(){

    if(($this->customer->login($this->request->post['email'], $this->request->post['password']))){
        $json['message']='login';


    }else{
        $json['message']='login failed';

    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));
}

}
