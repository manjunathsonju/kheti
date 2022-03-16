<?php
class ModelKhetiProducts extends Controller {
    
	 public function index(){   //unused check 1st
        //  if($location=='ktm'){
           $sql ="SELECT c.category_id,cd.name,c.image,cd.meta_title FROM oc_category c JOIN oc_category_description cd ON (cd.category_id=c.category_id) JOIN oc_category_to_store cs ON (cs.category_id=c.category_id) WHERE c.category_id IN (172,171,139,215,212,225,216,220,221,227,264,247,257,260) AND cd.language_id=1 AND cs.store_id=1";

        //  }else{
        //     $sql ="SELECT c.category_id,cd.name,c.image,cd.meta_title FROM oc_category c JOIN oc_category_description cd ON (cd.category_id=c.category_id) JOIN oc_category_to_store cs ON (cs.category_id=c.category_id) WHERE c.category_id IN (172,137,171,139,215,212,225,220,221,227,247,257) AND cd.language_id=1 AND cs.store_id=1";

        //  }
        $cat=$this->db->query($sql);
        $this->load->model('tool/image');

        
        foreach ($cat->rows as $result){
            if(strlen($result['image'])!=0){
                $image=$result['image'];
            }else{
                $image="no_image.png";
            }

            $Categories[]=array(
                'category_id'=>$result['category_id'],
                'name'=>$result['name'],
                'image'=>$this->model_tool_image->resize($image,105,105),
                'meta_title'=>$result['meta_title']
            );
        }
        return $Categories;
	 }

     public function getcategories($location,$store_id){ 
         if($store_id=='1'){
            $sql ="SELECT c.category_id,cd.name,c.image,cd.meta_title FROM oc_category c JOIN oc_category_description cd ON (cd.category_id=c.category_id) JOIN oc_category_to_store cs ON (cs.category_id=c.category_id) WHERE c.category_id IN (SELECT category_id FROM `app_categories` WHERE location='".$location."' AND status=1) AND cd.language_id=1 AND cs.store_id=1 AND c.status=1 ORDER BY c.sort_order";
           
         }else{
            $sql ="SELECT c.category_id,cd.name,c.image,cd.meta_title,cd.language_id,cn.name as nepali FROM oc_category c JOIN oc_category_description cd ON (cd.category_id=c.category_id) JOIN oc_category_to_store cs ON (cs.category_id=c.category_id) JOIN (SELECT * FROM oc_category_description WHERE category_id IN (60,151,62,66,148,156,220) AND language_id=2) cn ON (cn.category_id=c.category_id) WHERE c.category_id IN (60,151,62,66,148,156,220) AND cd.language_id=1 AND cs.store_id=0 AND c.status=1 ORDER BY c.sort_order";
         }
        
        $cat=$this->db->query($sql);
        $this->load->model('tool/image');

        
        foreach ($cat->rows as $result){
            if(strlen($result['image'])!=0){
                $image=$result['image'];
            }else{
                $image="no_image.png";
            }

            $Categories[]=array(
                'category_id'=>$result['category_id'],
                'name'=>$result['name'],
                'name_nep'=>$result['nepali'],

                'image'=>$this->model_tool_image->resize($image,105,105),
                'meta_title'=>$result['meta_title']
            );
        }
        return $Categories;
	 }

    public function categoryjson($category_id,$location){ 
        $cat=$this->db->query("SELECT * FROM `oc_category_description` WHERE category_id='".$category_id."' AND language_id=1");
	if($category_id=='221'){
        return array(
            'category_id' => $category_id,
            'category_name'=>  $cat->row['name'],
            'products' => $this->model_kheti_products->getprodcuts($category_id,$location),
            'special_offers'=>$this->model_kheti_products->getspecialoffers($category_id,$location),
            
            );	
    }else{
        return array(
            'category_id' => $category_id,
            'category_name'=>  $cat->row['name'],
            'products' => $this->model_kheti_products->getprodcuts($category_id,$location),
            'special_offers'=>$this->model_kheti_products->getspecialoffers($category_id,$location),
          


            );	
    }
       
	}

