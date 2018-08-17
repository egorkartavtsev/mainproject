<?php

class ModelReportDonor extends Model{
    public function getVariants($request) {
        $words = explode(" ", $request);
        $sql = "SELECT name, id, numb FROM ".DB_PREFIX."donor WHERE 1 ";
        foreach ($words as $word) {
            $sql.= "AND (LOCATE('".$word."', name) OR LOCATE('".$word."', numb)) ";
        }
        $sup = $this->db->query($sql);
        return $sup->rows;
    }
    public function getDonorInfo($request) {
        /*---------------------------------------------------------*/
            $quant = 0;
            $totalq = 0;
            $saleq = 0;
            $totalItemPrice = 0;
            $totalSalePrice = 0;
//            $currItems = array();
//            $saledItems = array();
            $donor = array(
                'info'=>array(),
                'currItem'=>array(),
                'saleItem'=>array()
            );
        /*---------------------------------------------------------*/
        $sql = "SELECT * FROM ".DB_PREFIX."donor WHERE numb = '".$request."'";
        $dquer = $this->db->query($sql);
        $sql = "SELECT "
                //-----------------------
                    . "p.product_id AS pid, "
                    . "p.image AS image, "
                    . "p.vin AS vin, "
                    . "p.stock AS stock, "
                    . "p.location AS location, "
                    . "p.price AS price, "
                    . "p.category AS category, "
                    . "p.quantity AS quant, "
                    . "p.status AS status, "
                    . "p.date_added AS date_added, "
                    . "si.saleprice AS saleprice, "
                    . "si.sku AS svin, "
                    . "si.name AS sname, "
                    . "pd.name AS itemname "
                //-----------------------
                ."FROM `".DB_PREFIX."product` p "
                ."LEFT JOIN `".DB_PREFIX."sales_info` si ON si.sku = p.vin "
                ."LEFT JOIN `".DB_PREFIX."product_description` pd ON pd.product_id = p.product_id "
                ."WHERE p.numb = '".$request."'";
        $iquer = $this->db->query($sql);
        $tableCurr = '<div class="clearfix"></div>
                      <div class="clearfix"><p></p></div>
                      <h3>Остаток товаров:</h3>
                      <table class="table table-bordered table-hover table-responsive">
                          <thead>
                            <tr>

                              <td class="text-center">Изображение</td>
                              <td>Название</td>
                              <td>Внутренний номер</td>
                              <td>Расположение</td>
                              <td>Цена</td>
                              <td>Категория</td>
                              <td>Количество</td>
                              <td class="text-left">Статус</td>
                              <td class="text-left">Дата создания</td>
                              <td class="text-left">Количество дней на складе</td>
                            </tr>
                          </thead>
                          <tbody>';
        $tableSale = '<div class="clearfix"></div>
                      <div class="clearfix"><p></p></div>
                      <h3>Проданные товары:</h3>
                      <table class="table table-bordered table-hover table-responsive">
                          <thead>
                            <tr>

                              <td class="text-center">Изображение</td>
                              <td>Название</td>
                              <td>Внутренний номер</td>
                              <td>Расположение</td>
                              <td>Цена</td>
                              <td>Категория</td>
                              <td>Количество</td>
                              <td class="text-left">Статус</td>
                              <td class="text-left">Дата создания</td>
                              <td class="text-left">Количество дней на складе</td>
                            </tr>
                          </thead>
                          <tbody>';
        $donor['info'] = $dquer->row;
        foreach ($iquer->rows as $item) {
            $date_added = DateTime::createFromFormat('Y-m-d H:i:s', $item['date_added'])->format('d.‌​m.Y');
            $status = $item['status']=='0'?'<span class="label label-danger">ОТКЛЮЧЕН</span>':'<span class="label label-success">ВКЛЮЧЕН</span>';
            if (is_file(DIR_IMAGE . $item['image'])) {
                        $image = $this->model_tool_image->resize($item['image'], 40, 40);
                } else {
                        $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                }
            $now = time();
            $added = strtotime($item['date_added']);
            $dateDif = abs($added-$now);
            $dateRes = floor($dateDif/(60*60*24));
            if($dateRes>=361){$class='label label-danger';}
            elseif($dateRes<361 && $dateRes>=181){$class='label label-warning';}
            elseif($dateRes<181 && $dateRes>=91){$class='label label-info';}
            elseif($dateRes<91 && $dateRes>=0){$class='label label-success';}
            if($item['svin']==NULL){
                $donor['currItem'][] = $item;
                $totalItemPrice+=$item['price'];
                ++$quant;
                $tableCurr.='<tr>'
                                . '<td><img src="'.$image.'" alt="'.$item['itemname'].'" class="img-thumbnail" /></td>'
                                . '<td>'.$item['itemname'].'</td>'
                                . '<td>'.$item['vin'].'</td>'
                                . '<td>'.$item['stock'].'/'.$item['location'].'</td>'
                                . '<td>'.$item['price'].'</td>'
                                . '<td>'.$item['category'].'</td>'
                                . '<td>'.$item['quant'].'</td>'
                                . '<td>'.$status.'</td>'
                                . '<td>'.$date_added.'</td>'
                                . '<td><span class="'.$class.'">'.$dateRes.'</span></td>'
                          . '</tr>';
            } else {
                $donor['saleItem'][] = $item;
                $totalSalePrice+=$item['saleprice'];
                ++$saleq;
                $tableSale.='<tr>'
                                . '<td><img src="'.$image.'" alt="'.$item['itemname'].'" class="img-thumbnail" /></td>'
                                . '<td>'.$item['itemname'].'</td>'
                                . '<td>'.$item['vin'].'</td>'
                                . '<td>'.$item['stock'].'/'.$item['location'].'</td>'
                                . '<td>'.$item['price'].'</td>'
                                . '<td>'.$item['category'].'</td>'
                                . '<td>'.$item['quant'].'</td>'
                                . '<td>'.$status.'</td>'
                                . '<td>'.$date_added.'</td>'
                                . '<td><span class="'.$class.'">'.$dateRes.'</span></td>'
                          . '</tr>';
            }
            ++$totalq;
        }
        $tableCurr.='</tbody></table>';
        $tableSale.='</tbody></table>';
        $donor['info']['totalSalePrice'] = $totalSalePrice;
        $donor['info']['currQuant'] = $quant;
        $donor['info']['totalItemPrice'] = $totalItemPrice;
        $donor['info']['saleQuant'] = $saleq;
        $donor['info']['totalQuant'] = $totalq;
        $donor['info']['percent'] = floor(($donor['info']['totalSalePrice']*100) / $donor['info']['price']);
        $donor['info']['tableItems'] = $tableCurr.$tableSale;
        return $donor;
    }
}