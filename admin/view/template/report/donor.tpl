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
      <div class="col-sm-12 dropdown" id="searchbox">
          <div class="alert alert-success form-group">
              <label for="dsearch">Введите внутренний номер донора, год выпуска, VIN, марку или модель</label>
              <input type="text" class="form-control" id="dsearch">
              <div class="clearfix"></div>
          </div>
          <ul class="dropdown-menu dropdown-menu-left" id="searchResult">
          </ul>
      </div>
      <div class="clearfix"></div>
      <div class="clearfix"><p></p></div>
      <div class="col-sm-12" id="donorInfo"></div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/reportscript.js"></script>
<link href="view/stylesheet/reportstyles.css" type="text/css" rel="stylesheet" />
<?php echo $footer; ?>