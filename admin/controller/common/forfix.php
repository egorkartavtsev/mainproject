<?php

class ControllerCommonForFix extends Controller {
    public function index() {
        if(!empty($this->request->post)){
            foreach ($this->request->post['info'] as $pid => $length) {
                if(($length!=='0') && ($length!='length') && ($length!=' Lacetti 2003-2013')){
                    $this->db->query("UPDATE ".DB_PREFIX."product SET length = '".$length."' WHERE product_id = ".(int)$pid);
                }
            }
        }
        $results = array();
        $query = $this->db->query("SELECT pd.name, p.product_id, p.length, p.sku FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id "
                . "WHERE LOCATE('>', pd.name) AND p.length = 'length' OR p.length = '0' ORDER BY p.product_id");
        $i = 1;
        $table = '<form method="post" action="'.$this->url->link('common/forfix', 'token='.$this->session->data['token']).'"><table>';
        foreach ($query->rows as $prod) {
            $table.='<tr>';
            $table.='<td style="border: solid 1px #000">'.$i.'</td>';
            $table.='<td style="border: solid 1px #000">'.$prod['product_id'].'</td>';
            $table.='<td style="border: solid 1px #000">'.$prod['sku'].'</td>';
            $table.='<td style="border: solid 1px #000; text-align: right;">'.$prod['name'].'</td>';
            $table.='<td style="border: solid 1px #000"><input type="text" name="info['.$prod['product_id'].']" value="'.trim($prod['length']).'"></td>';
            $table.='</tr>';
            ++$i;
        }
        $table.= '<input type="submit" value="save"></table></form>';
        echo $table;
    }
}