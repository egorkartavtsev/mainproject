<?php
class ModelCatalogProduct extends Model {
	//public function updateViewed($product_id) {
	//	$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	//}
        
        public function getModelID($name) {
            $query = $this->db->query("SELECT id FROM ".DB_PREFIX."brand WHERE name = '".$name."'");
            if(isset($query->row['id'])){
                return $query->row['id'];
            } else {
                return 0;
            }
        }
        
	public function getProduct($product_id) {
		
            //exit($product_id);
            //echo $product_id;
                $query = $this->db->query("SELECT DISTINCT *, "
                        . "pd.name AS name, "
                        . "p.image, "
                        . "p.note AS note, "
                        . "p.cond AS con_p, "
                        . "p.catn AS cat_numb, "
                        . "p.compability AS compability, "
                        . "p.vin AS vin, "
                        . "p.type AS ean, "
                        . "p.comp AS comp, "
                        . "p.price as price, "
                        . "p.dop AS dop, "
                        . "p.stock AS stock, "
                        . "p.model AS model, "
                        . "p.modR AS model_row, "
                        . "c.whole AS com_whole, "
                        . "c.price AS com_price,"
                        . "(SELECT b.name "
                            . "FROM " . DB_PREFIX . "brand b "
                            . "WHERE b.id = p.brand) AS manufacturer, "
//                        . "(SELECT c.whole "
//                            . "FROM " . DB_PREFIX . "complects c "
//                            . "WHERE c.heading = p.vin OR c.heading = p.comp) AS com_whole, "
//                        . "(SELECT c.price "
//                            . "FROM " . DB_PREFIX . "complects c "
//                            . "WHERE c.heading = p.vin OR c.heading = p.comp) AS com_price, "
                        /*//------------------------------
                        . "(SELECT price "
                            . "FROM " . DB_PREFIX . "product_discount pd2 "
                            . "WHERE pd2.product_id = p.product_id "
                                . "AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' "
                                . "AND pd2.quantity = '1' "
                                . "AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, "

                        . "(SELECT price "
                            . "FROM " . DB_PREFIX . "product_special ps "
                            . "WHERE ps.product_id = p.product_id "
                                . "AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' "
                                . "AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, "
                        // -------------------------------*/
                        . "(SELECT points "
                            . "FROM " . DB_PREFIX . "product_reward pr "
                            . "WHERE pr.product_id = p.product_id "
                            . "AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, "

                        . "(SELECT ss.name "
                            . "FROM " . DB_PREFIX . "stock_status ss "
                            . "WHERE ss.stock_status_id = p.jar "
                                . "AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, "

                        . "(SELECT AVG(rating) AS total "
                            . "FROM " . DB_PREFIX . "review r1 "
                            . "WHERE r1.product_id = p.product_id "
                                . "AND r1.status = '1' GROUP BY r1.product_id) AS rating, "

                        . "(SELECT COUNT(*) AS total "
                            . "FROM " . DB_PREFIX . "review r2 "
                            . "WHERE r2.product_id = p.product_id "
                                . "AND r2.status = '1' GROUP BY r2.product_id) AS reviews, "

                        . "p.sort_order "
                        
                        . "FROM " . DB_PREFIX . "product p "
                            . "LEFT JOIN " . DB_PREFIX . "product_description pd "
                                . "ON (p.product_id = pd.product_id) "
                            . "LEFT JOIN ". DB_PREFIX . "complects c "
                                . "ON (p.vin = c.heading OR p.comp = c.heading) "
                            . "LEFT JOIN " . DB_PREFIX . "product_to_store p2s "
                                . "ON (p.product_id = p2s.product_id) "
                            . "LEFT JOIN " . DB_PREFIX . "manufacturer m "
                                . "ON (p.brand = m.manufacturer_id) "
                            . "WHERE p.product_id = '" . (int)$product_id . "' "
                                . "AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' "
                                . "AND p.status = '1' AND p.date_available <= NOW() "
                                . "AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
                
                            //exit(var_dump($query));
                
		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
                                'comp'             => $query->row['comp'],
				'meta_title'       => $query->row['meta_title'],
				'compability'      => $query->row['compability'],
				'meta_h1'          => $query->row['meta_h1'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['vin'],
				'upc'              => $query->row['cond'],
				'ean'              => $query->row['type'],
				'jan'              => $query->row['note'],
				'isbn'             => $query->row['catn'],
				'mpn'              => $query->row['stell'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['brand'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => $query->row['price'],
                                'com_price'        => $query->row['com_price'],
                                'com_whole'        => $query->row['com_whole'],
                                //($query->row['discount'] ? $query->row['discount'] : 
				//'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				//'tax_class_id'     => $query->row['tax'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['stock'],
				
				'length'           => $query->row['modR'], 
				'width'            => $query->row['width'],
				'height'           => $query->row['donor'],
				
				//'subtract'         => $query->row['subtract'], используется в getProductOptions
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				//'viewed'           => $query->row['viewed'],удалить
                                
                                'model_row'        => $query->row['modR'],
                                'cat_numb'         => $query->row['catn'],
                                'vin'              => $query->row['vin'],
                                'stock'            => $query->row['stock'],
                                'note'             => $query->row['note'],
                                'dop'              => $query->row['dop'],
                                'con_p'            => $query->row['cond'],
			);
		} else {
			return false;
		}
	}

	public function getProducts($data = array()) {
		$sql = "SELECT p.product_id, "
                        . "p.vin AS vin, "
                        . "p.cond AS con_p, "
                        . "p.compability AS compability, "
                        . "p.catn AS catN, "
                        . "p.comp AS comp, "
                        . " (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.vin) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.cond) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.type) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.note) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.catn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.stell) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.brand = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " AND p.price != 0";
		$sql .= " GROUP BY p.product_id";

//		$sort_data = array(
//			'pd.name',
//			'p.model',
//			'p.quantity',
//			'p.price',
//			'rating',
//			'p.sort_order',
//			'p.date_added'
//		);
//
//		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
//			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
//				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
//			} elseif ($data['sort'] == 'p.price') {
//				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
//			} else {
//				$sql .= " ORDER BY " . $data['sort'];
//			}
//		} else {
//			$sql .= " ORDER BY p.sort_order";
//		}
//
//		if (isset($data['order']) && ($data['order'] == 'DESC')) {
//			$sql .= " DESC, LCASE(pd.name) DESC";
//		} else {
//			$sql .= " ASC, LCASE(pd.name) ASC";
//		}
                $sql .= " ORDER BY p.date_added DESC";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			
                        $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}
                //exit(var_dump($product_data));
		return $product_data;
	}

	public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT "
                            . "ps.product_id, "
                            . "(SELECT AVG(rating) "
                                . "FROM " . DB_PREFIX . "review r1 "
                                . "WHERE "
                                    . "r1.product_id = ps.product_id AND r1.status = '1' "
                                . "GROUP BY r1.product_id) AS rating "
                            . "FROM " . DB_PREFIX . "product_special ps "
                            . "LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = $this->cache->get('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewes DESC, p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					//'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}
	
        public function getProductCompls($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "' AND comp != '' ORDER BY sort_order ASC");
                
                if (!empty($query->row)){
                    $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."complects "
                                . "WHERE heading = '".$query->row['vin']."'");
                    if(!empty($sup->row)){
                        $prods = $this->db->query("SELECT * FROM ".DB_PREFIX."product p "
                                . "LEFT JOIN ".DB_PREFIX."product_description pd "
                                    . "ON pd.product_id = p.product_id "
                                . "WHERE p.comp = '".$sup->row['heading']."' OR p.vin = '".$sup->row['heading']."' ORDER BY pd.name ");
                        $complect['complect'] = $prods->rows;
                        
                        $complect['compl_price'] = $sup->row['price'];
                        $complect['link'] = $sup->row['link'];
                        $complect['whole'] = $sup->row['whole'];
                        $complect['c_id'] = $sup->row['id'];
                    } else {
                        $prods = $this->db->query("SELECT * FROM ".DB_PREFIX."product p "
                                . "LEFT JOIN ".DB_PREFIX."product_description pd "
                                    . "ON pd.product_id = p.product_id "
                                . "WHERE p.comp = '".$query->row['comp']."' OR p.vin = '".$query->row['comp']."' ORDER BY pd.name ");
                        $complect['complect'] = $prods->rows;
                        
                        foreach ($prods->rows as $prod) {
                            $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."complects "
                                . "WHERE heading = '".$prod['vin']."'");
                            if(!empty($sup->row)){
                                $complect['compl_price'] = $sup->row['price'];
                                $complect['link'] = $sup->row['link'];
                                $complect['whole'] = $sup->row['whole'];
                                $complect['c_id'] = $sup->row['id'];
                            }
                        }                        
                    }
                    return $complect;
                }
		return FALSE;
	}

	public function getProductRelated($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.vin) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.cond) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.type) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.note) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.catn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.stell) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.brand = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfile($product_id, $recurring_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r JOIN " . DB_PREFIX . "product_recurring pr ON (pr.recurring_id = r.recurring_id AND pr.product_id = '" . (int)$product_id . "') WHERE pr.recurring_id = '" . (int)$recurring_id . "' AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

		return $query->row;
	}

	public function getProfiles($product_id) {
		$query = $this->db->query("SELECT rd.* FROM " . DB_PREFIX . "product_recurring pr JOIN " . DB_PREFIX . "recurring_description rd ON (rd.language_id = " . (int)$this->config->get('config_language_id') . " AND rd.recurring_id = pr.recurring_id) JOIN " . DB_PREFIX . "recurring r ON r.recurring_id = rd.recurring_id WHERE pr.product_id = " . (int)$product_id . " AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
        
        public function getChilds($parent) {
            $query = $this->db->query("SELECT id, name FROM ".DB_PREFIX."brand WHERE parent_id = ".$parent." ORDER BY name ");
            if(!empty($query->rows)){
                $result = $query->rows;
                return $result;
            } else {
                return NULL;
            }
        }
        
        public function searchProducts($request) {
            $reqwords = explode(" ", $request);
            $query = "SELECT "
                    . "p.product_id AS product_id, "
                    . "pd.name AS name, "
                    . "p.vin AS vin, "
                    . "p.image AS image, "
                    . "p.note AS note, "
                    . "p.type AS ean, "
                    . "p.compability AS compability, "
                    . "p.catn AS cat_numb, "
                    . "p.price AS price, "
                    . "p.comp AS comp, "
                    . "p.minimum AS minimum, "
                    . "c.whole AS com_whole, "
                    . "c.price AS com_price "
//                    . "(SELECT c.whole "
//                        . "FROM " . DB_PREFIX . "complects c "
//                        . "WHERE c.heading = p.vin OR c.heading = p.comp) AS com_whole, "
//                    . "(SELECT c.price "
//                        . "FROM " . DB_PREFIX . "complects c "
//                        . "WHERE c.heading = p.vin OR c.heading = p.comp) AS com_price, "
                    . "FROM " . DB_PREFIX . "product p "
                            . "LEFT JOIN " . DB_PREFIX . "product_description pd "
                                . "ON (p.product_id = pd.product_id) "
                            . "LEFT JOIN ". DB_PREFIX . "complects c "
                                . "ON (p.vin = c.heading OR p.comp = c.heading) "
                            . "WHERE 1 ";
            //exit(var_dump($reqwords));
            if(count($reqwords)==1){
                $query.="AND (p.vin = '".$this->db->escape($reqwords[0])."' OR LOCATE ('".$this->db->escape($reqwords[0])."', p.catn)  OR p.category = '".$this->db->escape($reqwords[0])."' OR p.podcateg = '".$this->db->escape($reqwords[0])."' OR LOCATE ('" . $this->db->escape($reqwords[0]) . "', pd.name) OR LOCATE ('" . $this->db->escape($reqwords[0]) . "', p.compability) OR LOCATE ('" . $this->db->escape($reqwords[0]) . "', p.note)) ";
            } elseif (count($reqwords)>1) {
                foreach ($reqwords as $word){
                    $query.="AND (p.vin = '".$this->db->escape($word)."' OR LOCATE ('".$this->db->escape($word)."', p.catn)  OR p.category = '".$this->db->escape($word)."' OR p.podcateg = '".$this->db->escape($word)."' OR LOCATE ('" . $this->db->escape($word) . "', pd.name) OR LOCATE ('" . $this->db->escape($word) . "', p.compability) OR LOCATE ('" . $this->db->escape($word) . "', p.note)) ";
                }
            }
            $query.="AND status = 1 ";
            $query.="AND quantity > 0 ORDER BY p.date_added DESC";
//            exit($query);
            $result = $this->db->query($query);
            return $result->rows;
        }
        
        public function findAlters($request) {
            $search = explode(" ", trim($request));
            $resReq = '';
            $reqwords = array();
            $exceptions = array('задний', 'передний', 'верхний', 'нижний', 'левый', 'правый', 'центральный', 'задние', 'передние', 'верхние', 'нижние', 'левые', 'правые', 'центральные', 'задняя', 'передняя', 'верхняя', 'нижняя', 'левая', 'правая', 'центральная', 'заднее', 'переднее', 'верхнее', 'нижнее', 'левое', 'правое', 'центральное');
            foreach ($search as $word) {
                if(!in_array($word, $exceptions)){
                    $q = $this->db->query("SELECT name FROM ".DB_PREFIX."category_description WHERE LOCATE('".$word."', alters)");
                    if(!empty($q->rows)){
                        foreach($q->rows as $row){
                            if(!in_array($row['name'], $reqwords)){
                                $reqwords[] = $row['name'];
                            }
                        }
                    } else {
                        if(!in_array($word, $reqwords)){
                            $reqwords[] = $word;
                        }
                    }
                }
                
            }
            echo var_dump($reqwords).'<br>';
            exit($resReq);
            return trim($resReq);
        }
        
        public function searchVariants($request) {
            $exceptions = array('задний', 'передний', 'верхний', 'нижний', 'левый', 'правый', 'центральный', 'задние', 'передние', 'верхние', 'нижние', 'левые', 'правые', 'центральные', 'задняя', 'передняя', 'верхняя', 'нижняя', 'левая', 'правая', 'центральная', 'заднее', 'переднее', 'верхнее', 'нижнее', 'левое', 'правое', 'центральное');
            $search = explode(" ", $request);
            $result = array(
                'category'  => array(),
                'brand'     => array()
            );
            $sql = "SELECT name FROM ".DB_PREFIX."category_description WHERE ";
            
            
            foreach ($search as $word) {
                if(trim($word)!==''){
                    $sup = $this->db->query("SELECT name FROM ".DB_PREFIX."brand WHERE LOCATE('".$word."', transcript) OR LOCATE('".$word."', name) ");
                    if(!empty($sup->rows)){
                        $result['brand'][] = $sup->row['name'];
                    } else {
                        $sql.= "(LOCATE('".$word."', alters) OR LOCATE('".$word."', name)) AND ";
                    }
                }
            }
            $sql.= "1";
            $sup = $this->db->query($sql);
            if(!empty($sup->rows)){
                foreach ($sup->rows as $row) {
                    if(!in_array($row['name'], $result)){
                        $result['category'][] =  $row['name'];
                    }
                }
            }
            return $result;
        }
}
