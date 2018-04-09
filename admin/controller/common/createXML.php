<?php

class ControllerCommonCreateXML extends Controller {
    public function index() {
        $sup = $this->db->query("SELECT * FROM ".DB_PREFIX."");
    }
}