<?php
class ControllerLayoutHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();
		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}
                $this->load->model('tool/image');
                
                $data['logo'] = $this->model_tool_image->resize('../image/asmLogo.png',65,40);
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['code'] = $this->language->get('code');
		$data['lang'] = $this->language->get('lang');
		$data['direction'] = $this->language->get('direction');

		$this->load->language('common/header');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_order'] = $this->language->get('text_order');
		$data['text_processing_status'] = $this->language->get('text_processing_status');
		$data['text_complete_status'] = $this->language->get('text_complete_status');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_online'] = $this->language->get('text_online');
		$data['text_approval'] = $this->language->get('text_approval');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_stock'] = $this->language->get('text_stock');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_front'] = $this->language->get('text_front');
		$data['text_help'] = $this->language->get('text_help');
		$data['text_homepage'] = $this->language->get('text_homepage');
		$data['text_documentation'] = $this->language->get('text_documentation');
		$data['text_support'] = $this->language->get('text_support');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
		$data['text_logout'] = $this->language->get('text_logout');
                
                
                
		if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$data['logged'] = '';

			$data['home'] = $this->url->link('common/dashboard', '', true);
		} else {
			$data['logged'] = true;

			$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);
			$data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], true);

			// Orders
			$this->load->model('sale/order');

			// Processing Orders
			$data['processing_status_total'] = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_processing_status'))));
			$data['processing_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=' . implode(',', $this->config->get('config_processing_status')), true);

			// Complete Orders
			$data['complete_status_total'] = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_complete_status'))));
			$data['complete_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=' . implode(',', $this->config->get('config_complete_status')), true);

			// Returns
			$this->load->model('sale/return');

			$return_total = $this->model_sale_return->getTotalReturns(array('filter_return_status_id' => $this->config->get('config_return_status_id')));

			$data['return_total'] = $return_total;

			$data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], true);

			// Customers
			$this->load->model('report/customer');

			$data['online_total'] = $this->model_report_customer->getTotalCustomersOnline();

			$data['online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);

			$this->load->model('customer/customer');

			$customer_total = $this->model_customer_customer->getTotalCustomers(array('filter_approved' => false));

			$data['customer_total'] = $customer_total;
			$data['customer_approval'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&filter_approved=0', true);

			// Products
			$this->load->model('catalog/product');

			$product_total = $this->model_catalog_product->getTotalProducts(array('filter_quantity' => 0));

			$data['product_total'] = $product_total;

			$data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&filter_quantity=0', true);

			// Reviews
			$this->load->model('catalog/review');

			$review_total = $this->model_catalog_review->getTotalReviews(array('filter_status' => 0));

			$data['review_total'] = $review_total;

			$data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&filter_status=0', true);

			// Affliate
			$this->load->model('marketing/affiliate');

			$affiliate_total = $this->model_marketing_affiliate->getTotalAffiliates(array('filter_approved' => false));

			$data['affiliate_total'] = $affiliate_total;
			$data['affiliate_approval'] = $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'] . '&filter_approved=1', true);

			$data['alerts'] = $customer_total + $product_total + $review_total + $return_total + $affiliate_total;

			// Online Stores
			$data['stores'] = array();

			$data['stores'][] = array(
				'name' => $this->config->get('config_name'),
				'href' => HTTP_CATALOG
			);

			$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
                        $data['ses_token'] = $this->request->get['token'];
                        
                        $fcItems = $this->user->getLayout();
                        $data['fcItems'] = $fcItems['fcmenu'];
                        
                        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."order WHERE viewed = 0 ");
                        if($sup->num_rows){
                            $data['notice']['order'] = '<a class="hidden-md hidden-lg" href="'.$this->url->link('report/orders', 'token='.$this->session->data['token']).'" style="float: left;"><div class="top-notice orders"><h3><b style="color: white;">'.$sup->num_rows.'</b></h3></div></a>';
                        }
                        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_avito WHERE message = 1 ");
                        if($sup->num_rows){
                            $data['notice']['avito'] = '<a href="'.$this->url->link('avito/avito_list', 'token='.$this->session->data['token']).'" style="float: left;"><div class="top-notice avito"><h3><b style="color: white;">'.$sup->num_rows.'</b></h3></div></a>';
                        }
                        //CKEditor
                        if ($this->config->get('config_editor_default')) {
                            $data['scripts'][]='view/javascript/ckeditor/ckeditor.js';
                            $data['scripts'][]='view/javascript/ckeditor/ckeditor_init.js';
                        } else {
                            $data['scripts'][]='view/javascript/summernote/summernote.js';
                            $data['scripts'][]='view/javascript/summernote/lang/summernote-'.$this->language->get('lang').'.js';
                            $data['scripts'][]='view/javascript/summernote/opencart.js';
                            $data['styles'][] = array(
                                'href'=>'view/javascript/summernote/summernote.css',
                                'rel'=>'stylesheet',
                                'media'=>'screen'
                            );
                        }
                        $data['notices'] = $this->load->controller('layout/notices');
                        $this->load->model('tool/layout');
                        $notices = $this->model_tool_layout->getnoticeTotals();

                        foreach ($notices['notices'] as $key => $notice) {
                            $tmp = 'get'.$key;
                            $data['persMod'][$key]['link'] = '<li role="presentation"><a href="#'.$key.'" '.((int)$notice['fastviewed']?'btn_type="upd-notice"':'').' aria-controls="'.$key.'" role="tab" data-toggle="tab"><i class="'.$notice['icon'].'" btn_type="upd-notice"></i> '.$notice['text'].' '.($notice['new']?'<span class="hasNew">'.$notice['new'].'</span>':'').'</a></li>';
                            $data['persMod'][$key]['tab'] = $this->model_tool_layout->$tmp();
                        }
		}
                
		return $this->load->view('layout/header', $data);
	}
}