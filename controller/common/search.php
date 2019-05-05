<?php
class ControllerCommonSearch extends Controller {
	public function index() {
		$this->load->language('common/search');
                isset($this->request->get['buttrash']);
                
		$data['text_search'] = $this->language->get('text_search');
		if ((isset($this->request->get['search'])) and isset($this->request->get['butsearch'])) {
			$data['search'] = $this->request->get['search'];
		} elseif (isset($this->request->get['buttrash'])) {
			$data['text_search'] = $this->language->get('text_search');
                        $data['search'] = '';
		} else {
                        $data['search'] = '';
                }

		return $this->load->view('common/search', $data);
	}
}