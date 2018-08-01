<?php
namespace Cart;
class User {
	private $user_id;
	private $username;
        private $userlayout = array(
            'fcmenu' => array(),
            'leftcolumn' => array()
        );
        private $minaccesslevel;
        private $useral;
	private $permission = array();
        private $info;
        

	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1'");
			if ($user_query->num_rows) {
                                $this->setLayout($user_query->row['user_id']);
                                $this->info = $user_query->row;
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];
				$this->user_group_id = $user_query->row['user_group_id'];

				$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

				$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

				$permissions = json_decode($user_group_query->row['permission'], true);
                                
                                
                                
				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($username, $password) {
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape(htmlspecialchars($password, ENT_QUOTES)) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

		if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];

			$this->user_id = $user_query->row['user_id'];
			$this->username = $user_query->row['username'];
			$this->user_group_id = $user_query->row['user_group_id'];

			$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			$permissions = json_decode($user_group_query->row['permission'], true);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		unset($this->session->data['user_id']);

		$this->user_id = '';
		$this->username = '';
	}

        public function setLayout($user_id) {
            $access_level_query = $this->db->query("SELECT u.userAL, ug.minlevel FROM ".DB_PREFIX."user u "
                    . "LEFT JOIN ".DB_PREFIX."user_group ug ON u.user_group_id = ug.user_group_id "
                    . "WHERE user_id = ".(int)$user_id);
            $this->useral = $access_level_query->row['userAL'];
            $this->minaccesslevel = $access_level_query->row['minlevel'];
            $modules_query = $this->db->query("SELECT * FROM ".DB_PREFIX."modules "
                    . "WHERE ((accesslvl >= ".(int)$this->minaccesslevel." AND accesslvl <= ".(int)$this->useral.") OR accesslvl = 0) AND parent_id = 0 AND showmenu = 1");
                    
            $layout = array();
            foreach($modules_query->rows as $mod){
                $controllers_query = $this->db->query("SELECT * FROM ".DB_PREFIX."modules "
                    . "WHERE ((accesslvl >= ".(int)$this->minaccesslevel." AND accesslvl <= ".(int)$this->useral.") OR accesslvl = 0) AND parent_id = ".(int)$mod['id']." AND showmenu = 1");    
                $layout[$mod['id']] = array(
                    'name' => $mod['name'],
                    'description' => $mod['description'],
                    'icon' => $mod['icon'],
                    'text' => $mod['text'],
                    'childs' => array()
                );
                foreach ($controllers_query->rows as $controller) {
                    $layout[$mod['id']]['childs'][$controller['id']] = array(
                        'description' => $controller['description'],
                        'name' => $controller['name'],
                        'icon' => $controller['icon'],
                        'text' => $controller['text'],
                        'href' => $mod['name'].'/'.$controller['name']
                    );
                }
            }
            $this->userlayout['leftcolumn'] = $layout;
            $fcmenu = array();
            $fcquery = $this->db->query("SELECT fast_call FROM ".DB_PREFIX."user_customs WHERE user_id = ".(int)$user_id);
            $fcitems = explode(";", $fcquery->row['fast_call']);
            foreach($fcitems as $cid){
                if(trim($cid)!=''){
                    $sup = $this->db->query("SELECT *, (SELECT m1.name FROM ".DB_PREFIX."modules m1 WHERE m1.id = m.parent_id) AS module FROM ".DB_PREFIX."modules m WHERE id = ".(int)trim($cid));
                    $fcmenu[trim($cid)] = array(
                        'icon' => $sup->row['icon'],
                        'text' => $sup->row['text'],
                        'href' => 'index.php?route='.$sup->row['module'].'/'.$sup->row['name'].'&token='.$this->session->data['token']
                    );
                }
            }
            $this->userlayout['fcmenu'] = $fcmenu;
        }
        
	public function hasPermission($key, $value) {
            $module = explode("/", $value);
            if(isset($module[1])){
                $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."modules "
                        . "WHERE name = '".$module[1]."' "
                            . "AND parent_id = (SELECT id FROM ".DB_PREFIX."modules WHERE name = '".$module[0]."' AND parent_id = 0)");
                if($sup->num_rows){
                    $accessLVL = $sup->row['accesslvl'];
                    if(($accessLVL <= $this->useral && $accessLVL >= $this->minaccesslevel) || ($accessLVL === '0')){
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
	}

	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getUserName() {
		return $this->username;
	}
	
        public function getUserInfo() {
            $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."user_group WHERE user_group_id = ".(int)$this->user_group_id);
            $this->info['user_group'] = $sup->row['text'];
		return $this->info;
	}

	public function getGroupId() {
		return $this->user_group_id;
	}
        
        public function getLayout() {
            return $this->userlayout;
        }
}
