<?php
class ControllerTiresdiscCreate extends Controller {
    public function index() {
        $data = $this->getLayout();
        $this->response->setOutput($this->load->view('tiresdisc/create', $data));
    }
    
    public function getField() {
        $id = $this->request->post['id'];
        $cat = $this->request->post['cat'];
        $this->load->model('tiresdisc/tiresdisc');
        $parameters = $this->model_tiresdisc_tiresdisc->getAllParameters($cat);
        $h_ex = $this->db->query("SELECT name FROM ".DB_PREFIX."stocks WHERE 1");
        $stocks = array();
        
        foreach ($h_ex->rows as $stock) {
            $stocks[] = $stock['name'];
        }
        
        $form = '';
        $form.='<div class="form-group-sm col-md-3">';
            $form.='<label for="vin'.$id.'">Введите внутренний номер</label>';
            $form.='<input type="text" class="form-control" id="vin'.$id.'" name="info['.$id.'][vin]">';
        $form.='</div>';
        $form.='<div class="form-group-sm col-md-3">';
            $form.='<label for="price'.$id.'">Введите цену товара</label>';
            $form.='<input type="text" class="form-control" id="price'.$id.'" name="info['.$id.'][price]" value="0">';
        $form.='</div>';
        $form.='<div class="form-group-sm col-md-3">';
            $form.='<label for="quant'.$id.'">Введите количество товара</label>';
            $form.='<input type="text" class="form-control" id="quant'.$id.'" name="info['.$id.'][quant]" value="1">';
        $form.='</div>';
        
        foreach ($parameters as $field => $param) {
            $form.='<div class="form-group-sm col-md-3">';
                $form.='<label for="'.$field.$id.'">"'.$param['name'].'"</label>';
                $form.='<select class="form-control" id="'.$field.$id.'" name="info['.$id.']['.$field.']">';
                    foreach ($param['values'] as $value) {
                        $form.='<option value="'.$value['value'].'">'.$value['value'].'</option>';
                    }
                $form.='</select>';
            $form.='</div>';
        }
        $form.= '<div class="form-group-sm col-md-3">';
            $form.='<label for="photos'.$id.'">фотографии</label>';
            $form.= '<input name="photo['.$id.'][]" id="photos'.$id.'" class="form-control" type="file" multiple="true">';
        $form.= '</div>';
        
        /*-----------------------------------------------*/
        $form.='<div class="form-group-sm col-md-3">';
            $form.='<label for="stock'.$id.'">склад</label>';
            $form.='<select type="text" class="form-control" id="stock'.$id.'" name="info['.$id.'][stock]">';
                    $form.= '<option value="-">---</option>';
                    foreach ($stocks as $stock) {
                        $form.= '<option value="'.$stock.'">'.$stock.'</option>';
                    }
            $form.='</select>';
        $form.='</div>';
        $form.='<div class="col-md-6">';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="stell'.$id.'">стеллаж</label>';
                    $form.='<input type="text" class="form-control" id="stell'.$id.'" name="info['.$id.'][stell]">';
                $form.='</div>';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="jar'.$id.'">ярус</label>';
                    $form.='<input type="text" class="form-control" id="jar'.$id.'" name="info['.$id.'][jar]">';
                $form.='</div>';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="shelf'.$id.'">полку</label>';
                    $form.='<input type="text" class="form-control" id="shelf'.$id.'" name="info['.$id.'][shelf]">';
                $form.='</div>';
                $form.='<div class="form-group-sm col-md-3">';
                    $form.='<label for="box'.$id.'">коробку</label>';
                    $form.='<input type="text" class="form-control" id="box'.$id.'" name="info['.$id.'][box]">';
                $form.='</div>';
            $form.='</div>';
        /*------------------------------------------------*/
        $form.='<div class="form-group-sm col-md-3">';
            $form.='<label for="ctype'.$id.'">тип товара</label>';
            $form.='<select type="text" class="form-control" id="ctype'.$id.'" name="info['.$id.'][ctype]">'
                    . '<option value="Б/У">Б/У</option>'
                    . '<option value="Новый">Новый</option>'
                . '</select>';
        $form.='</div>';
        $form.='<div class="form-group-sm col-md-3">';
            $form.='<label for="cond'.$id.'">состояние товара</label>';
            $form.='<select type="text" class="form-control" id="cond'.$id.'" name="info['.$id.'][cond]">'
                    . '<option value="Отличное">Отличное</option>'
                    . '<option value="Хорошее">Хорошее</option>'
                    . '<option value="Удовлетворительное">Удовлетворительное</option>'
                . '</select>';
        $form.='</div>';
        $form.='<div class="form-group-sm col-md-3">';
            $form.='<label for="dop'.$id.'">Введите доп.информацию</label>';
            $form.='<input type="text" class="form-control" id="dop'.$id.'" name="info['.$id.'][dop]" value="">';
        $form.='</div>';
        $form.='<div class="form-group-sm col-md-3">';
            $form.='<label for="note'.$id.'">Введите примечание</label>';
            $form.='<input type="text" class="form-control" id="note'.$id.'" name="info['.$id.'][note]" value="">';
        $form.='</div>';
        echo $form;
    }
    
    public function create() {
//        exit(var_dump($this->request->post['info']));
        $this->load->model('tiresdisc/tiresdisc');
        $photos = $this->constructPhotoArray($this->request->files['photo']);
//        exit(var_dump($photos));
        foreach ($this->request->post['info'] as $pid => $prod) {
            $this->model_tiresdisc_tiresdisc->createProd($prod, isset($photos[$pid][0]['name'])?$photos[$pid]:FALSE);
        }
        $this->response->redirect($this->url->link('tiresdisc/create', 'token=' . $this->session->data['token'], true));
    }
    
    public function constructPhotoArray($photos) {
        $result = array();
        foreach ($photos as $key => $crit) {
            foreach ($crit as $pid => $value) {
                foreach($value as $photID => $photo){
                    if(strlen($photo)!=0){
                        $result[$pid][$photID][$key] = $photo;
                    }    
                } 
            }
        }
        return $result;
    }
    
    public function constructProdArray($prods, $photo) {
        
    }
    
    public function getLayout() {
        
        
        $this->load->language('tiresdisc/create');

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
                'href' => $this->url->link('tiresdisc/create', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['action'] = $this->url->link('tiresdisc/create/create', 'token=' . $this->session->data['token'], true);

        return $data;
        
    }
}

