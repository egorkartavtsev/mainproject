<?php
class ControllerCommonWriteOff extends Controller {
    public function index() {
        $data = $this->getLayout();
        $data['token_wo'] = $this->session->data['token'];
        if(!empty($this->request->post)){
            $this->load->model('common/write_off');
//            exit(var_dump($this->request->post));
            $this->load->controller('common/excelTools');
            $tools = new ControllerCommonExcelTools($this->registry);
            $info = $tools->constructSaleArray($this->request->post);
            $id_invoice = uniqid('r_');
            $prods = $this->model_common_write_off->sale($info, $id_invoice);
            $this->load->model('tool/xml');
            foreach ($info as $item) {
                $APinfo = array('vin' => $item['vin'], 'write_off' => 1, 'price' => $item['price']);
                $this->model_tool_xml->findARPart($APinfo);
                $this->model_tool_xml->findToRemove($item['vin']);
            }
            $this->response->setOutput($this->load->view('common/write_off_form', $data));
//            $this->response->redirect($this->url->link('common/write_off/downloadInvoice', 'token=' . $this->session->data['token'] . '&products=' . $prods . '&invoice=' .$id_invoice, true));
        }
        $this->response->setOutput($this->load->view('common/write_off_form', $data));
    }
    
    public function downloadInvoice() {
        
        $prod_arr = explode(',', $this->request->get['products']);
        $products = array();
        foreach($prod_arr as $prod){
            if($prod!=''){
                $products[] = $prod;
            }
        }
        $this->load->controller('common/excelTools');
        $tools = new ControllerCommonExcelTools($this->registry);
        $id_invoice = $this->request->get['invoice'];
        $info = $this->constructInfo($products);
        exit(var_dump($info));
        
        $tools->createInvoice($info, $id_invoice);
        
    }
    
    public function constructInfo($products) {
        $prod_info = array();
        $sql = 'SELECT p.price, pd.name, p.sku AS vin, p.quantity, p.location, p.comp FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON p.product_id = pd.product_id WHERE 0 ';
        $query = $this->db->query("SELECT firstname, lastname FROM ".DB_PREFIX."user WHERE user_id = '".$this->session->data['user_id']."'");
        $manager = $query->row['firstname'].' '.$query->row['lastname'];
        $info = array(
            'client'    => $manager,
            'city'      => 'Магнитогорск',
            'date'      => date("Y-m-d H:i:s"),
            'products'  => array()
        );
        foreach ($products as $prod) {
            $sql.= "OR p.sku = '".$prod."' ";
        }
        $sql.="ORDER BY p.product_id ";
        $quer = $this->db->query($sql);
        $prods = $quer->rows;
        
        foreach ($prods as $prod) {
            $name = $prod['name'];
            if($prod['comp']!=''){
                $name.=' (КОМПЛЕКТ)';
            }
            $info['products'][$prod['vin']] = array(
                'name'      => $name,
                'quan'      => $prod['quantity'],
                'price'     => $prod['price'],
                'pricefact' => $prod['price'],
                'quanfact'  => $prod['quantity'],
                'locate'    => $prod['location'],
                'reason'    => ''
            );
        }
        return $info;
    }
    
    public function findProd() {
        $this->load->model('tool/image');
        $token = $this->request->post['token'];
        $vin = $this->request->post['vin'];
        $this->load->model('common/write_off');
        $prod_info = $this->model_common_write_off->findProd($vin);
        if(!empty($prod_info)){
            if ($prod_info['image']) {
                $image = $this->model_tool_image->resize($prod_info['image'], 228, 228);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', 228, 228);
            }        
            $complect = $this->model_common_write_off->isComplect($vin);
            $product = '<div class="alert alert-success">';
                $product.= '<h4><span id="name">'.$prod_info['name'].'</span></h4>';
                $product.= '<div class="col-lg-6">';
                    $product.= '<img src="'.$image.'" alt="'.$prod_info['name'].'" title="'.$prod_info['name'].'" class="img-responsive" />';
                $product.= '</div>';
                $product.= '<div class="col-lg-6">';
                    $product.= '<h4><span class="text-muted">Цена: </span><span id="price">'.$prod_info['price'].'</h4>';
                    $product.= '<h4><span class="text-muted">Количество: </span><span id="quan">'.$prod_info['quan'].'</h4>';
                    $product.= '<h4><span class="text-muted">Расположение: </span><span id="loc">'.$prod_info['stock'].'/'.$prod_info['location'].'</h4>';
                    if($complect){
                        $product.= '<h4><span class="label label-warning">КОМПЛЕКТ</span></h4>';
                    }
                    if($prod_info['quan']>0){ 
                        $product.= '<button class="btn btn-success" id="writeoff" onclick="addToList(\''.$prod_info['vin'].'\', \''.$this->session->data['token'].'\')"><i class="fa fa-plus"></i> Добавить к списку списания</button>';
                    }

                $product.= '</div>';
            $product.= '</div>';
        } else {
            $product = '<div class="alert alert-success"><h4>Такого товара не существует. Проверьте внутренний номер детали.</h4></div>';
        }
        echo $product;
    }
    
