<?php
class ModelProductProduct extends Model {
    
    public function getInfo($pid) {
        $query = "SELECT "
                    . "pd.name AS name, "
                    . "b.name AS brand, "
                    . "p.manufacturer_id AS brand_id, "
                    . "p.model AS model, "
                    . "p.status AS status, "
                    . "p.height AS donor, "
                    . "p.comp AS complect, "
                    . "p.length AS modRow, "
                    . "p.sku AS vin, "
                    . "p.category AS categ, "
                    . "p.image AS mainimage, "
                    . "p.podcateg AS pcat, "
                    . "p.upc AS condit, "
                    . "p.ean AS type, "
                    . "p.width AS dop, "
                    . "p.jan AS note, "
                    . "p.compability AS compability, "
                    . "p.isbn AS catN, "
                    . "p.location AS location, "
                    . "p.price AS price, "
                    . "p.quantity AS quantity, "
                    . "p.status AS status, "
                    . "p.avito AS avito, "
                    . "p.drom AS drom, "
                    . "p.weight AS stock "
                . "FROM ".DB_PREFIX."product p "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON pd.product_id = p.product_id "
                . "LEFT JOIN ".DB_PREFIX."brand b ON b.id = p.manufacturer_id "
                . "WHERE p.product_id = ".(int)$pid;
        $arr = $this->db->query($query);
        return $arr->row;
    }
    
    public function getPhotos($pid){
        $query = $this->db->query("SELECT image FROM ".DB_PREFIX."product_image WHERE product_id = '".$pid."' ");
        $result = array();
        foreach ($query->rows as $img) {
            $result[] = $img['image'];
        }
        return $result;
    }

    public function getModels($par, $mr) {
        if($mr){
            $query = $this->db->query("SELECT id FROM ".DB_PREFIX."brand WHERE name = '".$par."' AND parent_id!=0");
            $par = $query->row['id'];
        }
        $query = "SELECT name FROM ".DB_PREFIX."brand WHERE parent_id = '".$par."' ";
        $arr = $this->db->query($query);
        $result = array();
        foreach ($arr->rows as $mod){
            $result[] = $mod['name'];
        }
        return $result;
    }
    
    public function getPCs($par) {
        $query = "SELECT cd.name AS name FROM ".DB_PREFIX."category_description cd LEFT JOIN ".DB_PREFIX."category c ON c.category_id = cd.category_id WHERE c.parent_id = '".$par."' ORDER BY cd.name";
        $arr = $this->db->query($query);
        $result = array();
        foreach ($arr->rows as $mod){
            $result[] = $mod['name'];
        }
        return $result;
    }
    
    public function getMId($name) {
        $name = htmlspecialchars($name);
        $search = array("&gt;", ">", "&amp;", "gt;");
        $name = str_replace($search, "", $name);
        $name = htmlspecialchars_decode($name);
        $req = "SELECT id FROM ".DB_PREFIX."brand WHERE LOCATE('". $name ."', name) ";
        $query = $this->db->query($req);
//        exit(var_dump($query->row['id']));
        return $query->row['id'];
    }
    
    private function getCategoryName($cat) {
        $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = '".$cat."'");
        return $quer->row['name'];
    }
    
    private function getPCID($pcat) {
        $quer = $this->db->query("SELECT category_id AS id FROM ".DB_PREFIX."category_description WHERE name = '".$pcat."'");
        return $quer->row['id'];
    }

