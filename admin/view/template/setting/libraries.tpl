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
      <div class="h4 well well-sm">
          <i class="fa fa-warning fw"></i> <?php echo $description; ?><br>
      </div>
      <ul class="list-unstyled list-inline">
          <?php foreach($libr_list as $lib){ ?>
            <li><a class="btn btn-success" href="<?php echo $lib['href']?>"><i class="fa fa-pencil-square-o"></i> Библиотека: <?php echo $lib['text']?></a></li>
          <?php } ?>
      </ul>
      <?php if(isset($success)) { ?><div class="h4 alert alert-success"><i class="fa fa-life-ring fw"></i> <?php echo $success; ?></div><? } ?>
    </div>
  </div>
  <div class="container-fluid">
      <form method="post" class="form" id="libraryForm" action="index.php?route=setting/libraries&token=<?php echo $token;?>">
          <div class="form-group col-lg-6">
              <label for='libr_text'>Название библиотеки</label>
              <input type="text" class="form-control" id="libr_text" name="libr_text" placeholder="Введите название библиотеки (по-русски)...">
          </div>
          <div class="form-group col-lg-6">
              <label for='libr_name'>Транслит названия(заполняется автоматически)</label>
              <input type="text" class="form-control" id="libr_name" name="libr_name" disabled>
          </div>
          <div class="form-group col-lg-12">
              <label for='libr_description'>Описание библиотеки</label>
              <input type="text" class="form-control" id="libr_description" name="libr_description" placeholder="Введите описание библиотеки...">
          </div>
          <div class="clearfix" id="openline"></div>
          <div class="clearfix"><p></p></div>
          
          
      </form>
      
      <button class="btn btn-success" onclick="addLibItem()"><i class="fa fa-plus fw"></i> добавить поле.</button>
      <button class="btn btn-info" onclick="saveLib()"><i class="fa fa-floppy-o fw"></i> сохранить библиотеку.</button>
  </div>
</div>

<?php echo $footer; ?>
