<?php

class ControllerCommonCreateXML extends Controller {
    public function index() {
//        exit(var_dump($this->session->data));
        $data['success'] = FALSE;
        if(isset($this->request->get['comm']) && $this->request->get['comm']=='true'){
            $this->createFile();
            $data['success'] = 'Работает';
        }
        $data['xmls'] = simplexml_load_file('../Avito/ads.xml');
        $xmls = $data['xmls'];
        foreach($xmls->Ad as $ad){
            if(in_array('0001', (array)$ad)){
                $ad->Price = 1234;
                $this->load->model('tool/xml');
                $this->model_tool_xml->createAd(null, $xmls);
            } else{ 
                $ad->Price = 4321;
            }
        }
        $xmls->saveXML('../Avito/ads.xml');
//        $data['xmls'] = simplexml_load_file('../Avito/test1.xml');
        $this->load->language('common/addprod');
        $this->document->setTitle('Тестовый контроллер');
        $data['heading_title'] = 'Тестовый контроллер';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/excel', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => 'Тестовый контроллер',
                'href' => $this->url->link('common/addprod', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token'] = $this->session->data['token'];
        $data['man'] = $this->user;
        $this->response->setOutput($this->load->view('common/createXML', $data));
    }
    
    private function createFile() {
        
        $dom = new DomDocument('1.0', "utf-8");
        
        $ads = $dom->appendChild($dom->createElement('Ads'));
        $ads->setAttribute('formatVersion', 3);
        $ads->setAttribute('target', 'Avito.ru');
        
        $ad = $ads->appendChild($dom->createElement('Ad'));
        $id = $ad->appendChild($dom->createElement('Id'));
        $id->appendChild($dom->createTextNode('001'));
//        //добавление корня - <books> 
//        $books = $dom->appendChild($dom->createElement('books')); 
//
//        //добавление элемента <book> в <books> 
//        $book = $books->appendChild($dom->createElement('book')); 
//
//        // добавление элемента <title> в <book> 
//        $title = $book->appendChild($dom->createElement('title')); 
//        $title->setAttribute('url', 'URL');
//        // добавление элемента текстового узла <title> в <title> 
//        $title->appendChild( 
//                        $dom->createTextNode('Great American Novel')); 
        
        //генерация xml 
        $dom->formatOutput = true; // установка атрибута formatOutput
                                   // domDocument в значение true 
        // save XML as string or file 
        
        $dom->save('../Avito/test1.xml'); // сохранение файла
    }
}

