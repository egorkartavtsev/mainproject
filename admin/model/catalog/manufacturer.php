<?php
class ModelCatalogManufacturer extends Model {
	public function addManufacturer($data) {

		$this->load->model('localisation/language');
		$language_info = $this->model_localisation_language->getLanguageByCode($this->config->get('config_language'));
                $front_language_id = $language_info['language_id'];
		$data['name'] = $data['manufacturer_description'][$front_language_id ]['name'];
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "brand "
                        . "SET name = '" . $this->db->escape($data['name']) . "'");

		$manufacturer_id = $this->db->getLastId()+1;

		foreach ($data['manufacturer_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "brand "
                                . "SET id = '" . (int)$manufacturer_id . "', "
                                    . "parent_id = 0, "
                                    . "name = '" . $this->db->escape($value['name']) . "', "
                                    . "description = '" . $this->db->escape($value['description']) . "', "
                                    . "meta_title = '" . $this->db->escape($value['meta_title']) . "', "
                                    . "meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', "
                                    . "meta_description = '" . $this->db->escape($value['meta_description']) . "', "
                                    . "meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
                
                if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "brand "
                                . "SET image = '" . $this->db->escape($data['image']) . "' "
                                . "WHERE id = '" . (int)$manufacturer_id . "'");
		}
                
		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('manufacturer');

		return $manufacturer_id;
	}

	public function editManufacturer($manufacturer_id, $data) {
		
		$this->load->model('localisation/language');
		$language_info = $this->model_localisation_language->getLanguageByCode($this->config->get('config_language'));
                $front_language_id = $language_info['language_id'];
		$data['name'] = $data['manufacturer_description'][$front_language_id ]['name'];

		$this->db->query("UPDATE " . DB_PREFIX . "brand "
                        . "SET name = '" . $this->db->escape($data['name']) . "' "
                        . "WHERE id = '" . (int)$manufacturer_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "brand "
                                . "SET image = '" . $this->db->escape($data['image']) . "' "
                                . "WHERE id = '" . (int)$manufacturer_id . "'");
		}

//		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description "
//                        . "WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($data['manufacturer_description'] as $language_id => $value) {
			$this->db->query("UPDATE " . DB_PREFIX . "brand "
                                . "SET name = '" . $this->db->escape($value['name']) . "', "
                                    . "description = '" . $this->db->escape($value['description']) . "', "
                                    . "meta_title = '" . $this->db->escape($value['meta_title']) . "', "
                                    . "meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', "
                                    . "meta_description = '" . $this->db->escape($value['meta_description']) . "', "
                                    . "meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "' "
                                . "WHERE id = '" . (int)$manufacturer_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('manufacturer');
	}

	public function deleteManufacturer($manufacturer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "brand WHERE id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");

		$this->cache->delete('manufacturer');
	}

	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT DISTINCT *, "
                        . "(SELECT keyword FROM " . DB_PREFIX . "url_alias "
                        . "WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "' LIMIT 1) AS keyword "
                        . "FROM " . DB_PREFIX . "brand "
                        . "WHERE id = '" . (int)$manufacturer_id . "'");

		return $query->row;
	}

	public function getManufacturerDescriptions($manufacturer_id) {
		$manufacturer_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "brand "
                        . "WHERE id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_description_data[1] = array(
				'name'             => $result['name'],
				'meta_title'       => $result['meta_title'],
				'meta_h1'          => $result['meta_h1'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'description'      => $result['description']
			);
		}

		return $manufacturer_description_data;
	}

	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "brand";

		$sql = "SELECT b.id AS manufacturer_id, b.name AS name "
                        . "FROM " . DB_PREFIX . "brand b "
                        . "WHERE b.parent_id = 0";



		if (!empty($data['filter_name'])) {
			$sql .= " AND name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getManufacturerStores($manufacturer_id) {
		$manufacturer_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_store_data[] = $result['store_id'];
		}

		return $manufacturer_store_data;
	}

	public function getTotalManufacturers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer");

		return $query->row['total'];
	}
}
