<?php
class ControllerCommonTopnav extends Controller{
    public function index() {
        $this->load->model('tool/layout');
        $this->load->model('catalog/information');
        $types = $this->model_tool_layout->getTypesTN();
        $librs = $this->model_tool_layout->getLibrsTN();
        $data['librs'] = array();
        foreach ($librs as $lib) {
            $data['librs'][] = array(
                'text'  => $lib['text'],
                'href'  => $this->url->link('catalog/catalog/library', 'libr='.$lib['library_id'])
            );
        }
        $data['types'] = array();
        foreach ($types as $type) {
            $data['types'][] = array(
                'text'  => $type['text'],
                'href'  => $this->url->link('catalog/catalog/products', 'type='.$type['type_id'])
            );
        }
        $data['informations'] = array();
        foreach ($this->model_catalog_information->getInformations() as $result) {
                if ($result['bottom']) {
                        $data['informations'][] = array(
                                'title' => $result['title'],
                                'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
                        );
                }
        }

        return $this->load->view('common/topnav', $data);
    }
}

