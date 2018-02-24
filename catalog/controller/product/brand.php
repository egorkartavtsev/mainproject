<?php

class ControllerProductBrand extends Controller{
    public function index(){
        
        $this->load->model('tool/image');
        
        $query = $this->db->query("SELECT "
                . "b.id AS id, "
                . "b.name AS brand_name,  "
                . "b.image AS image "
                . "FROM " . DB_PREFIX . "brand b "
                . "WHERE parent_id = 0 "
                . "ORDER BY b.name");
        $results = $query->rows;
        
        $data['brands'] = array();
        foreach ($results as $result) {
            
            if ($result['image']) {
		$image = $this->model_tool_image->resize($result['image'], 57, 57);
            } else {
		$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
            }
            
            $data['brands'][] = array(
                'id' => $result['id'],
                'name' => $result['brand_name'],
                'img' => $image,
                'href' => $this->url->link('product/brand/info', 'brand_id=' . $result['id'])
            );
            
        }
        //var_dump($data['brands']);
                
        
        $this->load->language('product/manufacturer');
        $this->load->language('product/product');
        $this->load->model('catalog/brand');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        
        
                $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
                $data['breadcrumbs'][] = array(
			'text' => 'Производитель',
			'href' => $this->url->link('product/brand/index')
		);
                
                $this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
        
        $data['column_left'] = $this->load->controller('common/column_left');
	$data['column_right'] = $this->load->controller('common/column_right');
	$data['content_top'] = $this->load->controller('common/content_top');
	$data['content_bottom'] = $this->load->controller('common/content_bottom');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
        
        $this->response->setOutput($this->load->view('product/brand_list', $data));
        
    }
    
    public function info() {
        
        $this->load->language('product/manufacturer');
        $this->load->language('product/product');
        $this->load->model('catalog/brand');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $data['curr_cat'] = 'Выберите категорию';
        $data['curr_podcat'] = 'Выберите подкатегорию';
        
        $query = $this->db->query("SELECT "
                . "c.category_id AS id, "
                . "cd.name AS name "
                . "FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd "
                    . "ON (cd.language_id=1 AND cd.category_id = c.category_id) "
                . "WHERE c.parent_id = 0 "
                . "ORDER BY cd.name ");
        
        $results = $query->rows;
        $data['category'] = array();
        foreach ($results as $res) {
                $data['category'][] = array(
                    'name' => $res['name'],
                    'href' => '?route='.$this->request->get['route'].'&brand_id='.$this->request->get['brand_id'].'&path='.$res['id']
                );
            }
        //exit(var_dump($data['category']));
        
        if(isset($this->request->get['path'])){
            $data['podcats'] = array();
            $quer = "SELECT "
                . "c.category_id AS id, "
                . "cd.name AS name "
                . "FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd "
                    . "ON (cd.language_id=1 AND cd.category_id = c.category_id) "
                . "WHERE c.parent_id = ".$this->request->get['path'];
            $quer.=" ORDER BY cd.name ";
            $query = $this->db->query($quer);
            $cc = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = ".$this->request->get['path']." AND language_id = 1");
            $data['curr_cat'] = $cc->row['name'];
            $results = $query->rows;
            
            foreach ($results as $res) {
                $data['podcat'][] = array(
                    'name' => $res['name'],
                    'href' => '?route='.$this->request->get['route'].'&brand_id='.$this->request->get['brand_id'].'&path='.$this->request->get['path'].'&podcat='.$res['id']
                );
            }
            
        }
//        exit(var_dump($data['podcat']));
        
                if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
                
                if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
		}
                
//дальше обработка ссылочки и вытаскивание марки, модели, года плюс формирование мешуры типа сортировки и количества 
//товаров на странице и ХЛЕБНЫЕКРОШКИ                

