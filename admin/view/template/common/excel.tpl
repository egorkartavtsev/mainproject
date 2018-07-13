<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
      <?php if (isset($success_upload)) { echo '<div class="alert alert-success"><p>'.$success_upload.'</p></div>';}?>
      <?php if ($broken!==NULL) { 
                echo '<div class="alert alert-danger">'; 
                if(is_array($broken)) { 
                    foreach($broken as $error) {
                        echo '<p>'.$error.'</p>'; 
                    }
                } else {
                    echo '<p>'.$broken.'</p>';
                }
                if(isset($matches)){
                    echo 'В загруженном файле находилось <b>'.$matches.'</b> продуктов уже имеющихся в базе(исключая те, что указаны выше).';
                }
                echo '</div>';
            }?>      
      <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-download"></i>&nbsp;Выгрузка товаров</a></li>
            <li role="presentation" class="active"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-upload"></i>&nbsp;Загрузка товаров</a></li>
            <li role="presentation"><a href="#sync" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-caret-square-o-right"></i>&nbsp;Синхронизация</a></li>
            <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><i class="fa fa-th-list"></i>&nbsp;Операции с файлами</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="home">
                <div class="col-lg-6">
                    <form class="alert alert-success" method="POST" action="index.php?route=common/excel/downloadFile&type=prods&token=<?php echo $token_excel;?>">
                        <h3>Настройте фильтр товаров для выгрузки</h3>
                        <hr>
                        <div class="form-group col-lg-6">
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' class="form-control" name="date" id="date" placeholder="Начальная дата"/>
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <div class='input-group date' id='datetimepicker2'>
                                <input type='text' class="form-control" name="date1" id="date" placeholder="Конечная дата"/>
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <?php if ($uType == 'adm') { ?>
                            <div class="form-group col-lg-6">
                                <select name='manager' class='form-control'>
                                    <option value="all">Выберите менеджера( = Все менеджеры)</option>
                                    <?php foreach ($managers as $key => $man_id) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $key; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php } ?>
                        <div class="form-group  col-lg-6">
                            <select name='type' class='form-control'>
                                <?php foreach ($types as $type) { ?>
                                    <option value="<?php echo $type['type_id']; ?>"><?php echo $type['text']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <hr>
                        <h3>Выберите склад</h3>

                        <?php foreach($stocks as $stock) { ?>
                        <div class='col-lg-3' style="float: left;">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="stock[<?php echo $stock['id']; ?>]" value="<?php echo $stock['name']; ?>"> <?php echo $stock['name']; ?>  
                                    </label>
                                </div>
                            </div>
                        <?php }?>
                        <div class="clearfix"></div>
                        <div class="clearfix"><p></p></div>
                        <div class="col-lg-6">
                            <hr>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="prod_on"> Выгрузить товары "в наличии"
                                </label>
                                <br>
                                <label>
                                    <input type="checkbox" name="prod_off"> Выгрузить товары "не в наличии"<br>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Выгрузить товары</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <h3>Скачать шаблон</h3>
                    <span class="label label-default">Скачайте на жёсткий диск шаблон для загрузки нескольких товаров в магазин</span>
                    <?php foreach($types as $type){ ?>
                        <hr>
                        <a href="index.php?route=common/excel/downloadTemplate&type=<?php echo $type['type_id']?>&token=<?php echo $token_excel;?>" class="btn btn-block btn-success"><i class="fa fa-download"></i>&nbsp;Скачать шаблон "<?php echo $type['text'];?>"</a>
                    <?php }?>
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"><p></p></div>
                <div class="col-lg-6">
                    <h3>Выгрузка товаров магазина</h3>
                    <span class="label label-default">Скачайте на жёсткий диск список товаров Вашего магазина</span>
                    <?php foreach($types as $type){ ?>
                        <hr>                    
                        <a target="blank" disabled title="Временно недоступно" href="index.php?route=common/excel/downloadAllProds&flag=prodList&token=<?php echo $token_excel;?>" class="btn btn-block btn-danger"><i class="fa fa-download"></i>&nbsp;Выгрузка всех товаров "<?php echo $type['text'];?>"</a>
                    <?php }?>
                </div>
                <div class="clearfix"></div>
                <hr>
            </div>
            <div role="tabpanel" class="tab-pane fade in active" id="messages">
                <h3>Загрузка товаров в магазин</h3>
                <span class="label label-default">Загрузите eXcel файл на сервер для обновления базы товаров магазина</span>
                <hr>
                <button disabled title="Временно недоступно" type="button" class="btn btn-block btn-success" id="upload"><i class="fa fa-upload"></i>&nbsp;Загрузка товаров</button>
                <div class="alert alert-success" id="up_form" style="margin-top: 5px; display: none;">
                    <div class="col-lg-12">
                        <div class="col-lg-6">
                            <form class="btn-group" role="group" enctype="multipart/form-data" action="index.php?route=common/excel/upload&type=addProduct&token=<?php echo $token_excel;?>" method="POST">
                                <input class="btn btn-default" name="userfile" type="file"/>
                                <input class="btn btn-success" type="submit" value="Отправить файл" />
                            </form>
                        </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="sync">
                <h3>Синхронизация товаров и фото</h3>
                <span class="label label-success">Загрузите eXcel файл на сервер для очистки фото у товаров, которые отсутствуют на складе</span><br>
                <span class="label label-warning">Программа удалит фотографии, непривязанные к товарам</span><br>
                <span class="label label-danger">Программа привяжет фотографии к их товарам.</span><br>
                <hr>
                <div class="col-lg-3">
                    <button disabled title="Временно недоступно" type="button" class="btn btn-block btn-success" id="synch"><i class="fa fa-upload"></i>&nbsp;Обновить список товаров</button>
                </div>
                <div class="col-lg-3"> 
                    <a type="button" href="index.php?route=common/excel/clearPhotos&token=<?php echo $token_excel;?>" class="btn btn-block btn-warning"><i class="fa fa-dedent"></i>&nbsp;Очистить фотографии.</a>
                </div>
                <div class="col-lg-3">
                    <a type="button" href="index.php?route=common/excel/PhotToProd&token=<?php echo $token_excel;?>" class="btn btn-block btn-danger"><i class="fa fa-code-fork"></i>&nbsp;Привязать фотографии.</a>
                </div>
                
                <div class="alert alert-success" id="s_form" style="margin-top: 60px; display: none; padding-bottom: 20px;">
                    <div class="col-lg-12">
                        <h3>Обновить список товаров</h3>
                        <div class="col-lg-6">
                            <form class="btn-group" role="group" enctype="multipart/form-data" action="index.php?route=common/excel/upload&type=synchProds&token=<?php echo $token_excel;?>" method="POST">
                                <input class="btn btn-default" name="userfile" type="file"/>
                                <input class="btn btn-success" type="submit" value="Отправить файл" />
                            </form>
                        </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->
                </div>
                
            </div>
            <div role="tabpanel" class="tab-pane fade" id="settings">
                <h3>Операции с файлами</h3>
                <span class="label label-default">Отслеживайте движение файлов в магазине</span>
                <hr>
                <div class="col-lg-12">
                    <table class="table table-striped"> 
                        <thead> 
                          <tr> 
                            <th>id</th> 
                            <th>Название документа</th> 
                            <th>Дата загрузки</th> 
                            <th>#</th> 
                          </tr> 
                        </thead> 
                        <tbody>
                            <?php
                                foreach($results_ex as $res){
                                    echo '<tr>'
                                        .'<th scope="row">'.$res['id'].'</th>'
                                        .'<td>'.$res['name'].'</td>'
                                        .'<td>'.$res['timedate'].'</td>'
                                        .'<td>'.$res['going'].'</td>'
                                        .'</tr>';
                                }
                            ?> 
                        </tbody> 
                      </table>
                </div>
            </div>
        </div>

      </div>
  </div>
</div>
<script type="text/javascript">
      $('#myTabs a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
      });
      $(function () {
        $('#datetimepicker1').datetimepicker();
      });
      $(function () {
        $('#datetimepicker2').datetimepicker();
      });
      $( "#upload" ).click(function() {
        $( "#up_form" ).animate({
          height: "toggle"
        }, 300, function() {
        });
      });
      $( "#synch" ).click(function() {
        $( "#s_form" ).animate({
          height: "toggle"
        }, 300, function() {
        });
      });
      $( "#upd" ).click(function() {
        $( "#u_form" ).animate({
          height: "toggle"
        }, 500, function() {
        });
      });
      
      
      
      
</script>
<?php echo $footer;?>