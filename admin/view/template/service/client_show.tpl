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
      <div class="col-md-4 alert alert-success">
          <h4>Информация о клиенте</h4>
          <table class="table table-bordered">
              <?php foreach($client as $info){ ?>
                <tr>
                    <td><?php echo $info['text'];?></td>
                    <td><?php echo $info['value'];?></td>
                </tr>
              <?php }?>
          </table>
      </div>
      <div class="col-md-4 alert alert-success">
          <h4>Автомобили клиента</h4>
          <?php foreach($auto as $key => $car){ ?>
          <div class="well a2cItem" target="<?php echo $key;?>">
              <p></p>
              <p></p>
              <p></p>
          </div>
          <?php }?>
          <button class="btn btn-block btn-success" btn_type="createAuto" data-toggle="modal" data-target="#autocreateModal"><i class="fa fa-plus-circle"></i> добавить</button>
      </div>
      <div class="col-md-4 alert alert-success">
          <h4>Заказ-наряды</h4>
      </div>
      <div class="clearfix"><p></p></div>
      <div class="clearfix"></div>
      <div class="col-md-4 alert alert-success">
          <h4>Договора с клиентом</h4>
      </div>
  </div>
</div>
<?php echo $modal_auto;?>
<?php echo $footer; ?>
