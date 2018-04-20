<?php

class ControllerCatalogCatalog extends Controller{
    
    public function products() {
        if(isset($this->request->get['libr']) || isset($this->request->get['type'])){
            $this->load->model('product/product');
            $this->load->model('tool/layout');
            $this->load->model('tool/image');
            $list = array();
            $filter = array();
            if(isset($this->request->get['libr'])){
                $types = $this->model_tool_layout->getTypesArray();
                $libr = array_pop(explode("_", trim($this->request->get['libr'])));
                $library = array_shift(explode("_", trim($this->request->get['libr'])));
                $products = $this->model_product_product->getProducts('libr', $libr);
                $list['total'] = $this->model_product_product->getTotalProducts('libr', $libr);
                $list['types'] = $types;
                $list['pagination'] = $this->model_tool_layout->pagination($list['total'], 'libr');
                $data['filterDiv'] = $this->model_tool_layout->constructFilter('libr', $library);
            }
            if(isset($this->request->get['type'])){
                $types = $this->model_tool_layout->getTypesArray($this->request->get['type']);
                $products = $this->model_product_product->getProducts('type', $this->request->get['type']);
                $list['total'] = $this->model_product_product->getTotalProducts('type', $this->request->get['type']);
                $list['types'] = $types;
                $list['pagination'] = $this->model_tool_layout->pagination($list['total'], 'type');
                $data['filterDiv'] = $this->model_tool_layout->constructFilter('type', $this->request->get['type']);
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
                    'href' => $this->url->link('product/product', 'product_id='.$prod['product_id']),
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
                $this->url->link('catalog/catalog/products', 'libr='.$library.'_'.$item['fill_id']);
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
                    . '<option value="" disabled selected>Выберите значение...</option>';
        foreach ($info['childs'] as $child) {
            $result.= '<option value="'.$child['id'].'">'.$child['name'].'</option>';
        }
        $result.= '</select>';
        echo $result;
    }
}

