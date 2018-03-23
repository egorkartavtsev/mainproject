<?php

class ControllerCommonCreateXML extends Controller {
    public function index() {
        $this->load->model('tool/translate');
        $string = 'Поехали с нами всё отлично работает и всё будет хорошо просто поверь в свою мечту и ты увидишь что жизнь меняется';
        if($this->model_tool_translate->validation($string)){
            exit($this->model_tool_translate->translate($string));
        } else {
            exit('Ne kanon!');
        }
    }
}