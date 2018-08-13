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
      <div class="row">
        <div>

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <?php foreach($types as $type){ ?>
            <li role="presentation"><a href="#dt<?php echo $type['type_id'];?>" aria-controls="dt<?php echo $type['type_id'];?>" role="tab" data-toggle="tab"><?php echo $type['text'];?></a></li>
            <?php }?>
            <li role="presentation" class="active"><a href="#prod" aria-controls="prod" role="tab" data-toggle="tab">Описание товара на сайте</a></li>
            <li role="presentation"><a href="#avito" aria-controls="avito" role="tab" data-toggle="tab">Описание товара на Авито</a></li>
            <li role="presentation"><a href="#drom" aria-controls="drom" role="tab" data-toggle="tab">Описание товара на Drom</a></li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
              <?php foreach($types as $type){ ?>
                <div role="tabpanel" class="tab-pane" id="dt<?php echo $type['type_id'];?>">
                  <div class="col-lg-12 form-group">
                        <div class="col-lg-8"><label for="templName">Маска наименования продуктов данного типа:</label>
                        <input class="form-control" id="templName" type="text" type_id="<?php echo $type['type_id'];?>" value="<?php echo $type['temp'];?>"/></div>
                        <label>&nbsp;</label><br><button class="btn btn-success" disabled btn_type="tempNameSave"><i class="fa fa-floppy-o"></i></button>
                  </div>
                  <div class="col-lg-6 form-group">
                      <form action="index.php?route=common/desctemp&token=<?php echo $token;?>" method="POST">
                        <label>Шаблон описания продуктов данного типа:</label>
                        <textarea name="template" data-lang="1" class="form-control summernote"><?php echo $type['desctemp']; ?></textarea>
                        <p></p>
                        <input type="hidden" name="type_id" class='btn btn-primary' value="<?php echo $type['type_id']?>">
                        <input type="submit" class='btn btn-primary' value="Сохранить">
                      </form>
                  </div>
                    <div class="col-lg-6 alert alert-success">
                        <table class="table table-bordered">
                            <?php foreach($type['options'] as $option){ ?>
                            <tr>
                                <td><?php echo '%'.$option['name'].'%';?></td>
                                <td><?php echo $option['text'];?></td>
                            </tr>
                            <?php }?>
                        </table>
                    </div>
                </div>
              <?php }?>
              <div role="tabpanel" class="tab-pane active" id="prod">
                  <div class="col-lg-6">
                    <textarea id="desctempl" data-lang="1" class="form-control summernote"><?php echo (isset($description_prod))?$description_prod : '12321'; ?></textarea>
                    <p></p>
                    <button class='btn btn-primary' onclick="sub_temp('desctempl', '1')">Сохранить</button>
                    <button class='btn btn-success' onclick="app_temp('<?php echo $token;?>')">Применить шаблон</button>
                  </div>
                  <div class="col-lg-6 alert alert-success">
                      <h3>Таблица регулярных переменных шаблона:</h3>
                      <table class="table-bordered col-lg-12">
                          <thead>
                            <tr>
                                <td class="col-lg-2">Переменная: </td>
                                <td>Значение:</td>
                            </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td><p>%mark%</p></td>
                                  <td><p>Марка</p></td>
                              </tr>
                              <tr>
                                  <td><p>%mr%</p></td>
                                  <td><p>Модельный ряд</p></td>
                              </tr>
                              <tr>
                                  <td><p>%model%</p></td>
                                  <td><p>Модель</p></td>
                              </tr>
                              <tr>
                                  <td><p>%podcat%</p></td>
                                  <td><p>Подкатегория</p></td>
                              </tr>
                              <tr>
                                  <td><p>%prim%</p></td>
                                  <td><p>Примечание</p></td>
                              </tr>
                          </tbody>
                      </table>
                      <p>
                          (!!!)Важно: переменные вставляются в текст в том виде, в каком они представлены в таблице! 
                          Иначе замена переменной значением не произойдёт и описание будет выглядеть не так, как Вы ожидали.
                      </p>
                  </div>
                  <p class="col-lg-12">&nbsp;</p>
                  <div class="col-lg-12" id='statbox' style="height: 50px; padding-bottom: 15px;"></div>
              </div>
              <div role="tabpanel" class="tab-pane" id="avito">
                  <h3><b>(!!!)Важно:</b> при редактировании описания товара на Авито, используйте только "выделение жирным" и "список"! <u>Иначе объявления будут блокироваться на сайте.</u></h3>
                  <div class="col-lg-6">
                    <textarea id="templAvito" data-lang="1" class="form-control summernote"><?php echo (isset($description_avito))?$description_avito : '12321'; ?></textarea>
                    <p></p>
                    <button class='btn btn-primary' onclick="sub_temp('templAvito', '2')">Сохранить</button>
                  </div>
                  <div class="col-lg-6 alert alert-success">
                      <h3>Таблица регулярных переменных шаблона:</h3>
                      <table class="table-bordered col-lg-12">
                          <thead>
                            <tr>
                                <td class="col-lg-2">Переменная: </td>
                                <td>Значение:</td>
                            </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td><p>%brand%</p></td>
                                  <td><p>Марка</p></td>
                              </tr>
                              <tr>
                                  <td><p>%trbrand%</p></td>
                                  <td><p>Марка(русская транскрипция)</p></td>
                              </tr>
                              <tr>
                                  <td><p>%modrow%</p></td>
                                  <td><p>Модельный ряд</p></td>
                              </tr>
                              <tr>
                                  <td><p>%trmodrow%</p></td>
                                  <td><p>Модельный ряд(русская транскрипция)</p></td>
                              </tr>
                              <tr>
                                  <td><p>%vin%</p></td>
                                  <td><p>Внутренний номер</p></td>
                              </tr>
                              <tr>
                                  <td><p>%podcat%</p></td>
                                  <td><p>Подкатегория</p></td>
                              </tr>
                              <tr>
                                  <td><p>%note%</p></td>
                                  <td><p>Примечание</p></td>
                              </tr>
                              <tr>
                                  <td><p>%condit%</p></td>
                                  <td><p>Состояние</p></td>
                              </tr>
                              <tr>
                                  <td><p>%compabil%</p></td>
                                  <td><p>Совместимость</p></td>
                              </tr>
                              <tr>
                                  <td><p>%dopinfo%</p></td>
                                  <td><p>Дополнительная информация</p></td>
                              </tr>
                              <tr>
                                  <td><p>%weekend%</p></td>
                                  <td><p>Выходные дни</p></td>
                              </tr>
                          </tbody>
                      </table>
                      <p>
                          (!!!)Важно: переменные вставляются в текст в том виде, в каком они представлены в таблице! 
                          Иначе замена переменной значением не произойдёт и описание будет выглядеть не так, как Вы ожидали.
                      </p>
                  </div>
                  <p class="col-lg-12">&nbsp;</p>
                  <div class="col-lg-12" id='statbox' style="height: 50px; padding-bottom: 15px;"></div>
              </div>
              <div role="tabpanel" class="tab-pane" id="drom">
                  <div class="col-lg-6">
                    <textarea id="dromtempl" data-lang="1" class="form-control summernote"><?php echo (isset($description_drom))?$description_drom : '12321'; ?></textarea>
                    <p></p>
                    <button class='btn btn-primary' onclick="sub_temp('dromtempl', '3')">Сохранить</button>
                  </div>
                  <div class="col-lg-6 alert alert-success">
                      <h3>Таблица регулярных переменных шаблона:</h3>
                      <table class="table-bordered col-lg-12">
                          <thead>
                            <tr>
                                <td class="col-lg-2">Переменная: </td>
                                <td>Значение:</td>
                            </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td><p>%brand%</p></td>
                                  <td><p>Марка</p></td>
                              </tr>
                              <tr>
                                  <td><p>%modR%</p></td>
                                  <td><p>Модельный ряд</p></td>
                              </tr>
                              <tr>
                                  <td><p>%model%</p></td>
                                  <td><p>Модель</p></td>
                              </tr>
                              <tr>
                                  <td><p>%podcateg%</p></td>
                                  <td><p>Подкатегория</p></td>
                              </tr>
                              <tr>
                                  <td><p>%note%</p></td>
                                  <td><p>Примечание</p></td>
                              </tr>
                              <tr>
                                  <td><p>%dop%</p></td>
                                  <td><p>Дополнительная информация</p></td>
                              </tr>
                              <tr>
                                  <td><p>%compabitity%</p></td>
                                  <td><p>Применимость</p></td>
                              </tr>
                              <tr>
                                  <td><p>%adress%</p></td>
                                  <td><p>Адрес</p></td>
                              </tr>
                              <tr>
                                  <td><p>%cond%</p></td>
                                  <td><p>Состояние</p></td>
                              </tr>
                              <tr>
                                  <td><p>%cond%</p></td>
                                  <td><p>Состояние</p></td>
                              </tr>
                          </tbody>
                      </table>
                      <p>
                          (!!!)Важно: переменные вставляются в текст в том виде, в каком они представлены в таблице! 
                          Иначе замена переменной значением не произойдёт и описание будет выглядеть не так, как Вы ожидали.
                      </p>
                  </div>
                  <p class="col-lg-12">&nbsp;</p>
                  <div class="col-lg-12" id='statbox' style="height: 50px; padding-bottom: 15px;"></div>
              </div>
          </div>

        </div>
      </div>
  </div>
</div>
<script type="text/javascript"><!--
  <?php if ($ckeditor) { ?>
    <?php foreach ($languages as $language) { ?>
      ckeditorInit('desctemp', getURLVar('token'));
    <?php } ?>
  <?php } ?>
  //-->
    $('#myTabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })
</script>
<?php echo $footer; ?>