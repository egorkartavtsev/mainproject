<?php

class ControllerProductTiredisc extends Controller {
    
    public function index() {
        $this->load->language('product/manufacturer');
        $this->load->language('product/product');
        $this->load->model('catalog/brand');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        
        $data['categ'] = 'all';
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
        );
        $data['breadcrumbs'][] = array(
                'text' => 'Шины и диски',
                'href' => $this->url->link('product/brand/index')
        );

        $this->document->setTitle('Шины и диски');

        $data['heading_title'] = 'Шины и диски';
        
        $this->load->model("catalog/tiredisc");
        
        $data['text_index'] = $this->language->get('text_index');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_continue'] = $this->language->get('button_continue');

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
        $data['column_left'] = $this->load->controller('common/column_left');
	$data['column_right'] = $this->load->controller('common/column_right');
	$data['content_top'] = $this->load->controller('common/content_top');
	$data['content_bottom'] = $this->load->controller('common/content_bottom');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
        
        $results = $this->model_catalog_tiredisc->getAll();
        foreach ($results as $result) {
                                if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}
                                                                
                                $data['products'][] = array(
                                        'product_id' => $result['pid'],
                                        'minimum' => $result['minimum'],
                                        'name' => $result['name'],
                                        'vin' => $result['vin'],
                                        'price' => $result['price'],
                                        'cond' => $result['cond'],
                                        'type' => $result['type'],
                                        'thumb' => $image,
                                        'href' => $this->url->link('product/tiredisc/'.($result['dImage']!=NULL?'disc':'tire'), 'product='.$result['pid'])
                                    
                                );
                    
                }
        
        
        $this->response->setOutput($this->load->view('product/tiredisc_info', $data));
    }
    
    public function tirelist() {
        $this->load->model('tool/image');
        $this->load->language('product/manufacturer');
        $this->load->language('product/product');
        $this->load->model('catalog/brand');
        $this->load->model('catalog/product');
        $this->load->model('catalog/tiredisc');
        $this->load->model('tool/image');
        
        $data['categ'] = 'tire';
        
        $data['filter_fields'] = array();
        $f_fields = $this->model_catalog_tiredisc->getParams('tire');
        foreach ($f_fields as $field => $name) {
            $values = $this->model_catalog_tiredisc->getValues($field, $data['categ']);
            $data['filter_fields'][$field] = array(
                'name' => $name,
                'values' => $values
            );
        }
        $filter_data = array();
        $data['path'] = '?route='.$this->request->get['route'];
        foreach ($this->request->get as $var => $value) {
            if($var!=='route'){
                $filter_data[$var] = $value;
                $data['curr_filter'][$var] = $value;
                $data['path'].='&'.$var.'='.$value;
            }
        }
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
        );
        $data['breadcrumbs'][] = array(
                'text' => 'Шины и диски',
                'href' => $this->url->link('product/tiredisc')
        );
        $data['breadcrumbs'][] = array(
                'text' => 'Шины',
                'href' => $this->url->link('product/tiredisc/tirelist')
        );

        $this->document->setTitle('Шины');

        $data['heading_title'] = 'Шины';
        
        $this->load->model("catalog/tiredisc");
        
        $data['text_index'] = $this->language->get('text_index');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_continue'] = $this->language->get('button_continue');

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
        $data['column_left'] = $this->load->controller('common/column_left');
	$data['column_right'] = $this->load->controller('common/column_right');
	$data['content_top'] = $this->load->controller('common/content_top');
	$data['content_bottom'] = $this->load->controller('common/content_bottom');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
        $results = $this->model_catalog_tiredisc->getTires($filter_data);
        $data['products'] = array();
        $data['reset'] = $this->url->link($this->request->get['route']);
        foreach ($results as $result) {
                                if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}
                                                                
                                $data['products'][] = array(
                                        'product_id' => $result['pid'],
                                        'minimum' => $result['minimum'],
                                        'name' => $result['name'],
                                        'vin' => $result['vin'],
                                        'price' => $result['price'],
                                        'cond' => $result['cond'],
                                        'type' => $result['type'],
                                        'thumb' => $image,
                                        'href' => $this->url->link('product/tiredisc/tire', 'product='.$result['pid'])
                                    
                                );
                    
                }
                
        $this->response->setOutput($this->load->view('product/tiredisc_info', $data));        
        
    }
    
    public function disclist() {
        $this->load->model('tool/image');
        $this->load->language('product/manufacturer');
        $this->load->language('product/product');
        $this->load->model('catalog/brand');
        $this->load->model('catalog/product');
        $this->load->model('catalog/tiredisc');
        $this->load->model('tool/image');
        
        $data['categ'] = 'disk';
        
        $data['filter_fields'] = array();
        $f_fields = $this->model_catalog_tiredisc->getParams('disk');
        foreach ($f_fields as $field => $name) {
            $values = $this->model_catalog_tiredisc->getValues($field, $data['categ']);
            $data['filter_fields'][$field] = array(
                'name' => $name,
                'values' => $values
            );
        }
        
        $filter_data = array();
        
        $data['path'] = '?route='.$this->request->get['route'];
        foreach ($this->request->get as $var => $value) {
            if($var!=='route'){
                $filter_data[$var] = $value;
                $data['curr_filter'][$var] = $value;
                $data['path'].='&'.$var.'='.$value;
            }
        }
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
        );
        $data['breadcrumbs'][] = array(
                'text' => 'Шины и диски',
                'href' => $this->url->link('product/tiredisc')
        );
        $data['breadcrumbs'][] = array(
                'text' => 'Диски',
                'href' => $this->url->link('product/tiredisc/disclist')
        );

        $this->document->setTitle('Диски');

        $data['heading_title'] = 'Диски';
        
        $this->load->model("catalog/tiredisc");
        
        $data['text_index'] = $this->language->get('text_index');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_continue'] = $this->language->get('button_continue');

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
        $data['column_left'] = $this->load->controller('common/column_left');
	$data['column_right'] = $this->load->controller('common/column_right');
	$data['content_top'] = $this->load->controller('common/content_top');
	$data['content_bottom'] = $this->load->controller('common/content_bottom');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
        
        $results = $this->model_catalog_tiredisc->getDisc($filter_data);
        $data['products'] = array();
        $data['reset'] = $this->url->link($this->request->get['route']);
        foreach ($results as $result) {
                                if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}
                                                                
                                $data['products'][] = array(
                                        'product_id' => $result['pid'],
                                        'minimum' => $result['minimum'],
                                        'name' => $result['name'],
                                        'vin' => $result['vin'],
                                        'price' => $result['price'],
                                        'cond' => $result['cond'],
                                        'type' => $result['type'],
                                        'thumb' => $image,
                                        'href' => $this->url->link('product/tiredisc/disc', 'product='.$result['pid'])
                                    
                                );
                    
                }
                
        $this->response->setOutput($this->load->view('product/tiredisc_info', $data));
    }
    
    public function tire() {
        $pid = $this->request->get['product'];
        $this->load->model('catalog/tiredisc');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->language('product/product');
        $product_info = $this->model_catalog_tiredisc->getProdInfo($pid, 'tire');
        $params = $this->model_catalog_tiredisc->getParams('tire');
        $data = $product_info;
        $data['parameters'] = $params;
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."stocks WHERE name = '".$product_info['stock']."' ");
        $data['adress'] = isset($query->row['adress'])?$query->row['adress']:'';
        $data['stock'] = $product_info['quantity'];
        if (isset($product_info['image']) && $product_info['image']!=''){
            if ($product_info['image']) {
                    $data['popup'] = $this->model_tool_image->resize($product_info['image'], 1024, 768);
            } else {
                    $data['popup'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));;
            }

            if ($product_info['image']) {
                    $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
                    $this->document->setOgImage($data['thumb']);
            } else {
                    $data['thumb'] = '';
            }

            $data['images'] = array();

            $results = $this->model_catalog_product->getProductImages($this->request->get['product']);

            foreach ($results as $result) {
                    $data['images'][] = array(
                            'popup' => $this->model_tool_image->resize($result['image'], 1024, 768),
                            'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
                    );
            }
        }
