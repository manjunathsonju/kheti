<?php
class ControllerExtensionModuleMayAdvancedOptions extends Controller {
	private $error = array();

	private $events = array(
		'may_ao_av_cocl_before' => array(
			'trigger' => 'admin/view/common/column_left/before',
			'action' => 'extension/may/advanced_options/vCommonColumnLeftBefore'
		),
		'may_ao_av_cpf_before' => array(
			'trigger' => 'admin/view/catalog/product_form/before',
			'action' => 'extension/may/advanced_options/vCatalogProductFormBefore'
		),
		'may_ao_am_cpap_after' => array(
			'trigger' => 'admin/model/catalog/product/addProduct/after',
			'action' => 'extension/may/advanced_options/mCatalogProductAddProductAfter'
		),
		'may_ao_am_cpep_after' => array(
			'trigger' => 'admin/model/catalog/product/editProduct/after',
			'action' => 'extension/may/advanced_options/mCatalogProductEditProductAfter'
		),
		'may_ao_am_codo_after' => array(
			'trigger' => 'admin/model/catalog/option/deleteOption/after',
			'action' => 'extension/may/advanced_options/mCatalogOptionDeleteOptionAfter'
		),
		'may_ao_am_coeo_before' => array(
			'trigger' => 'admin/model/catalog/option/editOption/before',
			'action' => 'extension/may/advanced_options/mCatalogOptionDeleteOptionBefore'
		),
		'may_ao_av_soi_before' => array(
			'trigger' => 'admin/view/sale/order_info/before',
			'action' => 'extension/may/advanced_options/vSaleOrderInfoBefore'
		),
		'may_ao_av_soiv_before' => array(
			'trigger' => 'admin/view/sale/order_invoice/before',
			'action' => 'extension/may/advanced_options/vSaleOrderInvoiceBefore'
		),
		'may_ao_av_sos_before' => array(
			'trigger' => 'admin/view/sale/order_shipping/before',
			'action' => 'extension/may/advanced_options/vSaleOrderShippingBefore'
		),

		'may_ao_cv_pp_before' => array(
			'trigger' => 'catalog/view/product/product/before',
			'action' => 'extension/may/advanced_options/vProductProductBefore'
		),
		'may_ao_cv_pc_before' => array(
			'trigger' => 'catalog/view/product/category/before',
			'action' => 'extension/may/advanced_options/vProductCategoryBefore'
		),
		'may_ao_cv_coc_before' => array(
			'trigger' => 'catalog/view/common/cart/before',
			'action' => 'extension/may/advanced_options/vCommonCartBefore'
		),
		'may_ao_cv_chc_before' => array(
			'trigger' => 'catalog/view/checkout/cart/before',
			'action' => 'extension/may/advanced_options/vCheckoutCartBefore'
		),
		'may_ao_cm_choao_before' => array(
			'trigger' => 'catalog/model/checkout/order/addOrder/before',
			'action' => 'extension/may/advanced_options/mCheckoutOrderAddOrderBefore'
		),
		'may_ao_cv_chcf_before' => array(
			'trigger' => 'catalog/view/checkout/confirm/before',
			'action' => 'extension/may/advanced_options/vCheckoutConfirmBefore'
		),
		'may_ao_cm_cpgp_after' => array(
			'trigger' => 'catalog/model/catalog/product/getProduct/after',
			'action' => 'extension/may/advanced_options/mCatalogProductGetProductAfter'
		),
	);