                $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
                $data['breadcrumbs'][] = array(
			'text' => 'Производитель',
			'href' => $this->url->link('product/brand/index')
		);
                
                                
                if (isset($this->request->get['brand_id'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$mark = '';

			$parts = explode('_', (string)$this->request->get['brand_id']);
                        
			
                        

			foreach ($parts as $brand_id) {
				if (!$mark) {
					$mark = (int)$brand_id;
				} else {
					$mark .= '_' . (int)$brand_id;
				}
                                
				$mark_info = $this->model_catalog_brand->getBrandInfo($brand_id);
                                
				if ($mark_info) {
					$data['breadcrumbs'][] = array(
						'text' => $mark_info['brand_name'],
						'href' => $this->url->link('product/brand/info', 'brand_id=' . $mark . $url)
					);
				}
			}
                        $last_brand_id = (int)array_pop($parts);
		} else {
			$errors['not_found'] = 1;
		}
//*******************************************************************************************************************************
        
            $models = $this->model_catalog_brand->getChilds($last_brand_id);
            
            foreach($models as $model){
                
                $data['models'][] = array(
						'brand_name' => $model['brand_name'],
						'href' => $this->url->link('product/brand/info', 'brand_id=' . $this->request->get['brand_id'] . '_' . $model['id'])
					);
                
            }
                        
                $this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_index'] = $this->language->get('text_index');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');
            
                        $data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');
                        $data['compare'] = $this->url->link('product/compare');
                        
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
                        $data['button_cart'] = $this->language->get('button_cart');
                        $data['button_grid'] = $this->language->get('button_grid');
                        $data['button_list'] = $this->language->get('button_list');
                        $data['text_compare'] = $this->language->get('text_compare');
                        $data['text_sort'] = $this->language->get('text_sort');
                        $data['text_limit'] = $this->language->get('text_limit');
                
                        
                if(isset($this->request->get['path'])){
                    if(isset($this->request->get['podcat'])){
                        $results = $this->model_catalog_brand->getBCProds($last_brand_id, $this->request->get['podcat']);
                        $cpc = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = ".$this->request->get['podcat']." AND language_id = 1");
                        $data['curr_podcat'] = isset($cpc->row['name'])?$cpc->row['name']:'';
                    } else {
                        $results = $this->model_catalog_brand->getBCProds($last_brand_id, $this->request->get['path']);
                        //exit(var_dump($results));
                        $cpc = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = ".$this->request->get['path']." AND language_id = 1");
                        $data['curr_cat'] = $cpc->row['name'];
                    }
                } else {
                    $results = $this->model_catalog_brand->getBrandProducts($last_brand_id);
                }
                        
                
                
                
                $data['products'] = array();
                
                foreach ($results as $result) {
                                if($result['comp']!=''){
                                    $comp = $this->language->get('comp');
                                } else{
                                    $comp = '';
                                    //exit(var_dump($results));
                                }
                                if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}
                                if ($result['note'] != ''){
                                    $note = $result['note'];
                                }
                                else{
                                    $note = '';
                                }
                                if (isset($result['compability']) && ($result['compability'] != '')){
                                    $compability = $result['compability'];
                                }
                                else{
                                    $compability = '';
                                }
                                
                                $data['products'][] = array(
                                    
                                        'thumb' => $image,
                                        'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => $result['vin'],
                                        'note'        => $result['note'],
                                        'compability' => $compability,
                                        'catN'        => $result['catN'],
                                        'ean'         => $result['ean'],
                                        'cond'        => $result['con_p'],
					'price'       => $result['price'],
					'minimum'     => ($result['minimum'] > 0) ? $result['minimum'] : 1,
                                        'comp'        => $comp,
					'href'        => $this->url->link('product/product', 'brand_id=' . $this->request->get['brand_id'] . '&product_id=' . $result['product_id'] . $url)
                                    
                                );
                    
                }
                
                
                //exit(var_dump($ProdsBrand));
            
            $this->response->setOutput($this->load->view('product/brand_info', $data));
            
    }
        
}
