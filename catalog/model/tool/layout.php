<?php
class ModelToolLayout extends Model {
    public function getTypesTN() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_type WHERE top_nav = 1");
        return $sup->rows;
    }
    
    public function getLibrsTN() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."libraries WHERE top_nav = 1");
        return $sup->rows;
    }
    
    public function getLibrName($library) {
        $sup = $this->db->query("SELECT text FROM ".DB_PREFIX."libraries WHERE library_id = ".(int) $this->db->escape($library));
        return $sup->row['text'];
    }
    
    public function getFillName($fill) {
        $sup = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE id = ".(int) $this->db->escape($fill));
        return $sup->row['name'];
    }
    
    public function getParentFills($library) {
        $result = array();
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_struct WHERE library_id = ".(int) $this->db->escape($library)." AND parent_id = 0  ");
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE library_id = ".(int) $this->db->escape($library)." AND item_id = ".(int)$sup->row['item_id']." ORDER BY name ASC");
        foreach ($query->rows as $row) {
            $result[] = array(
                'fill_id' => $row['id'],
                'trig' => 1,
                'name' => $row['name'],
                'img' => $row['image'],
                'showImg' => $sup->row['showImg'],
                'isParent' => $sup->row['isparent']
            );
        }
        return $result;
    }
    
    public function getFills($fill=0, $filters = 0, $lib_id = 0) {
        $result = array();
        $sql = "SELECT *, "
                . "(SELECT isparent FROM ".DB_PREFIX."lib_struct ls WHERE ls.item_id = lf.item_id) AS isparent, "
                . "(SELECT showImg FROM ".DB_PREFIX."lib_struct ls WHERE ls.item_id = lf.item_id) AS showImg "
              . "FROM ".DB_PREFIX."lib_fills lf "
                . "WHERE 1 ";
              
        if(!is_array($filters)){
            $sql.= "AND lf.parent_id = ".(int) $this->db->escape($fill)." ";
        } else {
            foreach ($filters as $filter) {
                $sql.= "AND (LOCATE('".$this->db->escape(trim($filter))."', lf.name) OR LOCATE('".$this->db->escape(trim($filter))."', lf.translate)) ";
            }
        }
        if($lib_id){$sql.= 'AND lf.library_id = '.(int)$this->db->escape($lib_id)." ";}
        $sql.= "ORDER BY lf.name ASC";
        $query = $this->db->query($sql);
        foreach ($query->rows as $row) {
            $result[] = array(
                'fill_id' => $row['id'],
                'name' => $row['name'],
                'img' => $row['image'],
                'showImg' => $row['showImg'],
                'isParent' => $row['isparent']
            );
        }
        return $result;
    }
    
    public function getTypesArray($type = 0) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_type WHERE ".($type?('type_id = '.(int)$type):'1'));
        $result = array();
        foreach ($sup->rows as $type) {
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE type_id = ".(int)$type['type_id']." AND viewed = 1 ORDER BY sort_order");
            foreach ($query->rows as $field) {
                $result[$type['type_id']][$field['name']] = $field;
            }
        }
        return $result;
    }
    
    public function pagination($total, $flag) {
        $pagination = '';
        if($total>$this->config->get('theme_default_product_limit')){
            $pageCount = ceil($total/$this->config->get('theme_default_product_limit'));
            $i =$currentPage = isset($this->request->get['page'])?$this->request->get['page']:1;
            $start = $i==1?1:$this->config->get('theme_default_product_limit')*($i-1)+1;
            $end = $start + $this->config->get('theme_default_product_limit')-1;
            if($i<=6){
                $min = 1;
                $max = $i+4;
                while ($min<=$max){
                    $pagination.= ' <a class="btn btn-default" href="'.$this->url->link('catalog/catalog/products', $flag.'='.$this->request->get[$flag].'&page='.$min).'">'.$min.'</a> ';
                    ++$min;
                }
                $pagination.= ' ... <a class="btn btn-default" href="'.$this->url->link('catalog/catalog/products', $flag.'='.$this->request->get[$flag].'&page='.$pageCount).'">'.$pageCount.'</a>';
                $pagination.= '<div class="clearfix"></div><div class="pull-left">Показаны товары с '.$start.' по '.$end.' (всего '.$total.')</div>';

            } elseif($i>=($pageCount-5)) {
                $min = $pageCount-8;
                $max = $pageCount;
                $pagination = '<a class="btn btn-default" href="'.$this->url->link('catalog/catalog/products', $flag.'='.$this->request->get[$flag].'&page=1').'">1</a> ... ';
                while ($min<=$max){
                    $pagination.= '<a class="btn btn-default" href="'.$this->url->link('catalog/catalog/products', $flag.'='.$this->request->get[$flag].'&page='.$min).'">'.$min.'</a>';
                    ++$min;
                }
                $pagination.= '<div class="clearfix"></div><div class="pull-left">Показаны товары с '.$start.' по '.$end.' (всего '.$total.')</div>';
            } else {
                $min = $i-4;
                $max = $i+4;
                $pagination = '<a class="btn btn-default" href="'.$this->url->link('catalog/catalog/products', $flag.'='.$this->request->get[$flag].'&page=1').'">1</a> ... ';
                while ($min<=$max){
                    $pagination.= ' <a class="btn btn-default" href="'.$this->url->link('catalog/catalog/products', $flag.'='.$this->request->get[$flag].'&page='.$min).'">'.$min.'</a> ';
                    ++$min;
                }         
                $pagination.= '... <a class="btn btn-default" href="'.$this->url->link('catalog/catalog/products', $flag.'='.$this->request->get[$flag].'&page='.$pageCount).'">'.$pageCount.'</a>';
                $pagination.= '<div class="clearfix"></div><div class="pull-left">Показаны товары с '.$start.' по '.$end.' (всего '.$total.')</div>';
            }
        }
        return $pagination;
    }
    
    public function constructFilter($flag, $param) {
        $result = '';
        switch ($flag){
            case 'type':
                $sup = $this->db->query("SELECT *, (SELECT parent_id FROM ".DB_PREFIX."lib_struct ls WHERE ls.item_id = tl.libraries) AS parent_item, (SELECT name FROM ".DB_PREFIX."lib_struct ls WHERE ls.parent_id = tl.libraries GROUP BY ls.parent_id) AS child_item FROM ".DB_PREFIX."type_lib tl WHERE type_id = ".(int)$param." AND filter = 1 ORDER BY sort_order");
            break;
            case 'libr':
                $items = $this->db->query("SELECT item_id, parent_id FROM ".DB_PREFIX."lib_struct WHERE library_id = ".(int)$param." ");
                $sql = "SELECT type_id FROM ".DB_PREFIX."type_lib WHERE 0 ";
                foreach ($items->rows as $row) {
                    $sql.= "OR libraries = ".(int)$row['item_id']." ";
                }
                $sql.= "GROUP BY type_id ";
                $types = $this->db->query($sql);
                $query = "SELECT *, (SELECT parent_id FROM ".DB_PREFIX."lib_struct ls WHERE ls.item_id = tl.libraries) AS parent_item, (SELECT name FROM ".DB_PREFIX."lib_struct ls WHERE ls.parent_id = tl.libraries GROUP BY ls.parent_id) AS child_item FROM ".DB_PREFIX."type_lib tl WHERE (0 ";
                foreach ($types->rows as $type){
                    $query.= "OR type_id = ".(int)$type['type_id']." ";
                }
                $query.= ") AND filter = 1 ORDER BY sort_order";
                $sup = $this->db->query($query);
            break;
            case 'search':
                $sup = '';
            break;
        }
        $libraries = '';
        $inputs = '';
        $selects = '';
        foreach($sup->rows as $field){
            switch ($field['field_type']){
                case 'input':
                    $inputs.='<div class="form-group form-group-sm">'
                                . '<label for="filter_'.$field['name'].'">'.$field['text'].':</label>
                                   <input id="filter_'.$field['name'].'" class="form-control">
                                 </div>';
                break;
                case 'select':
                    $selects.= '<div class="form-group form-group-sm">'
                                . '<label for="filter_'.$field['name'].'">'.$field['text'].':</label>
                                   <select id="filter_'.$field['name'].'" class="form-control">'
                                    . '<option value="" disabled selected>Выберите значение...</option>';
                        foreach (explode(";", $field['vals']) as $value) {
                            if(trim($value)!=='' && trim($value)!=='-'){
                                $selects.= '<option value="'.$value.'">'.$value.'</option>';
                            }
                        }                   
                    $selects.= '</select></div>';
                break;
                case 'library':
                    if(!in_array($field['libraries'], array_column($items->rows, 'item_id'))){
                        $parent = (int)$field['parent_item'];
                        if($parent){
                            $libraries.= '<div class="form-group form-group-sm" id="'.$field['name'].'"></div>';
                        } else {
                            $fills = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE item_id = ".(int)$field['libraries']." ORDER BY name ");
                            $libraries.= '<div class="form-group form-group-sm">'
                                            . '<label for="filter_'.$field['name'].'">'.$field['text'].':</label>'
                                            . '<select id="filter_'.$field['name'].'" class="form-control" select_type="library" child="'.$field['child_item'].'">'
                                                . '<option value="" disabled selected>Выберите значение...</option>';
                                    foreach ($fills->rows as $fill) {
                                        $libraries.='<option value="'.$fill['id'].'">'.$fill['name'].'</option>';
                                    }
                                 $libraries.= '</select></div>';
                        }
                    }
                break;
            }
        }
        $result.= '<h4><b>Фильтры:</b> </h4><div class="col-xs-12"><button class="btn btn-danger btn-block" disabled btn_type="filter">Применить фильтр</button></div>'.$libraries.'<div class="clearfix"></div>'.$selects.'<div class="clearfix"></div>'.$inputs.'<div class="col-xs-12"><button class="btn btn-danger btn-block" disabled btn_type="filter">Применить фильтр</button></div>';
        return $result;
    }
    
    public function getChilds($par) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = ".(int)$this->db->escape($par)." ORDER BY name ");
        $curr = $this->db->query("SELECT name, text FROM ".DB_PREFIX."lib_struct WHERE parent_id = (SELECT item_id FROM ".DB_PREFIX."lib_fills WHERE id = ".(int)$this->db->escape($par).")");
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_struct WHERE parent_id = (SELECT item_id FROM ".DB_PREFIX."lib_struct WHERE parent_id = (SELECT item_id FROM ".DB_PREFIX."lib_fills WHERE id = ".(int)$this->db->escape($par)."))");
        $result = array(
            'childs' => $sup->rows,
            'currId' => $curr->row['name'],
            'currText' => $curr->row['text'],
            'cName' => $query->row['name']
        );
        return $result;
    }
}
