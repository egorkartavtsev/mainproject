<?php

class ModelToolXml extends Model {
    
    public function findAd($data) {
        $xmls = simplexml_load_file('../Avito/ads.xml');
        $sup = 0;
        $this->load->model('common/avito');
        $settings = $this->model_common_avito->getSetts();
        foreach($xmls->Ad as $ad){
            if(in_array($data['vin'], (array)$ad)){
                if($data['price']<$settings['price']){
                    $dom=dom_import_simplexml($xmls->Ad[$sup]);
                    $dom->parentNode->removeChild($dom);
                    $xmls->saveXML('../Avito/ads.xml');
                    return 0;
                }
                $this->updateAd($data, $sup, $xmls);
                return 0;
            } else{ 
                ++$sup;
            }
        }
        if($data['price']>$settings['price']){
            $this->createAd($data, $xmls);
        }
    }
    
    public function createAd($data, $xmls) {
//        exit(var_dump($data));
        $this->load->model('common/avito');
        $this->load->model('product/product');
        $settings = $this->model_common_avito->getSetts();
        $stock = $this->db->query("SELECT * FROM ".DB_PREFIX."stocks WHERE name = '".$data['stock']."'");
        $weekend = $data['stock']=='KM'?'СБ, ВС - выходной':'СБ 11:00-16:00 , ВС - выходной';
        $phone = $data['stock']=='KM'?'+7 (908) 825-52-40':'+7 (912) 475-08-70';
        $img = $data['stock']=='KM'?'KM.jpg':'shop.jpg';
        //-----------------------------------------
        $templ = htmlspecialchars_decode($this->model_common_avito->getDescTempl());
        /************************/
            $templ = str_replace('%podcat%', $data['podcat'], $templ);
            $templ = str_replace('%brand%', $data['brandname'], $templ);
            $templ = str_replace('%modrow%', $data['mrname'], $templ);
            $templ = str_replace('%trbrand%', $data['trbrand'], $templ);
            $templ = str_replace('%trmodrow%', $data['trmodrow'], $templ);
            $templ = str_replace('%stock%', $stock->row['adress'], $templ);
            $templ = str_replace('%vin%', $data['vin'], $templ);
            if(trim($data['catN'])!==''){
                $templ = str_replace('%catn%', '<li>Каталожный номер: <strong>'.$data['catN'].'</strong></li>', $templ);
            } else {
                $templ = str_replace('%catn%', '', $templ);
            }
            if(trim($data['cond'])!=='-'){
                $templ = str_replace('%condit%', '<li>Состояние: '.$data['cond'].'</li>', $templ);
            } else {
                $templ = str_replace('%condit%', '', $templ);
            }
            if(trim($data['compability'])!==''){
                $templ = str_replace('%compabil%', '<li>Подходит на: '.$data['compability'].'</li>', $templ);
            } else {
                $templ = str_replace('%compabil%', '', $templ);
            }
            if(trim($data['note'])!==''){
                $templ = str_replace('%note%', '<li>'.$data['note'].'</li>', $templ);
            } else {
                $templ = str_replace('%note%', '', $templ);
            }
            if(trim($data['dop'])!==''){
                $templ = str_replace('%dopinfo%', '<li>'.$data['dop'].'</li>', $templ);
            } else {
                $templ = str_replace('%dopinfo%', '', $templ);
            }
            $templ = str_replace('%weekend%', $weekend, $templ);
    /******************************************************************/
        
        //-----------------------------------------
        $ad = $xmls->addChild('Ad');
        
            $ad->addChild('Id', $data['vin']);
            $ad->addChild('DateBegin', date('Y-m-d', strtotime("+".$settings['sdate']." days")));
            $ad->addChild('DateEnd', date('Y-m-d', strtotime("+".$settings['edate']." days")));
            $ad->addChild('ListingFee', $settings['listingfree']);
            $ad->addChild('AdStatus', $settings['adstatus']);
            $ad->addChild('AllowEmail', $settings['allowemail']);
            $ad->addChild('ManagerName', 'MGN-AUTO');
//            if($settings['managername']!=''){
//                $ad->addChild('ManagerName', $this->session->data['username']);
//            } else {
//                $ad->addChild('ManagerName', $data['manager']);
//            }
            $ad->addChild('Region', 'Челябинская область');
            $ad->addChild('City', 'Магнитогорск');
            $ad->addChild('ContactPhone', $phone);
            $ad->addChild('Category', 'Запчасти и аксессуары');
            $aid = $this->model_common_avito->getPCID($data['podcat']);
            $ad->addChild('TypeId', $aid);
            $ad->addChild('Title', $data['avitoname']);
            
            $desc = $ad->addChild('Description');
            $node = dom_import_simplexml($desc);
            $no   = $node->ownerDocument; 
            $node->appendChild($no->createCDATASection($templ));
            
            $ad->addChild('Price', $data['price']);
            /******************************/
            $images = $ad->addChild('Images');
            $image = $images->addChild('Image');
            $image->addAttribute('url', HTTP_CATALOG.'image/'.$data['image']);
            /*****************************/
            $photos = $this->model_product_product->getPhotos($data['pid']);
            $count=1;
            if(!empty($photos)){
                foreach ($photos as $photo) {
                    if($photo['img']!=$data['image'] && $count<=3){
                        $image = $images->addChild('Image');
                        $image->addAttribute('url', HTTP_CATALOG.'image/'.$photo['img']);
                        ++$count;
                    }
                }
            }
            $image = $images->addChild('Image');
            $image->addAttribute('url', HTTP_CATALOG.'image/'.$img);
        $xmls->saveXML('../Avito/ads.xml');
    }
    
