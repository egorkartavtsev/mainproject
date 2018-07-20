<?php

class ControllerToolFormTool extends Controller {
    public function libraryFields() {
        //exit(var_dump($this->request->post));
        $this->load->model('tool/forms');
        $result = '';
        $parent = $this->request->post['parent'];
        $fieldName = $this->request->post['parentName'];
        $num = $this->request->post['num'];
        $child = $this->model_tool_forms->getLibrChilds($parent, $fieldName);
        if($parent!=='-'){
            if($num==='compability'){
                if($child['js']!==''){
                    $result.= '<label>'.$child['text'].'</label>';
                    $result.= '<select class="form-control" '.$child['js'].'>';
                    $result.= '<option value="-">-</option>';
                    foreach ($child['fills'] as $fill) {
                        $result.= '<option value="'.$fill['id'].'">'.$fill['name'].'</option>';
                    }
                    $result.= '</select>';
                } else {
                    $result.= '<label>'.$child['text'].'</label>';
                    $result.='<br>';
                    foreach ($child['fills'] as $fill) {
                        $result.= '<span class="label label-success cpbItem" span_type="cpbItem">'.$fill['name'].'</span> ';
                    }
                }
            } elseif($num === 'prod-edit') {
                $result.= '<a class="btn btn-success btn-sm" data-toggle="modal" data-target="#createFillModal" parent="'.$child['parent_name'].'" btn_type="createFill"><i class="fa fa-plus"></i></a><label>  '.$child['text'].'  </label> ';
                $result.= '<select class="form-control" name = "info['.$fieldName.']" '.$child['js'].'>';
                $result.= '<option value="-">-</option>';
                foreach ($child['fills'] as $fill) {
                    $result.= '<option value="'.$fill['id'].'">'.$fill['name'].'</option>';
                }
                $result.= '</select>';
            } elseif($num === 'createauto') {
                $result.= '<label>'.$child['text'].'</label> ';
                $result.= '<select class="form-control" id = "select-'.$fieldName.'" '.$child['js'].'>';
                $result.= '<option value="-">-</option>';
                foreach ($child['fills'] as $fill) {
                    $result.= '<option value="'.$fill['id'].'">'.$fill['name'].'</option>';
                }
                $result.= '</select>';
            } else {
                if($num !== 'no-num'){
                    $result.= '<a class="btn btn-success btn-sm" data-toggle="modal" data-target="#createFillModal" parent="'.$child['parent_name'].'" btn_type="createFill"><i class="fa fa-plus"></i></a><label>  '.$child['text'].'  </label> ';
                    $result.= '<select class="form-control" name = "info['.$num.']['.$fieldName.']" '.$child['js'].'>';
                } else {
                    $result.= '<label>'.$child['text'].'</label>';
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
    public function createFill() {
        $parent = $this->request->post['parent'];
        $name = $this->request->post['name'];
        $item_name = $this->request->post['item'];
        $item_id = $this->db->query("SELECT item_id FROM ".DB_PREFIX."lib_struct WHERE name = '".$item_name."'")->row['item_id'];
        $check = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE name = '".$name."' AND item_id = '".$item_id."'");
        $check_num = $check->num_rows;
        if ($check_num <= 0){
            $this->load->model('tool/forms');
            $this->model_tool_forms->createFill($parent, $name);
        } else {
            $res = 'exists';
        }
        echo $res;
        
    }
}

