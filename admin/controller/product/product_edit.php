<?php
class ControllerProductProductEdit extends Controller {
    public function index(){
        $this->load->model('tool/product');
        $this->load->model('product/product');
        $this->load->model('tool/image');
        $this->load->model('tool/forms');
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

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();
        
        $data['token'] = $this->session->data['token'];

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );
        $data['breadcrumbs'][] = array(
                'text' => 'Товары',
                'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['form'] = $this->model_tool_forms->constructEditForm($info, $this->request->get['product_id']);
        $data['name'] = $this->model_tool_product->getProdName($this->request->get['product_id']);
        $data['action'] = $this->url->link('product/product_edit/saveForm', 'token='.$this->session->data['token'].'&product_id='.$this->request->get['product_id']);
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
            $brtr = $this->db->query("SELECT translate FROM ".DB_PREFIX."lib_fills WHERE name = '".$product['brand']."'");
            $mtr = $this->db->query("SELECT translate FROM ".DB_PREFIX."lib_fills WHERE name = '".$product['model']."'");
            $data['avitoname'] = $product['podcateg'].' '.$product['brand'].' '.$product['model'].'/'.$brtr->row['translate'].' '.$mtr->row['translate'];
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
    
    public function saveForm() {
        $this->load->model('tool/xml');
        $this->load->model('tool/forms');
        $this->load->model('tool/product');
        $info = $this->model_tool_product->getProdStructure($this->request->post, $this->request->get['product_id']);
//        exit(var_dump($info));
        $this->model_tool_forms->updateProduct($info, $this->request->get['product_id']);
        $alinfo = $this->request->post['info'];
        $alinfo['vin'] = $info['vin'];
        $alinfo['structure'] = $info['structure'];
//        exit(var_dump($alinfo));
        $alinfo['pid'] = $this->request->get['product_id'];
        if($this->session->data['uType']==='adm' && $this->request->post['allowavito']==='да'){
            $this->model_tool_xml->findAd($alinfo);
        }
        $alinfo['name'] = $alinfo['avitoname'];
//        exit(var_dump($alinfo));
        $this->model_tool_xml->findARPart($alinfo);
        $this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'], true));
//        exit(var_dump($this->request->post));
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
    
    public function remCompl() {
        $heading = $this->request->post['heading'];
        $item = $this->request->post['item'];
        $this->load->model('product/product');
        $this->model_product_product->remCompl($item, $heading);
        echo 1;
    }
}
