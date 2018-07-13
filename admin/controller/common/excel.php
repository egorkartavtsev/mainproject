<?php
    class ControllerCommonExcel extends Controller {
    private $errors = array();
    private $alphabet = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 
                              'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'aa', 'ab', 'ac', 'ad', 'ae', 
                              'af', 'ag', 'ah');
    private $uploadTypes = array(
        'add'   => 'addProduct',
        'synch' => 'synchProds'
    );
    private $downloadTypes = array(
        'template'  => 'template',
        'prods'     => 'prods'
    );

        public function index($err = 0, $mat = 0) {
            $uType = $this->session->data['uType'];
            $uName = $this->session->data['username'];

            $this->load->model("tool/layout");
            $this->load->model("common/excel");
            $this->load->model("tool/excel");
            $data = $this->model_tool_layout->getLayout($this->request->get['route']);
            $data['types'] = $this->model_common_excel->getTypes();
            $data['uType'] = $uType;
            $data['broken'] = $err!=0?$err:NULL;
            $data['matches'] = $mat!=0?$mat:NULL;
            /*****************************************************************/
            $data['stocks'] = $this->model_tool_excel->getStocks();

            //Берём менеджеров
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."user WHERE 1 ");
            $data['managers'] = array();
            foreach ($query->rows as $man) {
                $data['managers'][$man['firstname']." ".$man['lastname']] = $man['user_id'];  
            }
            /*****************************************************************/

    //        $data['results_ex'] = $this->getDBFiles();        
            $data['results_ex'] = array();        
            $data['token_excel'] = $this->session->data['token'];
            $this->response->setOutput($this->load->view('common/excel', $data));
        }

        public function downloadTemplate() {
            
            $this->load->model('tool/product');
            $templ = $this->model_tool_product->getExcelTempl($this->request->get['type']);
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            
            $xls = new PHPExcel();
            $xls->setActiveSheetIndex(0);
            $sheet = $xls->getActiveSheet();
            foreach ($templ as $col) {
                $sheet->setCellValue($this->alphabet[$col['excel']].'1', $col['text']);
                $sheet->getColumnDimension($this->alphabet[$col['excel']])->setAutoSize(true);
                $sheet
                    ->getStyle($this->alphabet[$col['excel']].'1'.':'.$this->alphabet[$col['excel']].'1')
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('DDDDDD'); 
                $sheet->getStyle($this->alphabet[$col['excel']].'1'.':'.$this->alphabet[$col['excel']].'1')->applyFromArray($styleArray);        
                $sheet->getStyle($this->alphabet[$col['excel']].'1'.':'.$this->alphabet[$col['excel']].'1')->getFont()->setBold(true);
            }
            
            
            header ( "Expires: " . gmdate("D,d M YH:i:s") . " GMT" );
            header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
            header ( "Cache-Control: no-cache, must-revalidate" );
            header ( "Pragma: no-cache" );
            header ( "Content-type: application/vnd.ms-excel" );
            header ( "Content-Disposition: attachment; filename=temp.xls" );
            
            $objWriter = new PHPExcel_Writer_Excel5($xls);
            $objWriter->save('php://output');
        }
        
        public function getLayout() {


                    $this->load->language('common/excel');

                    $this->document->setTitle($this->language->get('heading_title'));

                    $data['heading_title'] = $this->language->get('heading_title');

                    $data['breadcrumbs'] = array();

                    $data['breadcrumbs'][] = array(
                            'text' => $this->language->get('text_home'),
                            'href' => $this->url->link('common/excel', 'token=' . $this->session->data['token'], true)
                    );

                    $data['breadcrumbs'][] = array(
                            'text' => $this->language->get('heading_title'),
                            'href' => $this->url->link('common/excel', 'token=' . $this->session->data['token'], true)
                    );
                    $data['header'] = $this->load->controller('common/header');
                    $data['column_left'] = $this->load->controller('common/column_left');
                    $data['footer'] = $this->load->controller('common/footer');

                    return $data;

        }

        public function upload() {
            if(isset($this->request->get['type'])){
                if(in_array($this->request->get['type'], $this->uploadTypes)){

                    /* список подключаемых модулей, объявление переменных */
                        $optype = $this->request->get['type'];
                        $this->load->controller('common/excelTools');
                        $tools = new ControllerCommonExcelTools($this->registry);
                        $products = array();
                    /********************************/

                        if (!empty($_FILES)){
                            $uploaddir = DIR_SITE . "/uploadeXcelfiles/";
                            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
                            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                $data['success_upload'] = "Файл ".$_FILES['userfile']['name']." успешно загружен на сервер, обработан. Товары занесены в базу данных.";
                                $this->db->query("INSERT INTO " . DB_PREFIX . "eXcel_files "
                                    . "SET name = '" . $this->db->escape($_FILES['userfile']['name']) . "', "
                                        . "going = '1', "
                                        . "timedate = NOW()");                 
                            } else {
                                $this->index('Ошибка загрузки файла. Попробуйте загрузить его снова или зовите срочно администратора!');
                            }
                        } else {
                            $this->index('Файл для зугрузки не выбран.');
                        }

                        $XLSrows = $this->readFile($uploadfile);
                        array_shift($XLSrows);
                        $products = $tools->constructProdArr($XLSrows);
                        $data['broken'] = $tools->errors;
                        $data['matches'] = $tools->matches;
                        //exit(var_dump($products));
                        if(!empty($products)){
                            //exit(var_dump($products));
                            $tools->$optype($products);
                            $data['broken'] = $tools->errors;
                            $data['matches'] = $tools->matches;
                            $this->index($data['broken'], $data['matches']);
                        } else {
                            $this->index($data['broken']);
                        }

                } else {
                    $this->index('Данный тип загрузки не найден. Зовите срочно администратора!');
                }
            } else {    
                $this->index('Что-то пошло совсем не так, как должно было. Зовите срочно администратора!');
            }
            unset($tools);
        }

        public function readFile($file) {
            $objPHPExcel = PHPExcel_IOFactory::load($file);
            $objPHPExcel->setActiveSheetIndex(0);
            $aSheet = $objPHPExcel->getActiveSheet();

            $array = array();
            foreach($aSheet->getRowIterator() as $row){
              $cellIterator = $row->getCellIterator();
              $item = array();
              foreach($cellIterator as $cell){
                array_push($item, $cell->getCalculatedValue());
              }
              array_push($array, $item);
            }

            $result = $array;
            return $result;
        }

        public function clearPhotos() {
            $this->load->model('common/excel');
            $listProd = $this->model_common_excel->getListProds();
            $image_direct = DIR_IMAGE.'catalog/demo/production';
            $dirsProds = scandir($image_direct);
            array_shift($dirsProds);
            array_shift($dirsProds);

            foreach ($dirsProds as $dir) {
                if(!in_array($dir, $listProd)){
                    $this->model_common_excel->removeDirectory($image_direct.'/'.$dir.'/');
                }
            }
            $this->index();
        }
        
        public function photToProd() {
            $this->load->model('common/excel');
            $listProdIDs = $this->model_common_excel->getListProds('product_id');
            $listProdVins = $this->model_common_excel->getListProds('sku');
            $products = array();
            for($i = 0; $i < count($listProdIDs); ++$i){
                $products[$i] = array(
                    'id'    => $listProdIDs[$i],
                    'vin'   => $listProdVins[$i]
                );
            }
            foreach ($products as $value) {
                $dir = 'catalog/demo/production/'.$value['vin'].'/';
                if(file_exists(DIR_IMAGE.$dir)){
                    if(!$this->model_common_excel->haveImg($value['id'])){
                        $this->model_common_excel->setImg($value['id'], $dir);
                    }
                }
            }
            $this->index('Фотографии успешно привязаны к товарам!');
        }
        
        public function downloadFile(){
            if(isset($this->request->get['type'])){
                $dType = $this->request->get['type'];
                $filter_data = $this->request->post;
                $method = 'download_'.$dType;
                if(in_array($dType, $this->downloadTypes)){
                    $this->load->controller('common/excelTools');
                    $tools = new ControllerCommonExcelTools($this->registry);
                    if(!empty($filter_data)){
                        $filter = $tools->constructFilter($filter_data);
                    } else {
                        $filter = FALSE;
                    }
                    $tools->$method($filter);
                    unset($tools);
                    $this->index('Файл сформирован и выгружен.', 0);
                } else {
                    $msg = 'Зовите администратора срочно. Тут кто-то накосячил.';
                    $this->index($msg, 0);
                }
            } else {
                $msg = 'Зовите администратора срочно. Тут кто-то накосячил.';
                $this->index($msg, 0);
            }   
        }
        
        public function searchingProds() {
            $request = trim($this->request->post['request']);
            $i = 0;
            $output = '<table class="table table-responsive table-bordered table-hover">'
                    . '<tbody>';
            if($request==''){
                $output.= 'Введите название детали';
            } else {
                $this->load->model('common/excel');
                $total = $this->model_common_excel->searchingProds($request);
                if(!empty($total)){
                    foreach ($total as $prod) {
                        if($i<30){
                            $output.='<tr>'
                                        . '<td>'.$prod['vin'].'</td>'
                                        . '<td>'.$prod['name'].'</td>'
                                    . '</tr>';
                            ++$i;
                        }
                    }
                }else{
                    exit('Ничего не найдено');
                }
            }
            $output.='</tbody></table>';
            echo $output;
        }
        
        public function updateImportantInfo() {
            $listProds = $this->db->query("SELECT 
                                p2c.product_id, 
                                p2c.category_id, 
                                c.parent_id, 
                                cd.name 
                        FROM `".DB_PREFIX."product_to_category` p2c 
                        LEFT JOIN `".DB_PREFIX."category` c ON p2c.category_id = c.category_id 
                        LEFT JOIN `".DB_PREFIX."category_description` cd ON c.category_id = cd.category_id 
                        WHERE c.parent_id = 0  
                        GROUP BY p2c.product_id ");
//            exit(var_dump($listProds->rows));
            foreach ($listProds->rows as $prod) {
                $query = "UPDATE `oc_product` SET `category`='".$prod['name']."' WHERE product_id = '".$prod['product_id']."'";
                $this->db->query($query);
            }
            exit('Look at db');
        }
        
        public function downloadAllProds() {
            $this->load->model("tool/excel");
            $flag = $this->request->get['flag'];
            $file = $this->model_tool_excel->updateFile($flag);
            if (file_exists($file)) {
                // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
                // если этого не сделать файл будет читаться в память полностью!
                if (ob_get_level()) {
                  ob_end_clean();
                }
                // заставляем браузер показать окно сохранения файла
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                // читаем файл и отправляем его пользователю
                readfile($file);
          }
            $this->response->redirect($this->url->link('common/excel', 'token='.$this->session->data['token']));
        }
        
    }
