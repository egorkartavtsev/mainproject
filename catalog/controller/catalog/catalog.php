<?php

class ControllerCatalogCatalog extends Controller{
    
    public function products() {
        if(isset($this->request->get['libr']) || isset($this->request->get['type'])){
            $this->load->model('product/product');
            $this->load->model('tool/layout');
            $this->load->model('tool/image');
            $this->load->model("tool/product");
            $list = array();
            $filter = array();
            $data['breadcrumbs'][] = array(
                    'text' => '<i class="fa fa-home"></i>',
                    'href' => '/'
            );
            if(isset($this->request->get['sort'])){
                $sort = "ORDER BY p.".$this->request->get['sort'];
                if(isset($this->request->get['order'])){
                    $sort.= " ".$this->request->get['order'];
                }
            } else {
                $sort = "ORDER BY p.date_added DESC";
            }
            if(isset($this->request->get['libr'])){
                $req = explode("_", trim($this->request->get['libr']));
                $types = $this->model_tool_layout->getTypesArray();
                $libr = array_shift($req);
                $goal = array_pop($req);
                //exit(var_dump($req));
                $data['breadcrumbs'][] = array(
                    'text' => $this->model_tool_layout->getLibrName($libr),
                    'href' => $this->url->link('catalog/catalog/library', 'libr='.$libr)
                );
                $routeLib = $libr;
                foreach ($req AS $lib){
                    $routeLib.= '_'.$lib;
                    $data['breadcrumbs'][] = array(
                        'text' => $this->model_tool_layout->getFillName($lib),
                        'href' => $this->url->link('catalog/catalog/library', 'libr='.$routeLib)
                    );
                }
                $routeLib.= '_'.$goal;
                    $data['breadcrumbs'][] = array(
                        'text' => $this->model_tool_layout->getFillName($goal),
                        'href' => $this->url->link('catalog/catalog/products', 'libr='.$routeLib)
                    );
                $librs = explode("_", trim($this->request->get['libr']));
//                exit(var_dump($librs));
                $library = array_shift($librs);
                $products = $this->model_product_product->getProducts('libr', $goal, $sort);
                $list['total'] = $this->model_product_product->getTotalProducts('libr', $goal);
                $list['types'] = $types;
                $list['pagination'] = $this->model_tool_layout->pagination($list['total'], 'libr');
                $data['filterDiv'] = $this->model_tool_layout->constructFilter('libr', $library);
                $list['link'] = $this->url->link('catalog/catalog/products', 'libr='.$this->request->get['libr']);
            }
            if(isset($this->request->get['type'])){
                $data['breadcrumbs'][] = array(
                    'text' => $this->model_tool_layout->getTypeName($this->request->get['type']),
                    'href' => $this->url->link('catalog/catalog/products', 'type='.$this->request->get['type'])
                );
                $types = $this->model_tool_layout->getTypesArray($this->request->get['type']);
                $products = $this->model_product_product->getProducts('type', $this->request->get['type'], $sort);
                $list['total'] = $this->model_product_product->getTotalProducts('type', $this->request->get['type']);
                $list['types'] = $types;
                $list['pagination'] = $this->model_tool_layout->pagination($list['total'], 'type');
                $data['filterDiv'] = $this->model_tool_layout->constructFilter('type', $this->request->get['type']);
                $list['link'] = $this->url->link('catalog/catalog/products', 'type='.$this->request->get['type']);
            }
            $list['products'] = array();
            foreach ($products as $prod){
                $photos = $this->model_tool_product->getProdImg($prod['product_id']);
                $image = array();
                $local_id = 0;
                foreach($photos as $img){
                    $image[] = array (
                        'thumb'         => $this->model_tool_image->resize($img['image'], 228, 228),
                        'main'          => $img['image']==$prod['image']?TRUE:FALSE,
                        'lid'           => $local_id
                    );
                ++$local_id;    
                }
                if(!count($image)){
                    $image[] = array (
                        'thumb'         => $this->model_tool_image->resize('no-image.png', 228, 228),
                        'main'          => TRUE,
                        'lid'           => 0
                    );
                } elseif (!in_array(TRUE,array_column($image,'main'))) {
                    $image[0]['main'] = TRUE;
                }
//                if ($prod['image']) {
//                    $image = $this->model_tool_image->resize($prod['image'], 228, 228);
//                } else {
//                    $image = $this->model_tool_image->resize('placeholder.png', 228, 228);
//                }
                $list['products'][$prod['product_id']] = array(
                    'image' => $image,
                    'product_id' => $prod['product_id'],
                    'href' => $this->url->link('catalog/product', 'product_id='.$prod['product_id']),
                    'name' => $prod['name'],
                    'vin' => $prod['vin'],
                    'type' => $prod['type'],
                    'status' => $prod['status'],
                    'comp' => $prod['comp']==''?FALSE:$prod['comp'],
                    'com_whole' => $prod['comp_whole'],
                    'price' => $prod['price'],
                    'quantity' => $prod['quantity']    
                );
                foreach ($prod as $key => $field){
                    if(isset($types[$prod['structure']][$key])){
                        if($field!==''){
                            $list['products'][$prod['product_id']]['options'][] = array(
                                'text' => $types[$prod['structure']][$key]['text'],
                                'value' => $field
                            );
                            if((int)$types[$prod['structure']][$key]['label_order']){
                                $list['products'][$prod['product_id']]['labels'][(int)$types[$prod['structure']][$key]['label_order']] = array(
                                    'color' => $types[$prod['structure']][$key]['label_color'],
                                    'value' => $field
                                );
                            }
                        }
                    }
                }
            }
//          exit(var_dump($list['products'][17311]['vin']));
            if (isset($this->request->post['suc'])){
                $cause = $this->request->post['cause'];
                $pid = $this->request->post['product_id'];
                $comment = wordwrap($this->request->post['comment'],70,"\r\n");
                $mail =  'Имя: '.$this->request->post['name'].'; '. "\r\n" .
                         'Email: '.$this->request->post['email'].'; '. "\r\n" .
                         'Телефон: '.$this->request->post['phone'].'; '. "\r\n" .
                         'Артикул: '.$list['products'][$pid]['vin'].'; '. "\r\n" .
                         'Наименование товара: '.$list['products'][$pid]['name'].'; '. "\r\n" . 
                         'Комментарий: '.$comment;
                $headers  = 'From: autorazbor174@mail.ru' . "\r\n" . 
                            'Reply-To: autorazbor174@mail.ru' . "\r\n" .
                            'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $suc = true; 
                switch ($cause){
                    case 1:
                        $subject = 'Заявка на уточнение наличия товара с сайта авторазбор174.рф';
                        break;
                    case 2:
                        $subject = 'Заявка на уточнение стоимости товара с сайта авторазбор174.рф';
                        break;
                    case 3:
                        $subject = 'Заявка на заказ товара с сайта авторазбор174.рф';
                        break;
                }
                mail('autorazbor174@mail.ru', $subject, $mail, $headers);
                $data['suc_text'] = 'Ваша заявка успешно отправлена';
            }
            $list['modal_window'] = $this->load->view('modal_window/Modal_window');
            $server = $this->config->get('config_url');
            $list['whatsapp'] = $server . 'image/whatsapp.png';
            $list['lvk'] = $server . 'image/vk.png';
            $list['wapp'] = $server . 'image/wapp.png';
            $list['viber'] = $server . 'image/viber.png';
            $list['linst'] = $server . 'image/inst.png';
            $list['ldrom'] = $server . 'image/drom.png';
            $list['lavito'] = $server . 'image/avito.png';
            $list['lyt'] = $server . 'image/lyt.png';
            //exit(var_dump($list['products']));
            $data['productsDiv'] = $this->load->view('catalog/showproducts', $list);
        //----------------------------------------------------------------------------------
            $this->document->setTitle($this->config->get('config_meta_title'));
            $this->document->setDescription($this->config->get('config_meta_description'));
            $this->document->setKeywords($this->config->get('config_meta_keyword'));
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            
        /*-------------------------------------------------------------------------------------------*/
            $this->response->setOutput($this->load->view('catalog/prodslist', $data));
            //$this->response->setOutput($this->load->view('catalog/product_page', $data));
        } else {
            $this->response->redirect($this->url->link('common/home'));
        }
    }
    