    public function categoryjsonk($category_id,$location){ 
        $cat=$this->db->query("SELECT * FROM `oc_category_description` WHERE category_id='".$category_id."' AND language_id=1");
	if($category_id=='221'){
        return array(
            'category_id' => $category_id,
            'category_name'=>  $cat->row['name'],
            'products' => $this->model_kheti_products->getprodcuts1($category_id,$location),
            'special_offers'=>$this->model_kheti_products->getspecialoffers($category_id,$location),
            
            );	
    }else{
        return array(
            'category_id' => $category_id,
            'category_name'=>  $cat->row['name'],
            'products' => $this->model_kheti_products->getprodcuts1($category_id,$location),
            'special_offers'=>$this->model_kheti_products->getspecialoffers($category_id,$location),
          


            );	
    }
       
	}

     public function getprodcuts($cateegory_id,$location){        
        $this->load->model('catalog/product');
        $this->load->model('tool/image');	
            $filter_data = array(
                'filter_category_id' =>$cateegory_id,
                'location' => $location,
                'language_id' => 1
            ); 
       
        $results = $this->model_catalog_product->getProducts($filter_data);
        
        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], 140, 140);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price =(float)$result['price'];
            } else {
                $price = false;
            }
            if ((float) $result['special']) {
                $special = (float)$result['special'];
            } else {
                $special = false;
            }
            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
            } else {
                $tax = false;
            }

            if ($this->config->get('config_review_status')) {
                $rating = (int) $result['rating'];
            } else {
                $rating = false;
            }
            //handle special price for flutter app api consumption
            if($special == false) {
                $special = (float)$price;
                $price  = (float)$price;
            }
            
            $href = $this->url->link('product/product', 'product_id=' . $result['product_id']);
            if ((float)$result['special']) {
                $special = (float)$result['special'];
                $discount = '-'.round((($result['price'] - $result['special'])/$result['price'])*100, 0).'%';
            } else {
                // $special = false;
                $discount = false;
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
   
               // end for options
            
            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'location'  => $result['location'],
                'sku' =>$result['sku'],
                'thumb' => $image,
                'name' => $result['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' => strval($price),
                'special' =>strval($special),
                'option' =>$option,

                'tax' => $tax,
                'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating' => $result['rating'],
                'href' => str_replace("amp;", "", $href),
                'discount' => $discount,
                'quantity' => $result['quantity'],
                'viewed'   => $result['viewed'],

            );
        }
        return $data['products'];
    
    }

    public function getprodcuts1($cateegory_id){        
        $this->load->model('catalog/product');
        $this->load->model('tool/image');	
            $filter_data = array(
                'filter_category_id' =>$cateegory_id,
                'location' => 'ktm',
                'language_id' => 1
            ); 
       
        $results = $this->model_catalog_product->getProducts2($filter_data);
        
        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], 140, 140);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price =(float)$result['price'];
            } else {
                $price = false;
            }
            if ((float) $result['special']) {
                $special = (float)$result['special'];
            } else {
                $special = false;
            }
            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
            } else {
                $tax = false;
            }

            if ($this->config->get('config_review_status')) {
                $rating = (int) $result['rating'];
            } else {
                $rating = false;
            }
            //handle special price for flutter app api consumption
            if($special == false) {
                $special = (float)$price;
                $price  = (float)$price;
            }
            
            $href = $this->url->link('product/product', 'product_id=' . $result['product_id']);
            if ((float)$result['special']) {
                $special = (float)$result['special'];
                $discount = '-'.round((($result['price'] - $result['special'])/$result['price'])*100, 0).'%';
            } else {
                // $special = false;
                $discount = false;
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
   
               // end for options
            
            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'location'  => $result['location'],
                'sku' =>$result['sku'],
                'thumb' => $image,
                'name' => $result['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' => strval($price),
                'special' =>strval($special),
                'option' =>$option,

                'tax' => $tax,
                'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating' => $result['rating'],
                'href' => str_replace("amp;", "", $href),
                'discount' => $discount,
                'quantity' => $result['quantity'],
                'viewed'   => $result['viewed'],

            );
        }
        return $data['products'];
    
    }

