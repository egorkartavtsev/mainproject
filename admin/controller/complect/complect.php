<?php
class ControllerComplectComplect extends Controller {
    
    public function index(){
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $this->load->model('complect/complect');
        $data['complects'] = $this->model_complect_complect->getTotalComplects();
        $data['token'] = $this->session->data['token'];
        $this->response->setOutput($this->load->view('complect/complect', $data));
    }
    
    public function create() {
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
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
        $sale = $this->request->post['sale']!=0?$this->request->post['sale']:15;
        
        $this->model_complect_complect->create($name, $price, $heading, $complect, $whole, $sale);
        
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
        $sale = $this->request->post['sale']!=0?$this->request->post['sale']:15;
        $this->model_complect_complect->editComplect($id, $name, $price, $heading, $complect, $whole, $sale);
        
        echo $name.' - комплект успешно сохранён!';
    }
    
    public function deleteAccss(){
        $this->load->model('tool/complect');
        $heading = $this->model_tool_complect->isCompl($this->request->post['accss']);
        $this->model_tool_complect->compReprice($heading['complect']['heading']);
        $this->db->query("UPDATE ".DB_PREFIX."product SET comp = '' WHERE vin = '".$this->request->post['accss']."'");
    }

        public function edit() {
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $this->load->model('complect/complect');
        $complect = $this->request->get['complect'];
        $data['complect'] = $this->model_complect_complect->getComplect($complect);
        $data['id'] = $complect;
        $this->response->setOutput($this->load->view('complect/edit', $data));
    }
    
    function searchComplects() {
        $request = $this->request->post['request'];
        $this->load->model('complect/complect');
        if($request!=''){
            $total = $this->model_complect_complect->searchComplects($request);
        } else {
            $total = $this->model_complect_complect->getTotalComplects();
        }
        
        if(!empty($total)){
                $output = '';
                foreach ($total as $comp) {
                    $href = HTTP_SERVER.'index.php?route=complect/complect/edit&complect='.$comp['id'].'&token='.$this->session->data['token'];
                    $output.='<tr>'
                                . '<td>'.$comp['id'].'</td>'
                                . '<td>'.$comp['name'].'</td>'
                                . '<td>'.$comp['link'].'</td>'
                                . '<td>'.$comp['heading'].'</td>'
                                . '<td>'.$comp['price'].'</td>'
                                . '<td>
                                            <a href="'.$href.'" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Редактировать">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <button btn_type="deleteCompl" complId="'.$comp['id'].'"  data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Раскомплектация">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </td>'
                            . '</tr>';
                }
                exit($output);
            }else{
                exit('Ничего не найдено');
            }
    }
}

