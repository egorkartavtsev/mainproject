<?php

class ControllerSettingLabels extends Controller {
    public function index() {
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getLayout($this->request->get['route']);
        $this->response->setOutput($this->load->view('setting/labels', $data));
    }
}
