<?php
class ControllerApiKhetifoodhome extends Controller {
    public function index(){  
        if($this->request->post['key']=='!3t3kf0odh0m3'){
		$this->load->model('tool/image');		
		$this->load->model('kheti/products'); 
		$this->load->model('kheti/components'); 

		if(($this->request->post['location'])&&($this->request->post['location']!=NULL)){
			$location=$this->request->post['location'];
		}else{
			$location='ktm';
		}

		$json['components']=array(
			"6"=> array(
			   'component_id' => 1,
			   'component_name'=> 'Notification',
			   'content' => NULL),		
			
			"8"=> array(
				'component_id' => 2,
				'component_name'=> 'Shop by category',
				'content' => $this->model_kheti_products->getcategories($location,1)),

			"9"=> array(
				'component_id' => 3,
				'component_name'=> 'slider',
				'content' => $this->model_kheti_components->index($location,1)),
					
			"10"=> array(
					'component_id' => 4,
					'component_name'=> 'products',
					'content' => array($this->model_kheti_products->categoryjson(172,$location),$this->model_kheti_products->categoryjson(171,$location),$this->model_kheti_products->categoryjson(139,$location),$this->model_kheti_products->categoryjson(257,$location),$this->model_kheti_products->categoryjson(215,$location))),
	
			"11"=> array(
				'component_id' => 8,
				'component_name'=> 'testimonials',
				'content' => $this->model_kheti_components->testimonials()),

			"12"=> array(
				'component_id' => 9,
				'component_name'=> 'Valued Farmers',
				'content' => $this->model_kheti_components->valuedfarmers()),
								
			"13"=> array(
				'component_id' => 10,
				'component_name'=> 'Just For you',
				'content' => $this->model_kheti_products->getprodcuts(217,$location)),											

			"14"=> array(
				'component_id' => 11,
				'component_name'=> 'Shop By Brand',
				'content' => $this->model_kheti_components->vendors($location)),

			"15"=> array(
				'component_id' => 12,
				'component_name'=> 'Best Selling Products',
				'content' => $this->model_kheti_products->getbestselling($location,1)),
			);


		$sql="SELECT * FROM `app_B2C_home` WHERE location='".$location."'";
        $tokn=$this->db->query($sql);
            foreach($tokn->rows as $result){
				if($result['status']=='0'){
					$json['components'][$result['component_id']] = array(
						'component_id' => 7,
						'component_name'=> $result['component_name'],
						'content' => NULL);
				}else{
					$json['components'][$result['component_id']] = array(
						'component_id' => 7,
						'component_name'=> $result['component_name'],
						'content' => array('image'=>$result['image']),'href'=>$result['href'],'type'=>$result['type'],'filter'=>($result['filter']=='0')?(FALSE):(TRUE));
				}
			}
		$json['location']=$location; 
		$this->response->addHeader('Content-Type: application/json');
		$this->response->addHeader('Access-Control-Allow-Origin: *');
		$this->response->addHeader('Access-Control-Allow-Headers: *');
		$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
		$this->response->addHeader('Access-Control-Max-Age: 600');
		$this->response->setOutput(json_encode($json));	
        } 
    }	

	public function popularSearches(){ 
		$this->load->model('kheti/components'); 
		$json['keywords'] =$this->model_kheti_components->popularSearches(1);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->addHeader('Access-Control-Allow-Origin: *');
		$this->response->addHeader('Access-Control-Allow-Headers: *');
		$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
		$this->response->addHeader('Access-Control-Max-Age: 600');
		$this->response->setOutput(json_encode($json));
	}

	public function forgetpassword(){  
		$this->load->language('mail/forgotten');
        $data['text_greeting'] = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$data['text_change'] = $this->language->get('text_change');
		$data['text_ip'] = $this->language->get('text_ip');		
		$data['ip'] = $this->request->server['REMOTE_ADDR'];
		// $this->model_account_customer->editCode($this->request->post['email'], token(40));
		$sql="SELECT * FROM `oc_customer` WHERE email='".$this->db->escape($this->request->post['email'])."'";	
		$customer_info=$this->db->query($sql);

		if($customer_info->row['code']){
			$code=$customer_info->row['code'];
		}else{
			$code= token(40);
			$sql="UPDATE `oc_customer` SET code='".$this->db->escape($code)."' WHERE email='".$this->db->escape($this->request->post['email'])."'";	
			$this->db->query($sql);
			
		}
		$data['reset'] = 'https://khetifood.com/index.php?route=account/reset&code='.$code;
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($this->request->post['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_email'));
		$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'));
		$mail->setText($this->load->view('mail/forgotten', $data));
		$mail->send();
  		$json['success'] = 1;
    	$this->response->addHeader('Content-Type: application/json');
    	$this->response->addHeader('Access-Control-Allow-Origin: *');
    	$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    	$this->response->addHeader('Access-Control-Max-Age: 600');
    	$this->response->setOutput(json_encode($json)); 
    }

	public function findsession($customer_id){
		$sql="SELECT session_id FROM `oc_cart` WHERE cart_id=(SELECT MAX(cart_id) FROM oc_cart WHERE customer_id='".$customer_id."')";
		$session_if=$this->db->query($sql);
			if($session_if->row['session_id']){
				return $session_if->row['session_id'];		
			}else{
				return FALSE;
			}
	}

	public function findorderid($customer_id){
		$sql="SELECT MAX(order_id) AS order_id FROM `oc_order` WHERE customer_id='".$customer_id."' AND order_status_id='0'";
		$order_idif=$this->db->query($sql);
		if($order_idif->row['order_id']){
		return $order_idif->row['order_id'];
		}else{
			return FALSE;
		}
	}

	public function checklogin($customer_id,$token){
		$sql="SELECT * FROM `oc_customer` WHERE customer_id='".$customer_id."'";
					$customer_info=$this->db->query($sql);
					if($customer_info->row['token_app']==$token){
						return TRUE;
					}else{
						return FALSE;
					}
	}

