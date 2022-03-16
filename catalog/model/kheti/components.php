<?php
class ModelKhetiComponents extends Controller { 
	 public function index($location,$store_id){ 
		$this->load->model('tool/image');
		if($store_id=='1'){
			$module_id=($location=='ktm') ? 253 : 439;
		}elseif($store_id=='0'){
			$module_id=($location=='ktm') ? 101 : 101;
		}
        $sql ="SELECT * FROM `oc_so_homeslider` WHERE module_id='".$module_id."' AND status=1";
				  $img=$this->db->query($sql);
				  foreach ($img->rows as $result){
					//   $image = $this->model_tool_image->resize($result['image'],480,180);
					$image = $result['image'];  
					  $sliders[]=array(
						  'image'=>'https://khetifood.com/image/'.$result['image'],
						//   'image'=>$image,
						  'url'=>$result['url'],
						  'type'=>$result['type_for_app'],
						  'href'=>$result['id_for_app'],
					  );
				  
				  }
        return $sliders;      
	 }
	 

	 public function testimonials(){  			
		$this->load->language('extension/module/testimonials');
     	static $module = 0;	
		$this->load->model('extension/module/testimonials');
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
		$this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');        
            $result=$this->model_extension_module_testimonials->getTestimonials();

           $data['module_testimonials_awidth'] = $this->config->get('module_testimonials_awidth');
		   $data['module_testimonials_aheight'] = $this->config->get('module_testimonials_aheight');

            foreach($result as $results)
            {
               $data['testimonials'][]=array(
				'author'            => $results['author'],
			
				'description'      => $results['description'],
				'status'       => $results['status'],
				'sort_order' => $results['sort_order'],
				'designation'     => $results['designation']
			);
             
            }
			$data['module_testimonials_status'] = $this->config->get('module_testimonials_status');
			$data['module_testimonials_aheight'] = $this->config->get('module_testimonials_aheight');
			$data['module_testimonials_awidth'] = $this->config->get('module_testimonials_awidth');
			$data['module_testimonials_heading'] = $this->config->get('module_testimonials_heading');
			$data['module_testimonials_bgclr'] = $this->config->get('module_testimonials_bgclr');
			$data['module_testimonials_fontclr'] = $this->config->get('module_testimonials_fontclr');
			$data['store_id']=$this->config->get('config_store_id');     
            $data['module'] = $module++;
		   return $data['testimonials'];
		}

		public function valuedfarmers(){
			$this->load->model('tool/image');

			$sql ="SELECT * FROM `valued_farmers`";
					$farmers=$this->db->query($sql);
					foreach ($farmers->rows as $result){
						$farmersarray[]=array(
							'id'=>$result['id'],
							'name'=>$result['name'],
							'image'=>'https://khetifood.com/image/'.$result['image'],
							'address'=>$result['address'],
							'description'=>$result['description'],
	
						);
				
					}
			 return $farmersarray;
		}

		public function vendors($location){  
			$sql ="SELECT c.category_id,cd.name,c.image,cd.meta_title FROM oc_category c JOIN oc_category_description cd ON (cd.category_id=c.category_id) JOIN oc_category_to_store cs ON (cs.category_id=c.category_id) WHERE c.category_id IN (SELECT category_id FROM `app_vendors` WHERE status=1 AND location='".$location."') AND cd.language_id=1 AND c.status=1";
			$cat=$this->db->query($sql);
			$this->load->model('tool/image');	
			foreach ($cat->rows as $result){		
				$Categories[]=array(
					'category_id'=>$result['category_id'],
					'name'=>$result['name'],
					'image'=>$this->model_tool_image->resize($result['image'],105,105),
					'meta_title'=>$result['meta_title']
				);			
			}
			return $Categories;

		}

		public function address(){ 
			$sql="SELECT * FROM `oc_address` WHERE customer_id='".$this->request->post['customer_id']."'";
                $customeradres=$this->db->query($sql);
				foreach ($customeradres->rows as $result){
                    $addresss[]=array(
                        'address_id'=>$result['address_id'],
                        'firstname'=>$result['firstname'],
                        'lastname'=>$result['lastname'],
                        'company'=>$result['company'],
                        'address_1'=>$result['address_1'],
                        'address_2'=>$result['address_2'],
                        'city'=>$result['city'],
                        'postcode'=>$result['postcode'],
						'country_id'=>$result['country_id'],
                        'zone_id'=>$result['zone_id'],
                        'custom_field'=>$result['custom_field']
                    );                
                }
				return $addresss;  
		}

		public function popularSearches($store_id){ 
			$sql="SELECT keyword, COUNT(keyword) AS number FROM `oc_customer_search` WHERE keyword NOT IN('','Tihar Special') AND store_id='".$store_id."' GROUP BY keyword ORDER BY number DESC LIMIT 10";
			$keyword=$this->db->query($sql);
			$keywords=array();
			foreach ($keyword->rows as $result){
				array_push($keywords,$result['keyword']);
			}
			return $keywords;

		}

		


		
}