    public function library() {
        $this->load->model('tool/layout');
        $this->load->model('tool/image');
        $request = explode("_", $this->request->get['libr']);
    /*-------------------------------------------------------------------------------------------*/
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
                'text' => '<i class="fa fa-home"></i>',
                'href' => $this->url->link('common/home')
        );
    /*-------------------------------------------------------------------------------------------*/
        $library = array_shift($request);
        $data['library'] = $library;
        $data['breadcrumbs'][] = array(
                'text' => $this->model_tool_layout->getLibrName($library),
                'href' => $this->url->link('catalog/catalog/library', 'libr='.$library)
        );
        if(count($request)){
            while(count($request)){
                $top_lvl = array_shift($request);
                if(count($request)){
                    $data['breadcrumbs'][] = array(
                        'text' => $this->model_tool_layout->getFillName($top_lvl),
                        'href' => $this->url->link('catalog/catalog/library', 'libr='.$library."_".$top_lvl)
                    );
                } else {
                    $data['breadcrumbs'][] = array(
                        'text' => $this->model_tool_layout->getFillName($top_lvl),
                        'href' => $this->url->link('catalog/catalog/library', 'libr='.$library."_".$top_lvl)
                    );
                    $items = $this->model_tool_layout->getFills($top_lvl);
                }
            }
        } else {
            $items = $this->model_tool_layout->getParentFills($library);
        }
//        exit(var_dump($items));
        //------------------------
        foreach ($items as $item) {
            if ($item['img']) {
                $image = $this->model_tool_image->resize($item['img'], 57, 57);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', 57, 57);
            }
            
            if((int)$item['isParent']){
                $href = $this->url->link('catalog/catalog/library', 'libr='.$this->request->get['libr'].'_'.$item['fill_id']);
            }else{
                $href = $this->url->link('catalog/catalog/products', 'libr='.$this->request->get['libr'].'_'.$item['fill_id']);
            }
            
            $data['items'][] = array(
                'image' => $image,
                'name' => $item['name'],
                'showImg' => $item['showImg'],
                'href' => $href
            );
        }
        $data['libId'] = $library;
        $data['items'] = $this->load->view('catalog/showlib', $data);
    /*-------------------------------------------------------------------------------------------*/
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
    /*-------------------------------------------------------------------------------------------*/
        $this->response->setOutput($this->load->view('catalog/fillslist', $data));
    }
    
    public function search() {
        $this->load->model('tool/layout');
        $this->load->model('tool/image');
        $data = array();
        $request = trim($this->request->post['request']);
        $library = $this->request->post['lib_id'];
        if($request!==''){
            $filters = explode(" ", $request);
            $items = $this->model_tool_layout->getFills(0, $filters, $library);
        } else {
            $items = $this->model_tool_layout->getParentFills($library);
        }
        
         //------------------------
        foreach ($items as $item) {
            if ($item['img']) {
                $image = $this->model_tool_image->resize($item['img'], 57, 57);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', 57, 57);
            }
            
            if((int)$item['isParent']){
                $href = $this->url->link('catalog/catalog/library', 'libr='.$library.'_'.$item['fill_id']);
            }else{
                $href = $this->url->link('catalog/catalog/products', 'libr='.$library.'_'.$item['fill_id']);
            }
            
            $data['items'][] = array(
                'image' => $image,
                'name' => $item['name'],
                'showImg' => $item['showImg'],
                'href' => $href
            );
        }
        echo $this->load->view('catalog/showlib', $data);
    }
    
    public function showLibrChild() {
        $this->load->model('tool/layout');
        $result = '';
        $parent = $this->request->post['parent'];
        $info = $this->model_tool_layout->getChilds($parent);
        $result = '<label for="filter_'.$info['currId'].'">'.$info['currText'].':</label><select class="form-control" id="filter_'.$info['currId'].'" '.($info['cName']?'select_type="library" child="'.$info['cName'].'"':'').'>'
                    . '<option value="" disabled selected>Выберите значение...</option>'
                    . '<option value="Все товары">Все товары</option>';
        foreach ($info['childs'] as $child) {
            $result.= '<option value="'.$child['name'].'">'.$child['name'].'</option>';
        }
        $result.= '</select>';
        echo $result;
    }
    
    public function applyFilter() {
        $this->load->model('product/product');
        $this->load->model('tool/layout');
        $this->load->model('tool/image');
        $products = array();
        $result = '';
        $request = $this->request->post['filter'];
        $request = explode(";", $request);
        $filter = array();
        foreach ($request as $str) {
            $sup = explode(": ", $str);
            if(isset($sup[1]) && trim($sup[1])!=='' && trim($sup[1])!=='null' && trim($sup[1])!=='Все товары'){
                $filter[str_replace("filter_", '', $sup[0])] = $sup[1];
            }
        }
        if(isset($this->request->get['sort'])){
            $sort = "ORDER BY p.".$this->request->get['sort'];
            if(isset($this->request->get['order'])){
                $sort.= " ".$this->request->get['order'];
            }
        } else {
            $sort = "ORDER BY p.date_added DESC";
        }
//        exit(var_dump($filter));
//        exit(var_dump($this->request->get));
//        exit($sort);
        $list['pagination'] = '';
        if(isset($this->request->get['libr'])){
            $types = $this->model_tool_layout->getTypesArray();
            $librs = explode("_", trim($this->request->get['libr']));
            $libr = array_pop($librs);
            $library = array_shift($librs);
            $products = $this->model_product_product->getProducts('libr', $libr, $sort, $filter);
            $list['total'] = $this->model_product_product->getTotalProducts('libr', $libr, $filter);
            $list['types'] = $types;
            //$list['pagination'] = $this->model_tool_layout->pagination($list['total'], 'libr');
            $data['filterDiv'] = $this->model_tool_layout->constructFilter('libr', $library);
            $list['link'] = $this->url->link('catalog/catalog/products', 'libr='.$this->request->get['libr']);
        }
        if(isset($this->request->get['type'])){
            $types = $this->model_tool_layout->getTypesArray($this->request->get['type']);
            $products = $this->model_product_product->getProducts('type', $this->request->get['type'], $sort, $filter);
            $list['total'] = $this->model_product_product->getTotalProducts('type', $this->request->get['type'], $filter);
            $list['types'] = $types;
            //$list['pagination'] = $this->model_tool_layout->pagination($list['total'], 'type');
            $data['filterDiv'] = $this->model_tool_layout->constructFilter('type', $this->request->get['type']);
            $list['link'] = $this->url->link('catalog/catalog/products', 'type='.$this->request->get['type']);
        }
//        exit(var_dump($products));
        $server = $this->config->get('config_url');
        if(count($products)){
            $list['products'] = array();
            foreach ($products as $prod){
                if ($prod['image']) {
                    $image = $this->model_tool_image->resize($prod['image'], 228, 228);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', 228, 228);
                }
                $list['products'][$prod['product_id']] = array(
                    'thumb' => $image,
                    'href' => $this->url->link('catalog/product', 'product_id='.$prod['product_id']),
                    'name' => $prod['name'],
                    'status' => $prod['status'],
                    'vin' => $prod['vin'],
                    //'type' => $prod['type'],
                    'quantity' => $prod['quantity'],
                    'comp' => $prod['comp']==''?FALSE:$prod['comp'],
                    'com_whole' => $prod['comp_whole'],
                    'price' => $prod['price']
                );
                foreach ($prod as $key => $field){
                    if(isset($types[$prod['structure']][$key])){
                        if($field!==''){
                            $list['products'][$prod['product_id']]['options'][] = array(
                                'text' => $types[$prod['structure']][$key]['text'],
                                'value' => $field
                            );
                            if((int)$types[$prod['structure']][$key]['label_order']){
                                $list['products'][$prod['product_id']]['labels'][(int)$types[$prod['structure']][$key]['label_order']] = array(
                                    'color' => $types[$prod['structure']][$key]['label_color'],
                                    'value' => $field
                                );
                            }
                        }
                    }
                }
            }
            $list['modal_window'] = $this->load->view('modal_window/Modal_window');
            $list['whatsapp'] = $server . 'image/whatsapp.png';
            $list['lvk'] = $server . 'image/vk.png';
            $list['wapp'] = $server . 'image/wapp.png';
            $list['viber'] = $server . 'image/viber.png';
            $list['linst'] = $server . 'image/inst.png';
            $list['ldrom'] = $server . 'image/drom.png';
            $list['lavito'] = $server . 'image/avito.png';
            $list['lyt'] = $server . 'image/lyt.png';
            $result = $this->load->view('catalog/showproducts', $list);
        } else {
            $result = '<div class="col-lg-12 text-center">
            <img src="sad.png" width="150"/><br>
            <h4>
                К сожалению, ничего не найдено. Позвоните нам, чтобы уточнить наличие детали по телефону.<br>
                <a href="tel: +79124750870" class="hidden-md hidden-lg btn btn-danger col-lg-6"><span><i class="fa fa-phone"></i><b>+ ‎7 (912) 475 08 70</b></span></a>
                <span class="hidden-xs hidden-sm"><b>+ ‎7 (912) 475 08 70</b></span>
                <div class="col-sm-12 text-center">
                    Или любым удобным для Вас способом:<br>
                    <a style="cursor: pointer;" href="viber://chat?number=+79124750870"><img src="'.$server . 'image/viber.png" width="50"></a>
                    <a style="cursor: pointer;" href="https://wa.me/79124750870"><img src="'.$server . 'image/whatsapp.png" width="50"></a>
                    <a  target="_blank" href="https://vk.com/mgnautorazbor"><img src="'.$server . 'image/vk.png" width="50"></a>
                    <a  target="_blank" href="https://www.instagram.com/autorazbor174"><img src="'.$server . 'image/inst.png" width="50"></a>
                    <a  target="_blank" href="https://www.youtube.com/channel/UCNgBC4t07efN7qMYUls0fcw"><img src="'.$server . 'image/lyt.png" width="50"></a>
                    <a  target="_blank" href="https://baza.drom.ru/user/AUTORAZBOR174RU"><img src="'.$server . 'image/drom.png" width="50"></a>
                    <a  target="_blank" href="https://www.avito.ru/autorazbor174"><img src="'.$server . 'image/avito.png" width="50"></a>
                </div>
            </h4>
        </div>';
        }
        echo $result;
    }
}

