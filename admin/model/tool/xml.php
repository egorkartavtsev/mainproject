<?php

class ModelToolXml extends Model {
    
    public function findAd($data) {
        $xmls = simplexml_load_file('../Avito/ads.xml');
        $sup = 0;
        foreach($xmls->Ad as $ad){
            if(in_array($data['vin'], (array)$ad)){
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
            $ad->addChild('Category', 'Запчасти и аксессуары');
            $aid = $this->model_common_avito->getPCID($data['podcat']);
            $ad->addChild('TypeId', $aid);
            $ad->addChild('Title', $data['name']);
            $ad->addChild('Description', 'Описание товара');
            $ad->addChild('Price', $data['price']);
            /******************************/
            $images = $ad->addChild('Images');
            $image = $images->addChild('Image');
            $image->addAttribute('url', HTTP_CATALOG.$data['main-image']);
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
        $xmls->Ad[$id]->Price = $data['price'];
        $xmls->Ad[$id]->Title = $data['name'];
        $aid = $this->model_common_avito->getPCID($data['podcat']);
        $xmls->Ad[$id]->TypeId = $aid;
        $xmls->saveXML('../Avito/ads.xml');
    }
    
    public function removeAd($id, $xmls) {
        
    }
    
}