	public function logout(){ 
		$login= $this->checklogin($this->request->post['customer_id'],$this->request->post['token']);
		if($login){

		$sql="UPDATE `oc_customer` SET token_app='' WHERE customer_id='".$this->request->post['customer_id']."'";
		$this->db->query($sql);
		$json['success']='Logout Successful';

	}else{
		$json['error']='Invalid Token';
	}
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));

	}

    public function login(){  
		$sql="SELECT * FROM `oc_customer` WHERE email='".$this->db->escape($this->request->post['email'])."' OR telephone='".$this->request->post['email']."'";	
		$customer_info=$this->db->query($sql);

		if($customer_info->row['email']){
			if(($this->customer->login($customer_info->row['email'], $this->request->post['password']))){
				if($customer_info->row['token_app']!=NULL){
					$json['token']= $customer_info->row['token_app']; 
				}else{
					$token=token(10);
					$json['token']= $token;
					$sql="UPDATE `oc_customer` SET token_app='".$token."' WHERE customer_id='".$customer_info->row['customer_id']."'";
					$this->db->query($sql);
				}
				$sql="INSERT INTO `customer_login_app` (customer_id,ip,device_info,date_added,status) VALUES ('".$customer_info->row['customer_id']."','".$this->request->post['ip']."','".$this->db->escape($this->request->post['device_info'])."',NOW(),'1')";
				$this->db->query($sql);
				$login_id = $this->db->getLastId();
				$json['login_id']=$login_id;
				$json['customer_id']=$customer_info->row['customer_id'];
			
				if(($this->request->post['location'])&&($customer_info->row['location']==NULL)){
					$sql="UPDATE `oc_customer` SET location='".$this->request->post['location']."' WHERE customer_id='".$customer_info->row['customer_id']."'";
					$this->db->query($sql);
					$json['location']= $this->request->post['location'];
				}else{
					$json['location']= $customer_info->row['location'];
				}
				if(($this->request->post['session_id'])&&($this->request->post['session_id']!=NULL)){
					$sessioninformation= $this->findsession($customer_info->row['customer_id']);
					if($sessioninformation){

					$sql="SELECT * FROM `oc_cart` WHERE session_id='".$this->request->post['session_id']."'";
              		$products=$this->db->query($sql);

               		 foreach ($products->rows as $result){
						$sql="SELECT * FROM `oc_cart` WHERE session_id='".$sessioninformation."' AND product_id='".$result['product_id']."'";
						$products_exist=$this->db->query($sql);
						if($products_exist->row['product_id']){
							$sql="UPDATE `oc_cart` SET quantity=quantity+".(int)$result['quantity']." WHERE session_id='".$sessioninformation."' AND product_id='".$result['product_id']."'";
						}else{
							$sql="UPDATE `oc_cart` SET session_id='".$sessioninformation."' WHERE session_id='".$this->request->post['session_id']."'";
						}
						$this->db->query($sql);
					}						
						$json['session_id']= $sessioninformation;
						$json['order_id']= $sessioninformation;


					}else{
					$sql="UPDATE `oc_cart` SET customer_id='".$customer_info->row['customer_id']."' WHERE session_id='".$this->request->post['session_id']."'";
					$this->db->query($sql);
					$json['session_id']= $this->request->post['session_id'];
					$json['order_id']= $this->request->post['session_id'];

					}
					
				}else{
					$sql="SELECT session_id FROM `oc_cart` WHERE cart_id=(SELECT MAX(cart_id) FROM oc_cart WHERE customer_id='".$customer_info->row['customer_id']."')";
					$session_if=$this->db->query($sql);

					if($session_if->row['session_id']){
						$json['session_id']= $session_if->row['session_id'];
						$json['order_id']= $session_if->row['session_id'];

						}else{
							$json['session_id']= NULL;
							$json['order_id']= NULL;
						}
				}
				$json['success']= 1;
			}else{
				$json['error']= 'login failed';
			}  
		}else{
			$json['error']= 'Given user doesnot exist';
		}

	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
    }

    public function register(){  
        if($this->request->post['key']=='!3t3kf00dr3g1ater'){
            if($this->request->post['email'] && $this->request->post['number'] && $this->request->post['firstname'] && $this->request->post['lastname'] && $this->request->post['password']){
                $this->load->model('account/customer');
                $customer_id_email = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
                $customer_id_num = $this->model_account_customer->getCustomerByPhone($this->request->post['number']);
                 // register
                 if($customer_id_email== NULL && $customer_id_num== NULL){
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . '1' . "', store_id = '" . '1' . "', language_id = '" . '1' . "', firstname = '" . $this->db->escape($this->request->post['firstname']) . "', lastname = '" . $this->db->escape($this->request->post['lastname'])  . "', email = '" . $this->db->escape($this->request->post['email']) . "', telephone = '" . $this->request->post['number'] . "', custom_field = '" .''. "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($this->request->post['password'])))) . "', newsletter = '" . '1' . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '" . '1' . "', code = '" . $this->db->escape($salt = token(40)) . "', date_added = NOW()");
    
                $sql="SELECT * FROM `oc_customer` WHERE email='".$this->db->escape($this->request->post['email'])."'";
                $customer_info=$this->db->query($sql);
                $token=token(10);
                $json['token']= $token;
                $sql="UPDATE `oc_customer` SET token_app='".$token."' WHERE customer_id='".$customer_info->row['customer_id']."'";
                $this->db->query($sql);

				if($this->request->post['location']){
					$sql="UPDATE `oc_customer` SET location='".$this->request->post['location']."' WHERE customer_id='".$customer_info->row['customer_id']."'";
					$this->db->query($sql);
				}

				if(($this->request->post['session_id'])&&($this->request->post['session_id']!=NULL)){
					
					$sql="UPDATE `oc_cart` SET customer_id='".$customer_info->row['customer_id']."' WHERE session_id='".$this->request->post['session_id']."'";
					$this->db->query($sql);
					$json['session_id']= $this->request->post['session_id'];

				}
                $json['customer_id']=$customer_info->row['customer_id'];
                $json['success']= 1;

                 }else{
                    $json['error']= 'Email address or phone number already used';
                 }
            }else{
                $json['error']= 'Some fields missing';
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }

 
    public function addtocart(){  
        if($this->request->post['key']=='@d!3t3kf0dc@rt'){

			$this->load->model('kheti/products'); 

            $sql="SELECT * FROM `oc_cart` WHERE session_id='".$this->request->post['session_id']."'";
            $session_status=$this->db->query($sql);

			if(($this->request->post['option_id'])&&($this->request->post['option_id']!=NULL)){
				$option_id=$this->request->post['option_id'];
			}else{
				$option_id=0;
			}

			if($this->request->post['customer_id']){
				$customer_id=$this->request->post['customer_id'];
			}else{
				$customer_id=0;
			}
			
			if(($this->request->post['customer_id'])&&($this->request->post['customer_id']!=NULL)){
			$sessioninformation= $this->findsession($this->request->post['customer_id']);
					if($sessioninformation){
						$session_id=$sessioninformation;
					}else{
						$session_id=token(26);
					}
				}else{
					$session_id=$this->request->post['session_id'];
				}
					

			if(($this->request->post['option_id'])&&($this->request->post['option_id']!=NULL)&&($this->request->post['option_id']!=0)){
				$sql="SELECT * FROM `oc_cart` WHERE product_id='".$this->request->post['product_id']."' AND session_id='".$session_id."' AND `option_id`='".$this->request->post['option_id']."'";
			}else{
				$sql="SELECT * FROM `oc_cart` WHERE product_id='".$this->request->post['product_id']."' AND session_id='".$session_id."'";
			}
			$prodcut_exist=$this->db->query($sql);

            if(($this->request->post['session_id'])&&($session_status->row['session_id'])&&($this->request->post['session_id']!=NULL)){
               
                if($prodcut_exist->row['product_id']==$this->request->post['product_id']){ //if already in cart
					$this->model_kheti_products->updatecart($this->request->post['quantity'],$this->request->post['product_id'],$this->request->post['session_id'],$option_id);
                    $cart_id=$prodcut_exist->row['cart_id'];

				}else{ // if not in cart
					if($this->request->post['option_id']&&$this->request->post['option_id']!=NULL&&$this->request->post['option_id']!=''&&$this->request->post['option_id']!='0'){
						$optiona=$this->db->query("SELECT * FROM `product_options` WHERE option_id='".$this->request->post['option_id']."'");
						$optx=$optiona->row['options'];
					}else{
						$optx='[]';
					}
					$cart_id = $this->model_kheti_products->addtocart($customer_id,$session_status->row['session_id'],$this->request->post['product_id'],$optx,$this->request->post['option_id'],$this->request->post['quantity']);
					
                }
                $json['session_id']= $this->request->post['session_id'];
            }elseif(($this->request->post['customer_id'])&&($this->request->post['customer_id']!=NULL)){
					$sessioninformation= $this->findsession($this->request->post['customer_id']);
					if($sessioninformation){
						$session_id=$sessioninformation;
					}else{
						$session_id=token(26);
					}
					
					if($prodcut_exist->row['product_id']==$this->request->post['product_id']){	
						$this->model_kheti_products->updatecart($this->request->post['quantity'],$this->request->post['product_id'],$session_id,$option_id);
						$cart_id=$prodcut_exist->row['cart_id'];	
					}else{						
						$customer_id=$this->request->post['customer_id'];
						if($this->request->post['option_id']&&$this->request->post['option_id']!=NULL&&$this->request->post['option_id']!=''&&$this->request->post['option_id']!='0'){
							$optiona=$this->db->query("SELECT * FROM `product_options` WHERE option_id='".$this->request->post['option_id']."'");
							$optx=$optiona->row['options'];
						}else{
							$optx='[]';
						}
						$cart_id = $this->model_kheti_products->addtocart($customer_id,$session_id,$this->request->post['product_id'],$optx,$this->request->post['option_id'],$this->request->post['quantity']);
					}
					$json['session_id']= $session_id;

			}else{
                // create new token .this is for if no token is matched
                $token=token(26);
				if($this->request->post['option_id']&&$this->request->post['option_id']!=NULL&&$this->request->post['option_id']!=''&&$this->request->post['option_id']!='0'){
					$optiona=$this->db->query("SELECT * FROM `product_options` WHERE option_id='".$this->request->post['option_id']."'");
					$optx=$optiona->row['options'];
				}else{
					$optx='[]';
				}
				$cart_id = $this->model_kheti_products->addtocart($this->request->post['customer_id'],$token,$this->request->post['product_id'],$optx,$option_id,$this->request->post['quantity']);
                $json['session_id']= $token;
            }
			$json['success']='Added to your cart';
			$json['cart_id']=$cart_id;
			$json['product_id']=$this->request->post['product_id'];
			$json['quantity']=$this->request->post['quantity'];
			$json['option_id']=(string)$option_id;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }

	public function checkcartproducts(){  
		if($this->request->post['session_id']){
			if($this->request->post['option_id']){
				$sql="SELECT cart_id,quantity,product_id FROM `oc_cart` WHERE session_id='".$this->request->post['session_id']."' AND product_id='".$this->request->post['product_id']."' AND `option`='".$this->request->post['option_id']."'";
			}else{
				$sql="SELECT cart_id,quantity,product_id FROM `oc_cart` WHERE session_id='".$this->request->post['session_id']."' AND product_id='".$this->request->post['product_id']."'";
			}
		}elseif($this->request->post['order_id']){
			if($this->request->post['option_id']){
				$sql="SELECT op.order_product_id AS cart_id,op.quantity,op.product_id FROM `oc_order_product` op JOIN oc_order_option opp ON (op.order_product_id=opp.order_product_id) WHERE op.order_id='".$this->request->post['order_id']."' AND op.product_id='".$this->request->post['product_id']."' AND opp.type='".$this->request->post['option_id']."'";
			}else{
				$sql="SELECT order_product_id AS cart_id,quantity,product_id FROM `oc_order_product` WHERE order_id='".$this->request->post['order_id']."' AND product_id='".$this->request->post['product_id']."'";
			}
		}
		if($this->request->post['option_id']){
			$option_id=$this->request->post['option_id'];
		}else{
			$option_id=0;
		}
		$product=$this->db->query($sql);
		if($product->row['product_id']){
				$json['cart_product'] = array(
					'cart_id' => $product->row['cart_id'],
					'product_id' => $this->request->post['product_id'],
					'quantity' => $product->row['quantity'],
					'option_id' => $option_id,
				);
		}else{
			$json['cart_product']=NULL;
		}
		$this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));	        
	}
    public function getcartproducts(){  
        if($this->request->post['key']=='g3t!3t3kf0dc@rt'){
			$json['shipping_charge']=70;
			$json['checkout_limit']=500;
            if(($this->request->post['session_id']==NULL)||(!$this->request->post['session_id'])){
				$session= $this->findsession($this->request->post['customer_id']);				
			}else{
				$session= $this->request->post['session_id'];
			}
            if(($session)&&($session!=NULL)){
                $sql="SELECT c.* FROM `oc_cart` c JOIN oc_product p ON (p.product_id=c.product_id) JOIN oc_product_to_store ps ON (ps.product_id=p.product_id) WHERE p.status=1 AND c.session_id='".$session."' AND ps.store_id=1";
                $products=$this->db->query($sql);
				$subtotal=0;

                foreach ($products->rows as $result){
					if(($result['option']!='[]')&&($result['option']!=NULL)&&($result['option_id']==0)){
						$sql="SELECT * FROM `product_options` WHERE options='".$result['option']."'";
						$optionx=$this->db->query($sql);
						$sql="UPDATE `oc_cart` SET option_id='".$optionx->row['option_id']."' WHERE cart_id='".$result['cart_id']."'";
						$this->db->query($sql);
						$op=$optionx->row['option_id'];
					}else{
						$op=$result['option_id'];
					}
					$total=0;
					$this->load->model('catalog/product');
					$product_info = $this->model_catalog_product->getProduct($result['product_id']);					
					if(($result['option']!='[]')&&($result['option']!=NULL)){
						$sql="SELECT * FROM `product_options` WHERE option_id='".$op."'";						
						$option_name=$this->db->query($sql);
						$price=$option_name->row['price'];
						$option=$option_name->row['option_name'];
						}else{
							if($product_info['special']!=NULL&&$product_info['special']!=FALSE||$product_info['special']!=''){
								$price=$product_info['special'];
							}else{
								$price=$product_info['price'];

							}
							$option=NULL;
						}

					$total= (float)$price * (float) $result['quantity'];
					$subtotal=$subtotal+$total;
                    $image="https://khetifood.com/image/".$product_info['image'];			

					if($product_info['quantity']=='0'){
						$stock_status="Out Of Stock";

					}else{
						$stock_status=$product_info['stock_status'];

					}
					//check for maximum

					$sql="SELECT * FROM `oc_maximum` WHERE product_id='".$result['product_id']."'";
					$maximum=$this->db->query($sql);
					if($maximum->row['maximum']!='0'&&(int) $maximum->row['maximum']<(int) $result['quantity']){
						$stock_status="Out Of Stock";

					}
                    $cart_products[]=array(
                        'cart_id'=>$result['cart_id'],
                        'product_id'=>$result['product_id'],
                        'name'=>$product_info['name'],
						'option_id'=>$op,
						'sku'=>$product_info['sku'],
						'option'=>$option,
                        'image'=>$image,
						'price'=>strval((float)$price),
                        'quantity'=>$result['quantity'],
						'stock_status'=>$stock_status,
						'product_total'=>$total,
						'location'=>$product_info['location'],
                    );                
                }
				$json['sub-total']=$subtotal;
				// $json['Shipping Charge']=0;
                $json['products']=$cart_products;
                $json['session_id']=$session;
				$this->load->model('kheti/products'); 
				if($this->request->post['location']){
					$location=$this->request->post['location'];
				}else{
					$location='ktm';
				}
				$json['recommended_products']=$this->model_kheti_products->getprodcuts(217,$location);
				$json['banner']= array('component_name'=>'Dashain Dhamaka','image'=>'https://khetifood.com/image/test/pay.jpg','href'=>'','type'=>'');
				// $json['banner']= null;


			}else{
                $json['error']='No Products in your cart';
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
    }
    public function editcartproducts(){  
        if($this->request->post['key']=='3!t!3t3kf0dc@rt'){
               
            $sql="SELECT * FROM `oc_cart` WHERE session_id='".$this->request->post['session_id']."'";
            $session_status=$this->db->query($sql);

            if(($this->request->post['session_id'])&&($session_status->row['session_id'])&&($this->request->post['session_id']!=NULL)){
                $sql="UPDATE `oc_cart` SET quantity=".(int)$this->request->post['quantity']." WHERE session_id='".$this->request->post['session_id']."' AND product_id='".$this->request->post['product_id']."' AND cart_id='".$this->request->post['cart_id']."'";
                $this->db->query($sql);
                $json['success']='Done';
               
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']); 
				$sql="SELECT * FROM `oc_cart` WHERE product_id='".$this->request->post['product_id']."' AND session_id='".$this->request->post['session_id']."' AND cart_id='".$this->request->post['cart_id']."'";
                $prodcut_exist=$this->db->query($sql);
				if($prodcut_exist->row['option']!=0||$prodcut_exist->row['option']!='[]'){
					$sql="SELECT * FROM `product_options` WHERE option_id='".$prodcut_exist->row['option_id']."'";
					$option_name=$this->db->query($sql);
					$price=$option_name->row['price'];
				}else{
					$price=$product_info['price'];
				}
                $json['product_id']=$this->request->post['product_id'];
                $json['quantity']=$this->request->post['quantity'];
				$json['price']=$price;
				$json['product_total']=$price*(float)$this->request->post['quantity'];
                $json['cart_id']=$this->request->post['cart_id'];
                $json['session_id']= $this->request->post['session_id'];
			}
        }
        $this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));
    
}  
public function removecartproduct(){  
	if($this->request->post['key']=='d3l3t3kf0dc@rt'){
		$sql="SELECT * FROM `oc_cart` WHERE session_id='".$this->request->post['session_id']."'";
		$session_status=$this->db->query($sql);

		if(($this->request->post['session_id'])&&($session_status->row['session_id'])&&($this->request->post['session_id']!=NULL)){
			$sql="DELETE FROM `oc_cart` WHERE session_id='".$this->request->post['session_id']."' AND cart_id='".$this->request->post['cart_id']."'";
			$this->db->query($sql);
			$json['success']='Done';
		}else{
			$json['error']='Invalid session';
		}
	}
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
}
public function search(){   
	if($this->request->post['key']=='S3@rchm3'){            
	$this->load->language('product/search');
	$this->load->model('catalog/category');
	$this->load->model('catalog/product');
	$this->load->model('tool/image');

	if (isset($this->request->post['search'])) {
		$search = $this->request->post['search'];
	} else {
		$search = '';
	}

	if (isset($this->request->get['tag'])) {
		$tag = $this->request->get['tag'];
	} elseif (isset($this->request->post['search'])) {
		$tag = $this->request->post['search'];
	} else {
		$tag = '';
	}

	if (isset($this->request->get['description'])) {
		$description = $this->request->get['description'];
	} else {
		$description = '';
	}

	if (isset($this->request->get['category_id'])) {
		$category_id = $this->request->get['category_id'];
	} else {
		$category_id = 0;
	}

	if (isset($this->request->get['sub_category'])) {
		$sub_category = $this->request->get['sub_category'];
	} else {
		$sub_category = '';
	}

	if (isset($this->request->get['sort'])) {
		$sort = $this->request->get['sort'];
	} else {
		$sort = 'p.sort_order';
	}

	if (isset($this->request->get['order'])) {
		$order = $this->request->get['order'];
	} else {
		$order = 'ASC';
	}

	if (isset($this->request->get['page'])) {
		$page = $this->request->get['page'];
	} else {
		$page = 1;
	}

	if (isset($this->request->get['limit'])) {
		$limit = (int)$this->request->get['limit'];
	} else {
		$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
	}

	if (isset($this->request->post['search'])) {
		$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->post['search']);
	} elseif (isset($this->request->get['tag'])) {
		$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']);
	} else {
		$this->document->setTitle($this->language->get('heading_title'));
	}
	$url = '';

	if (isset($this->request->post['search'])) {
		$url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
	}

	if (isset($this->request->get['tag'])) {
		$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
	}

	if (isset($this->request->get['description'])) {
		$url .= '&description=' . $this->request->get['description'];
	}

	if (isset($this->request->get['category_id'])) {
		$url .= '&category_id=' . $this->request->get['category_id'];
	}

	if (isset($this->request->get['sub_category'])) {
		$url .= '&sub_category=' . $this->request->get['sub_category'];
	}

	if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
	}

	if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
	}

	if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	}

	if (isset($this->request->get['limit'])) {
		$url .= '&limit=' . $this->request->get['limit'];
	}

	$data['breadcrumbs'][] = array(
		'text' => $this->language->get('heading_title'),
		'href' => $this->url->link('product/search', $url)
	);

	if (isset($this->request->post['search'])) {
		$data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->post['search'];
	} else {
		$data['heading_title'] = $this->language->get('heading_title');
	}

	$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

	$data['compare'] = $this->url->link('product/compare');

	$this->load->model('catalog/category');

	// 3 Level Category Search
	$data['categories'] = array();

	$categories_1 = $this->model_catalog_category->getCategories(0);

	foreach ($categories_1 as $category_1) {
		$level_2_data = array();

		$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

		foreach ($categories_2 as $category_2) {
			$level_3_data = array();

			$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

			foreach ($categories_3 as $category_3) {
				$level_3_data[] = array(
					'category_id' => $category_3['category_id'],
					'name'        => $category_3['name'],
				);
			}

			$level_2_data[] = array(
				'category_id' => $category_2['category_id'],
				'name'        => $category_2['name'],
				'children'    => $level_3_data
			);
		}

		$data['categories'][] = array(
			'category_id' => $category_1['category_id'],
			'name'        => $category_1['name'],
			'children'    => $level_2_data
		);
	}

	$data['products'] = array();

	if($this->request->post['location']){
		$location=$this->request->post['location'];
	}else{
		$location='ktm';
	}

	if (isset($this->request->post['search']) || isset($this->request->get['tag'])) {
		$filter_data = array(
			'filter_name'         => $search,
			'filter_tag'          => $tag,
			'filter_description'  => $description,
			'filter_category_id'  => $category_id,
			'filter_sub_category' => $sub_category,
			'location'                => $location,
			'sort'                => $sort,
			'order'               => $order,
			'start'               => ($page - 1) * $limit,
			'limit'               => $limit
		);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			}

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
			} else {
				$special = false;
			}

			if ($this->config->get('config_tax')) {
				$tax = (float)$result['special'] ? $result['special'] : $result['price'];
			} else {
				$tax = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = (int)$result['rating'];
			} else {
				$rating = false;
			}
			
			//get category name for badge
			//$category_name = '';
			$category = $this->model_catalog_product->getCategories($result['product_id']);
			if ($category){
				$category_array = $this->model_catalog_category->getCategory($category[0]['category_id']);
				//$category_name = $category_array['name'];
			}
			/**/
			if($special==NULL||$special==FALSE){
				$special_price=$price;

			}else{
				$special_price=$special;
			}

			 //for options
             $sql="SELECT * FROM product_to_option op JOIN product_options p ON (op.option_group_id=p.option_group_id) WHERE op.product_id='". $result['product_id']."' ORDER BY p.option_id";
             $optionss=$this->db->query($sql);
 
             if($optionss->num_rows==0){
                 $option=NULL;
             }else{
                 foreach ($optionss->rows as $result2){
                     $option[]=array(
                         'option_id' => $result2['option_id'],
                         'option_name' => $result2['option_name'],
                         'price' => strval((float)$result2['price']),
                     );
                 }
             }
             if($result['location']==NULL){
                $result['location']='ktm,pkr';

             }

			 if ((float)$result['special']) {
                $special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
                $discount = '-'.round((($result['price'] - $result['special'])/$result['price'])*100, 0).'%';
            } else {
                // $special = false;
                $discount = false;
            }
 
             // end for options
		
			$data['products'][] = array(
				'product_id'  => $result['product_id'],
				'thumb'       => $image,
				'name'        => $result['name'],
				'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
				'price'       => $price,
				'special'     => $special_price,
				'tax'         => $tax,
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'rating'      => $result['rating'],
				'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url),
				'sku'         => $result['sku'],
				'location'    => $result['location'],	
				'option' =>$option,
                'discount' => $discount,

				'viewed' => $result['viewed'],                
			
				//'category_name'    => $category_name
			);
		}

		$url = '';

		if (isset($this->request->post['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
		}

		if (isset($this->request->get['category_id'])) {
			$url .= '&category_id=' . $this->request->get['category_id'];
		}

		if (isset($this->request->get['sub_category'])) {
			$url .= '&sub_category=' . $this->request->get['sub_category'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => $this->url->link('product/search', 'sort=p.sort_order&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link('product/search', 'sort=pd.name&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link('product/search', 'sort=pd.name&order=DESC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'p.price-ASC',
			'href'  => $this->url->link('product/search', 'sort=p.price&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'p.price-DESC',
			'href'  => $this->url->link('product/search', 'sort=p.price&order=DESC' . $url)
		);

		if ($this->config->get('config_review_status')) {
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('product/search', 'sort=rating&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('product/search', 'sort=rating&order=ASC' . $url)
			);
		}

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_model_asc'),
			'value' => 'p.model-ASC',
			'href'  => $this->url->link('product/search', 'sort=p.model&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => $this->url->link('product/search', 'sort=p.model&order=DESC' . $url)
		);

		$url = '';

		if (isset($this->request->post['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
		}

		if (isset($this->request->get['category_id'])) {
			$url .= '&category_id=' . $this->request->get['category_id'];
		}

		if (isset($this->request->get['sub_category'])) {
			$url .= '&sub_category=' . $this->request->get['sub_category'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['limits'] = array();

		$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('product/search', $url . '&limit=' . $value)
			);
		}

		$url = '';

		if (isset($this->request->post['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
		}

		if (isset($this->request->get['category_id'])) {
			$url .= '&category_id=' . $this->request->get['category_id'];
		}

		if (isset($this->request->get['sub_category'])) {
			$url .= '&sub_category=' . $this->request->get['sub_category'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('product/search', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

		if (isset($this->request->post['search']) && $this->config->get('config_customer_search')) {
			$this->load->model('account/search');

			if ($this->customer->isLogged()) {
				$customer_id = $this->customer->getId();
			} else {
				$customer_id = 0;
			}

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			$search_data = array(
				'keyword'       => $search,
				'category_id'   => $category_id,
				'sub_category'  => $sub_category,
				'description'   => $description,
				'products'      => $product_total,
				'customer_id'   => $customer_id,
				'ip'            => $ip
			);
			$this->model_account_search->addSearch($search_data);
		}
	}
	$data['search'] = $search;
	$data['description'] = $description;
	$data['category_id'] = $category_id;
	$data['sub_category'] = $sub_category;
	$data['sort'] = $sort;
	$data['order'] = $order;
	$data['limit'] = $limit;
	$json['products']=$data['products'];
	$json['search']=$this->request->post['search'];
	}
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
}
public function getAddresses(){   
	if($this->request->post['key']=='G3t@dres'){
		$this->load->model('kheti/components');
		$json['address']=$this->model_kheti_components->address($customer_id);
	}
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
}

public function deleteAddress(){   
	if($this->request->post['key']=='D3l@dres'){
		$sql="DELETE FROM `oc_address` WHERE address_id='".$this->request->post['address_id']."' AND customer_id='".$this->request->post['customer_id']."'";
		$this->db->query($sql);
		$json['success']=1;
	}else{
		$json['error']='wrong input';
	}
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
}
public function editAddress(){   
	if($this->request->post['key']=='@dres'){
		$sql="UPDATE `oc_address` SET firstname='".$this->request->post['firstname']."',lastname='".$this->request->post['lastname']."',company='".$this->request->post['company']."',address_1='".$this->request->post['address_1']."',address_2='".$this->request->post['address_2']."',city='".$this->request->post['city']."',country_id='".$this->request->post['country_id']."' WHERE address_id='".$this->request->post['address_id']."' AND customer_id='".$this->request->post['customer_id']."'";	
		$this->db->query($sql);
		$json['success']=1;
	}else{
		$json['error']='wrong input';

	}
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
}

public function insertAddress(){   
	if($this->request->post['key']=='!ns3rt@dres'){
		if($this->request->post['city']=='pokhara'){
			$zone_id=2318;

		}elseif($this->request->post['city']=='kathmandu'){

			$zone_id=2315;

		}
		if($this->request->post['address_2']){
			$address_2=$this->request->post['address_2'];

		}else{
			$address_2='';
		}

		if($this->request->post['company']){
			$company=$this->request->post['company'];

		}else{
			$company='';
		}
		if($this->request->post['postcode']){
			$postcode=$this->request->post['postcode'];

		}else{
			$postcode='';
		}
		$country_id=149;
		
		$sql="INSERT INTO `oc_address` (customer_id,firstname,lastname,company,address_1,address_2,city,postcode,country_id,zone_id,custom_field) VALUES ('".$this->request->post['customer_id']."','".$this->request->post['firstname']."','".$this->request->post['lastname']."','".$company."','".$this->request->post['address_1']."','".$address_2."','".$this->request->post['city']."','".$postcode."','".$country_id."','".$zone_id."','".$this->request->post['custom_field']."')";
		$this->db->query($sql);
		$address_id = $this->db->getLastId();
		$json['address_id']=$address_id;
	}
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
}

 public function gotocheckout(){ 
	$this->load->model('extension/total/coupon');
	   $login= $this->checklogin($this->request->post['customer_id'],$this->request->post['token']);
		if($login){
			$sql="SELECT c.* FROM `oc_cart` c JOIN oc_product p ON (p.product_id=c.product_id) JOIN oc_product_to_store ps ON (ps.product_id=p.product_id) WHERE p.status=1 AND c.session_id='".$this->request->post['session_id']."' AND ps.store_id=1";

			// $sql="SELECT * FROM `oc_cart` WHERE session_id='".$this->request->post['session_id']."'";
			$products=$this->db->query($sql);

			if(($this->request->post['session_id'])&&($this->request->post['session_id']!=NULL)&&($this->request->post['session_id']!=NULL)&&($products->row['session_id'])){
							$sql="SELECT * FROM `oc_customer` WHERE customer_id='".$this->request->post['customer_id']."'";
				$customer_info=$this->db->query($sql);
								$sql="INSERT INTO oc_order SET invoice_prefix = 'INV-2078-AP', store_id = '1', order_location = '', store_name = 'khetifood', store_url = 'https://khetifood.com/', customer_id = '" . $this->request->post['customer_id'] . "', customer_group_id = '" . $customer_info->row['customer_group_id'] . "', firstname = '" . $this->db->escape($customer_info->row['firstname']) . "', lastname = '" . $this->db->escape($customer_info->row['lastname']) . "', email ='".$this->db->escape($customer_info->row['email'])."', telephone = '" . $customer_info->row['telephone'] . "', fax='".$customer_info->row['fax'] ."',custom_field='".$customer_info->row['custom_field']."', order_status_id='0',affiliate_id='0',language_id='1',currency_id='3',currency_code='NPR',currency_value=1.00000000,payment_firstname='',payment_lastname='',payment_address_1='',payment_address_2='',payment_city='',payment_postcode='',payment_country='',payment_country_id='0',payment_zone='',payment_zone_id='0',payment_company='',payment_address_format='',payment_custom_field='',payment_method='',payment_code='',shipping_firstname='',shipping_lastname='',shipping_company='',shipping_address_1='',shipping_address_2='',shipping_city='',shipping_postcode='',shipping_country='',shipping_country_id='0',shipping_zone='',shipping_zone_id='0',shipping_address_format='',shipping_custom_field='',shipping_method='',shipping_code='',comment='',commission='0.0000',marketing_id='0',tracking='',ip='',forwarded_ip='',user_agent='',accept_language='', date_added = NOW(),date_modified = NOW()";
								$this->db->query($sql);
								$order_id = $this->db->getLastId();
								$json['order_id']=$order_id;									
								$subtotal=0.00;
								foreach ($products->rows as $result){							
									$this->load->model('catalog/product');
									$product_info = $this->model_catalog_product->getProduct($result['product_id']);
									if(($result['option_id']!=0)&&($result['option_id']!='0')&&($result['option_id']!='[]')){
										$sql="SELECT * FROM `product_options` WHERE option_id='".$result['option_id']."'";
										$option_name=$this->db->query($sql);
										$priceofproduct=(float)$option_name->row['price'];
									}else{
										if($product_info['special']){
											$priceofproduct=(float)$product_info['special'];							

										}else{
											$priceofproduct=(float)$product_info['price'];							

										}
									}
									$total= (float)$priceofproduct * (float) $result['quantity'];
									$subtotal=$subtotal+$total;
										
									$this->db->query("INSERT INTO oc_order_product SET order_id = '" . $order_id . "', product_id = '" . $result['product_id'] . "', name = '" .$this->db->escape($product_info['name']). "', model = '" .$this->db->escape($product_info['model']) . "', quantity = '" .  $result['quantity'] . "', price = '" . $priceofproduct . "', total = '" . $total . "', tax = '', reward = ''");
									$order_product_no = $this->db->getLastId();
									if(($result['option_id']!=0)&&($result['option_id']!='0')){
									$sql="INSERT INTO `oc_order_option` (`order_id`,`order_product_id`,`product_option_id`,`product_option_value_id`,`name`,`value`,`type`) VALUES ('" .$order_id. "','".$order_product_no."',0,0,'Weight/quantity','".$option_name->row['option_name']."','".$result['option_id']."')";
									$this->db->query($sql);							
							}
						}
						$sql="UPDATE `oc_order` SET total=".$subtotal." WHERE order_id='".$order_id."'";
						$this->db->query($sql);
						if(((float)$subtotal)<(float)1500){
							$shipp=(float)70;
						}else{
							$shipp=(float)0;
						}
						
						$grandtotal=$shipp+$subtotal;

						if($this->request->post['code']){

							$result_coupon = $this->model_extension_total_coupon->getCoupon1($this->request->post['code'],$this->request->post['customer_id']);
							if($result_coupon){

								if($result_coupon['type']=='P'){
									$discountamount=(((float)$result_coupon['discount']*$subtotal)/100);
				
								}elseif($result_coupon['type']=='F'){
									$discountamount=(float)$result_coupon['discount'];
								}

								$grandtotal=$grandtotal - $discountamount;
								$sql="INSERT INTO `oc_order_total` (order_id,code,title,value,sort_order) VALUES ('".$order_id."','coupon','Coupon (".$this->request->post['code'].")','".$discountamount."','0')";
								$this->db->query($sql);
							}
						}
						// $sql="SELECT count(*) as number1 FROM `oc_order` WHERE customer_id='".$this->request->post['customer_id']."' AND invoice_prefix='INV-2078-AP'";
						// $assk=$this->db->query($sql);
						// if($assk->row['number1']=='0'||$assk->row['number1']==0){
						// 	$sql="INSERT INTO `oc_order_total` (order_id,code,title,value,sort_order) VALUES ('".$order_id."','First order discount','First order discount','100','2')";
						// 	$this->db->query($sql);
						// 	$grandtotal=$grandtotal - 100;
						// }
						
					if($this->request->post['code']){
						$json['totals']=array(
							'Sub-Total' => $subtotal,
							'Coupon Discount' => $discountamount,
							'Shipping Charges'=> $shipp,
							'Total' => $grandtotal,
						   );
					}else{
						$json['totals']=array(
							'Sub-Total' => $subtotal,
							'Shipping Charges'=> $shipp,
							'Total' => $grandtotal,								
						   );
					}		
		
					$sql="INSERT INTO `oc_order_total` (order_id,code,title,value,sort_order) VALUES ('".$order_id."','sub_total','Sub-Total','".$subtotal."','1')";
					$this->db->query($sql);

					$sql="INSERT INTO `oc_order_total` (order_id,code,title,value,sort_order) VALUES ('".$order_id."','shipping','Shipping Charges','".$shipp."','3')";
					$this->db->query($sql);
					
					$sql="INSERT INTO `oc_order_total` (order_id,code,title,value,sort_order) VALUES ('".$order_id."','total','Total','".$grandtotal."','9')";
					$this->db->query($sql);
						// }

			}else{
				$json['error']='Add Products to cart first';
			}
		}else{
				$json['error']='login first';
			}	
		$this->response->addHeader('Content-Type: application/json');
		$this->response->addHeader('Access-Control-Allow-Origin: *');
		$this->response->addHeader('Access-Control-Allow-Headers: *');
		$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
		$this->response->addHeader('Access-Control-Max-Age: 600');
		$this->response->setOutput(json_encode($json));
	}
	public function confirm(){   
			$sql="SELECT * FROM `oc_order` WHERE order_id='".$this->request->post['order_id']."'";
						$orderispro=$this->db->query($sql);
						if($orderispro->row['order_id']){
							
							if($this->request->post['billing_address_id']&&$this->request->post['shipping_address_id']&&$this->request->post['payment_method']&&$this->request->post['shipping_method']&&$this->request->post['session_id']){
								$sql="DELETE FROM `oc_cart` WHERE session_id='".$this->request->post['session_id']."'";
								$this->db->query($sql);
								
								$sql="SELECT * FROM `oc_customer` WHERE customer_id='".$this->request->post['customer_id']."'";
								$customer_info=$this->db->query($sql);
								if($customer_info->row['token_app']==$this->request->post['token']){
									$sql="SELECT * FROM `oc_address` WHERE address_id='".$this->request->post['billing_address_id']."'";
									  $customerpay=$this->db->query($sql);
									//   $json['payment']=$customerpay;
									  $sql="SELECT * FROM `oc_address` WHERE address_id='".$this->request->post['shipping_address_id']."'";
									  $customership=$this->db->query($sql);
									//   $json['customership']=$customership;
									if($this->request->post['comment']){
										$comment=$this->request->post['comment'];
									}else{
										$comment='';
									}
									if($this->request->post['payment_method']=='Cash On Delivery'){
										$payment_code='cod';
									}elseif($this->request->post['payment_method']=='Esewa'){
										$payment_code='cod2';
									}elseif($this->request->post['payment_method']=='Connect IPS'||$this->request->post['payment_method']=='<i>connect</i>IPS'){
										$payment_code='payza';
			
									}elseif($this->request->post['payment_method']=='Fonepay'){
										$payment_code='cod1';			
			
									}elseif($this->request->post['payment_method']=='CellPay'){
										$payment_code='cellpay';
									}else{
										$payment_code='';
									}
									if($this->request->post['shipping_method']=='Shipping Charges'){
										$ship_code='geo_zone_shipping.0';
									}
			
									$sql="UPDATE `oc_order` SET payment_firstname='".$customerpay->row['firstname']."',payment_lastname='".$customerpay->row['lastname']."',payment_company='".$customerpay->row['company']."',payment_address_1='".$customerpay->row['address_1']."',payment_address_2='".$customerpay->row['address_2']."',payment_city='".$customerpay->row['city']."',payment_postcode='".$customerpay->row['postcode']."',payment_country='".$customerpay->row['country_id']."',payment_country_id='".(int)$customerpay->row['country_id']."',payment_zone='".$customerpay->row['zone_id']."',payment_zone_id='".(int)$customerpay->row['zone_id']."',payment_custom_field='".$customerpay->row['custom_field']."',payment_method='".$this->request->post['payment_method']."',payment_code='".$payment_code."',shipping_firstname='".$customership->row['firstname']."',shipping_lastname='".$customership->row['lastname']."',shipping_company='".$customership->row['company']."',shipping_address_1='".$customership->row['address_1']."',shipping_address_2='".$customership->row['address_2']."',shipping_city='".$customership->row['city']."',shipping_postcode='".(int)$customership->row['postcode']."',shipping_country='".$customership->row['country_id']."',shipping_country_id='".(int)$customership->row['country_id']."',shipping_custom_field='".$customership->row['custom_field']."',shipping_method='".$this->request->post['shipping_method']."',shipping_code='".$ship_code."',comment='".$comment."',order_status_id=1, date_added = NOW(),date_modified = NOW() WHERE order_id='".$this->request->post['order_id']."'";
									$this->db->query($sql);

									if($this->request->post['date_delivery']){
										$sql="INSERT INTO `order_delivery` (order_id,date_added,date_delivery) VALUES ('".$this->request->post['order_id']."',NOW(),'".$this->request->post['date_delivery']."')";
										$this->db->query($sql);
									}

									
									//viber
									$messageString="";
									
									$storeName="Kheti Food-App";
								
									$messageString=$messageString. $storeName. "\n"."\nOrder Id: ".$this->request->post['order_id']."\nDate: ".date("Y/m/d")."\nComment: ".$comment;
									
									$sql="SELECT * FROM `oc_order_product` WHERE order_id='".$this->request->post['order_id']."'";
									$products=$this->db->query($sql);
									foreach($products->rows as $product3) {
									// 	$productInfo=$this->model_catalog_product->getProduct($product3["product_id"]);
									// 	// $productCategory=$this->model_catalog_product->getProductCategory($product3["product_id"]);
										$messageString=$messageString ."\n\nProduct id: ".$product3["product_id"]. "\n" ."Product Name: ". $product3["name"]."\n"."Product price: ". strval((float)$product3["price"])."\n"."Quantity: ".$product3["quantity"];
									// 	unset($productInfo);
									// 	// unset($productCategory);
									}
									// $customerGroupName=$this->model_account_customer->getCustomerGroup($this->customer->getId());
						
									$messageString=$messageString."\n\n". "Customer "."\n"."First name: ".$orderispro->row['firstname']."\n"."Last Name: ".$orderispro->row['lastname']."\n"."Total: ".strval((float)$orderispro->row['total']);
						
									$url = 'https://kheti.pythonanywhere.com/stockInfo';
									$ch = curl_init($url);
									$payload = ($messageString);
									curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
									curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									$result = curl_exec($ch);
									curl_close($ch);
									//viber
									$json['success']=1;
								}else{
									$json['error']='Login failed';
			
								}
							}else{
								$json['error']="Some Field missing";
							}
						}else{
							$json['error']='Given order already processed';

						}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->addHeader('Access-Control-Allow-Origin: *');
		$this->response->addHeader('Access-Control-Allow-Headers: *');
		$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
		$this->response->addHeader('Access-Control-Max-Age: 600');
		$this->response->setOutput(json_encode($json));
	}
	


public function getinformation(){ 
	$this->load->language('information/information');

	$this->load->model('catalog/information');
	if (isset($this->request->post['information_id'])) {
		$information_id = (int)$this->request->post['information_id'];
	} else {
		$information_id = 0;
	}

	$information_info = $this->model_catalog_information->getInformation($information_id);

	if ($information_info) {
		$this->document->setTitle($information_info['meta_title']);
		$this->document->setDescription($information_info['meta_description']);
		$this->document->setKeywords($information_info['meta_keyword']);
		$json['heading_title'] = $information_info['title'];
		$json['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
		$json['information_id'] = $information_id;
	}
	$this->response->addHeader('Content-Type: application/json');
		$this->response->addHeader('Access-Control-Allow-Origin: *');
		$this->response->addHeader('Access-Control-Allow-Headers: *');
		$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
		$this->response->addHeader('Access-Control-Max-Age: 600');
		$this->response->setOutput(json_encode($json));
}

public function getorderinfo(){
	// if(($this->request->post['customer_id'])&&($this->request->post['token'])){
		$sql="SELECT * FROM `oc_customer` WHERE customer_id='".$this->request->get['customer_id']."'";
		$customer_info=$this->db->query($sql);
		// if($customer_info->row['token_app']==$this->request->post['token']){
		$this->load->language('account/order');

	if (isset($this->request->get['order_id'])) {
		$order_id = $this->request->get['order_id'];
	} else {
		$order_id = 0;
	}
	if (isset($this->request->get['customer_id'])) {
		$customer_id = $this->request->get['customer_id'];
	} else {
		$customer_id = 0;
	}

	$this->load->model('account/order');

	$order_info = $this->model_account_order->getOrderinfos($order_id,$customer_id);

	if ($order_info) {
		$this->document->setTitle($this->language->get('text_order'));
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if ($order_info['invoice_no']) {
			$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
		} else {
			$data['invoice_no'] = '';
		}

		$data['order_id'] = $this->request->post['order_id'];
		$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

		if ($order_info['payment_address_format']) {
			$format = $order_info['payment_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}


		$replace = array(
			'firstname' => $order_info['payment_firstname'],
			'lastname'  => $order_info['payment_lastname'],
			'company'   => $order_info['payment_company'],
			'address_1' => $order_info['payment_address_1'],
			'address_2' => $order_info['payment_address_2'],
			'city'      => $order_info['payment_city'],
			'postcode'  => $order_info['payment_postcode'],
			'zone'      => $order_info['payment_zone'],
			'zone_code' => $order_info['payment_zone_code'],
			'country'   => $order_info['payment_country']
		);

		$data['payment_address'] = $replace;
		$data['payment_method'] = $order_info['payment_method'];

		if ($order_info['shipping_address_format']) {
			$format = $order_info['shipping_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = array(
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{zone_code}',
			'{country}'
		);

		$replace = array(
			'firstname' => $order_info['shipping_firstname'],
			'lastname'  => $order_info['shipping_lastname'],
			'company'   => $order_info['shipping_company'],
			'address_1' => $order_info['shipping_address_1'],
			'address_2' => $order_info['shipping_address_2'],
			'city'      => $order_info['shipping_city'],
			'postcode'  => $order_info['shipping_postcode'],
			'zone'      => $order_info['shipping_zone'],
			'zone_code' => $order_info['shipping_zone_code'],
			'country'   => $order_info['shipping_country']
		);

		$data['shipping_address'] = $replace; 
		$data['shipping_method'] = $order_info['shipping_method'];

		$this->load->model('catalog/product');
		$this->load->model('tool/upload');

		// Products
		$data['products'] = array();

		$products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

		foreach ($products as $product) {
			$option_data = array();

			$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = array(
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				);
			}

			$product_info = $this->model_catalog_product->getProduct($product['product_id']);				

			$data['products'][] = array(
				'name'     => $product['name'],
				'model'    => $product['model'],
				'option'   => $option_data,
				'quantity' => $product['quantity'],
				'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				// 'reorder'  => $reorder,
				// 'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], true)
			);
		}

		// Voucher
		$data['vouchers'] = array();
		$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);
		foreach ($vouchers as $voucher) {
			$data['vouchers'][] = array(
				'description' => $voucher['description'],
				'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
			);
		}

		// Totals
		$data['totals'] = array();

		$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

		foreach ($totals as $total) {
			$data['totals'][] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}

		$data['comment'] = nl2br($order_info['comment']);

		// History
		$data['histories'] = array();

		$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'status'     => $result['status'],
				'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
			);
		}
	} 

	$htmla='<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Order Details</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<style>
#sidebar ul.components {
  margin-top: 10px;}

  ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  width: 180px;
  background-color: #f1f1f1;
  border: 1px solid #555;
}

li a {
  display: block;
  color: #000;
  padding: 8px 16px;
  text-decoration: none;
}
li {
text-align: center;
border-bottom: 1px solid #555;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;}

li:last-child {
  border-bottom: none;
}

li a.active {
  background-color: #4CAF50;
  color: white;
}

li a:hover:not(.active) {
  background-color: #555;
  color: white;
}
.wrapper {
	display: flex;
	width: 100%;
	align-items: stretch;
}
#sidebar {
	min-width: 200px;
	max-width: 200px;
	height: 700px;
	color: #fff;
	transition: all 0.3s;
}
#sidebar ul.components {
	margin-left: 20px;
margin-top: 10px;
border-bottom: 1px solid black;
}
.content-item{
	display: flex;
align-items: center;
justify-content: center;
width: 100%;
}
.groups{
display: flex;
flex-wrap: nowrap;
height: 50px;
margin-top: 10px;
}
.box {
border: solid 1px;
background: #ccc;
padding: 6px;
}
</style>
	</head>
	<body>
	<div class="wrapper">

<div class="content" style="width: 100%;">
<div class="content-item">

			<table class="table  table-striped  table-bordered border-primary " style="width: 375px; margin: 10px 10px;">
				<thead class="table-light ">
					<tr>                                      
						<td style="background: #0db04b; color: #ffffff; font-weight: 700; text-transform: capitalize;"><i class="fa fa-shopping-basket" aria-hidden="true" ></i>Order details</td>
						
					</tr>
				</thead>
				<tbody>                                 
							<tr>                                       
									<td> <i class="fa fa-shopping-cart" aria-hidden="true" style="padding:5px;"></i> kheti.food</td>
								
							</tr>
							<tr>
				
									<td><i class="fa fa-calendar" aria-hidden="true" style="padding: 5px;"></i>'.$data['date_added'].'</td>
								
							</tr>
					  
						<tr>
						  <td><p class="par">Order id:</p>'.$this->request->get['order_id'].'
						  
						   </td>
						</tr>
						</tbody>
					  </table>				
				
						<table class="table  table-striped table-bordered border-primary" style="margin: 10px 10px;">
				<thead class="table-light ">
							<tr>
							  
									<td style="background: #0db04b; color: #ffffff; font-weight: 700; text-transform: capitalize;"><i class="fa fa-user" aria-hidden="true" style="padding: 5px;"></i>Customer details</td>
								
							</tr>
						</thead>
						<tbody>                                  
							<tr>
								
									<td> <i class="fa fa-user-circle" aria-hidden="true" style="padding:5px;"></i>'.$customer_info->row['firstname'].' '.$customer_info->row['lastname'].'</td>
								
							</tr>
							<tr>
								
									<td><i class="fa fa-users" aria-hidden="true" style="padding: 5px;"></i>B2C</td>
								
							</tr>
							<tr>
								
									<td><i class="fa fa-envelope" aria-hidden="true" style="padding: 5px;"></i>'.$customer_info->row['email'].'</td>
								
							</tr>
							<tr>
								
								<td><i class="fa fa-mobile" aria-hidden="true" style="padding: 5px;"></i>'.$customer_info->row['telephone'].'</td>
							
						</tr>
						</tbody>
					  </table> 
					  </div></div></div>
					  <div class="wrapper">
					  <div class="content" style="width: 100%;">
					  <table class="table table-striped table-bordered table-responsive" style="width: 100%; display: table;">
					  <thead>
				  <tr>
				  <th scope="col">Product id</th>
				  <th scope="col">Product Name</th>
				  
					<th scope="col">Quantity</th>
					<th scope="col">Unit price</th>
					<th scope="col">Total</th>
					
				  </tr>
				</thead><tbody>';
				foreach ($products as $product) {
					$htmla=$htmla.' <tr>
					<td>'.$product['product_id'].'</td>
					<td>'.$product['name'].'</td>
					
					<td>'.$product['quantity'].'</td>
					<td>'.$product['price'].'</td>
					<td>'.$product['total'].'</td></tr>';
				}
				foreach ($data['totals'] as $total) {
					$htmla=$htmla.'<tr>
					<td></td>
					
					<td></td>
					<td></td>
	
					<td>'.$total['title'].'</td>
					<td>'.$total['text'].'</td>   
				  </tr>';
				}

				$htmla=$htmla.'
				</tbody>
			  </table>
			  </div></div>
	
				  <form >
					<div class="mb-3 row" style="    margin: 5px;">
						<label for="exampleFormControlTextarea1" class="form-label">Customer Comment</label>
						<textarea class="form-control" id="exampleFormControlTextarea1" rows="3">'.$data['comment'].'</textarea>
					  </div>
				  </form>
				  </div>
				</div>
		 <script src="request.js"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>   
	</body>
	</html>';
	echo($htmla);

}


public function profile(){
	if(($this->request->post['customer_id'])&&($this->request->post['token'])){
		$sql="SELECT * FROM `oc_customer` WHERE customer_id='".$this->request->post['customer_id']."'";
		$customer_info=$this->db->query($sql);
		if($customer_info->row['token_app']==$this->request->post['token']){
			$json['firstname'] = $customer_info->row['firstname'];
			$json['lastname'] = $customer_info->row['lastname'];
			$json['email'] = $customer_info->row['email'];
			$json['telephone'] = $customer_info->row['telephone'];
			$json['location'] = $customer_info->row['location'];
			$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . $this->request->post['customer_id']. "' AND o.order_status_id > '0' AND o.store_id = '1' AND os.language_id = '1' ORDER BY o.order_id DESC");
			foreach ($query->rows as $result) {
		$this->load->model('account/order');

		// $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
		// $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
		// $products = $this->model_account_order->getOrderProducts($result['order_id']);
		$query = $this->db->query("SELECT * FROM oc_order_product WHERE order_id = '" . $result['order_id']. "'");
		foreach ($query->rows as $productresult) {
		
			$products[]=array(
				'products_name'=>$productresult['name']
			);
		}

		$data['orders'][] = array(
			'order_id'   => $result['order_id'],
			'name'       => $result['firstname'] . ' ' . $result['lastname'],
			'status'     => $result['status'],
			'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
			'no_of_product'   => sizeof( $products),
			'products'   => $products,
			'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
			// 'view'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], true),
		);
	}
	$json['orders'] = $data['orders'];
		}else{
			$json['error'] = 'Login first';
		}
		
	}
	$this->response->addHeader('Content-Type: application/json');
		$this->response->addHeader('Access-Control-Allow-Origin: *');
		$this->response->addHeader('Access-Control-Allow-Headers: *');
		$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
		$this->response->addHeader('Access-Control-Max-Age: 600');
		$this->response->setOutput(json_encode($json));
}
	
public function changepassword(){  
	if($this->request->post['password']&&$this->request->post['customer_id']&&$this->request->post['token']){
		
		$sql="SELECT * FROM `oc_customer` WHERE customer_id='".$this->request->post['customer_id']."'";
		$customer_info=$this->db->query($sql);
		if($customer_info->row['token_app']==$this->request->post['token']){
			$this->load->model('account/customer');
			$this->model_account_customer->editPassword($customer_info->row['email'], $this->request->post['password']);
			$json['success'] = 1;
		}else{
			$json['error'] = 'Token mismatch';
		}			
	}else{
		$json['error'] = 'Fill all information';
	}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->addHeader('Access-Control-Allow-Origin: *');
		$this->response->addHeader('Access-Control-Allow-Headers: *');
		$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
		$this->response->addHeader('Access-Control-Max-Age: 600');
		$this->response->setOutput(json_encode($json));
}	

public function setLocation(){  
	if(($this->request->post['customer_id'])&&($this->request->post['token'])&&($this->request->post['location'])){
		$sql="SELECT * FROM `oc_customer` WHERE customer_id='".$this->request->post['customer_id']."'";
		$customer_info=$this->db->query($sql);
		if($customer_info->row['token_app']==$this->request->post['token']){
			$sql="UPDATE `oc_customer` SET location='".$this->request->post['location']."' WHERE customer_id='".$this->request->post['customer_id']."'";
			$this->db->query($sql);
			$json['success'] = 1;
		}else{
			$json['error'] = 'token mismatch';
		}
	}else{
		$json['error'] = 'parameters missing';

	}
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
}
public function getrewards(){  
	$this->load->model('account/reward');
	if($this->request->post['customer_id']){
		$customer_id=$this->request->post['customer_id'];
	
	$results = $this->model_account_reward->getRewardsap($customer_id);
	if($results){
		$resultstotal = $this->model_account_reward->getTotalPoints1($customer_id);

		$json['reward'] =$results ;
		$json['total'] =$resultstotal ;


	}else{
		$json['error'] ='No Rewards' ;
	}

	}else{
		$json['error'] ='No Rewards' ;
	}
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));

}
public function getwishlist(){ 
	$this->load->model('account/wishlist');

	$this->load->model('catalog/product');

	$this->load->model('tool/image'); 
	$results = $this->model_account_wishlist->getWishlist1($this->request->post['customer_id']);
	if($results){
		foreach ($results as $result) {
			$product_info = $this->model_catalog_product->getProduct($result['product_id']);

			if ($product_info) {

				if ($product_info['image']) {
					$image = "https://khetifood.com/image/".$product_info['image'];
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $product_info['price'];
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special = $price;
				}

				if ((float)$product_info['special']) {
					$discount = '-'.round((($product_info['price'] - $product_info['special'])/$product_info['price'])*100, 0).'%';
				} else {
					// $special = false;
					$discount = false;
				}

				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'location'  => $product_info['location'],
					'sku' =>$product_info['sku'],
					'thumb' => $image,
					'name' => $product_info['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price' => $price,
					'special' => $special,
					'tax' => $tax,
					'minimum' => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
					'rating' => $product_info['rating'],
					'href' => str_replace("amp;", "", $href),
					'discount' => $discount,
					'quantity' => $product_info['quantity'],
					'viewed' => $product_info['viewed'],

				);
			} else {
				$this->model_account_wishlist->deleteWishlist1($this->request->post['customer_id'],$result['product_id']);
			}
		}
		$json['wishlist'] =$data['products'] ;

	}else{
		$json['error'] ='No products in wishlist' ;
	}
	

	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));

	
}

public function addtowishlist(){ 
	$this->load->model('account/wishlist');
	$results = $this->model_account_wishlist->addWishlist1($this->request->post['customer_id'],$this->request->post['product_id']);
	$json['success'] ='Added to Your Wishlist';
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
}

public function removewishlist(){ 
	$this->load->model('account/wishlist');
	$results = $this->model_account_wishlist->deleteWishlist1($this->request->post['customer_id'],$this->request->post['product_id']);
	$json['success'] ='Removed From Your Wishlist';
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
}

public function checkcoupon(){ 
	$this->load->model('extension/total/coupon');
	$results = $this->model_extension_total_coupon->getCoupon1($this->request->post['code'],$this->request->post['customer_id']);


	$json['coupon'] =$results;
	$this->response->addHeader('Content-Type: application/json');
	$this->response->addHeader('Access-Control-Allow-Origin: *');
	$this->response->addHeader('Access-Control-Allow-Headers: *');
	$this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
	$this->response->addHeader('Access-Control-Max-Age: 600');
	$this->response->setOutput(json_encode($json));
}

}