/************************************************************************************/
//        $this->document->setDescription($product_info['meta_description']);
//        $this->document->setKeywords($product_info['meta_keyword']);
        
        $this->document->addLink($this->url->link('catalog/tiredisc/tire', 'product' . $this->request->get['product']), 'canonical');
        $this->document->addScript('catalog/view/javascript/jquery/Smoothproducts/js/smoothproducts.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/Smoothproducts/css/smoothproducts.css');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/locale/'.$this->session->data['language'].'.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
        );
        $data['breadcrumbs'][] = array(
                'text' => 'Шины и диски',
                'href' => $this->url->link('product/tiredisc')
        );
        $data['breadcrumbs'][] = array(
                'text' => 'Шины',
                'href' => $this->url->link('product/tiredisc/tirelist')
        );
        $data['breadcrumbs'][] = array(
                'text' => $data['name'],
                'href' => $this->url->link('product/tiredisc/tire', 'product='.$pid)
        );
        $data['text_select'] = $this->language->get('text_select');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_reward'] = $this->language->get('text_reward');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_stock'] = $this->language->get('text_stock');
        $data['text_discount'] = $this->language->get('text_discount');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_option'] = $this->language->get('text_option');
        $data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['quantity']);
        $data['text_write'] = $this->language->get('text_write');
        $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
        $data['text_note'] = $this->language->get('text_note');
        $data['text_tags'] = $this->language->get('text_tags');
        $data['text_related'] = $this->language->get('text_related');
        $data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['minimum'] = 1;

        $data['entry_qty'] = $this->language->get('entry_qty');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_review'] = $this->language->get('entry_review');
        $data['entry_rating'] = $this->language->get('entry_rating');
        $data['entry_good'] = $this->language->get('entry_good');
        $data['entry_bad'] = $this->language->get('entry_bad');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_continue'] = $this->language->get('button_continue');

        $this->load->model('catalog/review');

        $data['tab_description'] = $this->language->get('tab_description');

        $data['product_id'] = (int)$this->request->get['product'];

        $this->document->setTitle($this->language->get('text_error'));

        $data['heading_title'] = $this->language->get('text_error');

        $data['text_error'] = $this->language->get('text_error');

        $data['button_continue'] = $this->language->get('button_continue');
        if(isset($product_info['name'])){
            $this->document->setTitle($product_info['name']);

            $data['heading_title'] = $product_info['name'];
        }
        $data['continue'] = $this->url->link('common/home');

        $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
       
        if(isset($product_info['note'])){
            $data['note'] = ($product_info['note']=='')?'---':$product_info['note'];
        } else {
            $data['note'] = '---';
        }
        if(isset($product_info['dop'])){
            $data['dop'] = ($product_info['dop']=='')?'---':$product_info['dop'];
        } else {
            $data['dop'] = '---';
        }
        
        $data['sendLink'] = $this->url->link('product/tiredisc/tire', 'product='.$pid);
        $this->response->setOutput($this->load->view('product/td_product', $data));
    }
    
    public function disc() {
        $pid = $this->request->get['product'];
        $this->load->model('catalog/tiredisc');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->language('product/product');
        $product_info = $this->model_catalog_tiredisc->getProdInfo($pid, 'disk');
        $params = $this->model_catalog_tiredisc->getParams('disk');
        $data = $product_info;
//        exit(var_dump($product_info));
        if(strlen($product_info['complect'])>0){
            $complect = $this->model_catalog_tiredisc->getCItems($product_info['complect']);
            $data['complect'] = $complect['items'];
            $data['whole'] = $complect['whole'];
            $data['c_price'] = $complect['c_price'];
            $data['link'] = $complect['link'];
        }
        $data['parameters'] = $params;
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."stocks WHERE name = '".$product_info['stock']."' ");
        $data['adress'] = isset($query->row['adress'])?$query->row['adress']:'';
        $data['stock'] = $product_info['quantity'];
        if (isset($product_info['image']) && $product_info['image']!=''){
            if ($product_info['image']) {
                    $data['popup'] = $this->model_tool_image->resize($product_info['image'], 1024, 768);
            } else {
                    $data['popup'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));;
            }

            if ($product_info['image']) {
                    $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
                    $this->document->setOgImage($data['thumb']);
            } else {
                    $data['thumb'] = '';
            }

            $data['images'] = array();

            $results = $this->model_catalog_product->getProductImages($this->request->get['product']);

            foreach ($results as $result) {
                    $data['images'][] = array(
                            'popup' => $this->model_tool_image->resize($result['image'], 1024, 768),
                            'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
                    );
            }
        }
