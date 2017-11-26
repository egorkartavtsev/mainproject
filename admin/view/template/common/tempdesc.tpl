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
          <div class="col-lg-6">
            <textarea id="desctempl" data-lang="1" class="form-control summernote"><?php echo (isset($description))?$description : '12321'; ?></textarea>
            <p></p>
            <button class='btn btn-primary' onclick="sub_temp('<?php echo $token;?>')">Сохранить</button>
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
  </div>
</div>
<script type="text/javascript"><!--
  <?php if ($ckeditor) { ?>
    <?php foreach ($languages as $language) { ?>
      ckeditorInit('desctemp', getURLVar('token'));
    <?php } ?>
  <?php } ?>
  //--></script>
<?php echo $footer; ?>