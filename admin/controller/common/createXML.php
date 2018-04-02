<?php

class ControllerCommonCreateXML extends Controller {
    public function index() {
        $ignore = array(
			'common/dashboard',
			'common/startup',
			'common/login',
			'common/logout',
			'common/forgotten',
			'common/reset',			
			'common/footer',
			'common/header',
			'error/not_found',
			'error/permission'
		);

		$data['hiden'] = array();
		$data['permissions'] = array();

		$files = array();

		// Make path into an array
		$path = array(DIR_APPLICATION . 'controller/*');

		// While the path array is still populated keep looping through


		while (count($path) != 0) {
			$next = array_shift($path);
                        $g_files = glob($next);
                        if (!$g_files) $g_files = array();
			foreach ($g_files as $file) {

				// If directory add to path array
				if (is_dir($file)) {
					$path[] = $file . '/*';
				}

				// Add the file to the files to be deleted array
				if (is_file($file)) {
					$files[] = $file;
				}
			}
		}

		// Sort the file array
		sort($files);
					
		foreach ($files as $file) {
			$controller = substr($file, strlen(DIR_APPLICATION . 'controller/'));



			$permission = substr($controller, 0, strrpos($controller, '.'));

			$hidefiles = explode("/", $permission);
            //var_dump($hidefiles);

			if ($hidefiles[1] == "module" or $hidefiles[1] == "payment" or $hidefiles[1] == "shipping") {
				if (!in_array($permission, $ignore)) {
					$data['hiden'][] = $permission;
				}
			}

			if (!in_array($permission, $ignore)) {
                            $data['permissions'][] = $permission;
                            $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."controllers WHERE controller = '".$permission."'");
                            if(empty($sup->row)){
                                $this->db->query("INSERT INTO ".DB_PREFIX."controllers SET controller = '".$permission."' ");
                            }
			}
		}
    }
}