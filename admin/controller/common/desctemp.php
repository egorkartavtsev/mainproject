<?php
class ControllerCommonDescTemp extends Controller {
    
    public $regex = array(
        'brand' => '%mark%',
        'model' => '%model%',
        'modr' => '%mr%',
        'podcat' => '%podcat%',
        'note' => '%prim%'
    );


    public function index() {
            $this->load->model('tool/layout');
            $data = $this->model_tool_layout->getLayout($this->request->get['route']);
            $data['token'] = $this->session->data['token'];
            $data['ckeditor'] = $this->config->get('config_editor_default');
            $this->load->model('common/tempdesc');
            $this->load->model('tool/product');
            $data['types'] = array();
            $types = $this->model_tool_product->getStructures();
            foreach($types as $type){
                $options = $this->model_tool_product->getOptions($type['type_id']);
                $data['types'][] = array(
                    'type_id' => $type['type_id'],
                    'text' => $type['text'],
                    'temp' => $type['temp'],
                    'desctemp' => $type['desctemp'],
                    'options' => $options['options']
                );
            }
//            exit(var_dump($data['ckeditor']));
            if(isset($this->request->post['template'])){
//                exit(var_dump($this->request->post));
                $this->model_tool_product->saveTemp($this->request->post['template'], $this->request->post['type_id']);
                $this->response->redirect($this->url->link('common/desctemp', 'token=' . $this->session->data['token'], true));
            }
            
            $data['description_prod'] = $this->model_common_tempdesc->getTemp(1);
            $data['description_avito'] = $this->model_common_tempdesc->getTemp(2);
            $data['description_drom'] = $this->model_common_tempdesc->getTemp(3);
            $this->response->setOutput($this->load->view('common/tempdesc', $data));
        }
        
        public function apply() {
            $original = $this->request->post['temp'];
            $result_desc = $this->request->post['temp'];
            $this->load->model('common/tempdesc');
            $result_array = array();
            $products = $this->model_common_tempdesc->getProducts();
            
            foreach ($products as $prod) {
                foreach ($this->regex as $key => $repl) {
                    $result_desc = str_replace($repl, $prod[$key], $result_desc);
                }
                $result_desc = str_replace("'", "-", $result_desc);
                $result_desc = str_replace("\\", "-", $result_desc);
                $result_desc = str_replace("\"", "-", $result_desc);
                $result_array[] = array(
                    'id' => $prod['pid'],
                    'text' => $result_desc
                );
                $result_desc = $original;
            }
            $this->model_common_tempdesc->apply($result_array);
            echo '<div class="alert alert-success" id="stattext">Описания товаров изменены успешно!</div>';
        }
        
        
    public function getLayout() {

                    if ($this->config->get('config_editor_default')) {
                        $this->document->addScript('view/javascript/ckeditor/ckeditor.js');
                        $this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
                    } else {
                        $this->document->addScript('view/javascript/summernote/summernote.js');
                        $this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
                        $this->document->addScript('view/javascript/summernote/opencart.js');
                        $this->document->addStyle('view/javascript/summernote/summernote.css');
                    }
                    
                    $this->load->language('common/tempdesc');

                    $this->document->setTitle($this->language->get('heading_title'));

                    $data['heading_title'] = $this->language->get('heading_title');

                    $data['breadcrumbs'] = array();

                    $data['breadcrumbs'][] = array(
                            'text' => $this->language->get('text_home'),
                            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                    );

                    $data['breadcrumbs'][] = array(
                            'text' => $this->language->get('heading_title'),
                            'href' => $this->url->link('common/desctemp', 'token=' . $this->session->data['token'], true)
                    );
                    $data['header'] = $this->load->controller('common/header');
                    $data['column_left'] = $this->load->controller('common/column_left');
                    $data['footer'] = $this->load->controller('common/footer');
                    $data['token_add'] = $this->session->data['token'];
                    return $data;

        }
        
        public function netwTempl() {
            $template = $this->request->post['temp'];
            $temp_id = $this->request->post['temp_id'];
            $this->db->query("UPDATE ".DB_PREFIX."text_template SET text = '".$template."' WHERE id = ".(int)$temp_id);
        }
}