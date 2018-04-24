<?php

class ControllerToolFormTool extends Controller {
    public function libraryFields() {
        $this->load->model('tool/forms');
        $result = '';
        $parent = $this->request->post['parent'];
        $fieldName = $this->request->post['parentName'];
        $num = $this->request->post['num'];
        $child = $this->model_tool_forms->getLibrChilds($parent, $fieldName);
        $result.= '<label>'.$child['text'].'</label>';
        if(!empty($child) && $parent!=='-'){
            if($num==='compability'){
                if($child['js']!==''){
                    $result.= '<select class="form-control" '.$child['js'].'>';
                    $result.= '<option value="-">-</option>';
                    foreach ($child['fills'] as $fill) {
                        $result.= '<option value="'.$fill['id'].'">'.$fill['name'].'</option>';
                    }
                    $result.= '</select>';
                } else {
                    $result.='<br>';
                    foreach ($child['fills'] as $fill) {
                        $result.= '<span class="label label-success cpbItem" span_type="cpbItem">'.$fill['name'].'</span> ';
                    }
                }
            } else {
                if($num !== 'no-num'){
                    $result.= '<select class="form-control" name = "info['.$num.']['.$fieldName.']" '.$child['js'].'>';
                } else {
                    $result.= '<select class="form-control" name = "info['.$fieldName.']" '.$child['js'].'>';
                }
                $result.= '<option value="-">-</option>';
                foreach ($child['fills'] as $fill) {
                    $result.= '<option value="'.$fill['id'].'">'.$fill['name'].'</option>';
                }
                $result.= '</select>';
            }
        }
        echo $result;
    }
    
    public function isUnique() {
        $search = $this->request->post['search'];
        $field = $this->request->post['field'];
        $this->load->model('tool/forms');
        $result = $this->model_tool_forms->isUnique($search, $field);
        echo ($result?'false':'true');
    }
    
    public function isComplect() {
        $heading = $this->request->post['heading'];
        $this->load->model('tool/complect');
        echo $this->model_tool_complect->isHeading($heading);
    }
}

