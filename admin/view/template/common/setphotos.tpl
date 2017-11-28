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
  <?php if (isset($error) && $error>0) { ?>
    <div class="alert alert-danger">
        <?php echo $error_info; ?>
    </div>
  <?php } ?>
  <?php if (isset($success_message)) { ?>
    <div class="alert alert-success">
        <?php echo $success_message; ?>
    </div>
  <?php } ?>
  <div class="container-fluid">
    <div class="well well-sm" id="status">
        <form class="row" role="group" enctype="multipart/form-data" action="index.php?route=common/setphotos/upload&token=<?php echo $token_add; ?>" method="POST">
            <div class="form-group-lg col-lg-3" style="float: left;">
                <input class="form-control" name="vin" type="text" placeholder="Введите внутренний номер" />
            </div>
            <div class="form-group-lg col-lg-3" style="float: left;">
                <input class="btn btn-default" name="photo[]" type="file" multiple="true">
            </div>
            <div class="form-group-lg col-lg-3" style="float: left;">
                <input class="btn btn-success" type="submit" value="Загрузить">
            </div>
        </form>
    </div>
  </div>
</div>
<?php echo $footer;?>