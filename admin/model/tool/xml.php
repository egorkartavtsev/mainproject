<?php

class ModelToolXml extends Model {
    
    public function findAd($data) {
        $xmls = simplexml_load_file('../Avito/ads.xml');
        $sup = 0;
        foreach($xmls->Ad as $ad){
            if(in_array($data['vin'], (array)$ad)){
                if($data['price']<$settings['price']){
                    $dom=dom_import_simplexml($xmls->Ad[$sup]->Description);
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
        $this->createAd($data, $xmls);
    }
    
    public function createAd($data, $xmls) {
//        exit(var_dump($data));
        $this->load->model('common/avito');
        $this->load->model('product/product');
        $settings = $this->model_common_avito->getSetts();
        $stock = $this->db->query("SELECT * FROM ".DB_PREFIX."stocks WHERE name = '".$data['stock']."'");
        $weekend = $data['stok']=='KM'?'СБ, ВС - выходной':'СБ 11:00-16:00 , ВС - выходной';
        $phone = $data['stok']=='KM'?'+7 (908) 825-52-40':'+7 (912) 475-08-70';
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
            $templ = str_replace('%catn%', $data['catN'], $templ);
            $templ = str_replace('%condit%', $data['cond'], $templ);
            $templ = str_replace('%compabil%', $data['compability'], $templ);
            $templ = str_replace('%note%', $data['note'], $templ);
            $templ = str_replace('%dopinfo%', $data['dop'], $templ);
            $templ = str_replace('%weekend%', $weekend, $templ);
        /************************/
        
        //-----------------------------------------
        $ad = $xmls->addChild('Ad');
        
            $ad->addChild('Id', $data['vin']);
            $ad->addChild('DateBegin', date('Y-m-d', strtotime("+".$settings['sdate']." days")));
            $ad->addChild('DateEnd', date('Y-m-d', strtotime("+".$settings['edate']." days")));
            $ad->addChild('ListingFee', $settings['listingfree']);
            $ad->addChild('AdStatus', $settings['adstatus']);
            $ad->addChild('AllowEmail', $settings['allowemail']);
            if($settings['managername']!=''){
                $ad->addChild('ManagerName', $this->session->data['username']);
            } else {
                $ad->addChild('ManagerName', $data['manager']);
            }
            $ad->addChild('Region', 'Челябинская область');
            $ad->addChild('City', 'Магнитогорск');
            $ad->addChild('ContactPhone', $phone);
            $ad->addChild('Category', 'Запчасти и аксессуары');
            $aid = $this->model_common_avito->getPCID($data['podcat']);
            $ad->addChild('TypeId', $aid);
            $ad->addChild('Title', $data['avitoname']);
            
            $desc = $ad->addChild('Description', $desc);
            $node = dom_import_simplexml($desc);
            $no   = $node->ownerDocument; 
            $node->appendChild($no->createCDATASection($templ));
            
            $ad->addChild('Price', $data['price']);
            /******************************/
            $images = $ad->addChild('Images');
            $image = $images->addChild('Image');
            $image->addAttribute('url', HTTP_CATALOG.'image/'.$data['main-image']);
            /*****************************/
            $photos = $this->model_product_product->getPhotos($data['pid']);
            if(!empty($photos)){
                foreach ($photos as $photo) {
                    if($photo['img']!=$data['main-image']){
                        $image = $images->addChild('Image');
                        $image->addAttribute('url', HTTP_CATALOG.'image/'.$photo['img']);
                    }
                }
            }
        $xmls->saveXML('../Avito/ads.xml');
    }
    
    public function updateAd($data, $id, $xmls) {
        $this->load->model('common/avito');
        $this->load->model('product/product');
        $settings = $this->model_common_avito->getSetts();
        $stock = $this->db->query("SELECT * FROM ".DB_PREFIX."stocks WHERE name = '".$data['stock']."'");
        $weekend = $data['stok']=='KM'?'СБ, ВС - выходной':'СБ 11:00-16:00 , ВС - выходной';
        $phone = $data['stok']=='KM'?'+7 (908) 825-52-40':'+7 (912) 475-08-70';
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
            $templ = str_replace('%catn%', $data['catN'], $templ);
            $templ = str_replace('%condit%', $data['cond'], $templ);
            $templ = str_replace('%compabil%', $data['compability'], $templ);
            $templ = str_replace('%note%', $data['note'], $templ);
            $templ = str_replace('%dopinfo%', $data['dop'], $templ);
            $templ = str_replace('%weekend%', $weekend, $templ);
    /******************************************************************/
        $dom=dom_import_simplexml($xmls->Ad[$id]->Description);
        $dom->parentNode->removeChild($dom);
    //-----------------------------------------------------------------
        $desc = $xmls->Ad[$id]->addChild('Description', $desc);
        $node = dom_import_simplexml($desc);
        $no   = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($templ));
    //------------------------------------------------------------------
        $xmls->Ad[$id]->Price = $data['price'];
        $xmls->Ad[$id]->ContactPhone = $phone;
        $xmls->Ad[$id]->Title = $data['avitoname'];
        $aid = $this->model_common_avito->getPCID($data['podcat']);
        $xmls->Ad[$id]->TypeId = $aid;
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
    
}