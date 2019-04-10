<?php
class ControllerAccountIdentification extends Controller {

    public function index() {
		$cust_id = $this->request->get['uid'];
		$try_code = $this->request->get['code'];
                
                $try = $this->db->query("SELECT * FROM ".DB_PREFIX."customer WHERE customer_id = ".$cust_id." AND code = '".$try_code."'")->num_rows;
                if($try){
                    $this->db->query("UPDATE ".DB_PREFIX."customer SET approved = 1 WHERE customer_id = ".$this->customer->getId());
                }
                    $this->response->redirect($this->url->link('account/account'));                    
                
    }
    
    
    
                public function generateRandomString($length = 30) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                //return $randomString;
                //return token(15);
                //return uniqid("egor_petuh_");
                }
                
                
                public function bottonCompleteClick (){
                    
                    
                    
                    $backinfo = $this->customer->getTotalInfo();
                    $data['approved']=$backinfo['approved'];
                    $data['code']=$backinfo['code'];
                    $data['email']=$backinfo['email'];
                    
                    
                    $resultURL = $this->url->link('account/identification','code='.$backinfo['code'].'&uid='.$backinfo['customer_id']);
                    
                   
                    if (mail($data['email'], 'Регистрация' , "Ссылка для  подтверждения: ".html_entity_decode($resultURL)." ","From: АВТОРАЗБОР174.РФ \r\n")) { 
                                $data['textIdentificdtion'] = 'На данный email: <b>'.$backinfo['email'].'</b> придет сообщениe с ссылкой для активации. Если письма нет, проверьте папку спам.';
                                echo json_encode(array('message' => $data['textIdentificdtion']));
                    } 
                    else {
                                $data['textIdentificdtion'] = 'При отправке возникла ошибка, попробуйте позже. Либо обратитесь к администратору';
                                echo json_encode(array('message' => $data['textIdentificdtion']));
                    }
                }	
}
                
                
                

