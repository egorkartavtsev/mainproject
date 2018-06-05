<?php

class ControllerServiceAuto extends Controller {
    public function tryVIN() {
        $this->load->model('service/tools');
        $this->load->model('tool/forms');
        $result = $this->model_service_tools->tryVIN();
        if($result){
            $form = $this->load->view('form/createauto', $result);
        } else {
            $form = $this->load->view('form/createauto', array());
        }
        echo $form;
    }
}

