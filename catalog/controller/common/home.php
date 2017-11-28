<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}
                $this->load->model('catalog/brand');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
                
                
                //вытаскиваем всех производителей и кладём на главную
                
                
                $this->load->model('tool/image');
        
                $results = $this->model_catalog_brand->getBrands();
                //exit(var_dump($results));

                $data['brands'] = array();
                foreach ($results as $result) {

                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], 57, 57);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', 57, 57);
                    }

                    $data['brands'][] = array(
                        'id' => $result['id'],
                        'name' => $result['brand_name'],
                        'img' => $image,
                        'href' => $this->url->link('product/brand/info', 'brand_id=' . $result['id'])
                    );

                }
                
                //---------------------------------------------------
                
                
                
                
		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
