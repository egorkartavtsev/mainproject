<?php
class ControllerCommonAddprod extends Controller {
private $error = array();
    
    public function index() {
        if(!empty($this->request->post)){
            $this->updateDB($this->request->post['info']);
        }
        $data = $this->getLayout();
        //берём категории
        $query = $this->db->query("SELECT "
                . "c.category_id AS id, "
                . "cd.name AS name "
                . "FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd "
                    . "ON (cd.language_id=1 AND cd.category_id = c.category_id) "
                . "WHERE c.parent_id = 0 ORDER BY cd.name ");

        $results = $query->rows;
        $data['category'] = array();
        foreach ($results as $res) {
                $data['category'][] = array(
                    'name' => $res['name'],
                    'val'  => $res['id']
                );
        }

        //берём марки
        $query = $this->db->query("SELECT id, name FROM ".DB_PREFIX."brand "
                                . "WHERE parent_id = 0 ORDER BY name ");

        $brands = $query->rows;
        $data['brands'] = array();
        foreach ($brands as $res) {
            $data['brands'][] = array(
            'name' => $res['name'],
            'val'  => $res['id']
            );
        }
        if(isset($message)){
            $data['message'] = $message;
        } else {
            $data['message'] = '';
        }
        
        $this->response->setOutput($this->load->view('common/addprod', $data));
        }
        
    public function get_ajax(){
        
        sleep(1); 
	$result = '<select>';
	$i = 10;
	while ($i > 0) {
		
		$result .= '<option value="'. (100-$i) .'">'.$i.'</option>';
		$i--;
		
	}
	
	$result.='</select>';
	
	echo $result;
        
    }

    public function setPH() {
//        $photo = $_FILES;
          $data = $this->getLayout();
        $vin = $this->request->post['vin'];
        $uploadtmpdir = DIR_IMAGE . "tmp/";
        if ($vin!=''){
            mkdir(DIR_IMAGE . "catalog/demo/production/".$vin);
            $uploaddir = DIR_IMAGE . "catalog/demo/production/".$vin."/";
        }
        else{
            exit('введите внутренний номер');
        }
        
        $watermark = imagecreatefrompng(DIR_IMAGE . "watermark.png");
        
        $photo = array();
        
        $i = 0;
        foreach ($_FILES['photo']['name'] as $crit){
            $photo[$i]['name'] = $crit;
            $i++;
        }
        $i = 0;
        foreach ($_FILES['photo']['type'] as $crit){
            $photo[$i]['type'] = $crit;
            $i++;
        }
        $i = 0;
        foreach ($_FILES['photo']['error'] as $crit){
            $photo[$i]['error'] = $crit;
            $i++;
        }
        $i = 0;
        foreach ($_FILES['photo']['tmp_name'] as $crit){
            $photo[$i]['tmp_name'] = $crit;
            $i++;
        }
        $i = 0;
        foreach ($_FILES['photo']['size'] as $crit){
            $photo[$i]['size'] = $crit;
            $i++;
        }
        
        $optw = 1200;
        $name = 0;
        foreach ($photo as $file){
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
            
            $marge_right = 10;
            $marge_bottom = 10;
            $sx = imagesx($watermark);
            $sy = imagesy($watermark);
            
            imagecopy($dest, $watermark, imagesx($dest) - $sx - $marge_right, imagesy($dest) - $sy - $marge_bottom, 0, 0, imagesx($watermark), imagesy($watermark));
            
            imagejpeg($dest, $uploadtmpdir . $file['name'], 90);
            imagedestroy($dest);
            imagedestroy($source);
            
            copy($uploadtmpdir . $file['name'], $uploaddir . $name . '.jpg');
            
            unlink($uploadtmpdir . $file['name']);
            
            $name++;
            $data['status'] = 2;
        }
        $data['vin'] = $this->request->post['vin'];
        
        /*****************************************************************/
        
        //берём категории
        $query = $this->db->query("SELECT "
                . "c.category_id AS id, "
                . "cd.name AS name "
                . "FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd "
                    . "ON (cd.language_id=1 AND cd.category_id = c.category_id) "
                . "WHERE c.parent_id = 0");
        
        $results = $query->rows;
        $data['category'] = array();
        foreach ($results as $res) {
                $data['category'][] = array(
                    'name' => $res['name'],
                    'val'  => $res['id']
                );
        }
        
        //берём марки
        $query = $this->db->query("SELECT id, name FROM ".DB_PREFIX."brand "
                                . "WHERE parent_id = 0");
                        
        $brands = $query->rows;
        $data['brands'] = array();
        foreach ($brands as $res) {
            $data['brands'][] = array(
            'name' => $res['name'],
            'val'  => $res['id']
            );
        }
        
            
            
        /*****************************************************************/
        
        
        
        $this->response->setOutput($this->load->view('common/addprod', $data));
    }
    
