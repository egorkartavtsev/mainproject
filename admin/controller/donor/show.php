<?php

class ControllerDonorShow extends Controller {
    
    public function index() {
        $this->load->model('common/donor');
        
        $data['donor'] = $this->model_common_donor->getDonorShow($this->request->get['numb']);
        $this->load->model('tool/image');
        $this->document->setTitle($data['donor']['name']);

        $data['heading_title'] = $data['donor']['name'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => 'Список доноров',
                'href' => $this->url->link('donor/list', 'token=' . $this->session->data['token'], true)
        );
        
        $data['breadcrumbs'][] = array(
                'text' => $data['donor']['name'],
                'href' => $this->url->link('donor/edit', 'token=' . $this->session->data['token'], true)
        );
        $i = 0;
        $images = $this->model_common_donor->getImages($data['donor']['id']);
//        exit(var_dump($images));
        $data['mainimage'] = $data['donor']['image'];
        $data['images'] = array();
        if ($data['donor']['image']) {
                $data['popup'] = $this->model_tool_image->resize($data['donor']['image'], 1024, 768);
        } else {
                $data['popup'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));;
        }

        if ($data['donor']['image']) {
                $data['thumb'] = $this->model_tool_image->resize($data['donor']['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
                $this->document->setOgImage($data['thumb']);
        } else {
                $data['thumb'] = '';
        }
        foreach ($images as $image) {
            $data['images'][] = array(
                    'popup' => $this->model_tool_image->resize($image['image'], 1024, 768),
                    'thumb' => $this->model_tool_image->resize($image['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
            );
        }
        
        $data['products'] = array();
        $prods = $this->model_common_donor->getProds($data['donor']['numb']);
        $total_price = 0;
        $quant = 0;
        foreach ($prods as $result){
            $total_price += $result['price']*$result['quantity'];
            $quant+=$result['quantity'];
            
            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 60, 60);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }

            if($result['product_id']!=NULL){
                $data['products'][] = array(
                        'product_id' => $result['product_id'],
                        'image'      => $image,
                        'manager'    => $result['manager'],
                        'name'       => $result['name'],
                        'vin'        => $result['sku'],
                        'location'   => $result['location'],
                        'stock'      => isset($result['weight'])?$result['weight']:'не указан',
                        'model'      => $result['length'],
                        'price'      => $result['price'],
                        'date_added' => $result['date_added'],
                        'category'   => $result['category'],
                        'quantity'   => $result['quantity'],
                        'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                        'edit'       => $this->url->link('product/product_edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], true)
                );
            }
        }
        $data['donor']['quant'] = $quant;
        $data['donor']['totalp'] = $total_price;
        
        $data['go_site'] = HTTPS_CATALOG.'index.php?route=product/product&product_id=';
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token_add'] = $this->session->data['token'];
        
        $this->response->setOutput($this->load->view('donor/show', $data));
        
    }
    
}

