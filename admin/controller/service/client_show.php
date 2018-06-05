<?php

class ControllerServiceClientShow extends Controller {
    public function index() {
        $this->load->model('tool/layout');
        $this->load->model('service/tools');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $info = $this->model_service_tools->getClientInfo($this->request->get['client']);
        $auto = $this->model_service_tools->getClientAuto($this->request->get['client']);
        $data['auto'] = $auto;
        $data['client'] = array(
            'status' => array(
                'text' => 'Статус',
                'value' => (int)$info['legal']?'Физическое дицо':'Юридическое лицо'
            ),
        );
        if(!(int)$info['legal']){
            $data['client']['name'] = array(
                'text' => 'Наименование',
                'value' => $info['name']
            );
            $data['client']['address1'] = array(
                'text' => 'Фактический адрес',
                'value' => $info['fregion'].', '.($info['farea']==$info['fcity']?$info['farea']:$info['farea'].', '.$info['fcity']).', '.$info['fstreet'].', '.$info['fhome']
            );
            $data['client']['adress2']  = array(
                'text' => 'Юридический адрес',
                'value' => $info['lregion'].', '.($info['larea']==$info['lcity']?$info['larea']:$info['larea'].', '.$info['lcity']).', '.$info['lstreet'].', '.$info['lhome']
            );
            $data['client']['inn']  = array(
                'text' => 'ИНН',
                'value' => $info['INN']
            );
            $data['client']['kpp']  = array(
                'text' => 'КПП',
                'value' => $info['KPP']
            );
            $data['client']['ogrn']  = array(
                'text' => 'ОГРН',
                'value' => $info['OGRN']
            );
        }
        if((int)$info['legal']){
            $data['client']['name']  = array(
                'text' => 'ФИО',
                'value' => $info['secondname'].' '.$info['firstname'].' '.$info['patronymic']
            );
            $data['client']['bdate']  = array(
                'text' => 'Дата рождения',
                'value' => date("d.m.Y", strtotime($info['bdate']))
            );
            $data['client']['address1'] = array(
                'text' => 'Адрес регистрации',
                'value' => $info['fregion'].', '.($info['farea']==$info['fcity']?$info['farea']:$info['farea'].', '.$info['fcity']).', '.$info['fstreet'].', '.$info['fhome']
            );
            $data['client']['pass']  = array(
                'text' => 'Паспорт',
                'value' => $info['numpas']
            );
            $data['client']['ofpass']  = array(
                'text' => 'Кем выдан',
                'value' => $info['officepas']
            );
            $data['client']['dpass']  = array(
                'text' => 'Когда выдан',
                'value' => date("d.m.Y", strtotime($info['datepas']))
            );
        }
        $data['client']['phone1']  = array(
            'text' => 'Контактный телефон',
            'value' => $info['phone1']
        );
        $data['client']['phone2']  = array(
            'text' => 'Дополнительный телефон',
            'value' => $info['phone2']
        );
        $data['modal_auto'] = $this->load->view('modals/clear', array('target'=>'autocreate', 'header'=>'Добавить автомобиль', 'key'=>'auto'));
        $this->response->setOutput($this->load->view('service/client_show', $data));
    }
}

