<?php

class ControllerServiceAuto extends Controller {
    public function tryVIN() {
        $this->load->model('service/tools');
        $this->load->model('tool/forms');
        $result = $this->model_service_tools->tryVIN($this->request->post['vin']);
        $result['brands'] = $this->model_service_tools->getBrands();
        $result['colors'] = $this->model_service_tools->getColors();
        $result['categ'] = $this->model_service_tools->getCateg();
        $result['types'] = $this->model_service_tools->getTypes();
        $result['eclass'] = $this->model_service_tools->getEclass();
        if($result){
            $result['owner'] = $this->request->get['client'];
            $form = $this->load->view('form/createauto', $result);
        } else {
            $form = $this->load->view('form/createauto', array('owner'=>$this->request->get['client']));
        }
        echo $form;
    }
    public function addAuto() {
        $this->load->model('service/auto');
        $this->load->model('tool/forms');
        $owner = $this->request->get['client'];
        $request = $this->request->post['arr'];
        $sup = explode(";", $request);
        foreach($sup as $field){
            $subsup = explode(":", $field);
            if(isset($subsup[1])){
                $info[$subsup[0]] = $subsup[1];
            }
        }
        $info = $this->model_service_auto->addAuto($info);
        $result = '<div class="well a2cItem"><p>'.$info['brand'].' '.$info['model'].' '.$info['year'].'г.в.</p>';
        $result.= '<p>'.$info['vin'].' '.$info['numb'].'</p></div>';
        
        echo $result;
    }
}

