<?php
class ControllerTiresdiscParam extends Controller {
	public function index() {
            $data = $this->getLayout();
            $this->load->model('tiresdisc/tiresdisc');
            $data['parameters'] = $this->model_tiresdisc_tiresdisc->getParameters();
            $this->response->setOutput($this->load->view('tiresdisc/param', $data));
        }
        
        public function getValues() {
            $param = $this->request->post['param'];
            $this->load->model('tiresdisc/tiresdisc');
            $values = $this->model_tiresdisc_tiresdisc->getValues($param);
            $result = '<table class="table table-bordered table-hover table-responsive table-striped">';
            $result.= '<thead><tr><td>Параметр</td><td>Действие</td></tr></thead><tbody id="pv'.$param.'">';
            foreach ($values as $value) {
                $result.='<tr>';
                    $result.='<td style="cursor: pointer;" id="rpv'.$value['id'].'">'.$value['value'].'</td>';
                    $result.='<td>'
                            . '<button class="btn btn-primary" onclick="getEditPV(\'rpv'.$value['id'].'\', \''.$value['id'].'\')"><i class="fa fa-pencil fw"></i></button> '
                            . '<button class="btn btn-danger" onclick="confirm(\'Вы уверены?\') ? deletePVal(\''.$value['id'].'\', \'rpv'.$value['id'].'\') : false;"><i class="fa fa-trash-o fw"></i></button>'
                        . '</td>';
                $result.='</tr>';
            }
            $result.='</tbody>';
            $result.='</table><button class="btn btn-primary btn-block" onclick="createPValue(\''.$param.'\')"><i class="fa fa-plus-circle"></i> Добавить значение</button>';
            echo $result;
        }
        
        public function editParam() {
            $param = $this->request->post['param'];
            $name = $this->request->post['value'];
            if($this->request->post['table']=='discparams'){
                $belong = 'disk';
                $id = 'dp';
                $div = 'dlibr';
            } else {
                $belong = 'tire';
                $id = 'tp';
                $div = 'tlibr';
            }
            $this->load->model('tiresdisc/tiresdisc');
            $retVal = $this->model_tiresdisc_tiresdisc->editParam($param, $name, $belong);
            $result='<td style="cursor: pointer;" id="'.$id.$retVal['id'].'" onclick="getLibr(\''.$retVal['id'].'\', \''.$div.'\')">'.$retVal['name'].'</td>';
            $result.='<td>'
                        . '<button class="btn btn-primary" onclick="getEdit(\''.$id.$retVal['id'].'\', \''.$retVal['id'].'\', \''.$this->request->post['table'].'\')"><i class="fa fa-pencil fw"></i></button> '
                        . '<button class="btn btn-danger" onclick="confirm(\'Вы уверены?\') ? deleteParam(\''.$retVal['id'].'\', \''.$id.$retVal['id'].'\') : false;"><i class="fa fa-trash-o fw"></i></button>'
                    . '</td>';
            echo $result;
        }
        
        public function delParam() {
            $param = $this->request->post['param'];
            $this->load->model('tiresdisc/tiresdisc');
            $result = $this->model_tiresdisc_tiresdisc->deleteParam($param);
            echo $result;
        }
        
        public function delPVal() {
            $param = $this->request->post['pv'];
            $this->load->model('tiresdisc/tiresdisc');
            $result = $this->model_tiresdisc_tiresdisc->delPVal($param);
            echo $result;
        }
        
        public function editPValue() {
            $parent = $this->request->post['param'];
            $value = $this->request->post['value'];
            $type = $this->request->post['type'];
            $this->load->model('tiresdisc/tiresdisc');
            $retVal = $this->model_tiresdisc_tiresdisc->editPValue($parent, $value, $type);
            $result='<td style="cursor: pointer;" id="rpv'.$retVal['id'].'">'.$retVal['value'].'</td>';
            $result.='<td>'
                        . '<button class="btn btn-primary" onclick="getEditPV(\'rpv'.$retVal['id'].'\', \''.$retVal['id'].'\')"><i class="fa fa-pencil fw"></i></button> '
                        . '<button class="btn btn-danger" onclick="confirm(\'Вы уверены?\') ? deletePVal(\''.$retVal['id'].'\', \'rpv'.$retVal['id'].'\') : false;"><i class="fa fa-trash-o fw"></i></button>'
                    . '</td>';
            echo $result;
        }
        
        public function getLayout() {
        
        
                $this->load->language('tiresdisc/param');

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
			'href' => $this->url->link('tiresdisc/param', 'token=' . $this->session->data['token'], true)
		);
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                
                return $data;
        
    }
}
