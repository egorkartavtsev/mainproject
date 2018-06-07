<?php

class ControllerServiceClientList extends Controller{
    public function index(){
        $this->load->model('tool/layout');
        $this->load->model('service/client');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $data['clients'] = array();
        $url = '';
        $filter = array();
        if (isset($this->request->get['filter_fio'])) {
            $url .= '&filter_fio=' . $this->request->get['filter_fio'];
            $filter['filter_fio'] = $this->request->get['filter_fio'];
            $data['filter_fio'] = $this->request->get['filter_fio'];
        }
        if (isset($this->request->get['filter_phone'])) {
            $url .= '&filter_phone=' . $this->request->get['filter_phone'];
            $filter['filter_phone'] = $this->request->get['filter_phone'];
            $data['filter_phone'] = $this->request->get['filter_phone'];
        }
        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city=' . $this->request->get['filter_city'];
            $filter['filter_city'] = $this->request->get['filter_city'];
            $data['filter_city'] = $this->request->get['filter_city'];
        }
        if (isset($this->request->get['page']) && $this->request->get['page']!='1') {
            $page = $this->request->get['page'];
            $filter['start'] = ($page-1)*6;
        } else {
            $page = 1;
            $filter['start'] = 0;
        }
        $filter['limit'] = 6;
        $clients = $this->model_service_client->getClients($filter);
        $total = $this->model_service_client->getTotalClients($filter);
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
                'phone' => $phone
            );
        }
        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = 6;
        $pagination->url = $this->url->link('service/client_list', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        
        $this->response->setOutput($this->load->view('service/client_list', $data));
    }
}

