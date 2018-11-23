<?php
class ControllerCheckoutCheckout extends Controller {
    
	private $trnslt = array(
        'secondname'    => array('form'  => 'Фамилия', 'db'    => 'lastname'),
        'firstname'     => array('form'  => 'Имя', 'db'    => 'firstname'),
        'patron'        => array('form'  => 'Отчество', 'db'    => 'patron'),    
        'telephone'     => array('form'  => 'Телефон', 'db'    => 'telephone'),
        'email'         => array('form'  => 'Email', 'db'    => 'email'),     
        'region'        => array('form'  => 'Регион', 'db'    => 'payment_zone'),
        'city'          => array('form'  => 'Населённый пункт', 'db'    => 'payment_city'),      
        'street'        => array('form'  => 'Улица', 'db'    => 'payment_address_1')
    );
	
    public function index() {
		// Validate cart has products and has stock.
		if (!$this->cart->hasProducts()) {
                    $this->response->redirect($this->url->link('checkout/cart'));
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();
                
		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if (1 > $product_total) {
				$this->response->redirect($this->url->link('checkout/cart'));
			}
		}
                $this->load->model('account/customer');
		$this->load->language('checkout/checkout');

		$this->document->setTitle($this->language->get('heading_title'));
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_cart'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

                $data['heading_title'] = $this->language->get('heading_title');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
                // Captcha
                if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
                        $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
                } else {
                        $data['captcha'] = '';
                }
                
                if (isset($this->session->data['customer_id'])){
                    $data['customer_info'] = $this->model_account_customer->getCustomer($this->session->data['customer_id']);
                    $data['customer_check'] = $this->session->data['customer_id'];
                    if (substr_count($data['customer_info']['telephone'], '+') > 0){
                        $data['fix_telephone'] = substr_replace($data['customer_info']['telephone'],'', 0, 2);
                    } else {
                        $data['fix_telephone'] = substr_replace($data['customer_info']['telephone'],'', 0, 1);
                    }
                }
		$this->response->setOutput($this->load->view('checkout/checkout', $data));
	}
        
        public function applyStep(){
            if(isset($this->request->post['form'])){
                $post = $this->request->post['form'];
                $form = array();
                $result = '<ul>';
                //exit(var_dump($post));
                $this->session->data['cust_mail'] = FALSE;
                foreach ($post as $field){
                    $sup = explode(":", $field);
                    $form[$this->trnslt[$sup[0]]['db']] = $sup[1];
                    $result.= '<li class="h5"><strong>'.$this->trnslt[$sup[0]]['form'].'</strong>: <u>'.$sup[1].'</u></li>';
                }
                $result.= '</ul>';
            }
            $this->load->model('checkout/newOrder');
            switch ($this->request->post['step']) {
                case '1':
                    $this->model_checkout_newOrder->newOrder($form);
                    $this->session->data['cust_mail'] = $form['email'];
                    $this->model_checkout_newOrder->setProducts($this->cart->getProducts());
                    $this->cart->clear();
                    mail(ADM_EMAIL, 'сообщение с сайта авторазбор174.рф', 'Новый заказ с сайта авторазбор174.рф');
                break;
                case '2':
                    foreach ($form as $key => $value) {
                        $this->model_checkout_newOrder->updateOrder($key, $value);
                    }
                break;
                case 'last':
                        $this->model_checkout_newOrder->updateOrder('order_status_id', '2');
                        if($this->session->data['cust_mail']){
                            mail($this->session->data['cust_mail'], 'сообщение с сайта авторазбор174.рф', 'Ваш заказ принят в обработку. В скором времени с Вами свяжется наш менеджер. Спасибо! \n\ Удачного Вам дня :-)');
                        }
                break;
            }
            echo $result;
        }
        
        public function validate() {
            if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
                echo false;
            } else {
                echo true;
            }
	}
        
        private function createOrder($user){
            $this->load->model('checkout/order');
            $this->order = $this->model_checkout_order->newOrder($user);
            $this->model_checkout_order->setProducts($this->order, $this->cart->getProducts());
        }
        
}