	public function index() {
		$this->load->language('extension/module/may_advanced_options');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/javascript/may/css/colorpicker.css');
		$this->document->addScript('view/javascript/may/js/colorpicker.js');

		$this->document->addStyle('view/stylesheet/may/advanced_options.css');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('may_advanced_options', $this->request->post);

			$this->load->model('user/user_group');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_edit_hide_data_per_page'] = $this->language->get('text_edit_hide_data_per_page');
		$data['text_edit_hide_data_page_pp'] = $this->language->get('text_edit_hide_data_page_pp');
		$data['text_edit_hide_data_page_cc'] = $this->language->get('text_edit_hide_data_page_cc');
		$data['text_edit_hide_data_page_oi'] = $this->language->get('text_edit_hide_data_page_oi');
		$data['text_edit_for_developer'] = $this->language->get('text_edit_for_developer');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['text_edit_swatch_style'] = $this->language->get('text_edit_swatch_style');
		$data['text_edit_swatch_style_circle'] = $this->language->get('text_edit_swatch_style_circle');
		$data['text_edit_swatch_style_rectangle'] = $this->language->get('text_edit_swatch_style_rectangle');
		$data['text_edit_swatch_style_custom'] = $this->language->get('text_edit_swatch_style_custom');


		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_attribute'] = $this->language->get('entry_attribute');

		$data['entry_attribute_model'] = $this->language->get('entry_attribute_model');
		$data['entry_attribute_sku'] = $this->language->get('entry_attribute_sku');
		$data['entry_attribute_upc'] = $this->language->get('entry_attribute_upc');
		$data['entry_attribute_ean'] = $this->language->get('entry_attribute_ean');
		$data['entry_attribute_jan'] = $this->language->get('entry_attribute_jan');
		$data['entry_attribute_isbn'] = $this->language->get('entry_attribute_isbn');
		$data['entry_attribute_mpn'] = $this->language->get('entry_attribute_mpn');
		$data['entry_attribute_location'] = $this->language->get('entry_attribute_location');
		$data['entry_attribute_dimension'] = $this->language->get('entry_attribute_dimension');

		$data['entry_attribute_quantity'] = $this->language->get('entry_attribute_quantity');
		$data['entry_attribute_price'] = $this->language->get('entry_attribute_price');
		$data['entry_attribute_point'] = $this->language->get('entry_attribute_point');
		$data['entry_attribute_weight'] = $this->language->get('entry_attribute_weight');
		$data['entry_attribute_image'] = $this->language->get('entry_attribute_image');

		$data['entry_show_option_price'] = $this->language->get('entry_show_option_price');

		$data['entry_wrapper'] = $this->language->get('entry_wrapper');
		$data['entry_wrapper_comment'] = $this->language->get('entry_wrapper_comment');
		$data['entry_swatches'] = $this->language->get('entry_swatches');
		$data['entry_swatch_image'] = $this->language->get('entry_swatch_image');

		$data['entry_swatch_style_shape'] = $this->language->get('entry_swatch_style_shape');
		$data['entry_swatch_style_size'] = $this->language->get('entry_swatch_style_size');
		$data['entry_swatch_style_size_width'] = $this->language->get('entry_swatch_style_size_width');
		$data['entry_swatch_style_size_height'] = $this->language->get('entry_swatch_style_size_height');
		$data['entry_swatch_style_size_radius'] = $this->language->get('entry_swatch_style_size_radius');
		$data['entry_swatch_style_border'] = $this->language->get('entry_swatch_style_border');
		$data['entry_swatch_style_border_width'] = $this->language->get('entry_swatch_style_border_width');
		$data['entry_swatch_style_border_color_default'] = $this->language->get('entry_swatch_style_border_color_default');
		$data['entry_swatch_style_border_color_selected'] = $this->language->get('entry_swatch_style_border_color_selected');
		$data['entry_swatch_style_space'] = $this->language->get('entry_swatch_style_space');
		$data['entry_swatch_style_space_padding'] = $this->language->get('entry_swatch_style_space_padding');
		$data['entry_swatch_style_space_margin'] = $this->language->get('entry_swatch_style_space_margin');

		$data['entry_swatch_css'] = $this->language->get('entry_swatch_css');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/may_advanced_options', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/may_advanced_options', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['may_advanced_options_status'])) {
			$data['may_advanced_options_status'] = $this->request->post['may_advanced_options_status'];
		} else {
			$data['may_advanced_options_status'] = $this->config->get('may_advanced_options_status');
		}

		if (isset($this->request->post['may_advanced_options_attribute_model'])) {
			$data['may_advanced_options_attribute_model'] = $this->request->post['may_advanced_options_attribute_model'];
		} else {
			$data['may_advanced_options_attribute_model'] = $this->config->get('may_advanced_options_attribute_model');
		}

		if (isset($this->request->post['may_advanced_options_attribute_sku'])) {
			$data['may_advanced_options_attribute_sku'] = $this->request->post['may_advanced_options_attribute_sku'];
		} else {
			$data['may_advanced_options_attribute_sku'] = $this->config->get('may_advanced_options_attribute_sku');
		}

		if (isset($this->request->post['may_advanced_options_attribute_upc'])) {
			$data['may_advanced_options_attribute_upc'] = $this->request->post['may_advanced_options_attribute_upc'];
		} else {
			$data['may_advanced_options_attribute_upc'] = $this->config->get('may_advanced_options_attribute_upc');
		}

		if (isset($this->request->post['may_advanced_options_attribute_ean'])) {
			$data['may_advanced_options_attribute_ean'] = $this->request->post['may_advanced_options_attribute_ean'];
		} else {
			$data['may_advanced_options_attribute_ean'] = $this->config->get('may_advanced_options_attribute_ean');
		}

		if (isset($this->request->post['may_advanced_options_attribute_jan'])) {
			$data['may_advanced_options_attribute_jan'] = $this->request->post['may_advanced_options_attribute_jan'];
		} else {
			$data['may_advanced_options_attribute_jan'] = $this->config->get('may_advanced_options_attribute_jan');
		}

		if (isset($this->request->post['may_advanced_options_attribute_isbn'])) {
			$data['may_advanced_options_attribute_isbn'] = $this->request->post['may_advanced_options_attribute_isbn'];
		} else {
			$data['may_advanced_options_attribute_isbn'] = $this->config->get('may_advanced_options_attribute_isbn');
		}

		if (isset($this->request->post['may_advanced_options_attribute_mpn'])) {
			$data['may_advanced_options_attribute_mpn'] = $this->request->post['may_advanced_options_attribute_mpn'];
		} else {
			$data['may_advanced_options_attribute_mpn'] = $this->config->get('may_advanced_options_attribute_mpn');
		}

		if (isset($this->request->post['may_advanced_options_attribute_location'])) {
			$data['may_advanced_options_attribute_location'] = $this->request->post['may_advanced_options_attribute_location'];
		} else {
			$data['may_advanced_options_attribute_location'] = $this->config->get('may_advanced_options_attribute_location');
		}

		if (isset($this->request->post['may_advanced_options_attribute_dimension'])) {
			$data['may_advanced_options_attribute_dimension'] = $this->request->post['may_advanced_options_attribute_dimension'];
		} else {
			$data['may_advanced_options_attribute_dimension'] = $this->config->get('may_advanced_options_attribute_dimension');
		}

		if (isset($this->request->post['may_advanced_options_attribute_quantity'])) {
			$data['may_advanced_options_attribute_quantity'] = $this->request->post['may_advanced_options_attribute_quantity'];
		} else {
			$data['may_advanced_options_attribute_quantity'] = $this->config->get('may_advanced_options_attribute_quantity');
		}

		if (isset($this->request->post['may_advanced_options_attribute_price'])) {
			$data['may_advanced_options_attribute_price'] = $this->request->post['may_advanced_options_attribute_price'];
		} else {
			$data['may_advanced_options_attribute_price'] = $this->config->get('may_advanced_options_attribute_price');
		}

		if (isset($this->request->post['may_advanced_options_attribute_point'])) {
			$data['may_advanced_options_attribute_point'] = $this->request->post['may_advanced_options_attribute_point'];
		} else {
			$data['may_advanced_options_attribute_point'] = $this->config->get('may_advanced_options_attribute_point');
		}

		if (isset($this->request->post['may_advanced_options_weight'])) {
			$data['may_advanced_options_attribute_weight'] = $this->request->post['may_advanced_options_attribute_weight'];
		} else {
			$data['may_advanced_options_attribute_weight'] = $this->config->get('may_advanced_options_attribute_weight');
		}

		if (isset($this->request->post['may_advanced_options_show_option_price'])) {
			$data['may_advanced_options_show_option_price'] = $this->request->post['may_advanced_options_show_option_price'];
		} else {
			$data['may_advanced_options_show_option_price'] = $this->config->get('may_advanced_options_show_option_price');
		}

		if (isset($this->request->post['may_advanced_options_wrapper'])) {
			$data['may_advanced_options_wrapper'] = $this->request->post['may_advanced_options_wrapper'];
		} else {
			$data['may_advanced_options_wrapper'] = $this->config->get('may_advanced_options_wrapper');
		}

		if (isset($this->request->post['may_advanced_options_swatches'])) {
			$data['may_advanced_options_swatches'] = $this->request->post['may_advanced_options_swatches'];
		} else {
			$data['may_advanced_options_swatches'] = $this->config->get('may_advanced_options_swatches');
		}

		if (isset($this->request->post['may_advanced_options_swatch_image'])) {
			$data['may_advanced_options_swatch_image'] = $this->request->post['may_advanced_options_swatch_image'];
		} else {
			$data['may_advanced_options_swatch_image'] = $this->config->get('may_advanced_options_swatch_image');
		}

		if (isset($this->request->post['may_advanced_options_hide_pp_sku'])) {
			$data['may_advanced_options_hide_pp_sku'] = $this->request->post['may_advanced_options_hide_pp_sku'];
		} else {
			$data['may_advanced_options_hide_pp_sku'] = $this->config->get('may_advanced_options_hide_pp_sku');
		}

		if (isset($this->request->post['may_advanced_options_hide_pp_upc'])) {
			$data['may_advanced_options_hide_pp_upc'] = $this->request->post['may_advanced_options_hide_pp_upc'];
		} else {
			$data['may_advanced_options_hide_pp_upc'] = $this->config->get('may_advanced_options_hide_pp_upc');
		}

		if (isset($this->request->post['may_advanced_options_hide_pp_ean'])) {
			$data['may_advanced_options_hide_pp_ean'] = $this->request->post['may_advanced_options_hide_pp_ean'];
		} else {
			$data['may_advanced_options_hide_pp_ean'] = $this->config->get('may_advanced_options_hide_pp_ean');
		}

		if (isset($this->request->post['may_advanced_options_hide_pp_jan'])) {
			$data['may_advanced_options_hide_pp_jan'] = $this->request->post['may_advanced_options_hide_pp_jan'];
		} else {
			$data['may_advanced_options_hide_pp_jan'] = $this->config->get('may_advanced_options_hide_pp_jan');
		}

		if (isset($this->request->post['may_advanced_options_hide_pp_isbn'])) {
			$data['may_advanced_options_hide_pp_isbn'] = $this->request->post['may_advanced_options_hide_pp_isbn'];
		} else {
			$data['may_advanced_options_hide_pp_isbn'] = $this->config->get('may_advanced_options_hide_pp_isbn');
		}

		if (isset($this->request->post['may_advanced_options_hide_pp_mpn'])) {
			$data['may_advanced_options_hide_pp_mpn'] = $this->request->post['may_advanced_options_hide_pp_mpn'];
		} else {
			$data['may_advanced_options_hide_pp_mpn'] = $this->config->get('may_advanced_options_hide_pp_mpn');
		}

		if (isset($this->request->post['may_advanced_options_hide_pp_location'])) {
			$data['may_advanced_options_hide_pp_location'] = $this->request->post['may_advanced_options_hide_pp_location'];
		} else {
			$data['may_advanced_options_hide_pp_location'] = $this->config->get('may_advanced_options_hide_pp_location');
		}

		if (isset($this->request->post['may_advanced_options_hide_pp_dimension'])) {
			$data['may_advanced_options_hide_pp_dimension'] = $this->request->post['may_advanced_options_hide_pp_dimension'];
		} else {
			$data['may_advanced_options_hide_pp_dimension'] = $this->config->get('may_advanced_options_hide_pp_dimension');
		}

		if (isset($this->request->post['may_advanced_options_hide_cc_sku'])) {
			$data['may_advanced_options_hide_cc_sku'] = $this->request->post['may_advanced_options_hide_cc_sku'];
		} else {
			$data['may_advanced_options_hide_cc_sku'] = $this->config->get('may_advanced_options_hide_cc_sku');
		}

		if (isset($this->request->post['may_advanced_options_hide_cc_upc'])) {
			$data['may_advanced_options_hide_cc_upc'] = $this->request->post['may_advanced_options_hide_cc_upc'];
		} else {
			$data['may_advanced_options_hide_cc_upc'] = $this->config->get('may_advanced_options_hide_cc_upc');
		}

		if (isset($this->request->post['may_advanced_options_hide_cc_ean'])) {
			$data['may_advanced_options_hide_cc_ean'] = $this->request->post['may_advanced_options_hide_cc_ean'];
		} else {
			$data['may_advanced_options_hide_cc_ean'] = $this->config->get('may_advanced_options_hide_cc_ean');
		}

		if (isset($this->request->post['may_advanced_options_hide_cc_jan'])) {
			$data['may_advanced_options_hide_cc_jan'] = $this->request->post['may_advanced_options_hide_cc_jan'];
		} else {
			$data['may_advanced_options_hide_cc_jan'] = $this->config->get('may_advanced_options_hide_cc_jan');
		}

		if (isset($this->request->post['may_advanced_options_hide_cc_isbn'])) {
			$data['may_advanced_options_hide_cc_isbn'] = $this->request->post['may_advanced_options_hide_cc_isbn'];
		} else {
			$data['may_advanced_options_hide_cc_isbn'] = $this->config->get('may_advanced_options_hide_cc_isbn');
		}

		if (isset($this->request->post['may_advanced_options_hide_cc_mpn'])) {
			$data['may_advanced_options_hide_cc_mpn'] = $this->request->post['may_advanced_options_hide_cc_mpn'];
		} else {
			$data['may_advanced_options_hide_cc_mpn'] = $this->config->get('may_advanced_options_hide_cc_mpn');
		}

		if (isset($this->request->post['may_advanced_options_hide_cc_location'])) {
			$data['may_advanced_options_hide_cc_location'] = $this->request->post['may_advanced_options_hide_cc_location'];
		} else {
			$data['may_advanced_options_hide_cc_location'] = $this->config->get('may_advanced_options_hide_cc_location');
		}

		if (isset($this->request->post['may_advanced_options_hide_cc_dimension'])) {
			$data['may_advanced_options_hide_cc_dimension'] = $this->request->post['may_advanced_options_hide_cc_dimension'];
		} else {
			$data['may_advanced_options_hide_cc_dimension'] = $this->config->get('may_advanced_options_hide_cc_dimension');
		}		

		if (isset($this->request->post['may_advanced_options_hide_oi_sku'])) {
			$data['may_advanced_options_hide_oi_sku'] = $this->request->post['may_advanced_options_hide_oi_sku'];
		} else {
			$data['may_advanced_options_hide_oi_sku'] = $this->config->get('may_advanced_options_hide_oi_sku');
		}

		if (isset($this->request->post['may_advanced_options_hide_oi_upc'])) {
			$data['may_advanced_options_hide_oi_upc'] = $this->request->post['may_advanced_options_hide_oi_upc'];
		} else {
			$data['may_advanced_options_hide_oi_upc'] = $this->config->get('may_advanced_options_hide_oi_upc');
		}

		if (isset($this->request->post['may_advanced_options_hide_oi_ean'])) {
			$data['may_advanced_options_hide_oi_ean'] = $this->request->post['may_advanced_options_hide_oi_ean'];
		} else {
			$data['may_advanced_options_hide_oi_ean'] = $this->config->get('may_advanced_options_hide_oi_ean');
		}

		if (isset($this->request->post['may_advanced_options_hide_oi_jan'])) {
			$data['may_advanced_options_hide_oi_jan'] = $this->request->post['may_advanced_options_hide_oi_jan'];
		} else {
			$data['may_advanced_options_hide_oi_jan'] = $this->config->get('may_advanced_options_hide_oi_jan');
		}

		if (isset($this->request->post['may_advanced_options_hide_oi_isbn'])) {
			$data['may_advanced_options_hide_oi_isbn'] = $this->request->post['may_advanced_options_hide_oi_isbn'];
		} else {
			$data['may_advanced_options_hide_oi_isbn'] = $this->config->get('may_advanced_options_hide_oi_isbn');
		}

		if (isset($this->request->post['may_advanced_options_hide_oi_mpn'])) {
			$data['may_advanced_options_hide_oi_mpn'] = $this->request->post['may_advanced_options_hide_oi_mpn'];
		} else {
			$data['may_advanced_options_hide_oi_mpn'] = $this->config->get('may_advanced_options_hide_oi_mpn');
		}

		if (isset($this->request->post['may_advanced_options_hide_oi_location'])) {
			$data['may_advanced_options_hide_oi_location'] = $this->request->post['may_advanced_options_hide_oi_location'];
		} else {
			$data['may_advanced_options_hide_oi_location'] = $this->config->get('may_advanced_options_hide_oi_location');
		}

		if (isset($this->request->post['may_advanced_options_hide_oi_dimension'])) {
			$data['may_advanced_options_hide_oi_dimension'] = $this->request->post['may_advanced_options_hide_oi_dimension'];
		} else {
			$data['may_advanced_options_hide_oi_dimension'] = $this->config->get('may_advanced_options_hide_oi_dimension');
		}

		if (isset($this->request->post['may_advanced_options_swatch_style_shape'])) {
			$data['may_advanced_options_swatch_style_shape'] = $this->request->post['may_advanced_options_swatch_style_shape'];
		} else {
			$data['may_advanced_options_swatch_style_shape'] = $this->config->get('may_advanced_options_swatch_style_shape');
		}

		if (isset($this->request->post['may_advanced_options_swatch_style_size_width'])) {
			$data['may_advanced_options_swatch_style_size_width'] = $this->request->post['may_advanced_options_swatch_style_size_width'];
		} else {
			$data['may_advanced_options_swatch_style_size_width'] = $this->config->get('may_advanced_options_swatch_style_size_width');
		}

		if (isset($this->request->post['may_advanced_options_swatch_style_size_height'])) {
			$data['may_advanced_options_swatch_style_size_height'] = $this->request->post['may_advanced_options_swatch_style_size_height'];
		} else {
			$data['may_advanced_options_swatch_style_size_height'] = $this->config->get('may_advanced_options_swatch_style_size_height');
		}

		if (isset($this->request->post['may_advanced_options_swatch_style_size_radius'])) {
			$data['may_advanced_options_swatch_style_size_radius'] = $this->request->post['may_advanced_options_swatch_style_size_radius'];
		} else {
			$data['may_advanced_options_swatch_style_size_radius'] = $this->config->get('may_advanced_options_swatch_style_size_radius');
		}

		if (isset($this->request->post['may_advanced_options_swatch_style_border_width'])) {
			$data['may_advanced_options_swatch_style_border_width'] = $this->request->post['may_advanced_options_swatch_style_border_width'];
		} else {
			$data['may_advanced_options_swatch_style_border_width'] = $this->config->get('may_advanced_options_swatch_style_border_width');
		}

		if (isset($this->request->post['may_advanced_options_swatch_style_border_color_selected'])) {
			$data['may_advanced_options_swatch_style_border_color_selected'] = $this->request->post['may_advanced_options_swatch_style_border_color_selected'];
		} else {
			$data['may_advanced_options_swatch_style_border_color_selected'] = $this->config->get('may_advanced_options_swatch_style_border_color_selected');
		}

		if (isset($this->request->post['may_advanced_options_swatch_style_border_color_default'])) {
			$data['may_advanced_options_swatch_style_border_color_default'] = $this->request->post['may_advanced_options_swatch_style_border_color_default'];
		} else {
			$data['may_advanced_options_swatch_style_border_color_default'] = $this->config->get('may_advanced_options_swatch_style_border_color_default');
		}

		if (isset($this->request->post['may_advanced_options_swatch_style_space_padding'])) {
			$data['may_advanced_options_swatch_style_space_padding'] = $this->request->post['may_advanced_options_swatch_style_space_padding'];
		} else {
			$data['may_advanced_options_swatch_style_space_padding'] = $this->config->get('may_advanced_options_swatch_style_space_padding');
		}

		if (isset($this->request->post['may_advanced_options_swatch_css'])) {
			$data['may_advanced_options_swatch_css'] = $this->request->post['may_advanced_options_swatch_css'];
		} else {
			$data['may_advanced_options_swatch_css'] = $this->config->get('may_advanced_options_swatch_css');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/may_advanced_options', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/may_advanced_options')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/may/advanced_options');

		$this->model_extension_may_advanced_options->install();

		$this->load->model('setting/event');

		foreach ($this->events as $event_code => $event) {
			$this->model_setting_event->deleteEventByCode($event_code);
			$this->model_setting_event->addEvent($event_code, $event['trigger'], $event['action']);
		}

		$this->load->model('setting/setting');

		$this->model_setting_setting->editSetting('may_advanced_options', array(
			'may_advanced_options_status' => 1,

			'may_advanced_options_attribute_model' => 1,
			'may_advanced_options_attribute_sku' => 1,
			'may_advanced_options_attribute_upc' => 1,
			'may_advanced_options_attribute_ean' => 1,
			'may_advanced_options_attribute_jan' => 1,
			'may_advanced_options_attribute_isbn' => 1,
			'may_advanced_options_attribute_mpn' => 1,
			'may_advanced_options_attribute_location' => 1,
			'may_advanced_options_attribute_dimension' => 1,
			'may_advanced_options_attribute_quantity' => 1,
			'may_advanced_options_attribute_price' => 1,
			'may_advanced_options_attribute_point' => 1,
			'may_advanced_options_attribute_weight' => 1,

			'may_advanced_options_show_option_price' => 1,

			'may_advanced_options_swatches' => 1,
			'may_advanced_options_swatch_image' => 0,
			'may_advanced_options_swatch_style_shape' => 'custom',
			'may_advanced_options_swatch_style_size_width' => 35,
			'may_advanced_options_swatch_style_size_height' => 35,
			'may_advanced_options_swatch_style_size_radius' => 4,
			'may_advanced_options_swatch_style_border_width' => 1,
			'may_advanced_options_swatch_style_border_color_default' => 'dddddd',
			'may_advanced_options_swatch_style_border_color_selected' => '555555',
			'may_advanced_options_swatch_style_space_padding' => 1,

			'may_advanced_options_swatch_css' => '
.may-swatches > label {
	margin-right: 2px;
}
.may-swatches input[type=radio] {
	display: none;
}
.may-swatches input[type=radio] + img,
.may-swatches input[type=radio] + span {
	display: inline-block;
	cursor: pointer;
	text-align: center;
	-webkit-transition: border .2s ease-in-out;
	-o-transition: border .2s ease-in-out;
	transition: border .2s ease-in-out;
	-webkit-transition: background .2s ease-in-out;
	-o-transition: background .2s ease-in-out;
	transition: background .2s ease-in-out;
	-webkit-transition: opacity .2s ease-in-out;
	-o-transition: opacity .2s ease-in-out;
	transition: opacity .2s ease-in-out;
	opacity: 0.8;
}
.may-swatches input[type=radio] + img {
	background: #fff !important;
}
.may-swatches input[type=radio]:enabled + img:hover,
.may-swatches input[type=radio]:enabled + span:hover {
	background-color: #eee;
	opacity: 1;
}
.may-swatches input[type=radio]:checked + img,
.may-swatches input[type=radio]:checked + span {
	opacity: 1;
}
.may-swatches input[type=radio]:disabled + span {
	background-color: #f0f0f0;
	color: #e0e0e0;
	cursor: default;
}
.may-swatches input[type=radio]:disabled + img {
	opacity: .3;
	cursor: default;
}
.may-loading {
	display: inline-block;
	position: relative;
	width: 80px;
	height: 80px;
}
.may-loading div {
	position: absolute;
	width: 6px;
	height: 6px;
	background: #ddd;
	border-radius: 50%;
	animation: may-loading 1.2s linear infinite;
}
.may-loading div:nth-child(1) {
	animation-delay: 0s;
	top: 37px;
	left: 66px;
}
.may-loading div:nth-child(2) {
	animation-delay: -0.1s;
	top: 22px;
	left: 62px;
}
.may-loading div:nth-child(3) {
	animation-delay: -0.2s;
	top: 11px;
	left: 52px;
}
.may-loading div:nth-child(4) {
	animation-delay: -0.3s;
	top: 7px;
	left: 37px;
}
.may-loading div:nth-child(5) {
	animation-delay: -0.4s;
	top: 11px;
	left: 22px;
}
.may-loading div:nth-child(6) {
	animation-delay: -0.5s;
	top: 22px;
	left: 11px;
}
.may-loading div:nth-child(7) {
	animation-delay: -0.6s;
	top: 37px;
	left: 7px;
}
.may-loading div:nth-child(8) {
	animation-delay: -0.7s;
	top: 52px;
	left: 11px;
}
.may-loading div:nth-child(9) {
	animation-delay: -0.8s;
	top: 62px;
	left: 22px;
}
.may-loading div:nth-child(10) {
	animation-delay: -0.9s;
	top: 66px;
	left: 37px;
}
.may-loading div:nth-child(11) {
	animation-delay: -1s;
	top: 62px;
	left: 52px;
}
.may-loading div:nth-child(12) {
	animation-delay: -1.1s;
	top: 52px;
	left: 62px;
}
@keyframes may-loading {
	0%, 20%, 80%, 100% {
		transform: scale(1);
	}
	50% {
		transform: scale(1.5);
	}
}
ul.thumbnails li {
	position: relative;
}
ul.thumbnails li .may-loading {
	position: absolute;
	left: 50%;
	top: 50%;
	margin-left: -40px;
	margin-top: -40px;
}
ul.thumbnails li.image-additional .may-loading {
	display: none;
}
.may-loading div {
	background: #ddd;
}
.hidden {
	display: none !important;
}
#error-quantity.has-error {
	margin-top: 5px;
	color: rgba(208, 30, 36, 1) !important;
}
			',
		));

		$this->load->model('user/user_group');

		// Compatibility
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/may');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/may/advanced_options');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/may');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/may/advanced_options');
	}

	public function uninstall() {
		$this->load->model('extension/may/advanced_options');

		$this->model_extension_may_advanced_options->uninstall();

		$this->load->model('setting/event');

		foreach ($this->events as $event_code => $event) {
			$this->model_setting_event->deleteEventByCode($event_code);
		}
	}
}