    public function updateProduct($product) {
        //universal product?
        $name = $product['podcat'];
        $univ = array(
            'brand' => FALSE,
            'model' => FALSE,
            'mr'    => FALSE
        );
        if($product['brand']==='univ'){
            $univ['brand'] = TRUE;
            $univ['model'] = TRUE;
            $univ['mr'] = TRUE;
            $name.= ' '.$product['dop'];
        } else {
            $quer = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE id = ".(int)$product['brand']);
            $brand = $quer->row['name'];
            $name.= ' '.$brand.' '.$product['dop'];
            if($product['model']==='univ'){
                $product['model'] = 'Универсальный';
                $product['modRow'] = 'Универсальный';
                $univ['model'] = TRUE;
                $univ['mr'] = TRUE;
            } else {
                $model_id = $this->getMId($product['model']);
                if ($product['modRow']==='univ'){
                    $product['modRow'] = 'Универсальный';
                    $univ['mr'] = TRUE;
                } else {
                    $name = $product['podcat'].' '.$brand.' '.$product['modRow'];
                }
            }
        }
        
        //pereschet ceny komplecta
            if($product['complect']!=''){
                $query = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE heading = '".$product['vin']."' ");
                if(empty($query->row)){
                    $query = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE heading = '".$product['complect']."' ");
                    $sale = $query->row['sale'];
                    if($sale == 0){
                        $sale = 15;
                    }
                    $link = $query->row['link'];
                    $qcomp = $this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE comp = '".$product['complect']."' AND product_id != '".$product['pid']."' OR sku = '".$product['complect']."' AND product_id != '".$product['pid']."'");
                    $items = $qcomp->rows;
                    $price = $product['price'];
                    foreach($items as $item){
                        $price+=$item['price'];
                    }
                    $supsale = 100 - $sale;
                    $supsale = $supsale/100;
                    $price = ceil($price*$supsale);
                    //okruglenie
                        if($price<500){
                            $rvr = $price%100;
                            if($rvr>0){
                                $rvr = 50 - $rvr;
                                $price = $price + $rvr;
                                if($sale%10!=0){
                                    $helper = $price%100;
                                    $price = $price+(100-$helper);
                                }
                            }
                        } else {
                            $rvr = $price%100;
                            $rvr = 100 - $rvr;
                            $price = $price + $rvr;
                            if($sale%10!=0){
                                $helper = $price%100;
                                $price = $price+(100-$helper);
                            }
                        }
                    //---------------
                    $this->db->query("UPDATE ".DB_PREFIX."complects SET price = '".$price."' WHERE link = '".$link."' ");
                    $this->db->query("UPDATE ".DB_PREFIX."product SET price = '".$price."' WHERE sku = '".$link."'");
                    $this->db->query("UPDATE ".DB_PREFIX."product SET comp_price = '".$price."' WHERE sku = '".$product['complect']."'");
                } else {
                    $sale = $query->row['sale'];
                    if($sale == 0){
                        $sale = 15;
                    }
                    $link = $query->row['link'];
                    $qcomp = $this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE comp = '".$product['vin']."' ");
                    $items = $qcomp->rows;
                    $price = $product['price'];
                    foreach($items as $item){
                        $price+=$item['price'];
                    }
                    $supsale = 100 - $sale;
                    $supsale = $supsale/100;
                    $price = ceil($price*$supsale);
                    //okruglenie
                        if($price<500){
                            $rvr = $price%100;
                            if($rvr>0){
                                $rvr = 50 - $rvr;
                                $price = $price + $rvr;
                                if($sale%10!=0){
                                    $helper = $price%100;
                                    $price = $price+(100-$helper);
                                }
                            }
                        } else {
                            $rvr = $price%100;
                            $rvr = 100 - $rvr;
                            $price = $price + $rvr;
                            if($sale%10!=0){
                                $helper = $price%100;
                                $price = $price+(100-$helper);
                            }
                        }
                    //---------------
                    $this->db->query("UPDATE ".DB_PREFIX."complects SET price = '".$price."' WHERE link = '".$link."' ");
                    $this->db->query("UPDATE ".DB_PREFIX."product SET price = '".$price."' WHERE sku = '".$link."'");
                    $this->db->query("UPDATE ".DB_PREFIX."product SET comp_price = '".$price."' WHERE sku = '".$product['vin']."'");
                }

            }
            
        //--------------------------
        
        
        $pcat_id = $this->getPCID($product['podcat']);
        $category = $this->getCategoryName($product['category']);
        //update product
        $query = "UPDATE ".DB_PREFIX."product "
                . "SET "
                    . "manufacturer_id = '".$this->db->escape($product['brand'])."', "
                    . "category = '".$this->db->escape($category)."', "
                    . "weight = '".$this->db->escape($product['stock'])."', "
                    . "price = '".$this->db->escape($product['price'])."', "
                    . "height = '".$this->db->escape($product['donor'])."', "
                    . "model = '".$this->db->escape($product['model'])."', "
                    . "podcateg = '".$this->db->escape($product['podcat'])."', "
                    . "upc = '".$this->db->escape($product['cond'])."', "
                    . "ean = '".$this->db->escape($product['type'])."', "
                    . "width = '".$this->db->escape($product['dop'])."', "
                    . "quantity = '".$this->db->escape($product['quant'])."', "
                    . "status = '".$this->db->escape($product['status'])."', "
                    . "jan = '".$this->db->escape($product['note'])."', "
                    . "compability = '".$this->db->escape($product['compability'])."', "
                    . "avito = '".$this->db->escape($product['avito'])."', "
                    . "drom = '".$this->db->escape($product['drom'])."', "
                    . "length = '".$this->db->escape($product['modRow'])."', "
                    . "location = '".$this->db->escape($product['stell'])."/".$this->db->escape($product['jar'])."/".$this->db->escape($product['shelf'])."/".$this->db->escape($product['box'])."/"."', "
                    . "image = '".$this->db->escape($product['main-image'])."', "
                    . "sku = '".$this->db->escape($product['vin'])."', "
                    . "isbn = '".$this->db->escape($product['catN'])."' "
                . "WHERE product_id = ".(int)$this->db->escape($product['pid']);
        $this->db->query($query);
        
        //update name
        $this->db->query("UPDATE ".DB_PREFIX."product_description "
                . "SET "
                    . "name = '".$this->db->escape($name)."' "
                . "WHERE product_id = ".(int)$this->db->escape($product['pid']));
        
        //update images
        $this->db->query("DELETE FROM ".DB_PREFIX."product_image WHERE product_id = ".(int)$this->db->escape($product['pid']));
        if((isset($product['image'])) && (!empty($product['image']))){
            foreach ($product['image'] as $img) {
                $this->db->query("INSERT INTO ".DB_PREFIX."product_image (product_id, image) VALUES (".(int)$this->db->escape($product['pid']).", '".$this->db->escape($img)."')");
            }
        }
        //update category-links
        $this->db->query("DELETE FROM ".DB_PREFIX."product_to_category WHERE product_id = ".(int)$this->db->escape($product['pid']));
        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_category (product_id, category_id, main_category) VALUES (".(int)$this->db->escape($product['pid']).", '".(int)$this->db->escape($product['category'])."', 1)");
        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_category (product_id, category_id) VALUES (".(int)$this->db->escape($product['pid']).", '".(int)$this->db->escape($pcat_id)."')");
        //update brand-links
        $this->db->query("DELETE FROM ".DB_PREFIX."product_to_brand WHERE product_id = ".(int)$this->db->escape($product['pid']));
        if(!$univ['brand']){
            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand (product_id, brand_id) VALUES (".(int)$this->db->escape($product['pid']).", '".(int)$this->db->escape($product['brand'])."')");
            if(!$univ['model']){
                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand (product_id, brand_id) VALUES (".(int)$this->db->escape($product['pid']).", '".(int)$this->db->escape($model_id)."')");
                if(!$univ['mr']){
                    $modR = $this->db->query("SELECT length FROM ".DB_PREFIX."product WHERE product_id = ".(int)$this->db->escape($product['pid']));
                    $modR = $modR->row['length'];
                    $modR_id = $this->getMId($modR);
                    $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand (product_id, brand_id) VALUES (".(int)$this->db->escape($product['pid']).", '".(int)$this->db->escape($modR_id)."')");
                } else {
                    $quer = $this->db->query("SELECT * FROM ".DB_PREFIX."brand WHERE parent_id = ".(int)$model_id);
                    foreach ($quer->rows as $cpb) {
                        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand (product_id, brand_id) VALUES (".(int)$this->db->escape($product['pid']).", '".(int)$this->db->escape($cpb['id'])."')");
                    }
                }
            } else {
                $quer = $this->db->query("SELECT * FROM ".DB_PREFIX."brand WHERE parent_id = ".(int)$product['brand']);
                foreach ($quer->rows as $cpb) {
                    $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand (product_id, brand_id) VALUES (".(int)$this->db->escape($product['pid']).", '".(int)$this->db->escape($cpb['id'])."')");
                }
            }
        } else {
            $quer = $this->db->query("SELECT * FROM ".DB_PREFIX."brand WHERE 1");
            foreach ($quer->rows as $cpb) {
                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand (product_id, brand_id) VALUES (".(int)$this->db->escape($product['pid']).", '".(int)$this->db->escape($cpb['id'])."')");
            }
        }
        //apply compability
        $help = explode(";", trim($product['compability']));
        foreach ($help as $cpbItem) {
            if($cpbItem!=''){
                $this->db->query("INSERT INTO ".DB_PREFIX."product_to_brand (product_id, brand_id) VALUES (".(int)$this->db->escape($product['pid']).", '".(int)$this->getMId(trim($cpbItem))."')");
            }
        }
    }   
    
    public function getCatId($param) {
        $query = $this->db->query("SELECT category_id FROM ".DB_PREFIX."category_description WHERE name = '".$param."'");
        return $query->row['category_id'];
    }
    
    public function getVin($pid) {
        $quer = $this->db->query("SELECT sku FROM ".DB_PREFIX."product WHERE product_id = '".$pid."'");
        return $quer->row['sku'];
    }
}