public function getspecialoffers($category_id,$location){
   
    $this->load->model('catalog/product');
    $this->load->model('tool/image');

        $filter_data = array(
            'filter_category_id' => $category_id,
            'location' => $location,
            'sort'=> "rating",
            'order'=> "DESC",
            'start'=> 0,
            'limit'=> "4",
            'language_id' => 1
        );   
    $results = $this->model_catalog_product->getProducts($filter_data);
    
    foreach ($results as $result) {
        if ($result['image']) {
            $image = $this->model_tool_image->resize($result['image'], 140,140);
        } else {
            $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
        }
        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
            $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
        } else {
            $price = false;
        }
        if ((float) $result['special']) {
            $special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
        } else {
            $special = false;
        }
        if ($this->config->get('config_tax')) {
            $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
        } else {
            $tax = false;
        }
        if ($this->config->get('config_review_status')) {
            $rating = (int) $result['rating'];
        } else {
            $rating = false;
        }
       
        $href = $this->url->link('product/product', 'product_id=' . $result['product_id']);
        if ((float)$result['special']) {
            $special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
            $discount = '-'.round((($result['price'] - $result['special'])/$result['price'])*100, 0).'%';
        } else {
            // $special = false;
            $discount = false;
        }
         //handle special price for flutter app api consumption
        if($special == false) {
            $special = $price;
            $price  =  $price;
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

            // end for options
        
        $data['products'][] = array(
            'product_id' => $result['product_id'],
            'location'  => $result['location'],
            'sku' =>$result['sku'],
            'thumb' => $image,
            'name' => $result['name'],
            'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
            'price' => (string)$price,
            'special' => (string)$special,
            'option' =>$option,
            'tax' => $tax,
            'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
            'rating' => $result['rating'],
            'href' => str_replace("amp;", "", $href),
            'discount' => $discount,
            'quantity' => $result['quantity'],
            'viewed'   => $result['viewed'],

            
        );
    }
    return $data['products'];
}
     
public function getbestselling($location,$store_id){
    $this->load->model('catalog/product');
    $this->load->model('tool/image');
    $results = $this->model_catalog_product->getBestSellerProductsbylocation1(10,$location,$store_id);

    foreach ($results as $result) {
      if ($result['image']) {
          $image = $this->model_tool_image->resize($result['image'], 140,140);
      } else {
          $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
      }
      if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
          $price = (float)$result['price'];
      } else {
          $price = false;
      }
      if ((float) $result['special']) {
          $special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
      } else {
          $special = false;
      }
      if ($this->config->get('config_tax')) {
          $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
      } else {
          $tax = false;
      }
      if ($this->config->get('config_review_status')) {
          $rating = (int) $result['rating'];
      } else {
          $rating = false;
      }
      //handle special price for flutter app api consumption
      if($special == false) {
          $special = (float)$price;
          $price  =  (float)$price;
      }
      
      $href = $this->url->link('product/product', 'product_id=' . $result['product_id']);
      if ((float)$result['special']) {
          $special = $result['special'];
          $discount = '-'.round((($result['price'] - $result['special'])/$result['price'])*100, 0).'%';
      } else {
          // $special = false;
          $discount = false;
      }
      
      $data['products'][] = array(
          'product_id' => $result['product_id'],
          'location'  => $result['location'],
          'sku' =>$result['sku'],
          'thumb' => $image,
          'name' => $result['name'],
          'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
          'price' => (string)$price,
          'special' => (string)$special,
          'tax' => $tax,
          'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
          'rating' => $result['rating'],
          'href' => str_replace("amp;", "", $href),
          'discount' => $discount,
          'quantity' => $result['quantity'],
          'viewed'   => $result['viewed'],

      );
  }
  return $data['products'];		

}

// public function categoryHome($category_id){  
//     $fruits=array(
//         'category_id' => $category_id,
//         'category_name'=> 'Fruits',
//         'products' => $this->model_kheti_products->getprodcuts(172,$location),
//         'special_offers'=>$this->model_kheti_products->getspecialoffers(172,$location),
//        );

// }

 public function addtocart($customer_id,$session_id,$product_id,$options,$option_id,$quantity){  
    $sql="INSERT INTO `oc_cart` (api_id,customer_id,session_id,product_id,recurring_id,`option`,`option_id`,quantity,date_added) VALUES ('0','".$customer_id."','".$session_id."','".$product_id."','0','".$options."','".$option_id."','".$quantity."',NOW())";
    $this->db->query($sql);
    return $this->db->getLastId();

 }
 public function updatecart($quantity,$product_id,$session_id,$option_id){  
    $sql="UPDATE `oc_cart` SET quantity=quantity+".(int)$quantity." WHERE product_id='".$product_id."' AND session_id='".$session_id."' AND `option_id`='".$option_id."'";
	$this->db->query($sql);
    return TRUE;

 }
 public function increaseview($product_id){
    $sql="UPDATE `oc_product` SET viewed=viewed+1 WHERE product_id='".$product_id."'";
    $this->db->query($sql);
    return TRUE;
 }



}

