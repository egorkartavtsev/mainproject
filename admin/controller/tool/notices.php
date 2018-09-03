<?php

class ControllerToolNotices extends Controller {
    
    public function getNewNotices() {
        $this->load->model('tool/layout');
        $res = $this->model_tool_layout->getnoticeTotals();
        if($res['new']){
            foreach ($res['notices'] as $key => $notice) {
                $tmp = 'get'.$key;
                $res['notices'][$key]['tab'] = $this->model_tool_layout->$tmp();
            }
            echo json_encode($res);
        } else {
            echo false;
        }
    }
    
}