    public function updateAd($data, $id, $xmls) {
        $this->load->model('common/avito');
        $this->load->model('product/product');
        $settings = $this->model_common_avito->getSetts();
        $stock = $this->db->query("SELECT * FROM ".DB_PREFIX."stocks WHERE name = '".$data['stock']."'");
        $weekend = $data['stock']=='KM'?'СБ, ВС - выходной':'СБ 11:00-16:00 , ВС - выходной';
        $phone = $data['stock']=='KM'?'+7 (908) 825-52-40':'+7 (912) 475-08-70';
        $img = $data['stock']=='KM'?'KM.jpg':'shop.jpg';
        //-----------------------------------------
        $templ = htmlspecialchars_decode($this->model_common_avito->getDescTempl());
        /************************/
            $templ = str_replace('%podcat%', $data['podcateg'], $templ);
            $templ = str_replace('%brand%', $data['brandname'], $templ);
            $templ = str_replace('%modrow%', $data['mrname'], $templ);
            $templ = str_replace('%trbrand%', $data['trbrand'], $templ);
            $templ = str_replace('%trmodrow%', $data['trmodrow'], $templ);
            $templ = str_replace('%stock%', $stock->row['adress'], $templ);
            $templ = str_replace('%vin%', $data['vin'], $templ);
            if(trim($data['catN'])!==''){
                $templ = str_replace('%catn%', '<li>Каталожный номер: <strong>'.$data['catN'].'</strong></li>', $templ);
            } else {
                $templ = str_replace('%catn%', '', $templ);
            }
            if(trim($data['cond'])!=='-'){
                $templ = str_replace('%condit%', '<li>Состояние: '.$data['cond'].'</li>', $templ);
            } else {
                $templ = str_replace('%condit%', '', $templ);
            }
            if(trim($data['compability'])!==''){
                $templ = str_replace('%compabil%', '<li>Подходит на: '.$data['compability'].'</li>', $templ);
            } else {
                $templ = str_replace('%compabil%', '', $templ);
            }
            if(trim($data['note'])!==''){
                $templ = str_replace('%note%', '<li>'.$data['note'].'</li>', $templ);
            } else {
                $templ = str_replace('%note%', '', $templ);
            }
            if(trim($data['dop'])!==''){
                $templ = str_replace('%dopinfo%', '<li>'.$data['dop'].'</li>', $templ);
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
        $xmls->Ad[$id]->Price = $data['price'];
        $xmls->Ad[$id]->ContactPhone = $phone;
        $xmls->Ad[$id]->Title = $data['avitoname'];
        $aid = $this->model_common_avito->getPCID($data['podcat']);
        $xmls->Ad[$id]->TypeId = $aid;
        
    /******************************************************************/
        $domImg=dom_import_simplexml($xmls->Ad[$id]->Images);
        $domImg->parentNode->removeChild($domImg);
    //-----------------------------------------------------------------
        /******************************/
        $images = $xmls->Ad[$id]->addChild('Images');
        $image = $images->addChild('Image');
        $image->addAttribute('url', HTTP_CATALOG.'image/'.$data['image']);
        /*****************************/
        $photos = $this->model_product_product->getPhotos($data['pid']);
        $count=1;
        if(!empty($photos)){
            foreach ($photos as $photo) {
                if($photo['img']!=$data['image'] && $count<=3){
                    $image = $images->addChild('Image');
                    $image->addAttribute('url', HTTP_CATALOG.'image/'.$photo['img']);
                    ++$count;
                }
            }
        }
        $image = $images->addChild('Image');
        $image->addAttribute('url', HTTP_CATALOG.'image/'.$img);
        
        $xmls->saveXML('../Avito/ads.xml');
    }
    
    public function findToRemove($vin) {
        $xmls = simplexml_load_file('../Avito/ads.xml');
        $sup = 0;
        foreach($xmls->Ad as $ad){
            if(in_array($vin, (array)$ad)){
                $dom=dom_import_simplexml($xmls->Ad[$sup]);
                $dom->parentNode->removeChild($dom);
                $xmls->saveXML('../Avito/ads.xml');
                return 0;
            } else{ 
                ++$sup;
            }
        }
    }
    
    public function UpdateXMLDesc($prods) {
        $xmls = simplexml_load_file('../Avito/ads.xml');
        foreach ($prods as $data) {
            $id = 0;
            foreach($xmls->Ad as $ad){
                if(in_array($data['vin'], (array)$ad)){
                    $brand = $this->db->query("SELECT name, transcript FROM ".DB_PREFIX."brand WHERE id = '".$data['brand']."'");
                    $data['trbrand'] = $brand->row['transcript'];
                    $data['brandname'] = $brand->row['name'];
                    $data['trmodrow'] = '';
                    $data['mrname'] = $data['modRow'];
                    $this->load->model('common/avito');
                    $stock = $this->db->query("SELECT * FROM ".DB_PREFIX."stocks WHERE name = '".$data['stock']."'");
                    $weekend = $data['stock']=='KM'?'СБ, ВС - выходной':'СБ 11:00-16:00 , ВС - выходной';
                    $phone = $data['stock']=='KM'?'+7 (908) 825-52-40':'+7 (912) 475-08-70';
                    $img = $data['stock']=='KM'?'KM.jpg':'shop.jpg';
                    //-----------------------------------------
                    $templ = htmlspecialchars_decode($this->model_common_avito->getDescTempl());
                    /************************/
                        $templ = str_replace('%podcat%', $data['podcat'], $templ);
                        $templ = str_replace('%brand%', $data['brandname'], $templ);
                        $templ = str_replace('%modrow%', $data['mrname'], $templ);
                        $templ = str_replace('%trbrand%', $data['trbrand'], $templ);
                        $templ = str_replace('%trmodrow%', $data['trmodrow'], $templ);
                        $templ = str_replace('%stock%', $stock->row['adress'], $templ);
                        $templ = str_replace('%vin%', $data['vin'], $templ);
                        if(trim($data['catN'])!==''){
                            $templ = str_replace('%catn%', '<li>Каталожный номер: <strong>'.$data['catN'].'</strong></li>', $templ);
                        } else {
                            $templ = str_replace('%catn%', '', $templ);
                        }
                        if(trim($data['cond'])!=='-'){
                            $templ = str_replace('%condit%', '<li>Состояние: '.$data['cond'].'</li>', $templ);
                        } else {
                            $templ = str_replace('%condit%', '', $templ);
                        }
                        if(trim($data['compability'])!==''){
                            $templ = str_replace('%compabil%', '<li>Подходит на: '.$data['compability'].'</li>', $templ);
                        } else {
                            $templ = str_replace('%compabil%', '', $templ);
                        }
                        if(trim($data['note'])!==''){
                            $templ = str_replace('%note%', '<li>'.$data['note'].'</li>', $templ);
                        } else {
                            $templ = str_replace('%note%', '', $templ);
                        }
                        if(trim($data['dop'])!==''){
                            $templ = str_replace('%dopinfo%', '<li>'.$data['dop'].'</li>', $templ);
                        } else {
                            $templ = str_replace('%dopinfo%', '', $templ);
                        }
                        $templ = str_replace('%weekend%', $weekend, $templ);
                /******************************************************************/
                    $dom=dom_import_simplexml($xmls->Ad[$id]->Description);
//                    echo var_dump($xmls->Ad[$id]->Description).'<br>';
                    $dom->parentNode->removeChild($dom);
                //-----------------------------------------------------------------
                    $desc = $xmls->Ad[$id]->addChild('Description');
                    $node = dom_import_simplexml($desc);
                    $no   = $node->ownerDocument;
                    $node->appendChild($no->createCDATASection($templ));
                //------------------------------------------------------------------
                } else{ 
                    ++$id;
                }
            }
        }
//        exit();
        $xmls->saveXML('../Avito/ads.xml');
    }
    
    public function findARPart($data) {
        $xmls = simplexml_load_file('../Avito/autoru.xml');
        $sup = 0;
        //exit(var_dump($xmls));        
        foreach($xmls->part as $part){
            if(in_array($data['vin'], (array)$part)){
                if(isset($data['write_off'])){$this->saleARpart($vin, $sup); exit(var_dump($data));}
                $this->updateARPart($data, $sup, $xmls);
                return 0;
            } else{ 
                ++$sup;
            }
        }
        if($data['price']>0){
            $this->createARPart($data, $xmls);
        }
    }
    
    public function createARPart($data, $xmls) {
        $this->load->model('common/avito');
        $this->load->model('product/product');
        $this->load->model('tool/product');
        if(!isset($data['pid'])){
            $sup = $this->db->query("SELECT product_id FROM ".DB_PREFIX."product WHERE vin = '".$data['vin']."'");
            $data['pid']=$sup->row['product_id'];
        }
        //exit(var_dump($data));
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
            if($data['catn']!==''){
                $part->addChild('part_number', $data['catn']);
            }
            $part->addChild('description', (string)trim($templ));
            $part->addChild('is_new', ($data['type']==='Новый'?1:0));
            $part->addChild('price', $data['price']);
            $part->addChild('manufacturer', $data['brand']);
            $part->addChild('count', $data['quantity']);
            $avail = $part->addChild('availability');
                $avail->addChild('isAvailable', $data['status']);
                if(isset($data['compability']) && $data['compability']!==''){
                    $part->addChild('compability', $data['compability']);
                } else {
                    $part->addChild('compability', '');
                }
            /******************************/
            $images = $part->addChild('images');
            $images->addChild('image', HTTP_CATALOG.'image/'.$data['image']);
            /*****************************/
            $photos = $this->model_product_product->getPhotos($data['pid']);
            $count=1;
            if(!empty($photos)){
                foreach ($photos as $photo) {
                    if($photo['img']!=$data['image'] && $count<=3 && $photo['img']!=''){
                        $images->addChild('image', HTTP_CATALOG.'image/'.$photo['img']);
                        ++$count;
                    }
                }
            }
            /******************************************************/
            $props = $part->addChild('properties');
                if($data['cond']!='-'){
                    $prop = $props->addChild('property', $data['cond']);
                    $prop->addAttribute('name', 'Состояние');
                }
                if($data['note']!='-'){
                    $prop = $props->addChild('property', $data['note']);
                    $prop->addAttribute('name', 'Примечание');
                }
                if(isset($data['dop']) && $data['dop']!='-'){
                    $prop = $props->addChild('property', $data['dop']);
                    $prop->addAttribute('name', 'Дополнительная информация');
                }
        $xmls->saveXML('../Avito/autoru.xml');
    }
    
    public function updateARPart($data, $id, $xmls) {
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
        $xmls->part[$id]->manufactutrer = $data['brand'];
        $xmls->part[$id]->Price = $data['price'];
        $xmls->part[$id]->title = $data['avitoname'];
        $xmls->part[$id]->count = $data['quantity'];
        $xmls->part[$id]->availability->isAvailable = $data['status'];
        $xmls->part[$id]->description = (string)$templ;
        $xmls->part[$id]->is_new = $data['type']==='Новый'?1:0;
        $xmls->part[$id]->compability = isset($data['compability'])?$data['compability']:'';
        $xmls->part[$id]->title = $data['avitoname'];
        
    /******************************************************************/
        $domProp=dom_import_simplexml($xmls->part[$id]->properties);
        $domProp->parentNode->removeChild($domProp);
    //-----------------------------------------------------------------
        $props = $xmls->part[$id]->addChild('properties');
            if($data['cond']!='-'){
                $prop = $props->addChild('property', $data['cond']);
                $prop->addAttribute('name', 'Состояние');
            }
            if($data['note']!='-'){
                $prop = $props->addChild('property', $data['note']);
                $prop->addAttribute('name', 'Примечание');
            }
            if(isset($data['dop']) && $data['dop']!='-'){
                $prop = $props->addChild('property', $data['dop']);
                $prop->addAttribute('name', 'Дополнительная информация');
            }
        
    /******************************************************************/
        $domImg=dom_import_simplexml($xmls->part[$id]->images);
        $domImg->parentNode->removeChild($domImg);
    //-----------------------------------------------------------------
        /******************************/
            $images = $xmls->part[$id]->addChild('images');
            $images->addChild('image', HTTP_CATALOG.'image/'.$data['image']);
            /*****************************/
            $photos = $this->model_product_product->getPhotos($data['pid']);
            $count=1;
            if(!empty($photos)){
                foreach ($photos as $photo) {
                    if($photo['img']!=$data['image'] && $count<=3 && $photo['img']!=''){
                        $images->addChild('image', HTTP_CATALOG.'image/'.$photo['img']);
                        ++$count;
                    }
                }
            }
            /******************************************************/
        
        $xmls->saveXML('../Avito/autoru.xml');
    }
    
    public function saleARpart($id, $xmls) {
        $xmls->part[$id]->availability->isAvailable = 0;
        $xmls->saveXML('../Avito/autoru.xml');
    }
}