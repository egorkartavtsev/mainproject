<?php
class ControllerProductProductEdit extends Controller {
    public function index(){
        $data = $this->setLayout();
        $uType = $this->session->data['uType'];
        $uName = $this->session->data['username'];
        $data['uType'] = $uType;
        $data['uName'] = $uName;
        $pid = $this->request->get['product_id'];
        $this->load->model('product/product');
        
        $product_info = $this->model_product_product->getInfo($pid);
        
        $photos = $this->model_product_product->getPhotos($pid);
                
        foreach ($product_info as $key => $cell) {
            $data[$key] = $cell;
        }
        
        $this->load->model('tool/image');
        
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        $local_id = 0;
        $data['images'] = array();
        foreach ($photos as $img) {
            if($img===$data['mainimage']){
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
        
        $data['pid'] = $pid;
        $location = explode("/", $data['location']);
        $data['stell'] = isset($location[0])?$location[0]:'';
        $data['jar'] = isset($location[1])?$location[1]:'';
        $data['shelf'] = isset($location[2])?$location[2]:'';
        $data['box'] = isset($location[3])?$location[3]:'';
        $data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], true);
        $data['action'] = $this->url->link('product/product_edit/saveForm', 'token=' . $this->session->data['token'] . '&product_id=' . $pid, true);
        //берём категории
        $query = $this->db->query("SELECT "
                . "c.category_id AS id, "
                . "cd.name AS name "
                . "FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd "
                    . "ON (cd.language_id=1 AND cd.category_id = c.category_id) "
                . "WHERE c.parent_id = 0 ORDER BY cd.name ");

        $results = $query->rows;
        $data['category'] = array();
        foreach ($results as $res) {
                $data['category'][] = array(
                    'name' => $res['name'],
                    'val'  => $res['id']
                );
        }

        //берём марки
        $query = $this->db->query("SELECT id, name FROM ".DB_PREFIX."brand "
                                . "WHERE parent_id = 0 ORDER BY name");

        $brands = $query->rows;
        $data['brands'] = array();
        foreach ($brands as $res) {
            $data['brands'][] = array(
            'name' => $res['name'],
            'val'  => $res['id']
            );
        }

        //Берём склады
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."stocks WHERE 1");
        $stocks = $query->rows;
        foreach ($stocks as $stock) {
            $data['stocks'][] = $stock['name'];
        }
        
        //берём подкатегории
        $query = $this->db->query("SELECT cd.name AS name FROM ".DB_PREFIX."category_description cd LEFT JOIN ".DB_PREFIX."category c ON cd.category_id = c.category_id WHERE c.parent_id = ".(int)$this->model_product_product->getCatId($data['categ'])." ORDER BY cd.name ");
        foreach ($query->rows as $pc) {
            $data['podcategs'][] = $pc['name'];
        }
        
        //берём модели
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE parent_id = ".(int)$data['brand_id']." ORDER BY name ");
        $models = $query->rows;
        foreach ($models as $model) {
            $data['models'][] = $model['name'];
        }
        //берём модельные ряды
        $query = $this->db->query("SELECT id FROM ".DB_PREFIX."brand WHERE name = '".$data['model']."'");
        $mod_id = $query->row['id'];
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE parent_id = ".(int)$mod_id." ORDER BY name");
        $modRs = $query->rows;
        foreach ($modRs as $modR) {
            $data['modRs'][] = $modR['name'];
        }
        
        $this->response->setOutput($this->load->view('product/product_edit', $data));
    }
    
    public function get_model() {
        $currMod = $this->request->post['currMod'];
        $brand = $this->request->post['brand'];
        if(isset($this->request->post['mr'])){
            $mr = TRUE;
        } else {
            $mr = FALSE;
        }
        $this->load->model('product/product');
        $models = $this->model_product_product->getModels($brand, $mr);
        if (count($models)>1){
            $result = '<option disabled>Выберите модель</option>';
        } else {
            $result = '<option disabled selected>Выберите модель</option>';
        }
        foreach ($models as $model) {
            if(($model == $currMod) && (count($models)>1)){
                $result.= '<option value="'.$model.'" selected >'.$model.'</option>';
            } else {
                $result.= '<option value="'.$model.'">'.$model.'</option>';
            }
        }
        echo $result;
    }
    
    public function get_podcat() {
        $currPC = $this->request->post['currPC'];
        $cat = $this->request->post['cat'];
        $this->load->model('product/product');
        $pcats = $this->model_product_product->getPCs($cat);
        $result = '';
        foreach ($pcats as $pcat) {
            if($pcat == $currPC){
                $result.= '<option value="'.$pcat.'" selected >'.$pcat.'</option>';
            } else {
                $result.= '<option value="'.$pcat.'">'.$pcat.'</option>';
            }
        }
        echo $result;
    }
    
    private function setLayout() {
        $this->load->language('catalog/product');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();
        
        $data['token'] = $this->session->data['token'];

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('product/product_edit', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        return $data;

    }
    
    public function saveForm(){
        $this->load->model('product/product');
        
        $data = $this->request->post;
        $data['pid'] = $this->request->get['product_id'];
        if($data['vin']==''){
            $data['vin'] = $this->model_product_product->getVin($data['pid']);
        }
        $this->model_product_product->updateProduct($data);
        
        $this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'], true));
    }
}
