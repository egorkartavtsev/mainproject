<?php
class ControllerCommonDashboard extends Controller {
	public function index() {
		$this->load->language('common/dashboard');
                $this->load->model('tool/layout');
                $data = $this->model_tool_layout->getLayout($this->request->get['route']);
                $this->model_tool_layout->updateADS($this->request->get['route']);
                
                $data['ses_token'] = $this->session->data['token'];
		$data['breadcrumbs'] = array();
                $fcItems = $this->user->getLayout();
                $data['fcItems'] = $fcItems['fcmenu'];
		

		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		// Dashboard Extensions
		$dashboards = array();

		$this->load->model('extension/extension');

		// Get a list of installed modules
		$extensions = $this->model_extension_extension->getInstalled('dashboard');
		
		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $code) {
			if ($this->config->get('dashboard_' . $code . '_status') && $this->user->hasPermission('access', 'extension/dashboard/' . $code)) {
				$output = $this->load->controller('extension/dashboard/' . $code . '/dashboard');
				
				if ($output) {
					$dashboards[] = array(
						'code'       => $code,
						'width'      => $this->config->get('dashboard_' . $code . '_width'),
						'sort_order' => $this->config->get('dashboard_' . $code . '_sort_order'),
						'output'     => $output
					);
				}
			}
		}

		$sort_order = array();

		foreach ($dashboards as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $dashboards);
		
		// Split the array so the columns width is not more than 12 on each row.
		$width = 0;
		$column = array();
		$data['rows'] = array();
		
		foreach ($dashboards as $dashboard) {
			$column[] = $dashboard;
			
			$width = ($width + $dashboard['width']);
			
			if ($width >= 12) {
				$data['rows'][] = $column;
				
				$width = 0;
				$column = array();
			}
		}

		// Run currency update
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->refresh();
		}
                
                $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."order WHERE viewed = 0 ");
                if($sup->num_rows){
                    $data['notice']['order'] = '<a href="'.$this->url->link('report/orders', 'token='.$this->session->data['token']).'" style="float: left;">'
                            . '<div class="db-notice orders" style="color: white;">'
                                . '<h3>Новые заказы с сайта: </h3>'
                                . '<h1><i class="fa fa-pencil-square-o fw"></i> <b>'.$sup->num_rows.'</b>шт.</h3>'
                            . '</div>'
                        . '</a>';
                }
                $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_avito WHERE message = 1 ");
                if($sup->num_rows){
                    $data['notice']['avito'] = '<a href="'.$this->url->link('avito/avito_list', 'token='.$this->session->data['token']).'" style="float: left;">'
                            . '<div class="db-notice avito" style="color: white;">'
                                . '<h3>Окончилась активация: </h3>'
                                . '<h1><i class="fa fa-adn fw"></i> <b>'.$sup->num_rows.'</b>объявл.</h3>'
                            . '</div>'
                        . '</a>';
                }
                
		$this->response->setOutput($this->load->view('common/dashboard', $data));
	}
}