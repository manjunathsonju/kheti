<?php
class ControllerExtensionModuleSomegamenu extends Controller {
    public function index($setting) {
        // $this->load->model('extension/module/so_megamenu');
        $this->document->addStyle('catalog/view/javascript/so_megamenu/so_megamenu.css');
        $this->document->addStyle('catalog/view/javascript/so_megamenu/wide-grid.css');
        $this->document->addScript('catalog/view/javascript/so_megamenu/so_megamenu.js');
		// $module_id = (isset($setting['moduleid']) && $setting['moduleid']) ? $setting['moduleid'] : 0;
        // $data['menu'] = $this->model_extension_module_so_megamenu->getMenu($module_id);

        // //dev
        // $this->load->language('extension/module/so_megamenu');
        // $data['text_more_category']             = $this->language->get('text_more_category');
        // $data['text_close_category']            = $this->language->get('text_close_category');

		// foreach($data['menu'] as &$menu){
		// 	if(isset($menu['link']) && $menu['link']){
		// 		$menu['link'] = trim($menu['link']);
		// 		$link = (isset($menu['link']) && ($menu['link'])) ? unserialize($menu['link']) : array();
		// 		$menu['route'] = '';
		// 		$menu['path'] = '';
		// 		if($link){
		// 			if(isset($menu['type_link']) && $menu['type_link'] == 1){
		// 				$menu['link'] = $this->url->link('product/category', 'path=' . $link['category']);
		// 				$menu['route'] = 'product/category';
		// 				$menu['path']	= $link['category'];
		// 			}else
		// 				$menu['link'] = $link['url'];
		// 		}
		// 		else
		// 			$menu['link'] = '';
		// 	}	
		// }
        // $lang_id = $this->config->get('config_language_id');
		// if($setting['show_itemver'] == ""){
		// 	$setting['show_itemver'] = 5;
		// }
        // $data['ustawienia'] = array(
        //     'orientation' => $setting['orientation'],
        //     'search_bar' => $setting['search_bar'],
        //     'navigation_text' => $setting['navigation_text'],
        //     'full_width' => $setting['full_width'],
        //     'home_item' => $setting['home_item'],
        //     'home_text' => $setting['home_text'],
        //     'animation' => $setting['animation'],
        //     'show_itemver' => $setting['show_itemver'],
        //     'animation_time' => $setting['animation_time'],
		// 	'disp_title_module' => isset($setting['disp_title_module']) ? $setting['disp_title_module'] : ''
        // );
        // $data['navigation_text'] = 'Navigation';
        // if(isset($setting['navigation_text'][$lang_id])) {
        //     if(!empty($setting['navigation_text'][$lang_id])) {
        //         $data['navigation_text'] = $setting['navigation_text'][$lang_id];
        //     }
        // }
        // if(isset($setting['head_name'][$lang_id])) {
        //     if(!empty($setting['head_name'][$lang_id])) {
        //         $data['head_name'] = $setting['head_name'][$lang_id];
        //     }
        // }		
        // $data['home_text'] = 'Home';
        // if(isset($setting['home_text'][$lang_id])) {
        //     if(!empty($setting['home_text'][$lang_id])) {
        //         $data['home_text'] = $setting['home_text'][$lang_id];
        //     }
        // }
        // $data['home'] = $this->url->link('common/home');
        // $data['lang_id'] = $this->config->get('config_language_id');

        // $http = $_SERVER["HTTPS"]  ? 'https://' : 'http://';
        // $data['actual_link'] = $http."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        // if (isset($_GET['route']))
        //     $data['route']  = $_GET['route'];
        // else
        //     $data['route']  = '';

        // if (isset($_GET['path']))
        //     $data['path']   = $_GET['path'];
        // else
        //     $data['path']   = '';
		
        // Search
        // $this->language->load('common/header');
        $data['store_id'] = $this->config->get('config_store_id');
        //send user location data
			$user_location = 'ktm'; //default location
			if(isset($_COOKIE['location_cookie'])){ 
				//Decrypt cookie variable value
				$user_location = $this->decryptCookie($_COOKIE['location_cookie']);
			}
			
			$data['user_location'] = $user_location;


        // caching
        $use_cache = (int)$setting['use_cache'];
        $cache_time = (int)$setting['cache_time'];
        $folder_cache = DIR_CACHE.'so/Megamenu/';
        if(!file_exists($folder_cache))
            mkdir ($folder_cache, 0777, true);
        if (!class_exists('Cache_Lite'))
            require_once (DIR_SYSTEM . 'library/so/megamenu/Cache_Lite/Lite.php');

        $options = array(
            'cacheDir' => $folder_cache,
            'lifeTime' => $cache_time
        );
        $Cache_Lite = new Cache_Lite($options);
        if ($use_cache){
            $this->hash = md5( serialize($setting).$this->config->get('config_language_id').$this->session->data['currency']);
            $_data = $Cache_Lite->get($this->hash);
            if (!$_data) {
                $_data = $this->load->view('extension/module/so_megamenu/default', $data);
                $Cache_Lite->save($_data);
                return  $_data;
            } else {
                return  $_data;
            }
        }else{
            if(file_exists($folder_cache))
                $Cache_Lite->_cleanDir($folder_cache);
            return $this->load->view('extension/module/so_megamenu/default', $data);
        }
    }
    public function decryptCookie( $value ) {
		$key = 'pushparaj_encryption_32n4kj2b342b3j4223b7sbd5g4jvg35f';
		$c = base64_decode($value);
		$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
		$iv = substr($c, 0, $ivlen);
		$hmac = substr($c, $ivlen, $sha2len=32);
		$ciphertext_raw = substr($c, $ivlen+$sha2len);
		$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
		$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
		if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
		{
			return( $original_plaintext );
		}	
	}
}
?>