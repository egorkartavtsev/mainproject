<?php
class ControllerProductionCatalog extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		$this->getList();
	}
        
        public function getMatches(){
		$i=0;
		$query = $this->db->query("SELECT product_id, vin, date_added FROM `oc_product` p1 WHERE EXISTS (SELECT * FROM `oc_product` p2 WHERE p1.vin = p2.vin AND p1.product_id<>p2.product_id) ORDER BY p1.vin");
		echo '<table>';
			foreach($query->rows as $prods){
				echo '<tr>';
					echo '<td style="border: 1px solid #000;">'.$prods['product_id'].'</td>';
					echo '<td style="border: 1px solid #000;">'.$prods['vin'].'</td>';
					echo '<td style="border: 1px solid #000;">'.$prods['date_added'].'</td>';
				echo '</tr>';
				++$i;
			}
		echo '</table><br><b>'.$i.' products</b>';
	}
        
	public function add() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->addProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
			
                        if (isset($this->request->get['filter_vin'])) {
				$url .= '&filter_vin=' . $this->request->get['filter_vin'];
			}
                        
                        if (isset($this->request->get['filter_brand'])) {
				$url .= '&filter_brand=' . $this->request->get['filter_brand'];
			}
                        if (isset($this->request->get['filter_drom'])) {
				$url .= '&filter_drom=' . $this->request->get['filter_drom'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

                        if (isset($this->request->get['filter_category'])) {
                          $url .= '&filter_category=' . $this->request->get['filter_category'];
                        }
                        
                        if (isset($this->request->get['filter_wocat'])) {
                          $url .= '&filter_wocat=' . $this->request->get['filter_wocat'];
                        }
                        
                        

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
                        
                        if (isset($this->request->get['filter_vin'])) {
				$url .= '&filter_vin=' . $this->request->get['filter_vin'];
			}
                        
                        if (isset($this->request->get['filter_brand'])) {
				$url .= '&filter_brand=' . $this->request->get['filter_brand'];
			}
                        if (isset($this->request->get['filter_drom'])) {
				$url .= '&filter_drom=' . $this->request->get['filter_drom'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

      if (isset($this->request->get['filter_category'])) {
        $url .= '&filter_category=' . $this->request->get['filter_category'];
      }

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('production/catalog', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function copy() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->copyProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
			
                        if (isset($this->request->get['filter_vin'])) {
				$url .= '&filter_vin=' . $this->request->get['filter_vin'];
			}
                        
                        if (isset($this->request->get['filter_brand'])) {
				$url .= '&filter_brand=' . $this->request->get['filter_brand'];
			}
                        if (isset($this->request->get['filter_drom'])) {
				$url .= '&filter_drom=' . $this->request->get['filter_drom'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

      if (isset($this->request->get['filter_category'])) {
        $url .= '&filter_category=' . $this->request->get['filter_category'];
      }

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
            $this->load->model('tool/layout');
                $data = $this->model_tool_layout->getLayout($this->request->get['route']);
		if (isset($this->request->get['filter_name'])) {
			$filter_name = trim($this->request->get['filter_name']);
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_donor'])) {
			$filter_donor = trim($this->request->get['filter_donor']);
		} else {
			$filter_donor = null;
		}
		if (isset($this->request->get['filter_drom'])) {
			$filter_drom = trim($this->request->get['filter_drom']);
		} else {
			$filter_drom = null;
		}
		if (isset($this->request->get['filter_catn'])) {
			$filter_catn = trim($this->request->get['filter_catn']);
		} else {
			$filter_catn = null;
		}
                if (isset($this->request->get['filter_category'])) {
			$filter_category = trim($this->request->get['filter_category']);
		} else {
			$filter_category = NULL;
		}
                if (isset($this->request->get['filter_wocat'])) {
			$filter_wocat = trim($this->request->get['filter_wocat']);
		} else {
			$filter_wocat = null;
		}
                
		if (isset($this->request->get['filter_model'])) {
			$filter_model = trim($this->request->get['filter_model']);
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = trim($this->request->get['filter_price']);
		} else {
			$filter_price = null;
		}
		
                if (isset($this->request->get['filter_vin'])) {
			$filter_vin = trim($this->request->get['filter_vin']);
		} else {
			$filter_vin = null;
		}
                
                if (isset($this->request->get['filter_brand'])) {
			$filter_brand = trim($this->request->get['filter_brand']);
		} else {
			$filter_brand = null;
		}
                if (isset($this->request->get['filter_drom'])) {
			$filter_drom = trim($this->request->get['filter_drom']);
		} else {
			$filter_drom = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = trim($this->request->get['filter_quantity']);
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = trim($this->request->get['filter_status']);
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_image'])) {
			$filter_image = trim($this->request->get['filter_image']);
		} else {
			$filter_image = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = trim($this->request->get['sort']);
		} else {
			$sort = 'pd.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = trim($this->request->get['order']);
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = trim($this->request->get['page']);
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_catn'])) {
			$url .= '&filter_catn=' . urlencode(html_entity_decode($this->request->get['filter_catn'], ENT_QUOTES, 'UTF-8'));
			}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
		if (isset($this->request->get['filter_donor'])) {
			$url .= '&filter_donor=' . urlencode(html_entity_decode($this->request->get['filter_donor'], ENT_QUOTES, 'UTF-8'));
			}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
		}
		
                if (isset($this->request->get['filter_wocat'])) {
			$url .= '&filter_wocat=' . urlencode(html_entity_decode($this->request->get['filter_wocat'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		
                if (isset($this->request->get['filter_vin'])) {
			$url .= '&filter_vin=' . $this->request->get['filter_vin'];
		}
                
                if (isset($this->request->get['filter_brand'])) {
			$url .= '&filter_brand=' . $this->request->get['filter_brand'];
		}
                if (isset($this->request->get['filter_drom'])) {
			$url .= '&filter_drom=' . $this->request->get['filter_drom'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_image'])) {
			$url .= '&filter_image=' . $this->request->get['filter_image'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['add'] = $this->url->link('production/addition', 'token=' . $this->session->data['token'] . $url, true);
		$data['copy'] = $this->url->link('production/catalog/copy', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('production/catalog/delete', 'token=' . $this->session->data['token'] . $url, true);
                $data['go_site'] = HTTPS_CATALOG.'index.php?route=catalog/product&product_id=';
		$data['products'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_donor'	  => $filter_donor,
			'filter_catn'	  => $filter_catn,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_vin'	  => $filter_vin,
			'filter_brand'	  => $filter_brand,
			'filter_drom'	  => $filter_drom,
			'filter_quantity' => $filter_quantity,
                        'filter_category' => $filter_category,
                        'filter_wocat' => $filter_wocat,
			'filter_status'   => $filter_status,
			'filter_image'    => $filter_image,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                
		$results = $this->model_catalog_product->getProducts($filter_data);
                
		$this->load->model('catalog/category');

		$filter_data = array(
			'sort'        => 'name',
			'order'       => 'ASC'
		);

		$data['categories'] = $this->model_catalog_category->getCategories($filter_data);
                $data['utype'] = $this->session->data['uType'];
//                exit(var_dump($results));
		foreach ($results as $result) {

                    $category =  $this->model_catalog_product->getProductCategories($result['product_id']);

			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $product_special['price'];

					break;
				}
			}
                        
                        $now = time();
                        $added = strtotime($result['date_added']);
                        $dateDif = abs($added-$now);
                        $dateRes = floor($dateDif/(60*60*24));
                        
                        $quer = $this->db->query("SELECT * FROM ".DB_PREFIX."sales_info WHERE sku = '".$result['vin']."'");
                        $saleDate = '';
                        if(!empty($quer->row)){
                            $saleDate = $quer->row['date'];
                        } else {
                            $saleDate = 'В наличии';
                        }
                        $stat ='';
                        switch ($result['status']):
                        case 0:
                            $stat=$this->language->get('text_disabled');
                            break;
                        case 1:
                            $stat=$this->language->get('text_enabled');
                            break;
                        case 2:
                            $stat=$this->language->get('text_reserve');
                            break;
                        endswitch;
                        
                        if($result['product_id']!=NULL){
                            $data['products'][] = array(
                                    'product_id' => $result['product_id'],
                                    'image'      => $image,
                                    'saled'      => $saleDate,
                                    'manager'    => $result['manager'],
                                    'name'       => $result['name'],
                                    'vin'        => $result['vin'],
                                    'stell'      => (isset($result['stell'])) && ($result['stell']!=='')?$result['stell']:'-',
                                    'jar'        => (isset($result['jar'])) && ($result['jar']!=='')?$result['jar']:'-',
                                    'shelf'      => (isset($result['shelf'])) && ($result['shelf']!=='')?$result['shelf']:'-',
                                    'box'        => (isset($result['box'])) && ($result['box']!=='')?$result['box']:'-',
                                    'donor'      => $result['donor']!=''?'<a target="_blank" href="'.$this->url->link('donor/show', 'token=' . $this->session->data['token'] . '&numb=' . $result['donor'] . $url, true).'">'.$result['donor'].'</a>':'-',
                                    'stock'      => (isset($result['stock'])) && ($result['stock']!=='') && ($result['stock']!=='-')?$result['stock']:'не указан',
                                    'adress'     => (isset($result['adress'])) && ($result['adress']!=='') && ($result['adress']!=='-')?$result['adress']:'не указан',
                                    'model'      => $result['modR'],
                                    'price'      => $result['price'],
                                    'date_added' => $result['date_added'],
                                    'dateDif'    => $dateRes,
                                    'category'   => $category,
                                    'special'    => $special,
                                    'quantity'   => $result['quantity'],
                                    'status'     => $stat,
                                    'edit'       => $this->url->link('production/catalog/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, true)
                            );
                        }
		}

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
                $data['text_reserve'] = $this->language->get('text_reserve');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_category'] = $this->language->get('column_category');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_image'] = $this->language->get('entry_image');

		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_donor'])) {
			$url .= '&filter_donor=' . urlencode(html_entity_decode($this->request->get['filter_donor'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_catn'])) {
			$url .= '&filter_catn=' . urlencode(html_entity_decode($this->request->get['filter_catn'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		
                if (isset($this->request->get['filter_vin'])) {
			$url .= '&filter_vin=' . $this->request->get['filter_vin'];
		}
                
                if (isset($this->request->get['filter_brand'])) {
			$url .= '&filter_brand=' . $this->request->get['filter_brand'];
		}
                if (isset($this->request->get['filter_drom'])) {
			$url .= '&filter_drom=' . $this->request->get['filter_drom'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_category'])) {
                  $url .= '&filter_category=' . $this->request->get['filter_category'];
                }
		if (isset($this->request->get['filter_wocat'])) {
                  $url .= '&filter_wocat=' . $this->request->get['filter_wocat'];
                }

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_image'])) {
			$url .= '&filter_image=' . $this->request->get['filter_image'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, true);
		$data['sort_model'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.length' . $url, true);
		$data['sort_manager'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.manager' . $url, true);
		$data['sort_locate'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.weight' . $url, true);
		$data['sort_date_added'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, true);
		$data['sort_price'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, true);
		$data['sort_category'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p2c.category_id' . $url, true);
		$data['sort_quantity'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, true);
		$data['sort_status'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, true);
		$data['sort_order'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_donor'])) {
			$url .= '&filter_donor=' . urlencode(html_entity_decode($this->request->get['filter_donor'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_catn'])) {
			$url .= '&filter_catn=' . urlencode(html_entity_decode($this->request->get['filter_catn'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_vin'])) {
			$url .= '&filter_vin=' . $this->request->get['filter_vin'];
		}
		
                if (isset($this->request->get['filter_brand'])) {
			$url .= '&filter_brand=' . $this->request->get['filter_brand'];
		}
                if (isset($this->request->get['filter_drom'])) {
			$url .= '&filter_drom=' . $this->request->get['filter_drom'];
		}
                
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_image'])) {
			$url .= '&filter_image=' . $this->request->get['filter_image'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('production/catalog', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_donor'] = $filter_donor;
		$data['filter_catn'] = $filter_catn;
		$data['filter_model'] = $filter_model;
		$data['filter_price'] = $filter_price;
		$data['filter_vin'] = $filter_vin;
		$data['filter_brand'] = $filter_brand;
		$data['filter_drom'] = $filter_drom;
		$data['filter_quantity'] = $filter_quantity;
		$data['filter_category'] = $filter_category;
		$data['filter_wocat'] = $filter_wocat;
		$data['filter_status'] = $filter_status;
		$data['filter_image'] = $filter_image;

		$data['sort'] = $sort;
		$data['order'] = $order;
                
		$this->response->setOutput($this->load->view('catalog/product_list', $data));
	}
        
        
        
	protected function getForm() {
            $this->load->model('tool/product');
            $this->load->model('product/product');
            $this->load->model('tool/image');
            $this->load->model('tool/forms');
            $this->load->model('tool/layout');
            $data = $this->model_tool_layout->getLayout($this->request->get['route']);
            $data['uType'] = $this->session->data['uType'];
            $data['uName'] = $this->session->data['username'];
            $product = $this->model_tool_product->getProduct($this->request->get['product_id']);
            $photos = $this->model_tool_product->getProdImg($this->request->get['product_id']);
            $type = $this->model_tool_product->getOptions($product['structure']);
            $info = array();
            foreach ($type['options'] as $field) {
                $info[$field['name']] = array(
                    'text' => $field['text'],
                    'field_type' => $field['field_type'],
                    'vals' => $field['vals'],
                    'required' => $field['required'],
                    'description' => $field['description'],
                    'value' => $product[$field['name']],
                    'library' => $field['libraries']
                );
            }
            $info['vin'] = $product['vin'];
            $info['manager'] = $this->session->data['username'];
            $info['price'] = $product['price'];
            $info['quantity'] = $product['quantity'];
            $info['status'] = $product['status'];
            $this->load->language('catalog/product');
            $this->document->setTitle($this->language->get('heading_title'));
            $data['form'] = $this->model_tool_forms->constructEditForm($info, $this->request->get['product_id']);
            $data['name'] = $this->model_tool_product->getProdName($this->request->get['product_id']);
            $data['action'] = $this->url->link('production/catalog/saveForm', 'token='.$this->session->data['token'].'&product_id='.$this->request->get['product_id']);
            $local_id = 0;
            foreach($photos as $img){
                $data['images'][] = array(
                        'image'         => $img['image'],
                        'sort_order'    => $img['sort_order'],
                        'thumb'         => $this->model_tool_image->resize($img['image'], 100, 100),
                        'lid'           => $local_id,
                        'main'          => $img['image']==$product['image']?TRUE:FALSE
                    );
                ++$local_id;
            }
            $data['mainimage'] = $product['image']!=''?$product['image']:'no_image.png';
            if($product['avitoname']==''){
                $brtr = $this->db->query("SELECT name, translate FROM ".DB_PREFIX."lib_fills WHERE name = '".$product['brand']."'");
                $mtr = $this->db->query("SELECT name, translate FROM ".DB_PREFIX."lib_fills WHERE name = '".$product['model']."'");
                $data['avitoname'] = $product['podcateg'].' '.$mtr->row['name'].'/'.$mtr->row['translate'];
            } else {
                $data['avitoname'] = $product['avitoname'];
            }
            $data['complect'] = $product['comp'];
            $data['comp_price'] = $product['comp_price'];
            //берём комплектность
            if($data['complect']!='' && $data['comp_price']==''){
                $comp = $this->model_product_product->getComplect($data['complect']);
                $data['cname'] = $comp['name'];
                $data['clink'] = $this->url->link('complect/complect/edit', 'token=' . $this->session->data['token'] . '&complect=' . $comp['id'], true);
            }
            $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

            $this->response->setOutput($this->load->view('product/product_edit', $data));
        }

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['product_id']) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['product_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'production/catalog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}
			
                        if (isset($this->request->get['filter_donor'])) {
				$filter_donor = $this->request->get['filter_donor'];
			} else {
				$filter_donor = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_donor'  => $filter_donor,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['modR'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        
        public function getDesc() {
            $id = $this->request->post['id'];
            $this->load->model('tool/product');
            echo $this->model_tool_product->getDescription($id);
        }
        
        public function remCompl() {
            $heading = $this->request->post['heading'];
            $item = $this->request->post['item'];
            $this->load->model('product/product');
            $this->model_product_product->remCompl($item, $heading);
            echo 1;
        }
        
        public function setCompl() {
            $heading = $this->request->post['heading'];
            $item = $this->request->post['item'];
            $this->load->model('product/product');
            $result = $this->model_product_product->setCompl($item, $heading);
            echo $result;
        }
        
        public function getCompl() {
        $this->load->model('product/product');
        $compl = $this->model_product_product->findCompl($this->request->post['heading']);
        $html = '';
        if($compl){
            $this->load->model('tool/image');
            $image = $this->model_tool_image->resize($compl['image'], 100, 100);
            $html.= '<div class="col-sm-3">';
                $html.='<img src="'.trim($image).'" class="thumb" alt="" title="" data-placeholder="'.$this->model_tool_image->resize('no_image.png', 100, 100).'" />';
            $html.= '</div>';
            $html.= '<div class="col-sm-9">';
                $html.= '<h3>'.$compl['name'].'</h3>';
                $html.= '<p>Стоимость: <span class="label label-primary">'.$compl['price'].'</span></p>';
                $html.= '<p>Скидка на комплект: <span class="label label-primary">'.$compl['sale'].'</span></p>';
            $html.= '</div>';
            echo $html;
        } else {
            echo 0;
        }
    }
    
    public function saveForm() {
        $this->load->model('tool/xml');
        $this->load->model('tool/forms');
        $this->load->model('tool/product');
        $info = $this->model_tool_product->getProdStructure($this->request->post, $this->request->get['product_id']);
        $this->model_tool_forms->updateProduct($info, $this->request->get['product_id']);
        $info['options']['avitoname'] = $this->request->post['info']['avitoname'];
        $info['options']['vin'] = $info['vin'];
        $info['options']['pid'] = $this->request->get['product_id'];
//        exit(var_dump($this->request->post));
        if($this->user->hasPermission('', 'common/autoload') && $this->request->post['allowavito']==='да'){
            $this->model_tool_xml->avitoFind($info);
        }
        $this->model_tool_xml->ARUFind($info);
        
//        $this->model_tool_xml->findARPart($alinfo);
        $this->response->redirect($this->url->link('production/catalog', 'token=' . $this->session->data['token'], true));
//        exit(var_dump($this->request->post));
    }
}
