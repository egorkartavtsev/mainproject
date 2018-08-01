<?php
class ControllerCommonDashboard extends Controller {
	public function index() {
		$this->load->language('common/dashboard');
                $this->load->model('tool/layout');
                $this->load->model('tool/image');
                $data = $this->model_tool_layout->getLayout($this->request->get['route']);
                $this->model_tool_layout->updateADS($this->request->get['route']);
                
                $data['ses_token'] = $this->session->data['token'];
		$data['breadcrumbs'] = array();
                $fcItems = $this->user->getLayout();
                $data['fcItems'] = $fcItems['fcmenu'];
                
                $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."order WHERE viewed = 0 ");
                if($sup->num_rows){
                    $data['notice']['order'] = '<a href="'.$this->url->link('report/orders', 'token='.$this->session->data['token']).'" style="float: left;">'
                            . '<div class="db-notice orders" style="color: white;">'
                                . '<h3>Новые заказы с сайта: </h3>'
                                . '<h1><i class="fa fa-pencil-square-o fw"></i> <b>'.$sup->num_rows.'</b>шт.</h3>'
                            . '</div>'
                        . '</a>';
                }
                $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_avito WHERE message = 1 ");
                if($sup->num_rows){
                    $data['notice']['avito'] = '<a href="'.$this->url->link('avito/avito_list', 'token='.$this->session->data['token']).'" style="float: left;">'
                            . '<div class="db-notice avito" style="color: white;">'
                                . '<h3>Окончилась активация: </h3>'
                                . '<h1><i class="fa fa-adn fw"></i> <b>'.$sup->num_rows.'</b>объявл.</h3>'
                            . '</div>'
                        . '</a>';
                }
                
                $data['messages'] = FALSE;
                $data['version'] = sprintf('Версия программы V %s', VERSION);
                $user = $this->user->getUserInfo();
                if(!is_file(DIR_IMAGE.$user['image'])){
                    $data['version'].= ' (Аватар не добавлен. Аватар добавляется администратором.)';
                }
                $data['user'] = array(
                    'avatar' => $this->model_tool_image->resize($user['image'], 1200, 1200),
                    'group' => $user['user_group'],
                    'email' => $user['email'],
                    'first' => $user['firstname'],
                    'last' => $user['lastname']
                );
                
		$this->response->setOutput($this->load->view('common/dashboard', $data));
	}
}