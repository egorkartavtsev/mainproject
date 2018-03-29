<?php

class ModelCommonRefund extends Model {
    public function getProductInfo($sku) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."sales_info WHERE sku = '".$sku."'");
        if(!empty($sup->row)){
            return $sup->row;   
        } else {
            return FALSE;
        }
    }
    public function updateDataBase($info) {
        $pq = $this->db->query("SELECT product_id FROM ".DB_PREFIX."product WHERE vin = '".$info['sku']."'");
        $product_id = $pq->row['product_id'];
                
            $dir = DIR_IMAGE . 'catalog/demo/production/'.$info['sku'];
            $photos = scandir($dir);
            if(!empty($photos)){
                array_shift($photos);
                array_shift($photos);
                $image = 'catalog/demo/production/'.$info['sku'].'/'.$photos[0];
            } else {
                $image = '';
            }
        foreach ($photos as $photo){
            $this->db->query("INSERT INTO ". DB_PREFIX ."product_image "
                    . "SET "
                    . "product_id = ". (int)$product_id .", "
                    . "image = 'catalog/demo/production/".$info['sku']."/".$photo."' ");
        }
        if($this->db->query("INSERT INTO ".DB_PREFIX."product_history "
                . "SET "
                . "sku = '".$info['sku']."', "
                . "date_refund = '". date("Y-m-d")."', "
                . "manager = '".$info['manager']."', "
                . "reason_refund = '".$info['reason']."', "
                . "type_modify = 'Возврат товара'")){
            if($this->db->query("UPDATE ".DB_PREFIX."product SET quantity = '1', status = '1', image='".$image."' WHERE vin = '".$info['sku']."'")){
                if($this->db->query("DELETE FROM ".DB_PREFIX."sales_info WHERE sku = '".$info['sku']."'")){
                    return 'success';
                } else {
                    return 'удаление из проданных товаров';
                }
            } else {
                return 'обновить информацию в таблице товаров';
            }
        } else {
            return 'сделать запись в истории движения товара';
        }
    }
    
    public function setPhoto($vin) {
        $uploadtmpdir = DIR_IMAGE . "tmp/";
        mkdir(DIR_IMAGE . "catalog/demo/production/".$vin);
        $uploaddir = DIR_IMAGE . "catalog/demo/production/".$vin."/";

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

            

            imagejpeg($dest, $uploadtmpdir . $file['name'], 90);
            imagedestroy($dest);
            imagedestroy($source);

            copy($uploadtmpdir . $file['name'], $uploaddir .$vin.'-'. $name . '.jpg');

            unlink($uploadtmpdir . $file['name']);

            $name++;
        }
    }
}