    public function get_model() {
        $brand = $this->request->post['brand'];
        $token = $this->request->post['token'];
        $query = $this->db->query("SELECT "
                                . "b.id AS id, "
                                . "b.name AS name "
                                . "FROM ".DB_PREFIX."brand b "
                                . "WHERE b.parent_id = ".$brand." ORDER BY b.name");
        $results = $query->rows;
        $mods = array();
        foreach ($query->rows as $res) {
           $mods[] = array(
                'name' => $res['name'],
                'id'   => $res['id']
            );
        }
        $models = "<select name='model_id' class='form-control' id='model' onchange='";
        $models.='ajax({';
        $models.='url:"index.php?route=common/addprod/get_modelRow&token='.$token.'",';
        $models.='statbox:"status",
                    method:"POST",
                    data:
                    {
                        model: document.getElementById("model").value,
                        token: "'.$token.'"
                    },
                    success:function(data){document.getElementById("model_row").innerHTML=data;}';
        $models.='})';
        $models.="'>";
        $models.='<option selected="selected" disabled="disabled">Выберите модель</option>';
        foreach ($mods as $model){
            $models.='<option value="'.$model['id'].'">'.$model['name'].'</option>';
        }
        $models.='</select>';
        echo $models;
    }
   
    public function get_modalModel() {
        $brand = $this->request->post['brand'];
        $token = $this->request->post['token'];
        $query = $this->db->query("SELECT "
                                . "b.id AS id, "
                                . "b.name AS name "
                                . "FROM ".DB_PREFIX."brand b "
                                . "WHERE b.parent_id = ".$brand." ORDER BY b.name");
        $results = $query->rows;
        $mods = array();
        foreach ($query->rows as $res) {
           $mods[] = array(
                'name' => $res['name'],
                'id'   => $res['id']
            );
        }
        $models = "<select class='form-control' id='modalModel' onchange='";
        $models.='ajax({';
        $models.='url:"index.php?route=common/addprod/get_modalModelRow&token='.$this->session->data['token'].'",';
        $models.='statbox:"status",
                    method:"POST",
                    data:
                    {
                        model: document.getElementById("modalModel").value,
                        token: "'.$token.'"
                    },
                    success:function(data){document.getElementById("model_rows").innerHTML=data;}';
        $models.='})';
        $models.="'>";
        $models.='<option selected="selected" disabled="disabled">Выберите модель</option>';
        foreach ($mods as $model){
            $models.='<option value="'.$model['id'].'">'.$model['name'].'</option>';
        }
        $models.='</select>';
        echo $models;
    }
    
    public function get_modelRow() {
        $model = $this->request->post['model'];
        //exit(var_dump($_POST));
        $token = $this->request->post['token'];
        $query = $this->db->query("SELECT "
                                . "b.id AS id, "
                                . "b.name AS name "
                                . "FROM ".DB_PREFIX."brand b "
                                . "WHERE b.parent_id = ".$model." ORDER BY b.name ");
        $results = $query->rows;
        $modRs = array();
        foreach ($query->rows as $res) {
           $modRs[] = array(
                'name' => $res['name'],
                'id'   => $res['id']
            );
        }
        $modelRs = "<select name='modelRow_id' id='model_row_id' class='form-control'>";
        $modelRs.='<option selected="selected" disabled="disabled">Выберите модельный ряд</option>';
        foreach ($modRs as $modelR){
            $modelRs.='<option value="'.$modelR['id'].'">'.$modelR['name'].'</option>';
        }
        $modelRs.='</select>';
        echo $modelRs;
    }
    
    public function get_modalModelRow() {
        $model = $this->request->post['model'];
        //exit(var_dump($_POST));
        $token = $this->request->post['token'];
        $query = $this->db->query("SELECT "
                                . "b.id AS id, "
                                . "b.name AS name "
                                . "FROM ".DB_PREFIX."brand b "
                                . "WHERE b.parent_id = ".$model." ORDER BY b.name ");
        $results = $query->rows;
        $modRs = array();
        foreach ($query->rows as $res) {
           $modRs[] = array(
                'name' => $res['name'],
                'id'   => $res['id']
            );
        }
        $modelRs = '';
        foreach ($modRs as $modelR){
            $modelRs.='<span id="mrList'.$modelR['id'].'" class="bg-success cpbItem mrList" onclick="chooseCpb(\''.$modelR['id'].'\');">'.$modelR['name'].'</span>, ';
        }
        
        echo $modelRs;
    }
    
