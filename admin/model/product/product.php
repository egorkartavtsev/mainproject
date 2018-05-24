<?php
class ModelProductProduct extends Model {
    
    public function getInfo($pid) {
        $query = "SELECT "
                    . "pd.name AS name, "
                    . "b.name AS brand, "
                    . "p.brand AS brand_id, "
                    . "p.model AS model, "
                    . "p.avitoname AS avitoname, "
                    . "p.status AS status, "
                    . "p.comp_price AS comp_price, "
                    . "p.donor AS donor, "
                    . "p.comp AS complect, "
                    . "p.modR AS modRow, "
                    . "p.vin AS vin, "
                    . "p.category AS categ, "
                    . "p.image AS mainimage, "
                    . "p.podcateg AS pcat, "
                    . "p.cond AS condit, "
                    . "p.type AS type, "
                    . "p.dop AS dop, "
                    . "p.note AS note, "
                    . "p.compability AS compability, "
                    . "p.catn AS catN, "
                    . "p.location AS location, "
                    . "p.price AS price, "
                    . "p.quantity AS quantity, "
                    . "p.status AS status, "
                    . "p.avito AS avito, "
                    . "p.drom AS drom, "
                    . "p.stock AS stock "
                . "FROM ".DB_PREFIX."product p "
                . "LEFT JOIN ".DB_PREFIX."product_description pd ON pd.product_id = p.product_id "
                . "LEFT JOIN ".DB_PREFIX."brand b ON b.id = p.brand "
                . "WHERE p.product_id = ".(int)$pid;
        $arr = $this->db->query($query);
        return $arr->row;
    }
    
