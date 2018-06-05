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
      <div class="h4 well well-sm"><i class="fa fa-warning fw"></i> <?php echo $description; ?></div>
      <?php if(isset($success)){ ?><div class="h4 alert alert-success"><i class="fa fa-warning fw"></i> <?php echo $success; ?></div><?php }?>
    </div>
  </div>
  <div class="container-fluid">
      <div class="row">
          <div class="col-md-8 form-group-sm">
              <select class="form-control" select_type = "client_type" target="client" data="legal">
                  <option disabled selected>--Выберите тип клиента--</option>
                  <option value="0">Юридическое лицо</option>
                  <option value="1">Физическое лицо</option>
              </select>
          </div>
      </div>
      <div class="clearfix"><p></p></div>
      <div class="clearfix"></div>
      <div class="row" id="form_client"></div>
  </div>
</div>
<?php echo $footer; ?>
