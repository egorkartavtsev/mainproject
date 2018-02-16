<?php

class ControllerCommonRefund extends Controller {
    
    public function index(){
//        exit(var_dump($this->session->data));
        $data = $this->getLayout();
        if(isset($this->request->get['result'])){
            $data['result'] = '<div class="col-lg-12 well well-sm"><p>';
                if($this->request->get['result']=='success'){
                    $data['result'].= 'Товар успешно восстановлен.';
                }else{
                    $data['result'].= 'Товар не удалось восстановить. Возникла внутренняя ошибка. Не удалось: '.$this->request->get['result'].'. Повторите попытку, либо вызовите администратора.';
                }
            $data['result'].= '</p></div>';
        } else {
            $data['result'] = '';
        }
        $this->response->setOutput($this->load->view('common/refund', $data));
    }
    
    public function getLayout() {
        $this->document->setTitle('Возврат товара');

        $data['heading_title'] = 'Возврат товара';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => 'Возврат товара',
                'href' => $this->url->link('common/addprod', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token_add'] = $this->session->data['token'];
        return $data;
    }

    public function getInfo() {
        $sku = $this->request->post['sku'];
        $this->load->model('common/refund');
        $info = $this->model_common_refund->getProductInfo($sku);
        $result = '';
        if($info){
            $result.= '<div class="col-md-6">';
                $result.= '<h4>'.$info['name'].'</h4>';
                $result.= '<table class="table table-hover table-bordered table-condensed">';
                    $result.= '<tr>';
                        $result.= '<td>Внутренний номер</td><td>'.$info['sku'].'</td>';
                    $result.= '</tr>';
                    $result.= '<tr>';
                        $result.= '<td>Цена</td><td>'.$info['price'].'</td>';
                    $result.= '</tr>';
                    $result.= '<tr>';
                        $result.= '<td>Цена продажи</td><td>'.$info['saleprice'].'</td>';
                    $result.= '</tr>';
                    $result.= '<tr>';
                        $result.= '<td>Клиент</td><td>'.$info['client'].'</td>';
                    $result.= '</tr>';
                    $result.= '<tr>';
                        $result.= '<td>Горот покупателя</td><td>'.$info['city'].'</td>';
                    $result.= '</tr>';
                    $result.= '<tr>';
                        $result.= '<td>Дата продажи</td><td>'.$info['date'].'</td>';
                    $result.= '</tr>';
                    $result.= '<tr>';
                        $result.= '<td>Продавец</td><td>'.$info['manager'].'</td>';
                    $result.= '</tr>';
                $result.= '</table>';
            $result.= '</div>';
            $result.= '<div class="col-md-6">';
                $result.= '<form action="index.php?route=common/refund/refundProd&token='.$this->session->data['token'].'" enctype="multipart/form-data" class="form" method="post">';
                    $result.= '<div class="form-group">';
                        $result.= '<label for="reason">Укажите причину возврата:</label>';
                        $result.= '<input type="text" class="form-control" name="reason" id="reason" />';
                    $result.= '</div>';
                    $result.= '<div class="form-group">';
                        $result.= '<label for="photos">Загрузите фотографии товара:</label>';
                        $result.= '<input name="photo[]" id="photos" class="form-control" type="file" multiple="true">';
                        $result.= '<input name="sku" type="hidden" value="'.$sku.'">';
                        $result.= '<input name="date_wo" type="hidden" value="'.$info['date_mod'].'">';
                    $result.= '</div>';
                    $result.= '<input type="submit" class="btn btn-success" value="Восстановить товар">';
                $result.= '</form>';
            $result.= '</div>';
        } else {
            $result = '<h4 class="text-warning">Ошибка. Такого товара нет в базе, либо он не числится в проданных.</h4>';
        }
        echo $result;
    }
    
    public function refundProd() {
        $info = $this->request->post;
        $info['manager']  = $this->session->data['username'];
        $this->load->model('common/refund');
        if(strlen($_FILES['photo']['name'][0])!=0){ 
            $this->model_common_refund->setPhoto($this->request->post['sku']);
        }
        $result = $this->model_common_refund->updateDataBase($info);
        $this->response->redirect($this->url->link('common/refund', 'token='.$this->session->data['token'].'&result='.$result, TRUE));
    }
}