/************************************************************************************/
//        $this->document->setDescription($product_info['meta_description']);
//        $this->document->setKeywords($product_info['meta_keyword']);
        
        $this->document->addLink($this->url->link('catalog/tiredisc/disc', 'product' . $this->request->get['product']), 'canonical');
        $this->document->addScript('catalog/view/javascript/jquery/Smoothproducts/js/smoothproducts.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/Smoothproducts/css/smoothproducts.css');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/locale/'.$this->session->data['language'].'.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
        );
        $data['breadcrumbs'][] = array(
                'text' => 'Шины и диски',
                'href' => $this->url->link('product/tiredisc')
        );
        $data['breadcrumbs'][] = array(
                'text' => 'Диски',
                'href' => $this->url->link('product/tiredisc/disclist')
        );
        $data['breadcrumbs'][] = array(
                'text' => $data['name'],
                'href' => $this->url->link('product/tiredisc/disc', 'product='.$pid)
        );
        $data['text_select'] = $this->language->get('text_select');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_reward'] = $this->language->get('text_reward');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_stock'] = $this->language->get('text_stock');
        $data['text_discount'] = $this->language->get('text_discount');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_option'] = $this->language->get('text_option');
        $data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['quantity']);
        $data['text_write'] = $this->language->get('text_write');
        $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
        $data['text_note'] = $this->language->get('text_note');
        $data['text_tags'] = $this->language->get('text_tags');
        $data['text_related'] = $this->language->get('text_related');
        $data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['minimum'] = 1;

        $data['entry_qty'] = $this->language->get('entry_qty');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_review'] = $this->language->get('entry_review');
        $data['entry_rating'] = $this->language->get('entry_rating');
        $data['entry_good'] = $this->language->get('entry_good');
        $data['entry_bad'] = $this->language->get('entry_bad');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_continue'] = $this->language->get('button_continue');

        $this->load->model('catalog/review');

        $data['tab_description'] = $this->language->get('tab_description');

        $data['product_id'] = (int)$this->request->get['product'];

        $this->document->setTitle($this->language->get('text_error'));

        $data['heading_title'] = $this->language->get('text_error');

        $data['text_error'] = $this->language->get('text_error');

        $data['button_continue'] = $this->language->get('button_continue');
        if(isset($product_info['name'])){
            $this->document->setTitle($product_info['name']);

            $data['heading_title'] = $product_info['name'];
        }
        $data['continue'] = $this->url->link('common/home');

        $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
       
        if(isset($product_info['note'])){
            $data['note'] = ($product_info['note']=='')?'---':$product_info['note'];
        } else {
            $data['note'] = '---';
        }
        if(isset($product_info['dop'])){
            $data['dop'] = ($product_info['dop']=='')?'---':$product_info['dop'];
        } else {
            $data['dop'] = '---';
        }
        
        $data['sendLink'] = $this->url->link('product/tiredisc/disc', 'product='.$pid);
        //echo var_dump($data).'<br>';
        $this->response->setOutput($this->load->view('product/td_product', $data));
    }
    
}

