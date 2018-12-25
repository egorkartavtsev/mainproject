<?php
class ControllerAccountIdentification extends Controller {

    private $request;

    public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/identification', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/identification');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);
                
                $data['breadcrumbs'][] = array(
			'text' => $this->language->get('Идентификация'),
			'href' => $this->url->link('account/identification', '', true)
		);
                
		//$this->load->model('account/identification');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_points'] = $this->language->get('column_points');

		$data['text_total'] = $this->language->get('text_total');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['randomStr'] = $this->generateRandomString();
		
		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
                $backinfo = $this->customer->getTotalInfo();
                    $data['approved']=$backinfo['approved'];
                    $data['code']=$backinfo['code'];
                    $data['email']=$backinfo['email'];
              //  exit(var_dump($data));
               $mail = mail($data['email'], 'Регистрация' , "Код подтверждения: ".$data['code']."","From: АВТОРАЗБОР174.РФ \r\n");
                            $data['textIdentificdtion'] = 'На данный email: '.$data['email'].' придет сообщения, с кодом активации. Скопируйте его и вставте в поле, расспологающая под этой записью.';
		$this->response->setOutput($this->load->view('account/identification', $data));
                        }
                public function generateRandomString($length = 30) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
                }
                
                
                public function bottonCompleteClick (){
                    
                
                
                    $backinfo = $this->customer->getTotalInfo();
                    $data['approved']=$backinfo['approved'];
                    $data['code']=$backinfo['code'];
                    $data['email']=$backinfo['email'];
                   // $this->session->data['redirect'] = $this->url->link('account/identification', '', true);
                  //  $this->response->redirect($this->url->link('account/login', '', true));
                   /* if ($data['approved'] == 1) {
                            $data['textIdentificdtion'] = 'Этот ящик идентифицирован';
                            echo $data['textIdentificdtion']; }
                            else {
                    if (mail($data['email'], 'Регистрация' , "Код подтверждения:".$data['code']."","From: АВТОРАЗБОР174.РФ \r\n")) { 
                                $data['textIdentificdtion'] = 'На данный:'.$email.'придет сообщения, с кодом активации. Скопируйте его и вставте в поле, расспологающая под этой записью.';
                            echo $data['textIdentificdtion']; 
                    } 
                       }*/
                             $data['codeEmail'] = $this->request->post['codeEmail'];
                             $data['codeEmail'] = $data['code'];
                             
                              if ($data['code'] == $data['codeEmail'] ) {
                                   // Сюда нужно вставить ваш запрос для идентификации
                                  $data['textTIdentificdtion'] = 'Этот ящик идентифицирован';
                                    echo $this->request->post['Кнопка работает'];    
                                    }
                                    else{ 
                                    echo $this->request->post['Код неверен'];    
                                    }
                                     echo $this->request->post[''];    
                    $this->response->setOutput($this->load->view('account/identification', $data));
                }	
}
                
                
                

