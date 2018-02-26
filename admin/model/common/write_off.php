<?php
class ModelCommonWriteoff extends Model {
    
    public function findProd($vin) {
        
        $query = $this->db->query("SELECT "
                    . "p.product_id AS id, "
                    . "pd.name AS name, "
                    . "p.image AS image, "
                    . "p.product_id AS id, "
                    . "p.quantity AS quan, "
                    . "p.price AS price, "
                    . "p.weight AS stock, "
                    . "p.location AS location, "
                    . "p.sku AS vin "
                . "FROM ".DB_PREFIX."product p "
                . "LEFT JOIN ".DB_PREFIX."product_description pd "
                    . "ON pd.product_id = p.product_id "
                . "WHERE p.sku = '".$vin."' ");
        //exit(var_dump($query->row));
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
    
    public function sale($prods, $id_invoice) {
        $results = '';
        $this->load->model('tool/xml');
        $this->load->model("tool/complect");
        foreach ($prods as $data) {
            $reqComplect = $this->model_tool_complect->isCompl($data['vin']);
            $results.= $data['vin'].',';
            $query = $this->db->query("SELECT product_id, comp FROM ".DB_PREFIX."product WHERE sku = '".$data['vin']."'");
            $product_id = $query->row['product_id'];
            $heading = $query->row['comp'];

            if ($this->db->query("INSERT INTO ".DB_PREFIX."sales_info "
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
                        . "manager = '".$data['manager']."'")){
                $this->model_tool_xml->findToRemove($data['vin']);
                $result = TRUE;
            } else {
                $result = FALSE;
            }
            
            $endq = $data['quan'] - $data['quanfact'];
        
            if ($result){
                /*если это товар-ссылка на комплект*/
                $query = "SELECT * FROM ".DB_PREFIX."complects WHERE link = '".$data['vin']."' ";
                $arr = $this->db->query($query);
                if(!empty($arr->row)){
                    $this->db->query("UPDATE ".DB_PREFIX."product SET quantity=0, viewed=0, status=0 WHERE comp = '".$arr->row['heading']."' OR comp = '".$arr->row['id']."'");
                    $this->db->query("DELETE FROM ".DB_PREFIX."complects WHERE link = '".$data['vin']."' ");
                    $this->db->query("DELETE FROM ".DB_PREFIX."product WHERE sku = '".$data['vin']."' ");
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
                    if($reqComplect){
                        $this->model_tool_complect->compReprice($reqComplect['complect']['heading']);
                    }
                } else {
                    $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = '".$endq."' WHERE product_id = '".$product_id."'");
                }
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

