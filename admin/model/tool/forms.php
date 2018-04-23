<?php

class ModelToolForms extends Model {
    private $systemFields = array(
        'vin' => 'Внутренний номер', 'quantity' => 'Количество', 'price' => 'Цена'
    );
    private $ignoreFields = array('complect', 'heading', 'type_id');
    public function generateAddForm($id, $num){
        $this->load->model('tool/complect');
        $form = '';
        $systemF = '';
        $librF = '';
        $selectF = '';
        $inputF = '';
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."type_lib WHERE type_id = ".(int)$id." ORDER BY sort_order");
        foreach ($this->systemFields as $key => $field) {
            $systemF.= '<div class="form-group-sm col-md-4">'
                        . '<label>'.$field.($key==='vin'?'<span style="color: red;">*</span>':'').'</label>'
                        . '<input class="form-control" name="info['.$num.']['.$key.']" '.($key==='vin'?'required="required" aria-required="true" unique="unique" field="vin"':'').'/>'
                     . '</div>';
        }
        foreach ($sup->rows as $option) {
            
            switch ($option['field_type']) {
                case 'input':
                    $description = $option['description']===''?'':'data-toggle="tooltip" data-placement="bottom" data-original-title="'.$option['description'].'"';
                    $required = $option['required']==='1'?' required="required" aria-required="true"':'';
                    $unique = $option['unique_field']==='1'?' unique="unique" field="'.$option['name'].'"':'';
                    $inputF.='<div class="form-group-sm col-md-4">'
                        . '<label>'.$option['text'].($option['required']==='1'?'<span style="color: red;">*</span>':'').'</label>'
                        . '<input class="form-control" name="info['.$num.']['.$option['name'].']" '.$description.$required.$unique.' value="'.$option['def_val'].'"/>'
                    . '</div>';
                    break;
                case 'compability':
                    $description = $option['description']===''?'':'data-toggle="tooltip" data-placement="bottom" data-original-title="'.$option['description'].'"';
                    $required = $option['required']==='1'?' required="required" aria-required="true"':'';
                    $unique = $option['unique_field']==='1'?' unique="unique" field="'.$option['name'].'"':'';
                    $inputF.='<div class="form-group-sm col-md-4"><div class="col-lg-10">'
                        . '<label>'.$option['text'].($option['required']==='1'?'<span style="color: red;">*</span>':'').'</label>'
                        . '<input class="form-control" name="info['.$num.']['.$option['name'].']" id="'.$option['name'].$num.'" '.$description.$required.$unique.' value="'.$option['def_val'].'"/>'
                    . '</div><div class="col-lg-1"><label>&nbsp;</label><br><a class="btn btn-success" btn_type="compability" '.$description.' data-toggle="modal" data-target="#'.$option['name'].'-'.$num.'"><i class="fa fa-search"></i></a></div></div>'
                    .'<div class="modal fade" id="'.$option['name'].'-'.$num.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">'.$option['description'].'</h4>
                          </div>
                          <div class="modal-body"><div class="row" num="compability">';
                    $sup = $this->db->query("SELECT *, (SELECT name FROM ".DB_PREFIX."lib_struct ls2 WHERE ls2.parent_id = ls1.item_id) AS child FROM ".DB_PREFIX."lib_struct ls1 WHERE library_id = ".(int)$option['libraries']);
                    foreach ($sup->rows as $item) {
                        if($item['parent_id']){
                            $inputF.='<div class="col-lg-4" id="'.$item['name'].'"></div>';
                        } else {
                            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE item_id = ".(int)$item['item_id']);
                            $inputF.='<div class="col-lg-4" id="'.$item['name'].'"><label>'.$item['text'].'</label><select class="form-control" select_type="librSelect" child="'.$item['child'].'">';
                            $inputF.= '<option value="-">-</option>';
                            foreach ($query->rows as $value) {
                                $inputF.= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                            }
                            $inputF.='</select></div>';
                        }
                    }
                    
                    $inputF.= '</div></div>
                          <div class="modal-footer">
                            <p id="totalCpb" cpb></p>
                            <div class="col-lg-12"><hr></div>
                            <button type="button" class="btn btn-primary" cpbfield_id="'.$num.'" cpbfield_name="'.$option['name'].'" btn_type="applyCpb">Применить</button>
                          </div>
                        </div>
                      </div>
                    </div>';
                    break;
                case 'select':
                    $selectF.='<div class="form-group-sm  col-md-3">'
                             . '<label>'.$option['text'].'</label>';
                        $selectF.='<select class="form-control" name="info['.$num.']['.$option['name'].']">';
                        $vals = explode(';', $option['vals']);
                        foreach ($vals as $value) {
                            $selectF.='<option value="'.$value.'">'.$value.'</option>';
                        }
                        $selectF.='</select>';
                    $selectF.='</div>';
                    break;
                case 'library':
                    $sup = $this->db->query("SELECT *, (SELECT name FROM ".DB_PREFIX."lib_struct WHERE parent_id = ".(int)$option['libraries'].") AS child FROM ".DB_PREFIX."lib_struct WHERE item_id = ".(int)$option['libraries']);
                    if($sup->row['parent_id']){
                        $librF.='<div class="form-group-sm col-md-4" id="'.$option['name'].'">'
                              . '</div>'.($sup->row['isparent']?'':'<div class="clearfix"></div>');
                    }else{
                        $fillsquer = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE item_id = ".(int)$option['libraries']." ORDER BY name ");
                        $librF.='<div class="form-group-sm col-md-4" id="'.$option['name'].'">'
                                . '<label>'.$option['text'].'</label>'
                                . '<select class="form-control" name="info['.$num.']['.$option['name'].']" select_type="librSelect" child="'.$sup->row['child'].'">'
                                . '<option value="-">-</option>';
                        foreach ($fillsquer->rows as $fill) {
                            $librF.='<option value="'.$fill['id'].'">'.$fill['name'].'</option>';
                        }
                        $librF.='</select></div>';
                    }
                    break;
            }            
        }
        $form = $systemF."<div class='col-lg-12'><hr></div>".$librF."<div class='col-lg-12'><hr></div>".$selectF."<div class='col-lg-12'><hr></div>".$inputF."<div class='col-lg-12'><hr></div>";
        $form.= $this->model_tool_complect->constrCompField($num)."<div class='col-lg-12'><hr></div>";
        $form.='<div class="form-group-sm col-lg-3" >
            <label for="photos">Прикрепите фотографии:</label>
                    <input name="photo['.$num.'][]" id="photos" class="form-control" type="file" multiple="true">
                </div>';
        $form.='<input type="hidden" name="info['.$num.'][type_id]" value="'.$id.'">';
        return $form;
    }
    
    public function getLibrChilds($parent, $fieldName){
        $result = array('js' => '', 'text' => '', 'fills' => array());
        if($parent!=='-'){
            $jsChilds = '';
            $tquery = $this->db->query("SELECT text FROM ".DB_PREFIX."lib_struct WHERE name = '".$fieldName."'");
            $text = $tquery->row['text'];
            $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."lib_fills WHERE parent_id = ".(int)$parent." ORDER BY name");
            if($sup->num_rows){
                //exit(var_dump($text));
                $cquery = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_struct WHERE parent_id = ".(int)$sup->row['item_id']);
                if($cquery->num_rows){
                    $jsChilds = 'select_type="librSelect" child="'.$cquery->row['name'].'"';
                }
                foreach ($sup->rows as $fill) {
                    $result['fills'][] = $fill;
                }
            }
            $result['text'] = isset($text)?$text:'';
            $result['js'] = $jsChilds;
        }
        return $result;
    }
    
    public function isUnique($search, $field){
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product WHERE ".$field." = '".$search."'");
        return $sup->num_rows;
    }
    
    public function saveProdList($prodlist, $photos) {
//        echo var_dump($prodlist).'<br><hr><br>';
//        exit(var_dump());
        $this->load->model('tool/product');
        $this->load->model('tool/complect');
        foreach ($prodlist as $num => $product){
            //upload photos
            $this->uploadPhoto($product['vin'], $num, $photos);
            $prodItem = array();
            $prod2Lib = array();
            //"napolnenie" producta
            foreach ($this->systemFields as $key => $value) {
                $prodItem[$key] = $product[$key];
            }
            $struct = $this->model_tool_product->getProdTypeTemplate($product['type_id']);
            $name = $struct['temp'];
            $description = $struct['desctemp'];
            $tags = '';
            foreach ($struct['options'] as $option) {
                if(isset($product[$option['name']])){
                    if($option['field_type']==='library' && $product[$option['name']]!=='-'){
                        $sup = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE id = ".(int)$product[$option['name']]);
                        $prodItem[$option['name']] = $sup->row['name'];
                        $prod2Lib[] = $product[$option['name']];
                        $name = str_replace($option['name'], $sup->row['name'], $name);
                        $description = str_replace('%'.$option['name'].'%', $sup->row['name'], $description);
                        $tags.= $sup->row['name'].', ';
                    } else {
                        $prodItem[$option['name']] = $product[$option['name']];
                        if($product[$option['name']]!=='-'){
                            $name = str_replace($option['name'], $product[$option['name']], $name);
                            $description = str_replace('%'.$option['name'].'%', $product[$option['name']], $description);
                        } else {
                            $name = str_replace($option['name'], '', $name);
                            $description = str_replace('%'.$option['name'].'%', '', $description);
                        }
                    }
                }
            }
            $tags.= $name;
            switch ($product['complect']) {
                case 'set':
                    $prodItem['comp'] = $product['heading'];
                    break;
                case 'create':
                    $this->model_tool_complect->createComplect($product['vin'], $name);
                    break;
            }
            //------------------------------
            //add product to db
            $sql = "INSERT INTO ".DB_PREFIX."product SET ";
            foreach ($prodItem as $key => $value) {
                $sql.= $key." = '".$value."', ";
            }
            $sql.="date_added = NOW(), ";
            $sql.="date_modified = NOW(), ";
            $sql.="date_available = NOW(), ";
            $sql.="last_view = NOW(), ";
            $sql.="manager = '".$this->session->data['username']."', ";
            $sql.="structure = '".$product['type_id']."' ";
            $this->db->query($sql);
            $sup = $this->db->query("SELECT MAX(product_id) AS pid FROM ".DB_PREFIX."product ");
            $product_id = $sup->row['pid'];
            //-------------------------------
            //linked product to libraries
            foreach ($prod2Lib as $libitem) {
                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_lib SET  product_id = ".(int)$product_id.", fill_id = ".(int)$libitem);
            }
            //------------------------------------------
            //description to product
            $this->db->query("INSERT INTO ".DB_PREFIX."product_description "
                    . "(`product_id`, `language_id`, `name`, `description`, `tag`, `meta_title`, `meta_h1`, `meta_description`, `meta_keyword`) VALUES "
                    . "(".(int)$product_id.", 1, '".$name."', '".$description."', '".$tags."', '".$name."', '".$name."', '".$name."', '".$tags."')");
            //link photos to product
            if(is_dir(DIR_IMAGE . "catalog/demo/production/".$product['vin']."/")){
                $dir = "catalog/demo/production/".$product['vin']."/";
                $photos = scandir(DIR_IMAGE . $dir);
                array_shift($photos);
                array_shift($photos);
                $i = 0;
                foreach ($photos as $key => $image) {
                    $image = $dir.$image;
                    if($key==0){$this->db->query("UPDATE ".DB_PREFIX."product SET image = '".$image."' WHERE product_id = ".(int)$product_id);}
                    $this->db->query("INSERT INTO ".DB_PREFIX."product_image SET product_id = ".(int)$product_id.", image = '".$image."', sort_order = ".(int)$i);
                    ++$i;
                }
            }
            //product to store
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store (product_id, store_id) VALUES (".(int)$product_id.", 0)");
            //old link-tables
            $this->model_tool_product->oldLinks($product_id, $prod2Lib);
            
        }
        
    }
    
    public function uploadPhoto($vin, $num, $photos) {
        if(isset($photos['photo']['name'][$num])){
            $uploadtmpdir = DIR_IMAGE . "tmp/";
            $uploaddir = DIR_IMAGE . "catalog/demo/production/".$vin."/";
            if(!is_dir($uploaddir)){mkdir($uploaddir);}
            $photo = array();
            $i = 0;
            foreach ($photos['photo']['name'][$num] as $name){
                $photo[$i]['name'] = $name;
                ++$i;
            }
            $i = 0;
            foreach ($photos['photo']['type'][$num] as $name){
                $photo[$i]['type'] = $name;
                ++$i;
            }
            $i = 0;
            foreach ($photos['photo']['error'][$num] as $name){
                $photo[$i]['error'] = $name;
                ++$i;
            }
            $i = 0;
            foreach ($photos['photo']['tmp_name'][$num] as $name){
                $photo[$i]['tmp_name'] = $name;
                ++$i;
            }
            $i = 0;
            foreach ($photos['photo']['size'][$num] as $name){
                $photo[$i]['size'] = $name;
                ++$i;
            }
            $optw = 1200;
            $name = 0;
//            exit(var_dump($photo));
            foreach ($photo as $file){
                if($file['size']!=='0'){
                    //--------------//
                    if ($file['type'] == 'image/jpeg'){
                        $source = imagecreatefromjpeg ($file['tmp_name']);
                    }
                    elseif ($file['type'] == 'image/png'){
                        $source = imagecreatefrompng ($file['tmp_name']);
                    }
                    elseif ($file['type'] == 'image/gif'){
                        $source = imagecreatefromgif ($file['tmp_name']);
                    }
                    else{
                        exit ('wtf, dude?!');
                    }
                   /*****************/

                    $w_src = imagesx($source); 
                    $h_src = imagesy($source);

                    $ratio = $w_src/$optw;
                    $w_dest = $optw;
                    $h_dest = round($h_src/$ratio);

                    $dest = imagecreatetruecolor($optw, $h_dest);

                    imagecopyresampled($dest, $source, 0, 0, 0, 0, $optw, $h_dest, $w_src, $h_src);



                    imagejpeg($dest, $uploadtmpdir . $file['name'], 90);
                    imagedestroy($dest);
                    imagedestroy($source);

                    copy($uploadtmpdir . $file['name'], $uploaddir .$vin.'-'. $name . '.jpg');

                    unlink($uploadtmpdir . $file['name']);

                    $name++;
                }
            }
        }
    }
}

