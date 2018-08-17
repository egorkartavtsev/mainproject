<?php

class ControllerLayoutNotices extends Controller{
    public function index() {
        $this->load->model('tool/layout');
        $data = $this->model_tool_layout->getnoticeTotals();
        return $this->load->view('layout/notices', $data);
    }
}