    public function get_podcat() {
        $category = $this->request->post['categ'];
        //exit(var_dump($_POST));
        $token = $this->request->post['token'];
        $query = $this->db->query("SELECT "
                . "c.category_id AS id, "
                . "cd.name AS name "
                . "FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd "
                    . "ON (cd.language_id=1 AND cd.category_id = c.category_id) "
                . "WHERE c.parent_id = ".$category." ORDER BY cd.name ");
            $results = $query->rows;
            $podcats = array();
            foreach ($results as $res) {
                $podcats[] = array(
                    'name' => $res['name'],
                    'id'   => $res['id']
                );
            }
            
        $podcs = "<select name='podcat_id' id='pcat_id' class='form-control'>";
        $podcs.='<option selected="selected" disabled="disabled">Выберите подкатегорию</option>';
        foreach ($podcats as $podcat){
            $podcs.='<option value="'.$podcat['id'].'">'.$podcat['name'].'</option>';
        }
        $podcs.='</select>';
        echo $podcs;
    }
    
    public function prodToDB() {
        $data = $this->getLayout();
        
        $product = array();
        $product = $this->request->post;
        
        /*************************************************************************************/
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."brand "
                                . "WHERE id = ".$product['brand_id']);
        $product['brand'] = $query->row['name'];
        
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."brand "
                                . "WHERE id = ".$product['model_id']);
        $product['model'] = $query->row['name'];
        
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."brand "
                                . "WHERE id = ".$product['modelRow_id']);
        $product['modelRow'] = $query->row['name'];
        
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description "
                                . "WHERE category_id = ".$product['category_id']." AND language_id = 1");
        $product['category'] = $query->row['name'];
        
        $query = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description "
                                . "WHERE category_id = ".$product['podcat_id']." AND language_id = 1");
        $product['podcat'] = $query->row['name'];
        /***********************************************************************************/
        
        $product['name'] = $product['podcat'].' '.$product['brand'].' '.$product['modelRow'];
        
        $dir = DIR_IMAGE . 'catalog/demo/production/'.$product['vin'];
        $photos = array();
        $files = scandir($dir);
        $con = count($files);
        for($i = 2; $i < $con; $i++){
            $photos[] = $files[$i];
        }
        $product['image'] = 'catalog/demo/production/'.$product['vin']."/".$photos[0];
        
        $product['description'] = "<h4>Авторазбор174.рф</h6> предлагает Вам приобрести "
                                .$product['name']." для автомобиля "
                                .$product['brand']." ".$product['modelRow']." со склада в г.Магнитогорске. " 
                                ."Авторазбор автозапчасти б/у для ".$product['brand']." ".$product['modelRow'];
        
        $tag = $product['brand'].', '.$product['model'].', '.$product['modelRow'].', '.$product['podcat'].', '.$product['name'].', '.$product['catn'];
        
        $this->db->query("INSERT INTO ".DB_PREFIX."product "
                        . "SET "
                        . "`manufacturer_id` = '". $product['brand_id'] ."', "
                        . "`model` = '". $product['model'] ."', "
                        . "`jan` = '". $product['prim'] ."', "
                        . "`sku` = '". $product['vin'] ."', "
                        . "`upc` = '". $product['fix'] ."', "
                        . "`ean` = '". $product['type'] ."', "
                        . "`location` = '".$product['sklad']."/".$product['stell']."/".$product['yarus']."/".$product['polka']."/".$product['korobka']."', "
                        . "`isbn` = '". $product['catn'] ."', "
                        . "`mpn` = ' ', "
                        . "`weight` = 0, "
                        . "`price` = ". $product['price'] .", "
                        . "`image` = '". $product['image'] ."', "
                        . "`quantity` = ".$product['quant'].", "
                        . "`length` = '".$product['modelRow']."', "
                        . "`width` = '".$product['avito']."', "
                        . "`height` = '".$product['drom']."', "
                        . "`status` = 1, "
                        . "`date_added` = NOW(), "
                        . "`date_available` = NOW(), "
                        . "`date_modified` = NOW(), "
                        . "`stock_status_id` = 7");
        
        $product_id = $this->db->getLastId();
                
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_description "
                            . "SET "
                            . "product_id = '" . (int)$product_id . "', "
                            . "language_id = 1, "
                            . "name = '" . $product['name'] ."', "
                            . "description = '".$product['description']."', "
                            . "tag =  '".$tag."', "
                            . "meta_title = '" . $product['name'] . "', "
                            . "meta_h1 = '" . $product['name'] . "', "
                            . "meta_description = '" . $tag . "', "
                            . "meta_keyword = '" . $tag . "'");
        
        $this->db->query("INSERT INTO ". DB_PREFIX ."product_to_store "
                        . "SET "
                        . "product_id = '".(int)$product_id."',"
                        . "store_id = 0");
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET "
                                        . "product_id = '" . (int)$product_id . "', "
                                        . "category_id = '" . (int)$product['category_id'] . "'");
                        
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET "
                                        . "product_id = '" . (int)$product_id . "', "
                                        . "category_id = '" . (int)$product['podcat_id'] . "'");
        
        $this->db->query("INSERT INTO ". DB_PREFIX ."url_alias "
                        . "SET "
                        . "query = 'product_id=".(int)$product_id."'");
        
        foreach ($photos as $photo){
                        $this->db->query("INSERT INTO ". DB_PREFIX ."product_image "
                                . "SET "
                                . "product_id = ". (int)$product_id .", "
                                . "image = 'catalog/demo/production/".$product['vin']."/".$photo."' ");
                    }
        
        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                        . "SET "
                        . "product_id = ". (int)$product_id .", "
                        . "brand_id = ".$product['brand_id']);            
        
        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                        . "SET "
                        . "product_id = ". (int)$product_id .", "
                        . "brand_id = ".$product['model_id']);
        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand "
                        . "SET "
                        . "product_id = ". (int)$product_id .", "
                        . "brand_id = ".$product['modelRow_id']);
        
        $data['status'] = 3;
        //exit(var_dump($products));
        $this->response->setOutput($this->load->view('common/addprod', $data));
    }
    
    public function getLayout() {


                    $this->load->language('common/addprod');

                    $this->document->setTitle($this->language->get('heading_title'));

                    $data['heading_title'] = $this->language->get('heading_title');

                    $data['breadcrumbs'] = array();

                    $data['breadcrumbs'][] = array(
                            'text' => $this->language->get('text_home'),
                            'href' => $this->url->link('common/excel', 'token=' . $this->session->data['token'], true)
                    );

                    $data['breadcrumbs'][] = array(
                            'text' => $this->language->get('heading_title'),
                            'href' => $this->url->link('common/addprod', 'token=' . $this->session->data['token'], true)
                    );
                    $data['header'] = $this->load->controller('common/header');
                    $data['column_left'] = $this->load->controller('common/column_left');
                    $data['footer'] = $this->load->controller('common/footer');
                    $data['token_add'] = $this->session->data['token'];
                    return $data;

        }
        
    public function addToList() {
//        exit(var_dump($_FILES));
        $this->load->model('common/addprod');
        if(strlen($_FILES['photo']['name'][0])!=0){ 
            $this->model_common_addprod->setPhoto($this->request->post['vin']);
        }
        $product = $this->model_common_addprod->createProduct($this->request->post, $this->session->data['username']);
        $h_ex = $this->db->query("SELECT name FROM ".DB_PREFIX."stocks WHERE 1");
        $stocks = array();
        
        foreach ($h_ex->rows as $stock) {
            $stocks[] = $stock['name'];
        }

        $update_form = '<div class="alert alert-success col-lg-3" style="margin-left: 25px;">';
            $update_form.= '<h4 id="name'.$product['id'].'">'.$product['name'].' <b>('.$product['vin'].')</b></h4>';
            $update_form.= '<input type="hidden" name="info['.$product['id'].'][vin]" value="'.$product['vin'].'">';
            $update_form.= '<input type="hidden" name="info['.$product['id'].'][name]" value="'.$product['name'].'">';
            $update_form.= '<div class="clearfix"></div>';
            $update_form.= '<div class="clearfix"><p></p></div>';
            
            $update_form.= '<div class="form-group-sm">';
                $update_form.= '<label for="catn'.$product['id'].'">Каталожный номер</label>';
                $update_form.= '<input class="form-control" type="text" name="info['.$product['id'].'][catn]" id="catn'.$product['id'].'" placeholder="Каталожный номер">';
            $update_form.= '</div>';
            $update_form.= '<div class="form-group-sm">';
                $update_form.= '<label for="note'.$product['id'].'">Примечание</label>';
                $update_form.= '<input class="form-control" type="text" name="info['.$product['id'].'][note]" id="note'.$product['id'].'" placeholder="Примечание">';
            $update_form.= '</div>';
            $update_form.= '<div class="form-group-sm">';
                $update_form.= '<label for="price'.$product['id'].'">Цена</label>';
                $update_form.= '<input class="form-control" type="text" name="info['.$product['id'].'][price]" id="price'.$product['id'].'" placeholder="Цена">'; 
            $update_form.= '</div>';
            $update_form.= '<div class="form-group-sm">';
                $update_form.= '<label for="quantity'.$product['id'].'">Количество</label>';
                $update_form.= '<input class="form-control" type="text" name="info['.$product['id'].'][quantity]" id="quantity'.$product['id'].'" placeholder="Количество" value="1">';
            $update_form.= '</div>';
            $update_form.= '<div class="clearfix"></div>';
            $update_form.= '<div class="clearfix"><p></p></div>';
            $update_form.= '<div class="form-group-sm">';
                $update_form.= '<select class="form-control" data-toggle="tooltip" data-original-title="Тип детали" name="info['.$product['id'].'][type]">'
                                . '<option value="Б/У">Б/У</option>'
                                . '<option value="Новый">Новый</option>'
                             . '</select>';
            $update_form.= '</div>';
            $update_form.= '<div class="clearfix"></div>';
            $update_form.= '<div class="clearfix"><p></p></div>';
            $update_form.= '<div class="form-group-sm">';
                $update_form.= '<select class="form-control" data-toggle="tooltip" data-original-title="Состояние" name="info['.$product['id'].'][cond]">'
                                . '<option value="Отличное">Отличное</option>'
                                . '<option value="-">-</option>'
                                . '<option value="Хорошее">Хорошее</option>'
                                . '<option value="Повреждения">Повреждения</option>'
                             . '</select>';
            $update_form.= '</div>';
            $update_form.= '<div class="clearfix"></div>';
            $update_form.= '<div class="clearfix"><p></p></div>';
            $update_form.= '<div class="form-group-sm">';
                $update_form.= '<select class="form-control" data-toggle="tooltip" data-original-title="Склад" name="info['.$product['id'].'][stock]">';
                $update_form.= '<option value="-">---</option>';
                foreach ($stocks as $stock) {
                    $update_form.= '<option value="'.$stock.'">'.$stock.'</option>';
                }
                $update_form.= '</select>';
            $update_form.= '</div>';
            
            $update_form.= '<div class="col-lg-6">';
                $update_form.= '<input class="form-control" data-toggle="tooltip" data-original-title="Стеллаж" type="text" name="info['.$product['id'].'][stell]" placeholder="Стеллаж">';
            $update_form.= '</div>';
            $update_form.= '<div class="col-lg-6">';
                $update_form.= '<input class="form-control" data-toggle="tooltip" data-original-title="Ярус" type="text" name="info['.$product['id'].'][jar]" placeholder="Ярус">';
            $update_form.= '</div>';
            $update_form.= '<div class="col-lg-6">';
                $update_form.= '<input class="form-control" type="text" data-toggle="tooltip" data-original-title="Полка" name="info['.$product['id'].'][shelf]" placeholder="Полка">';
            $update_form.= '</div>';
            $update_form.= '<div class="col-lg-6">';
                $update_form.= '<input class="form-control" type="text" data-toggle="tooltip" data-original-title="Коробка" name="info['.$product['id'].'][box]" placeholder="Коробка">';
            $update_form.= '</div>';
            $update_form.= '<div class="clearfix"></div>';
            $update_form.= '<div class="clearfix"><p></p></div>';
            
            $update_form.= '<div class="form-group-sm">';
                $update_form.= '<select class="form-control" id="complect'.$product['id'].'" onchange="setComplect(\''.$product['id'].'\')">';
                    $update_form.= '<option value="1">Не в комплекте</option>';
                    $update_form.= '<option value="2">Головной</option>';
                    $update_form.= '<option value="3">Комплектующее</option>';
                $update_form.= '</select>';
            $update_form.= '</div>';
            $update_form.= '<div class="clearfix"></div>';
            $update_form.= '<div class="clearfix"><p></p></div>';
            $update_form.= '<div class="form-group-sm" id="compl'.$product['id'].'">';
                $update_form.= '<input class="form-control" type="hidden" name="info['.$product['id'].'][heading]" value="skip">';
            $update_form.= '</div>';
            $update_form.= '<div class="clearfix"></div>';
            $update_form.= '<div class="clearfix"><p></p></div>';
            $update_form.= '<div class="form-group-sm">';
                $update_form.= '<input class="form-control" type="text" name="info['.$product['id'].'][compability]" id="cpb'.$product['id'].'" placeholder="Применимость" data-toggle="modal" data-target="#cpbModal" data-whatever="'.$product['id'].'">';
            $update_form.= '</div>';
            
        $update_form.= '</div>';
        //$update_form.= '<div class="col-lg-1"><p></p></div>';
        //exit(var_dump($_FILES));
        echo $update_form;
        
    }

    public function updateDB($data) {
        $this->load->model('common/addprod');
        $prods = $this->model_common_addprod->updateDB($data);
        $this->response->redirect($this->url->link('common/addprod/downloadInvoice', 'token=' . $this->session->data['token'] . '&products=' . $prods, true));
    }
    
    public function downloadInvoice() {
        $prod_arr = explode(',', $this->request->get['products']);
        $products = array();
        foreach($prod_arr as $prod){
            if($prod!=''){
                $products[] = $prod;
            }
        }
        $this->load->controller('common/excelTools');
        $tools = new ControllerCommonExcelTools($this->registry);
        $id_invoice = uniqid('p_');
        $info = $this->constructInfo($products);
        
        $tools->createInvoice($info, $id_invoice);
    }
    
    public function constructInfo($products) {
        $prod_info = array();
        $sql = 'SELECT p.price, pd.name, p.sku AS vin, p.quantity, p.location, p.comp FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON p.product_id = pd.product_id WHERE 0 ';
        $query = $this->db->query("SELECT firstname, lastname FROM ".DB_PREFIX."user WHERE user_id = '".$this->session->data['user_id']."'");
        $manager = $query->row['firstname'].' '.$query->row['lastname'];
        $info = array(
            'client'    => $manager,
            'city'      => 'Магнитогорск',
            'date'      => date("Y-m-d H:i:s"),
            'products'  => array()
        );
        foreach ($products as $prod) {
            $sql.= "OR p.product_id = '".$prod."' ";
        }
        $sql.="ORDER BY p.product_id ";
        $quer = $this->db->query($sql);
        $prods = $quer->rows;
        
        foreach ($prods as $prod) {
            $name = $prod['name'];
            if($prod['comp']!=''){
                $name.=' (КОМПЛЕКТ)';
            }
            $info['products'][$prod['vin']] = array(
                'name'      => $name,
                'quan'      => $prod['quantity'],
                'price'     => $prod['price'],
                'pricefact' => $prod['price'],
                'quanfact'  => $prod['quantity'],
                'locate'    => $prod['location'],
                'reason'    => ''
            );
        }
        return $info;
    }
    
    public function validateVin() {
        $vin = $this->request->post['vin'];
        $this->load->model('common/addprod');
        $messsage = $this->model_common_addprod->validateVin($vin);
        echo $messsage;
    }
    
    public function get_pcs() {
        $podcat = trim($this->request->post['podcat']);
        $request = explode(" ", $podcat);
        if($podcat==''){
            exit('');
        }
        $this->load->model('common/addprod');
        $podcats = $this->model_common_addprod->pcs($request);
        $list = '';
        foreach ($podcats as $pc) {
            $list.='<li class="searchItem" onclick="chooseCPC(\''.$pc['id'].'\', \''.$pc['name'].'\');">'.$pc['name'].'</li>';
        }
        echo $list;
    }
    
    public function get_cat() {
        $podcat_id = $this->request->post['podcat'];
        $query = $this->db->query("SELECT cd.name, c.parent_id AS id "
                . "FROM ".DB_PREFIX."category c "
                . "LEFT JOIN ".DB_PREFIX."category_description cd ON c.parent_id = cd.category_id "
                . "WHERE c.category_id = '".$podcat_id."'");
        echo '<input type="hidden" name="category_id" id="category_id" value="'.$query->row['id'].'"><input type="text" class="form-control" disabled value="'.$query->row['name'].'">';
    }
    
    public function tryCompl() {
        $head = $this->request->post['head'];
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE heading = '".$head."' ");
        if(!empty($query->row)){
            echo '1';
        } else{
            echo '0';
        }
    }
}