<?php
class ControllerReportsReport extends Controller {
	public function index() {

        $this->load->model('reports/report');

        if($this->request->get['date_to']){
            $date_to=$this->request->get['date_to'].' 00:00:00';

        }else{
            $date_to=NULL;
        }

        if($this->request->get['date_from']){
            $date_from=$this->request->get['date_from'].' 00:00:00';

        }else{
            $date_from=NULL;
        }
       

		$sales_total = $this->model_reports_report->getsalesreport($this->request->get['Product_id_filter'],$date_from,$date_to);

        foreach ($sales_total as $result) {

			$data['products'][] = array(
				'order_id' => $result['order_id'],
				'product_id' => $result['product_id'],
				'name'    => $result['name'],
				'model'    => $result['model'],
				'quantity'  => $result['quantity'],
				'price'     => $result['price'],
				'total'  => $result['total']
			);
		}

        // var_dump($products);
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        $data['href'] = 'index.php?route=reports/report&user_token='.$this->request->get['user_token'];


        $this->response->setOutput($this->load->view('reports/report', $data));


    }
}