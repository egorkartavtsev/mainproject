<?php

class ControllerCatalogCatalog extends Controller{
    
    public function products() {
        if(isset($this->request->get['libr']) || isset($this->request->get['type'])){
            $this->load->model('product/product');
            $this->load->model('tool/layout');
            $this->load->model('tool/image');
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
//                exit(var_dump($prod));
//                exit(var_dump($types));
                if ($prod['image']) {
                    $image = $this->model_tool_image->resize($prod['image'], 228, 228);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', 228, 228);
                }
                $list['products'][$prod['product_id']] = array(
                    'thumb' => $image,
                    'href' => $this->url->link('catalog/product', 'product_id='.$prod['product_id']),
                    'name' => $prod['name'],
                    'vin' => $prod['vin'],
                    'type' => $prod['type'],
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
                        }
                    }
                }
            }
            if (isset($this->request->post['suc'])){
                $mail =  'Имя: '.$this->request->post['name'].'; '
                       . 'Email: '.$this->request->post['email'].'; '
                       . 'Телефон: '.$this->request->post['phone'].'; '
                       . 'Товар: '.$this->request->post['vin'].'; '
                       . 'Наименование товара: '.$this->request->post['product_name'].'; ' 
                       . 'Комментарий: '.$this->request->post['comment'];
                $headers  = 'From: autorazbor174@mail.ru' . " " 
                          . 'Reply-To: autorazbor174@mail.ru' . " "
                          . 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $suc = true;
                mail('autorazbor174@mail.ru', 'Заявка на уточнение цены товара с сайта авторазбор174.рф', $mail);
                $list['suc_text'] = 'Ваша заявка успешно отправлена';
            }
            $list['modal_window'] = $this->load->view('modal_window/Modal_window');
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
//        $data['items'] = $this->load->view('catalog/showlib', $data);
//        exit(var_dump($library));
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
        if(isset($this->request->get['libr'])){
            $types = $this->model_tool_layout->getTypesArray();
            $librs = explode("_", trim($this->request->get['libr']));
            $libr = array_pop($librs);
            $library = array_shift($librs);
            $products = $this->model_product_product->getProducts('libr', $libr, $sort, $filter);
            $list['total'] = $this->model_product_product->getTotalProducts('libr', $libr, $filter);
            $list['types'] = $types;
            $list['pagination'] = $this->model_tool_layout->pagination($list['total'], 'libr');
            $data['filterDiv'] = $this->model_tool_layout->constructFilter('libr', $library);
            $list['link'] = $this->url->link('catalog/catalog/products', 'libr='.$this->request->get['libr']);
        }
        if(isset($this->request->get['type'])){
            $types = $this->model_tool_layout->getTypesArray($this->request->get['type']);
            $products = $this->model_product_product->getProducts('type', $this->request->get['type'], $sort, $filter);
            $list['total'] = $this->model_product_product->getTotalProducts('type', $this->request->get['type'], $filter);
            $list['types'] = $types;
            $list['pagination'] = $this->model_tool_layout->pagination($list['total'], 'type');
            $data['filterDiv'] = $this->model_tool_layout->constructFilter('type', $this->request->get['type']);
            $list['link'] = $this->url->link('catalog/catalog/products', 'type='.$this->request->get['type']);
        }
//        exit(var_dump($products));
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
                    'vin' => $prod['vin'],
                    'type' => $prod['type'],
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
                        }
                    }
                }
            }
            $list['modal_window'] = $this->load->view('modal_window/Modal_window');
            $result = $this->load->view('catalog/showproducts', $list);
        } else {
            $result = '<div class="col-lg-12 text-center"><img src="sad.png" width="150"/><br><h4>К сожалению, ничего не найдено. Попробуйте изменить значения фильтра или позвоните нам, чтобы уточнить наличие детали по телефону.<br><b>+ ‎7 (912) 475 08 70</b></h4></div>';
        }
        echo $result;
    }
}

