<?php
class ModelCommonWriteoff extends Model {
    
    public function findProd($vin) {
        
        $query = $this->db->query("SELECT * "
                . "FROM ".DB_PREFIX."product p "
                . "LEFT JOIN ".DB_PREFIX."product_description pd "
                    . "ON pd.product_id = p.product_id "
                . "WHERE p.vin = '".$vin."' ");
        return $query->row;
    }
    
    public function isComplect($id) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE link = '".$id."' ");
        if(empty($query->row)){
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function isCompl($vin) {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."complects WHERE link = '".$vin."' ");
        $result = array();
        if($sup->num_rows){
            $qComp = $this->db->query("SELECT * "
                    . "FROM ".DB_PREFIX."product p "
                    . "LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                    . "WHERE (comp = '".$sup->row['heading']."' OR vin = '".$sup->row['heading']."') "
                        . "AND quantity != 0 "
                        . "AND status = 1 ");
            foreach ($qComp->rows as $prod){
                $result[$prod['vin']] = array(
                    'vin'       => $prod['vin'],
                    'location'  => $prod['location'],
                    'stock'     => $prod['stock'],
                    'name'      => '(Комплект)'.$prod['name'],
                    'price'     => $prod['price'],
                    'image'     => $prod['image'],
                    'quantity'  => $prod['quantity'],
                    'reason'    => 'Продажа комплекта',
                    'fact_price'=> 0
                );
            }
            if(count($result)){
                return $this->repriceForCompl($result, $sup->row);
            } else {
                return $result;
            }
        } else {
            return FALSE;
        }
    }
    
    public function repriceForCompl($prods, $compl) {
        if(count($prods)){
            $sale = (int)$compl['sale']?$compl['sale']:15;
            $total = 0;
            $min = '';
            foreach ($prods as $vin => $prod){
                $prods[$vin]['fact_price'] = ($prod['price']*(100 - $sale))/100;
                $total+= $prods[$vin]['fact_price'];
                $min = $vin;
            }
            $dif = $compl['price'] - $total;
            if(isset($prods[$compl['heading']])){
                $prods[$compl['heading']]['fact_price']+= $dif;
                $prods[$compl['heading']]['name'].= '(Головной)';
            } else {
                $prods[$min]['fact_price']+= $dif;       
            }
        }
        return $prods;
    }
    
    public function sale($prods, $id_invoice) {
        $results = '';
        $this->load->model('tool/xml');
        $this->load->model("tool/complect");
        $this->load->model("tool/excel");
        foreach ($prods as $data) {
            $reqComplect = $this->model_tool_complect->isCompl($data['vin']);
            $results.= $data['vin'].',';
            $query = $this->db->query("SELECT product_id, comp FROM ".DB_PREFIX."product WHERE vin = '".$data['vin']."'");
            $product_id = $query->row['product_id'];
            $heading = $query->row['comp'];

            $this->db->query("INSERT INTO ".DB_PREFIX."sales_info "
                    . "SET "
                        . "name = '".$data['name']."', "
                        . "invoice = '".$id_invoice."', "
                        . "sku = '".$data['vin']."', "
                        . "city = '".$data['city']."', "
                        . "client = '".$data['client']."', "
                        . "summ = '".$data['summ']."', "
                        . "wherefrom = '".$data['wherefrom']."', "
                        . "loc = '".$data['location']."', "
                        . "saleprice = '".$data['saleprice']."', "
                        . "price = '".$data['price']."', "
                        . "reason = '".$data['reason']."', "
                        . "date = '".$data['date']."', "
                        . "date_mod = NOW(), "
                        . "manager = '".$data['manager']."'");
            $endq = $data['quan'] - $data['quanfact'];
            
            $this->db->query("INSERT INTO ".DB_PREFIX."product_history ("
                . "sku, "
                . "date_sale, "
                . "manager, "
                . "type_modify) "
                . "VALUES ('".$data['vin']."', NOW(), '".$data['manager']."', 'Списание товара')");
        
            /*если это товар-ссылка на комплект*/
            $query = "SELECT * FROM ".DB_PREFIX."complects WHERE link = '".$data['vin']."' ";
            $arr = $this->db->query($query);
            if(!empty($arr->row)){
                $this->db->query("UPDATE ".DB_PREFIX."product SET quantity=0, viewed=0, status=0 WHERE comp = '".$arr->row['heading']."' OR comp = '".$arr->row['id']."'");
                $this->db->query("DELETE FROM ".DB_PREFIX."complects WHERE link = '".$data['vin']."' ");
                $this->db->query("DELETE FROM ".DB_PREFIX."product WHERE vin = '".$data['vin']."' ");
            }

            /*если головной*/
            if($reqComplect && $reqComplect['heading']){
                $this->db->query("UPDATE ".DB_PREFIX."product SET comp='' WHERE comp = '".$data['vin']."'");
                $this->db->query("DELETE FROM ".DB_PREFIX."complects WHERE heading = '".$data['vin']."' ");
            }

            if($endq===0){
                $this->db->query("DELETE FROM ".DB_PREFIX."product_image WHERE product_id = ".(int)$product_id);
                $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = '".$endq."', status = 0, image = '', comp='', comp_price='' WHERE product_id = '".$product_id."'");
                $dir = DIR_IMAGE."catalog/demo/production/".$data['vin']."/";
                if(is_dir($dir)){
                    $this->removeDirectory($dir);
                }
                if($reqComplect && $this->model_tool_complect->checkCompl($reqComplect['complect']['heading'])){
                    $this->model_tool_complect->compReprice($reqComplect['complect']['heading']);
                }
            } else {
                $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = '".$endq."' WHERE product_id = '".$product_id."'");
            }
        }
        return $results;
    }
    
    public function getSales(){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."sales_info WHERE 1");
        return $query->rows;
    }
    
    private function removeDirectory($dir) {
		$objs = scandir($dir);
            //??????я так захотел**************
                $fuck = array_shift($objs);
                $fuck = array_shift($objs);
            //*********************************
		
		foreach($objs as $obj) {
				$objct = $dir;
				$objct.= $obj;
                unlink($objct);
        }
		rmdir($dir);
    }
    
//    public function saleListProds($info) {
//        //exit(var_dump($info));
//        
//        
//        $data = $this->getLayout();
//        $data['token_wo'] = $this->session->data['token'];
//        $this->response->setOutput($this->load->view('common/write_off_form', $data));
//    }
    
    public function getLayout() {

        $this->load->language('common/write_off');

        $this->document->setTitle($this->language->get('heading_title'));
        $data['notice'] = $this->language->get('notice');
        $data['lable_vn'] = $this->language->get('lable_vn');
        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/write_off', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token_em'] = $this->session->data['token'];
        return $data;

    }
}

