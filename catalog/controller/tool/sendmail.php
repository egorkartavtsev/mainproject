<?php
    class ControllerToolSendmail extends Controller {
        public function send() {
            $this->load->model('product/catalog');
            $this->load->model("tool/product");
            $product = $this->model_product_catalog->getProductInfo($this->request->post['product_id']);
            $description = $this->model_tool_product->getDescription($this->request->post['product_id']);
            if (trim($this->request->post['name']) != '') {
                $name = trim($this->request->post['name']);
            } else {
                $name = "Не указано";
            }
            if (trim($this->request->post['phone']) != '') {
                $phone = trim($this->request->post['phone']);
            } else {
                $phone = "Не указан";
            }
            $email = $this->request->post['email'];
            $vin = $product['vin'];
            $product_name = $description['name'];
            //$charset ='utf-8';
            $charset ='UTF-8';
            $comment = wordwrap($this->request->post['comment'],70,"\r\n");
            $cause = $this->request->post['cause'];
            $quest = 'Комментарий: ';
            $subject ='';
            switch ($cause){
                case 1:
                    $subject = "=?UTF-8?b?".'Заявка на уточнение наличия товара с сайта авторазбор174.рф'."?=";
                    break;
                case 2:
                    $subject = "=?UTF-8?b?".'Заявка на уточнение стоимости товара с сайта авторазбор174.рф'."?=";
                    break;
                case 3:
                    $subject = "=?UTF-8?b?".'Заявка на заказ товара с сайта авторазбор174.рф'."?=";
                    break;
                case 4:
                    $subject = "=?UTF-8?b?".'Вопрос о товаре "'.$product_name.'" с сайта авторазбор174.рф'."?=";
                    $quest = 'Вопрос: ';
                    break;
                case 5:
                    $subject = 'Заказ на звонок с сайта авторазбор174.рф';
                    $vin = "-";
                    $product_name = "-";
                    if ($this->request->post['email'] !=='') {
                        $email = 'autorazbor174@mail.ru';
                    }
                    break;
            }
            $mail =  'Имя: '.$name.'; '. "\n" .
                     'Email: '.$email.'; '. "\n" .
                     'Телефон: '.$phone.'; '. "\n" .
                     'Артикул: '.$vin.'; '. "\n" .
                     'Наименование товара: '.$product_name.'; '. "\n" . 
                     $quest.$comment;
            
            $headers  = "Content-type: text/html; charset=$charset\n";
            $headers .= "MIME-Version: 1.0\n";
            $headers .= "From:".trim($email)."\n";
            $headers .= "Content-type: text/html; charset=$charset\n";
            
            mail('autorazbor174@mail.ru', $subject, $mail, $headers);
            return;    
        }
    } 
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

