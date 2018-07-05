<?php

class ModelToolXml extends Model {
/*****************************AVITO_XML_AUTOLOAD********************************************/    
    public function avitoFind($data) {
        if($data['structure']==1){
            $xmls = simplexml_load_file('../Avito/ads.xml');
            $sup = 0; 
            foreach($xmls->Ad as $ad){
                if(in_array($data['vin'], (array)$ad)){
                    if(isset($data['write_off'])){
                        $dom=dom_import_simplexml($xmls->Ad[$sup]);
                        $dom->parentNode->removeChild($dom);
                        $xmls->saveXML('../Avito/ads.xml');
                        $this->db->query("DELETE FROM ".DB_PREFIX."product_to_avito WHERE vin = '".$data['vin']."' ");
                        return 0;
                    }
                    $this->avitoUpdateAd($data['options'], $sup, $xmls);
                    return 0;
                } else { 
                    ++$sup;
                }
            }
            if(!isset($data['write_off'])){
                if($data['options']['price']['value']>=500){
                    $this->avitoCreateAd($data['options'], $xmls);
                }
            }
        }
    }
    
    public function avitoUpdateAd($data, $id, $xmls) {
        $this->load->model('common/avito');
        $this->load->model('product/product');
        $settings = $this->model_common_avito->getSetts();
        $adress = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['adress']['value']."'");
        $stock = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['stock']['value']."'");
        $weekend = $adress->row['name']=='пр. Карла Маркса, 179'?'СБ, ВС - выходной':'СБ 11:00-16:00 , ВС - выходной';
        $phone = $adress->row['name']=='пр. Карла Маркса, 179'?'+7 (908) 825-52-40':'+7 (912) 475-08-70';
        $img = $adress->row['name']=='пр. Карла Маркса, 179'?'KM.jpg':'shop.jpg';
        $podcateg = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['podcateg']['value']."'");
        $brand = $this->db->query("SELECT name, translate FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['brand']['value']."'");
        $modR = $this->db->query("SELECT name, translate FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['modR']['value']."'");
        //-----------------------------------------
        $templ = htmlspecialchars_decode($this->model_common_avito->getDescTempl());
        /************************/
            $templ = str_replace('%podcat%', $podcateg->row['name'], $templ);
            if($data['brand']['value']!=='-' && $data['brand']['value']!==''){
                $templ = str_replace('%brand%', $brand->row['name'], $templ);
                $templ = str_replace('%trbrand%', $brand->row['translate'], $templ);
            } else {
                $templ = str_replace('%brand%', '', $templ);
                $templ = str_replace('%trbrand%', '', $templ);
            }
            if($data['modR']['value']!=='-' && $data['modR']['value']!==''){
                $templ = str_replace('%modrow%', $modR->row['name'], $templ);
                $templ = str_replace('%trmodrow%', $modR->row['translate'], $templ);
            } else {
                $templ = str_replace('%modrow%', '', $templ);
                $templ = str_replace('%trmodrow%', '', $templ);
            }
            $templ = str_replace('%stock%', $adress->row['name'], $templ);
            $templ = str_replace('%vin%', $data['vin'], $templ);
            if(trim($data['catn']['value'])!==''){
                $templ = str_replace('%catn%', '<li>Каталожный номер: <strong>'.$data['catn']['value'].'</strong></li>', $templ);
            } else {
                $templ = str_replace('%catn%', '', $templ);
            }
            if(trim($data['cond']['value'])!=='-'){
                $templ = str_replace('%condit%', '<li>Состояние: '.$data['cond']['value'].'</li>', $templ);
            } else {
                $templ = str_replace('%condit%', '', $templ);
            }
            if(trim($data['compability']['value'])!==''){
                $templ = str_replace('%compabil%', '<li>Подходит на: '.$data['compability']['value'].'</li>', $templ);
            } else {
                $templ = str_replace('%compabil%', '', $templ);
            }
            if(trim($data['note']['value'])!==''){
                $templ = str_replace('%note%', '<li>'.$data['note']['value'].'</li>', $templ);
            } else {
                $templ = str_replace('%note%', '', $templ);
            }
            if(trim($data['dop']['value'])!==''){
                $templ = str_replace('%dopinfo%', '<li>'.$data['dop']['value'].'</li>', $templ);
            } else {
                $templ = str_replace('%dopinfo%', '', $templ);
            }
            $templ = str_replace('%weekend%', $weekend, $templ);
    /******************************************************************/
        $dom=dom_import_simplexml($xmls->Ad[$id]->Description);
        $dom->parentNode->removeChild($dom);
    //-----------------------------------------------------------------
        $desc = $xmls->Ad[$id]->addChild('Description');
        $node = dom_import_simplexml($desc);
        $no   = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($templ));
    //------------------------------------------------------------------
        $xmls->Ad[$id]->ManagerName = 'MGN-AUTO';
        $xmls->Ad[$id]->Price = $data['price']['value'];
        $xmls->Ad[$id]->ContactPhone = $phone;
        $xmls->Ad[$id]->Title = $data['avitoname'];
        $aid = $this->model_common_avito->getPCID($data['podcateg']['value']);
        $xmls->Ad[$id]->TypeId = trim($aid);
        
    /******************************************************************/
        $domImg=dom_import_simplexml($xmls->Ad[$id]->Images);
        $domImg->parentNode->removeChild($domImg);
    //-----------------------------------------------------------------
        /******************************/
        $images = $xmls->Ad[$id]->addChild('Images');
        $image = $images->addChild('Image');
        $image->addAttribute('url', HTTP_CATALOG.'image/'.$data['image']['value']);
        /*****************************/
        $photos = $this->model_product_product->getPhotos($data['pid']);
        $count=1;
        if(!empty($photos)){
            foreach ($photos as $photo) {
                if($photo['img']!=$data['image']['value'] && $count<=3){
                    $image = $images->addChild('Image');
                    $image->addAttribute('url', HTTP_CATALOG.'image/'.$photo['img']);
                    ++$count;
                }
            }
        }
        $image = $images->addChild('Image');
        $image->addAttribute('url', HTTP_CATALOG.'image/'.$img);
        
        $xmls->saveXML('../Avito/ads.xml');
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_avito WHERE vin = '".$data['vin']."' ");
        if($sup->num_rows<1){
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_avito "
                    . "(`product_id`, `vin`, `dateStart`, `dateEnd`, `message`) VALUES "
                    . "(".$data['pid'].", '".$data['vin']."', '".date('Y-m-d', strtotime("+".$settings['sdate']." days"))."', '".date('Y-m-d', strtotime("+".$settings['edate']." days"))."', 0) ");
        }
    }
    
    public function avitoCreateAd($data, $xmls) {
//        exit(var_dump($data));
        $this->load->model('common/avito');
        $this->load->model('product/product');
        $settings = $this->model_common_avito->getSetts();
        $adress = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['adress']['value']."'");
        $stock = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['stock']['value']."'");
        $weekend = $adress->row['name']=='пр. Карла Маркса, 179'?'СБ, ВС - выходной':'СБ 11:00-16:00 , ВС - выходной';
        $phone = $adress->row['name']=='пр. Карла Маркса, 179'?'+7 (908) 825-52-40':'+7 (912) 475-08-70';
        $img = $adress->row['name']=='пр. Карла Маркса, 179'?'KM.jpg':'shop.jpg';
        $podcateg = $this->db->query("SELECT name FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['podcateg']['value']."'");
        $brand = $this->db->query("SELECT name, translate FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['brand']['value']."'");
        $modR = $this->db->query("SELECT name, translate FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['modR']['value']."'");
        //-----------------------------------------
        $templ = htmlspecialchars_decode($this->model_common_avito->getDescTempl());
        /************************/
            $templ = str_replace('%podcat%', $podcateg->row['name'], $templ);
            $templ = str_replace('%podcat%', $podcateg->row['name'], $templ);
            if($data['brand']['value']!=='-' && $data['brand']['value']!==''){
                $templ = str_replace('%brand%', $brand->row['name'], $templ);
                $templ = str_replace('%trbrand%', $brand->row['translate'], $templ);
            } else {
                $templ = str_replace('%brand%', '', $templ);
                $templ = str_replace('%trbrand%', '', $templ);
            }
            if($data['modR']['value']!=='-' && $data['modR']['value']!==''){
                $templ = str_replace('%modrow%', $modR->row['name'], $templ);
                $templ = str_replace('%trmodrow%', $modR->row['translate'], $templ);
            } else {
                $templ = str_replace('%modrow%', '', $templ);
                $templ = str_replace('%trmodrow%', '', $templ);
            }
            $templ = str_replace('%stock%', $adress->row['name'], $templ);
            $templ = str_replace('%vin%', $data['vin'], $templ);
            if(trim($data['catn']['value'])!==''){
                $templ = str_replace('%catn%', '<li>Каталожный номер: <strong>'.$data['catn']['value'].'</strong></li>', $templ);
            } else {
                $templ = str_replace('%catn%', '', $templ);
            }
            if(trim($data['cond']['value'])!=='-'){
                $templ = str_replace('%condit%', '<li>Состояние: '.$data['cond']['value'].'</li>', $templ);
            } else {
                $templ = str_replace('%condit%', '', $templ);
            }
            if(trim($data['compability']['value'])!==''){
                $templ = str_replace('%compabil%', '<li>Подходит на: '.$data['compability']['value'].'</li>', $templ);
            } else {
                $templ = str_replace('%compabil%', '', $templ);
            }
            if(trim($data['note']['value'])!==''){
                $templ = str_replace('%note%', '<li>'.$data['note']['value'].'</li>', $templ);
            } else {
                $templ = str_replace('%note%', '', $templ);
            }
            if(trim($data['dop']['value'])!==''){
                $templ = str_replace('%dopinfo%', '<li>'.$data['dop']['value'].'</li>', $templ);
            } else {
                $templ = str_replace('%dopinfo%', '', $templ);
            }
            $templ = str_replace('%weekend%', $weekend, $templ);
    /******************************************************************/
            $ad = $xmls->addChild('Ad');

                $ad->addChild('Id', $data['vin']);
                $ad->addChild('DateBegin', date('Y-m-d', strtotime("+".$settings['sdate']." days")));
                $ad->addChild('DateEnd', date('Y-m-d', strtotime("+".$settings['edate']." days")));
                $ad->addChild('ListingFee', $settings['listingfree']);
                $ad->addChild('AdStatus', $settings['adstatus']);
                $ad->addChild('AllowEmail', $settings['allowemail']);
                $ad->addChild('ManagerName', 'MGN-AUTO');
                $ad->addChild('Region', 'Челябинская область');
                $ad->addChild('City', 'Магнитогорск');
                $ad->addChild('ContactPhone', $phone);
                $ad->addChild('Category', 'Запчасти и аксессуары');
                $aid = $this->model_common_avito->getPCID($data['podcateg']['value']);
                $ad->addChild('TypeId', trim($aid));
                $ad->addChild('Title', $data['avitoname']);

                $desc = $ad->addChild('Description');
                $node = dom_import_simplexml($desc);
                $no   = $node->ownerDocument; 
                $node->appendChild($no->createCDATASection($templ));

                $ad->addChild('Price', $data['price']['value']);
                /******************************/
                $images = $ad->addChild('Images');
                $image = $images->addChild('Image');
                $image->addAttribute('url', HTTP_CATALOG.'image/'.$data['image']['value']);
                /*****************************/
                $photos = $this->model_product_product->getPhotos($data['pid']);
                $count=1;
                if(!empty($photos)){
                    foreach ($photos as $photo) {
                        if($photo['img']!=$data['image']['value'] && $count<=3){
                            $image = $images->addChild('Image');
                            $image->addAttribute('url', HTTP_CATALOG.'image/'.$photo['img']);
                            ++$count;
                        }
                    }
                }
                $image = $images->addChild('Image');
                $image->addAttribute('url', HTTP_CATALOG.'image/'.$img);
            $xmls->saveXML('../Avito/ads.xml');
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_avito "
                    . "(`product_id`, `vin`, `dateStart`, `dateEnd`, `message`) VALUES "
                    . "(".$data['pid'].", '".$data['vin']."', '".date('Y-m-d', strtotime("+".$settings['sdate']." days"))."', '".date('Y-m-d', strtotime("+".$settings['edate']." days"))."', 0) ");
    }
/*---------------------------------------------------------------------------------------------*/    
    public function ARUFind($data) {
//        exit(var_dump($data));
        if($data['structure']==1){
            $xmls = simplexml_load_file('../Avito/autoru.xml');
            $sup = 0;
            if(isset($data['write_off'])){
                foreach($xmls->part as $part){
                    if(in_array($data['vin'], (array)$part)){
                        $this->ARUsalePart($sup, $xmls);
                        return 0;
                    } else{ 
                        ++$sup;
                    }
                }
                return 0;
            }        
            foreach($xmls->part as $part){
                if(in_array($data['vin'], (array)$part)){
                    $this->ARUUpdateAd($data['options'], $sup, $xmls);
                    return 0;
                } else{ 
                    ++$sup;
                }
            }
            if($data['options']['price']['value']>0){
                $this->ARUCreateAd($data['options'], $xmls);
            }
        }
    }
    
    public function ARUCreateAd($data, $xmls) {
        $this->load->model('common/avito');
        $this->load->model('product/product');
        $this->load->model('tool/product');
        
        $templ = htmlspecialchars_decode($this->model_tool_product->getDescription($data['pid']));
        $templ = str_replace("p>", "p> ", $templ);
        $templ = str_replace($data['vin'], "", $templ);
        $templ = strip_tags($templ);
        $templ = str_replace("&nbsp;", " ", $templ);
        $templ = str_replace("\n", " ", $templ);
        $templ = preg_replace("/ +/", " ", $templ);
        //-----------------------------------------
//        exit(var_dump($xmls));
        $part = $xmls->addChild('part');
        
            $part->addChild('id', $data['vin']);
            $part->addChild('title', $data['avitoname']);
            if(isset($data['catn']) && $data['catn']!==''){
                $part->addChild('part_number', $data['catn']['value']);
            }
            if(isset($data['type']) && $data['type']!==''){
                $part->addChild('is_new', ($data['type']['value']==='Новый'?1:0));
            }
            if(isset($data['brand']['value']) && $data['brand']['value']!==''){
                $brand = $this->db->query("SELECT name, translate FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['brand']['value']."'");
                $part->addChild('manufacturer', $brand->row['name']);
            }
            $part->addChild('description', (string)trim($templ));
            $part->addChild('price', $data['price']['value']);
            $part->addChild('count', $data['quantity']['value']);
            $avail = $part->addChild('availability');
                $avail->addChild('isAvailable', $data['status']['value']);
                if(isset($data['compability']) && $data['compability']['value']!==''){
                    $part->addChild('compability', $data['compability']['value']);
                } else {
                    $part->addChild('compability', '');
                }
            /******************************/
                if(isset($data['image'])){
                    $images = $part->addChild('images');
                    $images->addChild('image', HTTP_CATALOG.'image/'.$data['image']['value']);
                    /*****************************/
                    $photos = $this->model_product_product->getPhotos($data['pid']);
                    $count=1;
                    if(!empty($photos)){
                        foreach ($photos as $photo) {
                            if($photo['img']!=$data['image']['value'] && $count<=3 && $photo['img']!=''){
                                $images->addChild('image', HTTP_CATALOG.'image/'.$photo['img']);
                                ++$count;
                            }
                        }
                    }
                }
            /******************************************************/
            $props = $part->addChild('properties');
                if(isset($data['cond']) && $data['cond']['value']!='-'){
                    $prop = $props->addChild('property', $data['cond']['value']);
                    $prop->addAttribute('name', 'Состояние');
                }
                if(isset($data['note']) && $data['note']['value']!='-'){
                    $prop = $props->addChild('property', $data['note']['value']);
                    $prop->addAttribute('name', 'Примечание');
                }
                if(isset($data['dop']) && $data['dop']['value']!='-'){
                    $prop = $props->addChild('property', $data['dop']['value']);
                    $prop->addAttribute('name', 'Дополнительная информация');
                }
        $xmls->saveXML('../Avito/autoru.xml');
    }
    
    public function ARUUpdateAd($data, $id, $xmls) {
        $this->load->model('common/avito');
        $this->load->model('product/product');
        $this->load->model('tool/product');
        if(!isset($data['pid'])){
            $sup = $this->db->query("SELECT product_id FROM ".DB_PREFIX."product WHERE vin = '".$data['vin']."'");
            $data['pid']=$sup->row['product_id'];
        }
        //-----------------------------------------
        $templ = htmlspecialchars_decode($this->model_tool_product->getDescription($data['pid']));
        $templ = str_replace("p>", "p> ", $templ);
        $templ = str_replace($data['vin'], "", $templ);
        $templ = strip_tags($templ);
        $templ = str_replace("&nbsp;", " ", $templ);
        $templ = str_replace("\n", " ", $templ);
        $templ = preg_replace("/ +/", " ", $templ);
        //-----------------------------------------
    //------------------------------------------------------------------
        
        if(isset($data['brand']['value']) && $data['brand']['value']!==''){
            $brand = $this->db->query("SELECT name, translate FROM ".DB_PREFIX."lib_fills WHERE id = '".$data['brand']['value']."'");
            $xmls->part[$id]->manufacturer = $brand->row['name'];
        }
        $xmls->part[$id]->price = $data['price']['value'];
        $xmls->part[$id]->title = $data['avitoname'];
        $xmls->part[$id]->count = $data['quantity']['value'];
        $xmls->part[$id]->availability->isAvailable = $data['status']['value'];
        $xmls->part[$id]->description = (string)$templ;
        $xmls->part[$id]->is_new = $data['type']['value']==='Новый'?1:0;
        $xmls->part[$id]->compability = isset($data['compability'])?$data['compability']['value']:'';
        
    /******************************************************************/
        $domProp=dom_import_simplexml($xmls->part[$id]->properties);
        $domProp->parentNode->removeChild($domProp);
    //-----------------------------------------------------------------
        $props = $xmls->part[$id]->addChild('properties');
            if($data['cond']['value']!='-'){
                $prop = $props->addChild('property', $data['cond']['value']);
                $prop->addAttribute('name', 'Состояние');
            }
            if($data['note']['value']!='-'){
                $prop = $props->addChild('property', $data['note']['value']);
                $prop->addAttribute('name', 'Примечание');
            }
            if(isset($data['dop']) && $data['dop']['value']!='-'){
                $prop = $props->addChild('property', $data['dop']['value']);
                $prop->addAttribute('name', 'Дополнительная информация');
            }
        
    /******************************************************************/
        $domImg=dom_import_simplexml($xmls->part[$id]->images);
        $domImg->parentNode->removeChild($domImg);
    //-----------------------------------------------------------------
        /******************************/
            $images = $xmls->part[$id]->addChild('images');
            $images->addChild('image', HTTP_CATALOG.'image/'.$data['image']['value']);
            /*****************************/
            $photos = $this->model_product_product->getPhotos($data['pid']);
            $count=1;
            if(!empty($photos)){
                foreach ($photos as $photo) {
                    if($photo['img']!=$data['image']['value'] && $count<=3 && $photo['img']!=''){
                        $images->addChild('image', HTTP_CATALOG.'image/'.$photo['img']);
                        ++$count;
                    }
                }
            }
            /******************************************************/
        
        $xmls->saveXML('../Avito/autoru.xml');
    }
    
    public function ARUsalePart($id, $xmls) {
        $xmls->part[$id]->availability->isAvailable = 0;
        $xmls->saveXML('../Avito/autoru.xml');
    }
}