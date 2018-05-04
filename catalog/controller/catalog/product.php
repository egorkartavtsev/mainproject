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
//      -----------------------------Выборка данных----------------------------------------------------------------------------------------------------------------------------------------------           
            $product = $this->model_product_catalog->getProductInfo($this->request->get['product_id']);
            $photos = $this->model_product_catalog->getProductImages($this->request->get['product_id']);
            $type = $this->model_tool_product->getType($product['structure']);
            $description = $this->model_tool_product->getDescription($this->request->get['product_id']);
            $complect_arr = $this->model_tool_product->getProductCompls($this->request->get['product_id']);
            $list['breadcrumbs'][] = array(
                    'text' => '<i class="fa fa-home"></i>',
                    'href' => '/'
            );
            
//      ------------------------------Комплекты(Как в старом)------------------------------------------------------------------------------------------------------------------------------------
            if($complect_arr){
                $data['id_comp_ref'] = isset($complect_arr['id_comp_ref'])?$complect_arr['id_comp_ref']:NULL;
                $data['c_id'] = isset($complect_arr['c_id'])?$complect_arr['c_id']:NULL;
                $data['c_price'] = isset($complect_arr['compl_price'])?$complect_arr['compl_price']:NULL;
                $data['link'] = isset($complect_arr['link'])?$complect_arr['link']:NULL;
                $data['complect'] = isset($complect_arr['complect'])?$complect_arr['complect']:NULL;
                $data['whole'] = isset($complect_arr['whole'])?$complect_arr['whole']:NULL;
            }
//      ------------------------------Структура данных по товара----------------------------------------------------------------------------------------------------------------------------------  
            foreach($product AS $key => $value){
                    $type[$key]['value'] = $value;
            }
            //exit(var_dump($type));
//      ------------------------------Отображаемая информация------------------------------------------------------------------------------------------------------------------------------------              
            foreach($type AS $key => $value){
                    $options[$key] = $type[$key];
            }
            $data['options'] = $options;
            //exit(var_dump($data['options']));
//      ------------------------------Скрытая информация(Надо переименовать или создать общую структуру и вынести логику отображения в tpl)-----------------------------------------------------------------------------------------------                
//      ------------------------------Описание--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------            
            $data['description'] = html_entity_decode($description['description'] , ENT_QUOTES, 'UTF-8');
            //exit($list['title']);
//      ------------------------------Осонованя информация--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            $data['product'] = array(
                'product_id' => $product['product_id'],
                'minimum'    => $product['minimum'] > 0 ? $product['minimum'] : 1,                 
                'href'       => $this->url->link('catalog/product', 'product_id='.$product['product_id']),
                'name'       => $description['name'],
                'vin'        => $product['vin'],
                'type'       => $product['type'],
                'comp'       => $product['comp']==''?FALSE:$product['comp'],
                'com_whole'  => $product['comp_whole'],
                'price'      => $product['price'],
            );
//      -------------------------------Изображения-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
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
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
            $this->document->addScript('catalog/view/javascript/jquery/Smoothproducts/js/smoothproducts.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/Smoothproducts/css/smoothproducts.css');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/locale/'.$this->session->data['language'].'.js');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
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
                    $data['stock'] = $product['stock_status'];
                    $data['no_prod'] = TRUE;
            } elseif ($this->config->get('config_stock_display')) {
                    $data['stock'] = $product['quantity'];
            } else {
                    $data['stock'] = $this->language->get('text_instock');
            }
            if ($data['no_prod']){
                $data['stock'] = 'Нет в наличии на складе';
            }
//      =========================================Хуй пойми че с этим делать. Тегов не завезли.==================================================================================================================
            $data['tags'] = array();
            if ($product['tag']) {
                $tags = explode(',', $product['tag']);
                foreach ($tags as $tag) {
                    $data['tags'][] = array(
                        'tag'  => trim($tag),
                        'href' => $this->url->link('product/search', 'tag=' . trim($tag))
                    );
                }
            }
//      =====================================Оповещение=======================================================================================================================================               
            $data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);
            if (isset($this->request->post['suc'])){
                $mail =  'Имя: '.$this->request->post['name'].'; '
                       . 'Email: '.$this->request->post['email'].'; '
                       . 'Телефон: '.$this->request->post['phone'].'; '
                       . 'Товар: '.$data['vin'].'; '
                       . 'Наименование товара: '.$data['heading_title'].'; ' 
                       . 'Комментарий: '.$this->request->post['comment'];
                $headers  = 'From: autorazbor174@mail.ru' . " " 
                          . 'Reply-To: autorazbor174@mail.ru' . " "
                          . 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $suc = true;
                if ($product['quantity'] <= 0 && $product['price'] != 0.00 ) {
                    mail('autorazbor174@mail.ru', 'Заявка на заказ товара с сайта авторазбор174.рф', $mail); 
                } 
                else {
                    mail('autorazbor174@mail.ru', 'Заявка на уточнение цены товара с сайта авторазбор174.рф', $mail);
                } 
                $data['suc_text'] = 'Ваша заявка успешно отправлена';
            }
//      =====================================Отправка данных в tpl=============================================================================================================================              
            $data['modal_window'] = $this->load->view('modal_window/modal_window'); 
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