<?php
class ControllerErrorNotFound extends Controller {
	public function index() {
		$this->load->model('tool/layout');
                $data = $this->model_tool_layout->getLayout('error/not_found');
                $data['text_permission'] = 'Доступ запрещён. У Вас не хватает прав доступа к данной странице.';
		$this->response->setOutput($this->load->view('error/not_found', $data));
	}
}