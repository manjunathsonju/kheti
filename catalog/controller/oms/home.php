<?php
class ControllerOmsHome extends Controller {
	public function index() {
        if($this->checklogin(array($this->request->get['i'],$this->request->get['t']))){
            // var_dump($this->request->get['i']);
            // var_dump($this->request->get['t']);

            $data['sidebar']='<div class="sidebar_menu">
            <div class="inner__sidebar_menu">
                <ul>
                    <li>
                        <a href="index.php?route=oms/home&r=c&t='.$this->request->get['t'].'&i='.$this->request->get['i'].'" class="active" style="border-bottom: 1px solid #ddd;">
                            <span class="icon"><i class="fas fa-border-all"></i>
                            </span>
                            <span class="list">Categories
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?route=oms/home&r=p&t='.$this->request->get['t'].'&i='.$this->request->get['i'].'" style="border-bottom: 1px solid #ddd;">
                            <span class="icon">
                                <i class="fas fa-chart-pie"> </i>
                            </span>
                            <span class="list">Product</span>
                        </a>
                    </li>
                    <li>
                        <a href="./filter.html" style="border-bottom: 1px solid #ddd;">
                            <span class="icon">
                                <i class="fas fa-address-book">
                                </i>
                            </span>category
                    </li>
                    <li>
                        <a href="#" style="border-bottom: 1px solid #ddd;"> <span class="icon">
                                <i class="fas fa-address-card">
                                </i>
                            </span>
                            <span class="list">About</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" style="border-bottom: 1px solid #ddd;">
                            <span class="icon"> <i class="fab fa-blogger"></i></span>
                            <span class="list">ok</span> </a>
                    </li>
                    <li>
                        <a href="#" style="border-bottom: 1px solid #ddd;">
                            <span class="icon"><i class="fas fa-map-marked-alt"></i></span>
                            <span class="list">ok</span>
                        </a>
                    </li>
                </ul>
                <div class="hamburger">
                    <div class="inner_hamburger">
                        <span class="arrow">
                            <i class="fas fa-long-arrow-alt-left"></i>
                            <i class="fas fa-long-arrow-alt-right"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>';


            $data['customer_id']=(int)$this->request->get['i'];
            if($this->request->get['r']=='h'){
                $this->response->setOutput($this->load->view('oms/home', $data));
            }elseif($this->request->get['r']=='c'){
                $this->response->setOutput($this->load->view('oms/category', $data));

            }elseif($this->request->get['r']=='p'){
                $this->response->setOutput($this->load->view('oms/products', $data));

            }

        }else{
            header("Location: https://khetifood.com/oms/");
            exit();
        }
        
      


    }
    public function checklogin($useridarray) {
        if($useridarray[0]==NULL||$useridarray[1]==NULL){
            return FALSE;
        }
            $sql="SELECT * FROM `b2b_oms_token` WHERE customer_id='".$useridarray[0]."'";
            $hash=$this->db->query($sql);
       
            if($hash->row['token']==$useridarray[1]){
                return TRUE;
            }else{
                return FALSE;
            } 
    }
}