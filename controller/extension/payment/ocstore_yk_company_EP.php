<?php
class ControllerExtensionPaymentOcstoreYkCompanyEP extends Controller {
    public function index() {
        return $this->load->controller('extension/payment/ocstore_yk', array('paymentType' => 'EP'));
    }
}