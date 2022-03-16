<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		//for tracking

			$dt=strtotime( now);
			$dtm=(date("Y-m-d", $dt));
			
			$dathome=$this->db->query("SELECT * FROM click_tracker WHERE track_date='".$dtm."' AND track_type ='".($this->config->get('config_meta_description'))."' AND platform='".web."'");
			if ($dathome->num_rows) {
				$this->db->query("UPDATE  click_tracker  SET clicks = (clicks + 1) WHERE track_type ='".($this->config->get('config_meta_description'))."' AND platform='".web."'");
				}else{
				$this->db->query("INSERT click_tracker  SET platform= '".web."' , track_type='".($this->config->get('config_meta_description'))."',clicks='1',track_date='".$dtm."'");
			}

		

		//end for tracking



		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_new'] = $this->load->controller('common/content_new');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
