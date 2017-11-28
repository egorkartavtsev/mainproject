<?php  
class ControllerModuleYandexMaps extends Controller {
	protected function index($setting) {
		static $module_map = 0;
		
		$this->language->load('module/yandex_maps');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$maps =  array();
		if (isset($this->request->post['yandex_maps_module_map'])) {
			$maps = $this->request->post['yandex_maps_module_map'];
		} elseif ($this->config->get('yandex_maps_module_map')) { 
			$maps = $this->config->get('yandex_maps_module_map');
		}
		$this->data['ymaps'] = array();
		$fistmaplatlong = false;
		foreach ($maps as $map) {
			$split_mts = explode(',', $setting['mts']);
			
			foreach ($split_mts as $smts) {
				if ($smts == $map['mapalias']) {
					if ($fistmaplatlong == false) {
						$this->data['ymap_flatlong'] = $map['latlong'];
						$fistmaplatlong = true;
					}
					$tmpmaptext = $map['maptext'][$this->config->get('config_language_id')];
					$tmpmaptext = str_replace('\n', '', $tmpmaptext);
					$tmpmaptext = str_replace(PHP_EOL, '', $tmpmaptext);

					$tmponeline = $map['onelinetext'][$this->config->get('config_language_id')];
					$tmponeline = str_replace('\n', '', $tmponeline);
					$tmponeline = str_replace(PHP_EOL, '', $tmponeline);

					$this->data['ymaps'][] = array(
						'mapalias'		=> $map['mapalias'],
						'onelinetext'	=> html_entity_decode($tmponeline, ENT_QUOTES, 'UTF-8'),
						'latlong'		=> $map['latlong'],
						'icontype'	=> $map['icontype'],
						'iconcolor'	=> $map['iconcolor'],
						'iconimagehref'	=> $map['iconimagehref'],
						'iconimagesize'	=> $map['iconimagesize'],
						'iconimageoffset'	=> $map['iconimageoffset'],
						'maptext'		=> html_entity_decode($tmpmaptext, ENT_QUOTES, 'UTF-8')
					);				
				
				}
				
			}

		}
		$this->data['ymap_showbox'] = $setting['showbox'];
		$this->data['ymap_maptype'] = $setting['maptype'];
		$this->data['ymap_maplang'] = $setting['maplang'];
		$this->data['ymap_boxtitle'] = $setting['boxtitle'][$this->config->get('config_language_id')];
		$this->data['ymap_width'] = $setting['width'];
		$this->data['ymap_height'] = $setting['height'];
		$this->data['ymap_zoom'] = $setting['zoom'];
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/yandex_maps.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/yandex_maps.tpl';
		} else {
			$this->template = 'default/template/module/yandex_maps.tpl';
		}
		$this->data['module_map'] = $module_map++;
		
		$this->render();
	}
}

?>