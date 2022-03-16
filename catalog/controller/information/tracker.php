<?php
class ControllerInformationTracker extends Controller{
    public function index(){

		if(($this->request->get['information_id'])=='10'){
			$dt=strtotime( now);
			$dtm=(date("Y-m-d", $dt));
			$dat=$this->db->query("SELECT * FROM click_tracker WHERE track_date='".$dtm."' AND track_type ='"."food health tips"."' AND platform='".$this->request->get['platform']."'");
			if ($dat->num_rows) {
				$this->db->query("UPDATE  click_tracker  SET clicks = (clicks + 1) WHERE track_type ='"."food health tips"."' AND platform='".$this->request->get['platform']."'");
			}else{
				$this->db->query("INSERT click_tracker  SET platform= '".$this->request->get['platform']."' , track_type='"."food health tips"."',clicks='1',track_date='".$dtm."'");
			}

		}
        if(($this->request->get['information_id'])=='12'){

			$dt=strtotime( now);
			$dtm=(date("Y-m-d", $dt));
			$dat=$this->db->query("SELECT * FROM click_tracker WHERE track_date='".$dtm."' AND track_type ='"."faq how to"."' AND platform='".$this->request->get['platform']."'");
			if ($dat->num_rows) {
				$this->db->query("UPDATE  click_tracker  SET clicks = (clicks + 1) WHERE track_type ='"."faq how to"."' AND platform='".$this->request->get['platform']."'");
			}else{
				$this->db->query("INSERT click_tracker  SET platform= '".$this->request->get['platform']."' , track_type='"."faq how to"."',clicks='1',track_date='".$dtm."'");
			}
		}
		

        // $file= fopen("tracker.txt",'r+');
        // $clicks=fread($file,filesize('tracker.txt'));
        // $clicks1=(int)$clicks;
        // $clicks1++;
        // // var_dump($clicks1);
        // $file1=fopen("tracker.txt",'w+');
        // fwrite($file1,$clicks1);
        // fclose($file);
        // fclose($file1);

        // $strpltm=($this->request->get['platform']);
        // if($strpltm == "web"){
            
        //     $file= fopen("trackerweb.txt",'r+');
        //     $clicks=fread($file,filesize('trackerweb.txt'));
        //     $clicks1=(int)$clicks;
        //     // var_dump($clicks1);
        //     $clicks1++;
        //     // var_dump($clicks1);

        //     $file1=fopen("trackerweb.txt",'w+');
        //     fwrite($file1,$clicks1);
        //     fclose($file);
        //     fclose($file1);

        // }
        // else{
        //     $file= fopen("trackermobile.txt",'r+');
        //     $clicks=fread($file,filesize('trackermobile.txt'));
        //     $clicks1=(int)$clicks;
        //     $clicks1++;
        //     $file1=fopen("trackermobile.txt",'w+');
        //     fwrite($file1,$clicks1);
        //     fclose($file);
        //     fclose($file1);

        // }
       
        $this->load->language('information/information');

		$this->load->model('catalog/information');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$this->document->setTitle($information_info['meta_title']);
			$this->document->setDescription($information_info['meta_description']);
			$this->document->setKeywords($information_info['meta_keyword']);

			$data['breadcrumbs'][] = array(
				'text' => $information_info['title'],
				'href' => $this->url->link('information/information', 'information_id=' .  $information_id)
			);

			$data['heading_title'] = $information_info['title'];

			$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			//send store id
			$data['store_id'] = $this->config->get('config_store_id');
			//send information id
			$data['information_id'] = $information_id;
			
			//custom information
			$data['custom_info'] = json_encode(get_included_files());
			
			$this->response->setOutput($this->load->view('information/information', $data));
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('information/information', 'information_id=' . $information_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function agree() {
		$this->load->model('catalog/information');

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$output = '';

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
		}

		$this->response->setOutput($output);

    }
}