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
            $data = $this->getLayout();
            $data['token'] = $this->session->data['token'];
            $data['ckeditor'] = $this->config->get('config_editor_default');
            $this->load->model('common/tempdesc');
            $data['description'] = $this->model_common_tempdesc->getTemp(1);
            
            if(isset($this->request->post['temp'])){
                $this->model_common_tempdesc->saveTemp(1, $this->request->post['temp']);
            }
            
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
}