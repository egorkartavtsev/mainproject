<?php
class ControllerCatalogProduct extends Controller {
    public function index() {
        if(isset($this->request->get['product_id'])){
//      ------------------------------Подключение моделей----------------------------------------------------------------------------------------------------------------------------------   
            $this->load->language('product/product');
            $this->load->model('catalog/product');
            $this->load->model('product/catalog');
            $this->load->model('product/product');
            $this->load->model('tool/layout');
            $this->load->model('tool/product');
            $this->load->model('tool/image');
            $this->document->addStyle('view/javascript/jquery/owl-carousel/owl.carousel.css');
            $this->document->addStyle('view/javascript/jquery/owl-carousel/owl.transitions.css');
            $this->document->addScript('view/javascript/jquery/owl-carousel/owl.carousel.min.js');
//      -----------------------------Выборка данных----------------------------------------------------------------------------------------------------------------------------------------------           
            $product = $this->model_product_catalog->getProductInfo($this->request->get['product_id']);
            $photos = $this->model_product_catalog->getProductImages($this->request->get['product_id']);
            $type = $this->model_tool_product->getType($product['structure']);
            $description = $this->model_tool_product->getDescription($this->request->get['product_id']);
            $complect_arr = $this->model_tool_product->getProductCompls($this->request->get['product_id']);
//      ------------------------------Комплекты(Как в старом)200------------------------------------------------------------------------------------------------------------------------------------
            if($complect_arr){
                $data['id_comp_ref'] = isset($complect_arr['id_comp_ref'])?$complect_arr['id_comp_ref']:NULL;
                $data['c_id'] = isset($complect_arr['c_id'])?$complect_arr['c_id']:NULL;
                $data['c_price'] = isset($complect_arr['compl_price'])?$complect_arr['compl_price']:NULL;
                $data['link'] = isset($complect_arr['link'])?$complect_arr['link']:NULL;
                $data['complect'] = isset($complect_arr['complect'])?$complect_arr['complect']:NULL;
                $data['whole'] = isset($complect_arr['whole'])?$complect_arr['whole']:NULL;
            }
            $data['labels'] = array();
//      ------------------------------Структура данных по товара----------------------------------------------------------------------------------------------------------------------------------  
            foreach($product AS $key => $value){
                    $type[$key]['value'] = $value;
                    if(isset($type[$key]['label_order']) && (int)$type[$key]['label_order']){
                        $data['labels'][(int)$type[$key]['label_order']] = array(
                            'color' => $type[$key]['label_color'],
                            'value' => $type[$key]['value']
                        );
                    }
            }
//      ------------------------------Отображаемая информация------------------------------------------------------------------------------------------------------------------------------------              
            $data['options'] = array();
            foreach($type AS $key => $value){
                     $options[$key] = $type[$key];
            }
            $data['options'] = $options;
            //exit(var_dump($data['options']));
//      ------------------------------Описание--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------            
            $data['description'] = html_entity_decode($description['description'] , ENT_QUOTES, 'UTF-8');
//      ------------------------------Осонованя информация--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            $data['product'] = array(
                'product_id' => $product['product_id'],
                'gen_image'  => $product['image'],
                'minimum'    => $product['minimum'] > 0 ? $product['minimum'] : 1,                 
                'href'       => $this->url->link('catalog/product', 'product_id='.$product['product_id']),
                'name'       => $description['name'],
                'vin'        => $product['vin'],
                'type'       => $product['type'],
                'comp'       => $product['comp']==''?FALSE:$product['comp'],
                'com_whole'  => $product['comp_whole'],
                'price'      => $product['price'],
                'modR'       => $product['modR'],
            );            
//      -------------------------------Изображенияттттест-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
            if (isset($product['image']) && $product['image']!=''){
                $data['images'] = array();
                if ($product['image']) {
                        $data['popup'] = $this->model_tool_image->resize($product['image'], 1024, 768);
                        $data['images'][] = array(
                                'popup' => $this->model_tool_image->resize($product['image'], 1024, 768)
                        );
                } else {
                        $data['popup'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                        $data['images'][] = array(
                                'popup' => $this->model_tool_image->resize('no_image.png', 1024, 768)
                        );
                }

                if ($product['image']) {
                        $data['thumb'] = $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
                        $this->document->setOgImage($data['thumb']);
                } else {
                        $data['thumb'] = '';
                }
                foreach ($photos as $result) {
                        if($result['image']!== $product['image']){
                            $data['images'][] = array(
                                    'popup' => $this->model_tool_image->resize($result['image'], 1024, 768)

                            );
                        }
                }
            }
//      ==============================================================================================================================================
            $this->document->addScript('view/javascript/jquery/datetimepicker/moment.js');
            $this->document->addScript('view/javascript/jquery/Smoothproducts/js/smoothproducts.min.js');
            $this->document->addStyle('view/javascript/jquery/Smoothproducts/css/smoothproducts.css');
            $this->document->addScript('view/javascript/jquery/datetimepicker/locale/'.$this->session->data['language'].'.js');
            $this->document->addScript('view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
            $this->document->addStyle('view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
//      =====================================Тупые языковые перменные, которые по сути не нужны но хз, как подругому=============================================================================
            $data['text_select'] = $this->language->get('text_select');
            $data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $data['text_model'] = $this->language->get('text_model');
            $data['text_reward'] = $this->language->get('text_reward');
            $data['text_points'] = $this->language->get('text_points');
            $data['text_stock'] = $this->language->get('text_stock');
            $data['text_discount'] = $this->language->get('text_discount');
            $data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product['minimum']);
            $data['text_write'] = $this->language->get('text_write');
            $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
            $data['text_note'] = $this->language->get('text_note');
            $data['text_tags'] = $this->language->get('text_tags');
            $data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
            $data['text_loading'] = $this->language->get('text_loading');

            $data['entry_qty'] = $this->language->get('entry_qty');
            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_review'] = $this->language->get('entry_review');
            $data['entry_rating'] = $this->language->get('entry_rating');
            $data['entry_good'] = $this->language->get('entry_good');
            $data['entry_bad'] = $this->language->get('entry_bad');
            $data['entry_compl'] = $this->language->get('entry_compl');

            $data['button_cart'] = $this->language->get('button_cart');
            $data['button_wishlist'] = $this->language->get('button_wishlist');
            $data['button_compare'] = $this->language->get('button_compare');
            $data['button_upload'] = $this->language->get('button_upload');
            $data['button_continue'] = $this->language->get('button_continue');

            $data['tab_description'] = $this->language->get('tab_description');
            $data['tab_attribute'] = $this->language->get('tab_attribute');
//      ======================================Колличество(надо переделать)==================================================================================================================
            $data['no_prod'] = FALSE;
            if ($product['quantity'] <= 0) {
                    $data['stock'] = 'Нет в наличии на складе';
                    $data['no_prod'] = TRUE;
            } elseif ($this->config->get('config_stock_display')) {
                    $data['stock'] = $product['quantity'];
            } else {
                    $data['stock'] = $this->language->get('text_instock');
            }
            if ($data['no_prod']){
                $data['stock'] = 'Нет в наличии на складе';
            }
            if ($product['status'] == '2') {
                $data['stock'] = 'Зарезервирован'; 
            }
            $data['status']=$product['status'];
//      =====================================Оповещение=======================================================================================================================================               
            $data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);
//      =====================================Отправка данных в tpl=============================================================================================================================              
//      =====================================Генерация ссылки показать все=============================================================================================================================              
            if ($product['modR'] != '') {
                    $data['allPLink'] = $this->url->link('product/search','search='.$product['modR']);
            } 
                else { $data['allPLink'] = $this->url->link('product/search','search='.$description['name']); }                                     
//      =====================================Остальное=============================================================================================================================              
            $data['modal_window'] = $this->load->view('modal_window/Modal_window');
            $data['sendLink'] = $this->url->link('catalog/product', 'product_id='.$this->request->get['product_id']);
            $data['product_id'] = $this->request->get['product_id'];
            $data['youtube'] = $product['youtube'];
            $similar = $this->model_product_product->getSimilar($this->request->get['product_id']);
            $data['similar_list'] = [];
            foreach($similar as $sim){
                $data['similar_list'][] = [
                    'image' => $this->model_tool_image->resize($sim['image'], 200, 170),
                    'price' => $sim['price'],
                    'vin' => $sim['vin'],
                    'comp' => $sim['comp'],
                    'com_whole' => $sim['comp_whole'],
                    'quantity' => $sim['quantity'],
                    'status' => $sim['status'],
                    'href'  => $this->url->link('catalog/product', 'product_id='.$sim['product_id']),
                    'name'  => $sim['name']
                ];
            }
            $list['productpage'] = $this->load->view('catalog/oneproduct', $data);
//      ---------------------------------------------------------------------------------
            $this->document->setTitle($description['meta_title']);
            $this->document->setDescription($this->config->get('config_meta_description'));
            $this->document->setKeywords($this->config->get('config_meta_keyword'));
            $this->document->addLink($this->url->link('catalog/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
            $list['column_left'] = $this->load->controller('common/column_left');
            $list['column_right'] = $this->load->controller('common/column_right');
            $list['content_top'] = $this->load->controller('common/content_top');
            $list['content_bottom'] = $this->load->controller('common/content_bottom');
            $list['footer'] = $this->load->controller('common/footer');
            $list['header'] = $this->load->controller('common/header');
//      ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            $this->response->setOutput($this->load->view('catalog/product_page', $list));
        } else {
            $this->response->redirect($this->url->link('common/home'));
        }
    }   
}
