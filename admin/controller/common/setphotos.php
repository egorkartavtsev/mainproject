<?php
class ControllerCommonSetphotos extends Controller {
private $error = array();
    
    public function index() {
            
            $data = $this->getLayout();
            $data['status'] = 1;
            $this->response->setOutput($this->load->view('common/setphotos', $data));
        }
        
        public function upload() {
            
            $piq = $this->db->query("SELECT `product_id` FROM ".DB_PREFIX."product WHERE `sku` = '".$_POST['vin']."'");
			//exit(var_dump($piq));
			if (!empty($piq->rows)) {
				$product_id = $piq->row['product_id'];
				/**************************************/
				$vins = array();
				$query = $this->db->query("SELECT `sku` AS vin FROM ".DB_PREFIX."product WHERE 1");
				$vins = $query->rows;
				/*************************************/
				$uploaddir = DIR_IMAGE.'catalog/demo/production/';
				$err = 0;
				$error = 0;
				$error_info = '';
				$dirs = scandir($uploaddir);
				mkdir(DIR_IMAGE . "catalog/demo/production/".$_POST['vin']);
				
				foreach ($vins as $numb) {
					if ($_POST['vin'] == $numb['vin']) {
						$err++;
					}
				}
				if ($err = 0){
					$error_info = 'Такого товара не существует. Проверьте правильность введения номера или добавьте товар в соответствующем разделе.';
					$error = 1;
				}
				elseif ($err > 1) {
					$error_info = 'Товаров с таким номером более одного. Ошибка на сервере. Свяжитесь с разработчиком';
					$error = 1;
				}
				else {
					$err = 0;
					foreach ($dirs as $numb) {
						if ($_POST['vin'] == $numb){
							$err++;
						}
					}
					if ($err > 0){
						$error_info = 'Данный товар уже есть базе, и папка с фотографиями уже сознада для него.';
						$error = 1;
					}
					else {
						
						$vin = $this->request->post['vin'];
						$uploadtmpdir = DIR_IMAGE . "tmp/";
						$uploaddir.=$vin.'/';
						
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
							
							$photo_name = $name.'.jpg';
							
							$this->db->query("INSERT INTO ". DB_PREFIX ."product_image "
									. "SET "
									. "product_id = ". (string)$product_id .", "
									. "image = 'catalog/demo/production/".$vin."/".$photo_name."' ");
							
							$name++;
							$data['success_message'] = 'Фотографии загружены и прикреплены.';
							$this->db->query("UPDATE `oc_product` SET `image` = 'catalog/demo/production/".$vin."/0.jpg' WHERE `sku` = '".$vin."'");
						}                   
					}
					
				}
			}
			else{
				$error = 1;
				$error_info = 'Такой товар не существует!';
			}
            $data = $this->getLayout();
            $data['error'] = $error;
            $data['error_info'] = $error_info;
            $this->response->setOutput($this->load->view('common/setphotos', $data));
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
                                . "WHERE b.parent_id = ".$brand);
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
    
    public function get_modelRow() {
        $model = $this->request->post['model'];
        //exit(var_dump($_POST));
        $token = $this->request->post['token'];
        $query = $this->db->query("SELECT "
                                . "b.id AS id, "
                                . "b.name AS name "
                                . "FROM ".DB_PREFIX."brand b "
                                . "WHERE b.parent_id = ".$model);
        $results = $query->rows;
        $modRs = array();
        foreach ($query->rows as $res) {
           $modRs[] = array(
                'name' => $res['name'],
                'id'   => $res['id']
            );
        }
        $modelRs = "<select name='modelRow_id' class='form-control'>";
        $modelRs.='<option selected="selected" disabled="disabled">Выберите модельный ряд</option>';
        foreach ($modRs as $modelR){
            $modelRs.='<option value="'.$modelR['id'].'">'.$modelR['name'].'</option>';
        }
        $modelRs.='</select>';
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
                . "WHERE c.parent_id = ".$category);
            $results = $query->rows;
            $podcats = array();
            foreach ($results as $res) {
                $podcats[] = array(
                    'name' => $res['name'],
                    'id'   => $res['id']
                );
            }
            
        $podcs = "<select name='podcat_id' class='form-control'>";
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
        
        $product['description'].="<h6><b>Цена товара:</b></h6>".$product['price']." рублей;<br/>";
        
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


                    $this->load->language('common/setphotos');

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
}