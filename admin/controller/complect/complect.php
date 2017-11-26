<?php
class ControllerComplectComplect extends Controller {
    
    public function index(){
        $data = $this->getLayout();
        $this->load->model('complect/complect');
        $data['complects'] = $this->model_complect_complect->getTotalComplects();
        $data['token'] = $this->session->data['token'];
        $this->response->setOutput($this->load->view('complect/complect', $data));
    }
    
    public function create() {
        $data = $this->getLayout();
        $data['breadcrumbs'][] = array(
            'href' => 'index.php?route=complect/complect/create&token='.$this->session->data['token'],
            'text' => 'Создание комплекта'
        );
        $data['token'] = $this->session->data['token'];
        $this->response->setOutput($this->load->view('complect/create', $data));
    }

    public function validVin() {
        $this->load->model('complect/complect');
        $this->load->language('complect/complect');
        $vin = $this->request->post['vin'];
        if($vin==''){
            exit('');
        }
        $valid_result = $this->model_complect_complect->validation($vin);
        if($valid_result){
            echo $valid_result['name'];
            //echo var_dump($valid_result);
        } else {
            echo $this->language->get('invalid').' - '.$vin;
        }
    }
    
    public function writeOff() {
        $this->load->model('complect/complect');
        $id = $this->request->post['id'];
        $this->model_complect_complect->writeOff($id);
    }
    
    public function addComplect() {
        $this->load->model('complect/complect');
        
        $name = $this->request->post['name'];
        $price = $this->request->post['price'];
        $heading = $this->request->post['heading'];
        $complect = explode(",", $this->request->post['complect']);
        $whole = $this->request->post['whole'];
        
        $this->model_complect_complect->create($name, $price, $heading, $complect, $whole);
        
        echo $name.' - комплект успешно создан! <br><br>';
    }
    
    public function editComplect() {
        
        $this->load->model('complect/complect');
        $id = $this->request->post['id'];
        $name = $this->request->post['name'];
        $price = $this->request->post['price'];
        $heading = $this->request->post['heading'];
        $complect = explode(",", $this->request->post['complect']);
        $whole = $this->request->post['whole'];
        $this->model_complect_complect->editComplect($id, $name, $price, $heading, $complect, $whole);
        
        echo $name.' - комплект успешно сохранён!';
    }
    
    public function deleteAccss(){
        $this->db->query("UPDATE ".DB_PREFIX."product SET comp = '' WHERE sku = '".$this->request->post['accss']."'");
    }

        public function edit() {
        $data = $this->getLayout();
        $this->load->model('complect/complect');
        $complect = $this->request->get['complect'];
        $data['complect'] = $this->model_complect_complect->getComplect($complect);
        $data['id'] = $complect;
        $data['heading_title'] = $data['complect']['name'];
        $data['breadcrumbs'][] = array(
            'href' => 'index.php?route=complect/complect/edit&token='.$this->session->data['token'].'&complect='.$complect,
            'text' => 'Редактирование комплекта'
        );
        $data['token'] = $this->session->data['token'];
        $this->response->setOutput($this->load->view('complect/edit', $data));
    }
    
    public function getLayout() {
        
        
                $this->load->language('complect/complect');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('complect/complect', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('complect/complect', 'token=' . $this->session->data['token'], true)
		);
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                
                return $data;
        
    }
    
    function searchComplects() {
        $request = $this->request->post['request'];
        if($request!=''){
            $this->load->model('complect/complect');
            $total = $this->model_complect_complect->searchComplects($request);
            if(!empty($total)){
                $output = '';
                foreach ($total as $comp) {
                    $output.='<tr>'
                                . '<td>'.$comp['id'].'</td>'
                                . '<td>'.$comp['name'].'</td>'
                                . '<td>'.$comp['heading'].'</td>'
                                . '<td>'.$comp['price'].'</td>'
                                . '<td></td>'
                            . '</tr>';
                }
                exit($output);
            }else{
                exit('Ничего не найдено');
            }
        }
    }
}

