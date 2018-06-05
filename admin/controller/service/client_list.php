<?php

class ControllerServiceClientList extends Controller{
    public function index(){
        $this->load->model('tool/layout');
        $this->load->model('service/client');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $clients = $this->model_service_client->getClients();
        $total = $this->model_service_client->getTotalClients();
        foreach ($clients as $row) {
            switch ($row['legal']) {
                case '1':
                    $name = $row['secondname'].' '.$row['firstname'].' '.$row['patronymic'];
                    $legal = 'Физическое лицо';
                    $adress = $row['fregion'].', ';
                    if($row['farea']===$row['fcity']){
                        $adress.= $row['fcity'].', ';
                    } else {
                        $adress.= $row['farea'].', '.$row['fcity'].', ';
                    }
                    $adress.= $row['fstreet'].', '.$row['fhome'];
                    $phone = $row['phone1'];
                    if($row['phone2']!==''){
                        $phone.='<br>'.$row['phone2'];
                    }
                    break;
                case '0':
                    $name = $row['name'];
                    $legal = 'Юридическое лицо';
                    $adress = $row['fregion'].' ';
                    if($row['farea']===$row['fcity']){
                        $adress.= $row['fcity'].', ';
                    } else {
                        $adress.= $row['farea'].' '.$row['fcity'].' ';
                    }
                    $adress.= $row['fstreet'].', '.$row['fhome'];
                    $adress.= '<br>'.$row['lregion'].', ';
                    if($row['larea']===$row['lcity']){
                        $adress.= $row['lcity'].', ';
                    } else {
                        $adress.= $row['larea'].', '.$row['lcity'].', ';
                    }
                    $adress.= $row['lstreet'].', '.$row['lhome'];
                    $phone = $row['phone1'];
                    if($row['phone2']!==''){
                        $phone.='<br>'.$row['phone2'];
                    }
                    //-----------------------------------------------
                    break;
            }
            $data['clients'][$row['id']] = array(
                'name'  => $name,
                'legal'  => $legal,
                'adress' => $adress,
                'phone' => $phone,
                'action' => $this->url->link('service/client_show', 'token='.$this->session->data['token'].'&client='.$row['id'])
            );
        }
        $this->response->setOutput($this->load->view('service/client_list', $data));
    }
}

