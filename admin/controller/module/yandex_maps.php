<?php
class ControllerModuleYandexMaps extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/yandex_maps');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('yandex_maps', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['ymaps_version'] = $this->language->get('ymaps_version');
		$this->data['ymaps_info'] = $this->language->get('ymaps_info');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_map'] = $this->language->get('text_map');
		$this->data['text_satellite'] = $this->language->get('text_satellite');
		$this->data['text_hybrid'] = $this->language->get('text_hybrid');
		$this->data['text_publicMap'] = $this->language->get('text_publicMap');
		$this->data['text_publicMapHybrid'] = $this->language->get('text_publicMapHybrid');
		$this->data['text_homemap'] = $this->language->get('text_homemap');
		$this->data['text_street'] = $this->language->get('text_street');
		$this->data['text_city'] = $this->language->get('text_city');
		$this->data['text_country'] = $this->language->get('text_country');
		$this->data['text_world'] = $this->language->get('text_world');
		$this->data['text_caution'] = $this->language->get('text_caution');
		$this->data['text_visual'] = $this->language->get('text_visual');
		$this->data['text_html'] = $this->language->get('text_html');
		$this->data['text_doticon'] = $this->language->get('text_doticon');
		$this->data['text_icon'] = $this->language->get('text_icon');
		$this->data['text_circledoticon'] = $this->language->get('text_circledoticon');
		$this->data['text_circleicon'] = $this->language->get('text_circleicon');
		$this->data['text_adminlang'] = $this->language->get('text_adminlang');
		$this->data['text_buypro'] = $this->language->get('text_buypro');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_settigns'] = $this->language->get('entry_settigns');
		$this->data['entry_ballon_text'] = $this->language->get('entry_ballon_text');
		$this->data['entry_theme_box'] = $this->language->get('entry_theme_box');
		$this->data['entry_theme_box_title'] = $this->language->get('entry_theme_box_title');
		$this->data['entry_theme_show_box'] = $this->language->get('entry_theme_show_box');
		$this->data['entry_options'] = $this->language->get('entry_options');
		$this->data['entry_latlong'] = $this->language->get('entry_latlong');
		$this->data['entry_widthheight'] = $this->language->get('entry_widthheight');
		$this->data['entry_zoom'] = $this->language->get('entry_zoom');
		$this->data['entry_mts'] = $this->language->get('entry_mts');
		$this->data['entry_mapid'] = $this->language->get('entry_mapid');
		$this->data['entry_maptype'] = $this->language->get('entry_maptype');
		$this->data['entry_maplang'] = $this->language->get('entry_maplang');
		$this->data['entry_standard_mark'] = $this->language->get('entry_standard_mark');
		$this->data['entry_marktype'] = $this->language->get('entry_marktype');
		$this->data['entry_iconcolor'] = $this->language->get('entry_iconcolor');
		$this->data['entry_custommarker'] = $this->language->get('entry_custommarker');
		$this->data['entry_iconimagehref'] = $this->language->get('entry_iconimagehref');
		$this->data['entry_iconimagesize'] = $this->language->get('entry_iconimagesize');
		$this->data['entry_iconimageoffset'] = $this->language->get('entry_iconimageoffset');
		
		$this->data['confirm_mapid'] = $this->language->get('confirm_mapid');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_addmarker'] = $this->language->get('button_addmarker');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_addmap'] = $this->language->get('button_addmap');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/yandex_maps', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/yandex_maps', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['yandex_maps_module'])) {
			$this->data['modules'] = $this->request->post['yandex_maps_module'];
		} elseif ($this->config->get('yandex_maps_module')) { 
			$this->data['modules'] = $this->config->get('yandex_maps_module');
		}
		
		$this->data['ymaps'] = array();
		
		if (isset($this->request->post['yandex_maps_module_map'])) {
			$this->data['ymaps'] = $this->request->post['yandex_maps_module_map'];
		} elseif ($this->config->get('yandex_maps_module_map')) { 
			$this->data['ymaps'] = $this->config->get('yandex_maps_module_map');
		} 		
					
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/yandex_maps.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/yandex_maps')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		
		if (isset($this->request->post['yandex_maps_module_map'])) {
			foreach ($this->request->post['yandex_maps_module_map'] as $key => $value) {
				if (!$value['mapalias']) {
					$this->error['warning'] = $this->language->get('error_mapid');
				}			
			}
		}
		
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>