    public function getPhotos($pid){
        $query = $this->db->query("SELECT image, sort_order FROM ".DB_PREFIX."product_image WHERE product_id = '".$pid."' ORDER BY sort_order ");
        $result = array();
        foreach ($query->rows as $img) {
            $result[] = array(
                'img'           =>  $img['image'],
                'sort_order'    =>  $img['sort_order']
            );
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
        
        $pcat_id = $this->getPCID($product['podcat']);
        $category = $this->getCategoryName($product['category']);
        //update product
        $query = "UPDATE ".DB_PREFIX."product "
                . "SET "
                    . "brand = '".$this->db->escape($product['brand'])."', "
                    . "category = '".$this->db->escape($category)."', "
                    . "stock = '".$this->db->escape($product['stock'])."', "
                    . "price = '".$this->db->escape($product['price'])."', "
                    . "donor = '".$this->db->escape($product['donor'])."', "
                    . "avitoname = '".$this->db->escape($product['avitoname'])."', "
                    . "model = '".$this->db->escape($product['model'])."', "
                    . "podcateg = '".$this->db->escape($product['podcat'])."', "
                    . "cond = '".$this->db->escape($product['cond'])."', "
                    . "type = '".$this->db->escape($product['type'])."', "
                    . "dop = '".$this->db->escape($product['dop'])."', "
                    . "quantity = '".$this->db->escape($product['quant'])."', "
                    . "status = '".$this->db->escape($product['status'])."', "
                    . "note = '".$this->db->escape($product['note'])."', "
                    . "compability = '".$this->db->escape($product['compability'])."', "
                    . "avito = '".$this->db->escape($product['avito'])."', "
                    . "drom = '".$this->db->escape($product['drom'])."', "
                    . "modR = '".$this->db->escape($product['modRow'])."', "
                    . "location = '".$this->db->escape($product['stell'])."/".$this->db->escape($product['jar'])."/".$this->db->escape($product['shelf'])."/".$this->db->escape($product['box'])."/"."', "
                    . "image = '".$this->db->escape($product['main-image'])."', "
                    . "vin = '".$this->db->escape($product['vin'])."', "
                    . "catn = '".$this->db->escape($product['catN'])."' "
                . "WHERE product_id = ".(int)$this->db->escape($product['pid']);
        $this->db->query($query);
        
        //update name
        $tag = $brand.', '.$product['model'].', '.$product['modRow'].', '.$product['podcat'].', '.$name;
        $this->load->model('common/tempdesc');
        $description = $this->model_common_tempdesc->getTemp(1);
        $description = str_replace("%mark%", $brand, $description);
        $description = str_replace("%model%", $product['model'], $description);
        $description = str_replace("%mr%", $product['modRow'], $description);
        $description = str_replace("%podcat%", $product['podcat'], $description);
        $description = str_replace("%prim%", $product['note'], $description);
        $this->db->query("UPDATE ".DB_PREFIX."product_description "
                . "SET "
                    . "name = '".$this->db->escape($name)."', "
                    . "description = '".$description."', "
                    . "tag =  '".$tag."', "
                    . "meta_title = '" . $name . "', "
                    . "meta_h1 = '" . $name . "', "
                    . "meta_description = '" . $description . "', "
                    . "meta_keyword = '" . $tag . "' "
                . "WHERE product_id = ".(int)$this->db->escape($product['pid']));
        
        //update images
        $this->db->query("DELETE FROM ".DB_PREFIX."product_image WHERE product_id = ".(int)$this->db->escape($product['pid']));
        if((isset($product['image'])) && (!empty($product['image']))){
            foreach ($product['image'] as $img) {
                $this->db->query("INSERT INTO ".DB_PREFIX."product_image (product_id, image, sort_order) VALUES (".(int)$this->db->escape($product['pid']).", '".$this->db->escape($img['img'])."', '".$this->db->escape($img['sort-order'])."')");
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
                    $modR = $this->db->query("SELECT modR FROM ".DB_PREFIX."product WHERE product_id = ".(int)$this->db->escape($product['pid']));
                    $modR = $modR->row['modR'];
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
        $this->db->query("INSERT INTO ".DB_PREFIX."product_history ("
                . "sku, "
                . "date_modify, "
                . "manager, "
                . "type_modify) "
                . "VALUES ('".$product['vin']."', NOW(), '".$this->getManager($product['pid'])."', 'Обновление товара')");
        $this->load->model("tool/complect");
        $this->model_tool_complect->compReprice($product['vin']);
    }   
    
    public function getCatId($param) {
        $query = $this->db->query("SELECT category_id FROM ".DB_PREFIX."category_description WHERE name = '".$param."'");
        return $query->row['category_id'];
    }
    
    public function getVin($pid) {
        $quer = $this->db->query("SELECT vin FROM ".DB_PREFIX."product WHERE product_id = '".$pid."'");
        return $quer->row['vin'];
    }
    
    public function findCompl($head) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE heading = '".$head."' ");
        if(empty($sup->row)){
            return FALSE;
        } else {
            $compl = array(
                'image'     => $sup->row['image'],
                'name'      => $sup->row['name'],
                'price'     => $sup->row['price'],
                'sale'      => $sup->row['sale']
            );
            return $compl;
        }
    }
    
    public function setCompl($newItem, $heading) {
        if($this->db->query("UPDATE ".DB_PREFIX."product SET comp = '".$heading."' WHERE product_id = '".$newItem."' ")){
            $this->load->model('tool/complect');
            $this->model_tool_complect->compReprice($heading);
            return 1;
        } else {
            return 0;
        }
    }
    
    public function remCompl($item, $heading) {
        $this->db->query("UPDATE ".DB_PREFIX."product SET comp = '' WHERE product_id = '".$item."' ");
        $this->load->model('tool/complect');
        $this->model_tool_complect->compReprice($heading);
    }
    
    private function recountCompl($heading){
        $supIt = $this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE vin = '".$heading."' OR comp = '".$heading."' ");
        $supCp = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE heading = '".$heading."' ");
        $price = 0;
        
        foreach ($supIt->rows as $itPrice){
            $price+= $itPrice['price'];
        }
        $supsale = 100 - $supCp->row['sale'];
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
        $this->db->query("UPDATE ".DB_PREFIX."complects SET price = '".$price."' WHERE link = '".$supCp->row['link']."' ");
        $this->db->query("UPDATE ".DB_PREFIX."product SET price = '".$price."' WHERE vin = '".$supCp->row['link']."'");
        $this->db->query("UPDATE ".DB_PREFIX."product SET comp_price = '".$price."' WHERE vin = '".$heading."'");
    }
    
    public function getManager($id) {
        $sup = $this->db->query("SELECT manager FROM ".DB_PREFIX."product WHERE product_id = '".$id."'");
        return $sup->row['manager'];
    }
    
    public function getComplect($heading) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE heading = '".$heading."' ");
        return $sup->row;
    }
}
