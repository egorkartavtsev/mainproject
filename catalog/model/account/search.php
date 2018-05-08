<?php
class ModelAccountSearch extends Model {
	public function addSearch($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_search` "
                        . "SET `store_id` = '" . (int)$this->config->get('config_store_id') . "', "
                        . "`language_id` = '" . (int)$this->config->get('config_language_id') . "', "
                        . "`customer_id` = '" . (int)$data['customer_id'] . "', "
                        . "`keyword` = '" . $this->db->escape($data['keyword']) . "', "
                        . "`products` = '" . (int)$data['products'] . "', "
                        . "`ip` = '" . $this->db->escape($data['ip']) . "', "
                        . "`date_added` = NOW()");
	}
}
