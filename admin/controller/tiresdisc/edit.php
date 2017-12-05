<?php

class ControllerTiresdiscEdit extends Controller {
    
    public function index() {
        $data = $this->getLayout();
        $id = $this->request->get['prod'];
        $this->load->model('tiresdisc/tiresdisc');
        if(!empty($this->request->post)){
            $this->model_tiresdisc_tiresdisc->updateDB($this->request->post, $id);
        }
        $cat = $this->model_tiresdisc_tiresdisc->getCat($id);
        $info = $this->model_tiresdisc_tiresdisc->getProdInfo($id, $cat);
        $data['form'] = $this->getForm($id, $info, $cat);
        $photos = $this->model_tiresdisc_tiresdisc->getImages($id);
        $local_id = 0;
        $data['images'] = array();
        foreach ($photos as $img) {
            if($img===$info['main-image']){
                $data['images'][] = array(
                    'image' => $img,
                    'thumb' => $this->model_tool_image->resize($img, 100, 100),
                    'lid'   => $local_id,
                    'main' => TRUE
                );
            } else {
                $data['images'][] = array(
                    'image' => $img,
                    'thumb' => $this->model_tool_image->resize($img, 100, 100),
                    'lid'   => $local_id,
                    'main' => FALSE
                );
            }
            ++$local_id;
        }
        
        $this->response->setOutput($this->load->view('tiresdisc/edit', $data));
    }
    
    private function getForm($id, $info, $cat) {
        $this->load->model('tiresdisc/tiresdisc');
        $parameters = $this->model_tiresdisc_tiresdisc->getAllParameters($cat);
        $h_ex = $this->db->query("SELECT name FROM ".DB_PREFIX."stocks WHERE 1");
        $stocks = array();
        
        foreach ($h_ex->rows as $stock) {
            $stocks[] = $stock['name'];
        }
        
        $form = '<h3>'.$info['name'].'</h3>';
        $form.= '<form method="post" class="row alert alert-success" action="'.$this->url->link('tiresdisc/edit', 'token=' . $this->session->data['token'] . '&prod=' . $id, true).'">';
            $form.='<input type="hidden" name="mainimage" value="'.$info['main-image'].'" id="input-main-image" />';
            $form.='<div class="col-sm-12">';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="vin'.$id.'">Введите внутренний номер</label>';
                    $form.='<input type="text" class="form-control" id="vin'.$id.'" name="vin" value="'.$info['vin'].'">';
                $form.='</div>';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="price'.$id.'">Введите цену товара</label>';
                    $form.='<input type="text" class="form-control" id="price'.$id.'" name="price" value="'.$info['price'].'">';
                $form.='</div>';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="quant'.$id.'">Введите количество товара</label>';
                    $form.='<input type="text" class="form-control" id="quant'.$id.'" name="quant" value="'.$info['quant'].'">';
                $form.='</div>';

                foreach ($parameters as $field => $param) {
                    $form.='<div class="form-group-sm col-md-3">';
                        $form.='<label for="'.$field.$id.'">'.$param['name'].'</label>';
                        $form.='<select class="form-control" id="'.$field.$id.'" name="'.$field.'">';
                            foreach ($param['values'] as $value) {
                                $selected = ($value['value']===$info[$field])?'selected':NULL;
                                $form.='<option value="'.$value['value'].'" '.$selected.'>'.$value['value'].'</option>';
                            }
                        $form.='</select>';
                    $form.='</div>';
                }
                /*-----------------------------------------------*/
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="stock'.$id.'">Склад</label>';
                    $form.='<select type="text" class="form-control" id="stock'.$id.'" name="stock">';
                            $form.= '<option value="-">---</option>';
                            foreach ($stocks as $stock) {
                                $selected = $stock==$info['stock']?'selected':NULL;
                                $form.= '<option value="'.$stock.'" '.$selected.'>'.$stock.'</option>';
                            }
                    $form.='</select>';
                $form.='</div>';
                $form.='<div class="col-md-6">';
                        $form.='<div class="form-group-sm col-md-3">';
                            $form.='<label for="stell'.$id.'">Стеллаж</label>';
                            $form.='<input type="text" class="form-control" id="stell'.$id.'" name="stell" value="'.$info['stell'].'">';
                        $form.='</div>';
                        $form.='<div class="form-group-sm col-md-3">';
                            $form.='<label for="jar'.$id.'">Ярус</label>';
                            $form.='<input type="text" class="form-control" id="jar'.$id.'" name="jar" value="'.$info['jar'].'">';
                        $form.='</div>';
                        $form.='<div class="form-group-sm col-md-3">';
                            $form.='<label for="shelf'.$id.'">Полку</label>';
                            $form.='<input type="text" class="form-control" id="shelf'.$id.'" name="shelf" value="'.$info['shelf'].'">';
                        $form.='</div>';
                        $form.='<div class="form-group-sm col-md-3">';
                            $form.='<label for="box'.$id.'">Коробку</label>';
                            $form.='<input type="text" class="form-control" id="box'.$id.'" name="box" value="'.$info['box'].'">';
                        $form.='</div>';
                    $form.='</div>';
                /*------------------------------------------------*/
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="ctype'.$id.'">Тип товара</label>';
                    $form.='<select type="text" class="form-control" id="ctype'.$id.'" name="ctype">'
                            . '<option value="Б/У" ';
                            if($info['ctype']=='Б/У'){$form.='selected';}
                            $form.='>Б/У</option>'
                            .'<option value="Новый" ';
                            if($info['ctype']!='Б/У'){$form.='selected';}
                            $form.='>Новый</option>'
                        . '</select>';
                $form.='</div>';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="cond'.$id.'">Cостояние товара</label>';
                    $form.='<input type="text" class="form-control" id="cond'.$id.'" name="cond" value="'.$info['cond'].'">';
                $form.='</div>';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="stat'.$id.'">Статус товара</label>';
                    $form.='<select class="form-control" id="stat'.$id.'" name="status" value="'.$info['cond'].'">';
                        $form.='<option value="1">Включен</option>';
                        $form.='<option value="0" '.($info['status']=='0'?'selected':'').'>Отключен</option>';
                    $form.='</select>';
                $form.='</div>';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="dop'.$id.'">Доп.информация</label>';
                    $form.='<input type="text" class="form-control" id="dop'.$id.'" name="dop" value="'.$info['dop'].'">';
                $form.='</div>';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="note'.$id.'">Примечание</label>';
                    $form.='<input type="text" class="form-control" id="note'.$id.'" name="note" value="'.$info['note'].'">';
                $form.='</div>';
            $form.='</div>';
        return $form;
    }
    
    public function getLayout() {
        
        
        $this->load->language('tiresdisc/edit');

        $this->document->setTitle($this->language->get('heading_title'));
        $data['token'] = $this->session->data['token'];
        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('main'),
                'href' => $this->url->link('tiresdisc/list', 'token=' . $this->session->data['token'], true)
        );
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        return $data;
        
    }
}