    public function saled() {
        exit(var_dump($this->request->post));
        $this->load->model('common/write_off');
        $this->load->language('common/write_off');
        $denied = array("!", "@", "#", "$", "/", "%", "^", "&", "*", "(", ")", "+", "=", "-", "[", "]", "?", "<", ">");
        $message = '';
        
        $data = array(
            'name' => $this->request->post['name'],
            'price' => $this->request->post['price'],
            'quan_stock' => $this->request->post['quan'],
            'location' => str_replace(",", "/", $this->request->post['location']),
            'client' => str_replace($denied, "NO",$this->request->post['client']),
            'city' => str_replace($denied, "NO",$this->request->post['city']),
            'quantity' => str_replace($denied, "NO",$this->request->post['quantity']),
            'date' => $this->request->post['date'],
            'vin' => $this->request->post['sku'],
            'saleprice' => $this->request->post['saleprice'],
            'reason' => $this->request->post['reason']
        );
        //exit(var_dump($data));
        if((empty($data['client'])) || (empty($data['quantity'])) || (empty($data['city'])) || (empty($data['date'])) || (empty($data['name']))){
                $message = $this->language->get('err_mess').': '.$this->language->get('err_empty');
        } else {
            if(($data['quan_stock']<$data['quantity']) || ($data['quantity'] < 1)){
                $message = $this->language->get('err_mess').': '.$this->language->get('err_quan');
            } else{
                if(empty($data['vin'])){
                    $message = $this->language->get('err_vin');
                } else {
                    $try = $this->model_common_write_off->sale($data);
                    if($try){
                        $message = $this->language->get('success');
                    } else {
                        $message = $this->language->get('err_mysql');
                      }
                  }
              }
          }
        
//        $message = '<button onclick="alert(document.'
//                . "getElementById('name').innerText)"
//                . ';" class="btn btn-success">Подтвердить</button>';
        echo $message;
    }
    
    public function saleList() {
        $data = $this->getLayout();
        $this->load->model('common/write_off');
        $results = $this->model_common_write_off->getSales();
        $res_sales = array();
        
        foreach ($results as $result) {
            $res_sales[] = array(
                'name' => $result['name'],
                'sku' => $result['sku'],
                'city' => $result['city'],
                'client' => $result['client'],
                'summ' => $result['summ'],
                'price' => $result['price'],
                'saleprice' => $result['saleprice'],
                'reason' => $result['reason'],
                'loc' => $result['loc'],
                'date' => date('Y-m-d H:i', strtotime($result['date'])),
                'date_mod' => $result['date_mod'],
                'manager' => $result['manager']
            );
        }
        $countres = count($res_sales);
        $i = 0;
        $j = 0;
        $helper = array();
        for($i; $i<$countres; ++$i ){
            for($j; $j<$countres; ++$j ){
                if($res_sales[$i]['date']>$res_sales[$j]['date']){
                    $helper = $res_sales[$i];
                    $res_sales[$i] = $res_sales[$j];
                    $res_sales[$j] = $helper;
                }
            }
            $j = 0;
            $helper = array();
        }
        $data['res_sales'] = $res_sales;
        $this->response->setOutput($this->load->view('common/sale_list', $data));
    }
    
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
    
    public function addToList() {
        $vin = $this->request->post['vin'];
        $this->load->model('common/write_off');
        $this->load->model('tool/image');
        $prod_info = $this->model_common_write_off->findProd($vin);
        //exit(var_dump($prod_info));
        //exit($vin.' - это вин');
        if(!empty($prod_info)){
            if ($prod_info['image']) {
                $image = $this->model_tool_image->resize($prod_info['image'], 228, 228);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', 228, 228);
            }
            $list = '<tr id="'.$vin.'">'
                        . '<td>'.$prod_info['name'].'<input type="hidden" name="products['.$vin.'][name]" value="'.$prod_info['name'].'"></td>'
                        . '<td><b>'.$vin.'</b></td>'
                        . '<td>'.$prod_info['quan'].'<input id="quan'.$vin.'" id="quan'.$vin.'" type="hidden" name="products['.$vin.'][quan]" value="'.$prod_info['quan'].'"></td>'
                        . '<td>'.$prod_info['price'].'<input type="hidden" name="products['.$vin.'][price]" value="'.$prod_info['price'].'"></td>'
                        . '<td>'
                            . '<input type="text" class="form-control" placeholder="Введите цену продажи" value="'.$prod_info['price'].'" name="products['.$vin.'][pricefact]">'
                        . '</td>'
                        . '<td>'
                            . '<input type="text" class="form-control" oninput="validate(\'quan'.$vin.'fact\', \'quan'.$vin.'\');" id="quan'.$vin.'fact" placeholder="Введите количество к продаже" value="'.$prod_info['quan'].'" name="products['.$vin.'][quanfact]"><input type="hidden" name="products['.$vin.'][locate]" value="'.$prod_info['stock'].'/'.$prod_info['location'].'">'
                        . '</td>'
                        . '<td>'
                            . '<input type="text" class="form-control" name="products['.$vin.'][reason]" placeholder="Введите причину уценки">'
                        . '</td>'
                        . '<td>'
                            . '<button onclick="unsetElement(\''.$vin.'\')" class="btn btn-danger">X</button>'
                        . '</td>'
                    . '</tr>';
        }
        echo $list;
    }
}