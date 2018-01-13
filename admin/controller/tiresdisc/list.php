<?php

class ControllerTiresDiscList extends Controller {
    public function index() {
        $data = $this->getLayout();
        $this->load->model("tiresdisc/tiresdisc");
        $filter = array();
        $data['link'] = 'index.php?route='.$this->request->get['route'].'&token='.$this->session->data['token'];
        if(isset($this->request->get['sortorder'])){
            $data['sortorder'] = $this->request->get['sortorder']=='ASC'?'DESC':'ASC';
        } else {
            $data['sortorder'] = 'ASC';
        }
        $data['link'].= '&sortorder='.$data['sortorder'];
        if(!empty($this->request->post)){
            foreach ($this->request->post as $field => $value) {
                $filter[$field] = $value=='all'?FALSE:$value;
            }
        }
        if(isset($this->request->get['sort'])){
            $sort = 'ORDER BY '.$this->request->get['sort'].' '.$this->request->get['sortorder'];
        } else {
            $sort = '';
        }
        $data['curr_filter'] = (isset($filter['cat']) && $filter['cat'])?$filter['cat']:'all';
        $list = $this->model_tiresdisc_tiresdisc->getList($filter, $sort);
        $this->load->model('tool/image');
        $data['list'] = array();
        foreach ($list as $prod) {
            if(isset($prod['discImage']) && $prod['discImage']!==NULL){
                if (is_file(DIR_IMAGE.$prod['discImage'])) {
                        $image = $this->model_tool_image->resize($prod['discImage'], 40, 40);
                } else {
                        $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                }
                $data['list'][] = array(
                    'name'      => $prod['name'],
                    'image'     => $image,
                    'cat'       => 'disc',
                    'cat_name'  => 'Диски',
                    'date'      => $prod['date'],
                    'link'      => $prod['link'],
                    'linkEdit'  => $this->url->link('tiresdisc/edit', 'token='.$this->session->data['token'].'&prod='.$prod['link'], TRUE),      
                    'price'     => $prod['price'],
                    'vin'       => $prod['vin'],      
                    'quant'     => $prod['quan'],      
                    'locate'    => $prod['stock'].'/'.$prod['location'],      
                    'stat'      => $prod['status'],      
                    'type'      => $prod['type'],      
                    'cond'      => $prod['cond']      
                );
            } else {
                if (is_file(DIR_IMAGE.$prod['tiresImage'])) {
                        $image = $this->model_tool_image->resize($prod['tiresImage'], 40, 40);
                } else {
                        $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                }
                $data['list'][] = array(
                    'name'  => $prod['name'],
                    'image'  => $image,
                    'cat'  => 'tire',
                    'cat_name'  => 'Шины',
                    'date'  => $prod['date'],
                    'price'     => $prod['price'],
                    'vin'       => $prod['vin'],      
                    'quant'     => $prod['quan'],      
                    'locate'    => $prod['stock'].'/'.$prod['location'],      
                    'stat'      => $prod['status'],
                    'link'      => $prod['link'],
                    'linkEdit'  => $this->url->link('tiresdisc/edit', 'token='.$this->session->data['token'].'&prod='.$prod['link'], TRUE),      
                    'type'      => $prod['type'],      
                    'cond'      => $prod['cond']
                );
            }
        }
        
        
        $this->response->setOutput($this->load->view("tiresdisc/list", $data));
    }
    
    public function deleteItem(){
        $id = $this->request->post['id'];
        $this->load->model('tiresdisc/tiresdisc');
        $result = $this->model_tiresdisc_tiresdisc->deleteProd($id);
        echo $result;
    }


    public function getLayout() {
        
        
        $this->load->language('tiresdisc/list');

        $this->document->setTitle($this->language->get('heading_title'));
        $data['token'] = $this->session->data['token'];
        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('tiresdisc/list', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        return $data;
        
    }
    
    public function getFilter() {
        $categ = $this->request->post['categ']=='disc'?'disk':'tire';
        $cat = $this->request->post['categ'];
        if($this->request->post['categ']=='all'){
            $categ = $this->request->post['categ'];
            $cat = 'all';
        }
        $this->load->model('tiresdisc/tiresdisc');
        $params = array();
        if($categ!=='all'){
            $params = $this->model_tiresdisc_tiresdisc->getAllParameters($categ);
        }
        $result = '<form method="post" action="index.php?route=tiresdisc/list&token='.$this->session->data['token'].'">';
        
        foreach ($params as $field => $value) {
            $result.='<div class="form-group col-md-3">';
                $result.='<label for="'.$field.'">'.$value['name'].'</label>';
                $result.='<select id="'.$field.'" name="'.$field.'" class="form-control">';
                $result.='<option value="all">Все товары</option>';
                foreach ($value['values'] as $row) {
                    $result.='<option value="'.$row['value'].'">'.$row['value'].'</option>';
                }
                $result.='</select>';
            $result.='</div>';
        }
            $result.='<div class="form-group col-md-3"><label for="vin">Внутренний номер</label><input type="text" class="form-control" id="vin" name="vin"></div>';
            $result.='<div class="clearfix"></div>';
            $result.='<input type="hidden" id="cat" name="cat" value="'.$cat.'">';
            $result.='<button class="btn btn-primary" id="submit-filters">Применить фильтры</button>';
        $result.='</form>';
        echo $result;
    }
    
}

