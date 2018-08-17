<?php

class ControllerReportDonor extends Controller{
    public function index() {
        /*-------------------------------------------------------------------------------------------------------*/
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $data['token_add'] = $this->session->data['token'];
        /*----------------------------------------------------------------------------------------------------------*/
        
        $this->response->setOutput($this->load->view('report/donor', $data));
    }
    
    public function getVars() {
        $request = $this->request->post['request'];
        $this->load->model('report/donor');
        $variants = $this->model_report_donor->getVariants($request);
        $result = '';
        if($variants){
            foreach ($variants as $var) {
                $result.= '<li onclick="getDonor(\''.$var['numb'].'\', \''.$var['name'].'\');" class="dsitem">'.$var['name'].'</li>';
            }
        } else {
            $result = '<li class="dsitem" disabled>По данному запросу ничего не найдено. Попробуйте изменить запрос.</li>';
        }
        echo $result;
    }
    
    public function getDonor() {
        $numb = $this->request->post['request'];
        $this->load->model('report/donor');
        $this->load->model('tool/image');
        $info = $this->model_report_donor->getDonorInfo($numb);
        if (is_file(DIR_IMAGE . $info['info']['image'])) {
                $dimage = $this->model_tool_image->resize($info['info']['image'], 240, 240);
        } else {
                $dimage = $this->model_tool_image->resize('no_image.png', 240, 240);
        }
        //-------------------image-------------------
            $result = '<div class="col-md-3">';
                $result.='<img src="'.$dimage.'" alt="'.$info['info']['name'].'" class="img-thumbnail" />';
            $result.= '</div>';
        //-----------------mainInfo------------------
            $result.= '<div class="col-md-4 alert alert-success">';
                $result.= '<table class="table table-hover table-bordered table-condensed">'
                                .'<tr><td><b>Внутренний номер: </b></td><td>'.$info['info']['numb'].'</td></tr>'
                                .'<tr><td><b>Тип кузова: </b></td><td>'.$info['info']['ctype'].'</td></tr>'
                                .'<tr><td><b>VIN: </b></td><td>'.$info['info']['vin'].'</td></tr>'
                                .'<tr><td><b>Марка: </b></td><td>'.$info['info']['brand'].'</td></tr>'
                                .'<tr><td><b>Модель: </b></td><td>'.$info['info']['model'].'</td></tr>'
                                .'<tr><td><b>Модельный ряд: </b></td><td>'.$info['info']['modR'].'</td></tr>'
                                .'<tr><td><b>ДВС: </b></td><td>'.$info['info']['dvs'].'</td></tr>'
                                .'<tr><td><b>Цвет: </b></td><td>'.$info['info']['color'].'</td></tr>'
                                .'<tr><td><b>Трансмиссия: </b></td><td>'.$info['info']['trmiss'].'</td></tr>'
                                .'<tr><td><b>Пробег: </b></td><td>'.$info['info']['kmeters'].'</td></tr>'
                                .'<tr><td><b>Привод: </b></td><td>'.$info['info']['priv'].'</td></tr>'
                                .'<tr><td><b>Год выпуска: </b></td><td>'.$info['info']['year'].'</td></tr>'
                            .'</table><p><b>Примечание: </b>'.$info['info']['note'].'</p>';
            $result.= '</div>';
        //-----------------saleInfo-------------------
            $result.= '<div class="col-md-5">';
                $result.= '<div class="well well-sm" style="font-size: 12pt;">'
                                .'<p>Общее количество деталей: <span class="label label-success">'.$info['info']['totalQuant'].'</span></p>'
                                .'<p>Общее количество оставшихся деталей: <span class="label label-success">'.$info['info']['currQuant'].'</span></p>'
                                .'<p>Общее количество проданных деталей: <span class="label label-success">'.$info['info']['saleQuant'].'</span></p>'
                                .'<p>Общяя стоимость оставшихся деталей: <span class="label label-success">'.$info['info']['totalItemPrice'].'</span></p>'
                                .'<p>Общяя стоимость проданных деталей: <span class="label label-success">'.$info['info']['totalSalePrice'].'</span></p>'
                                .'<p>Общяя стоимость донора: <span class="label label-success">'.$info['info']['price'].'</span></p>'
                                .'<p>На сколько окупил себя донор: </p>'
                                .'<div class="progress">'
                                    .'<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$info['info']['percent'].'" aria-valuemin="0" aria-valuemax="200" style="width: '.$info['info']['percent'].'%;"><span style="color: black;">'.$info['info']['percent'].'%</span></div>'
                                .'</div>'
                           .'</div>';
            $result.= '</div>';
        $result.= '</div>'.$info['info']['tableItems'];
        
        
        echo $result;
    }
}

