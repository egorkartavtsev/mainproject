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
    </div>
  </div>
  <div class="container-fluid">
      <div class="col-md-3">
          <div class="alert alert-success">
              <table class="table table-hover">
                  <?php foreach($templates as $temp) { ?>
                    <tr>
                        <td><?php echo $temp['text'];?></td>
                        <td><button class="btn btn-block btn-info" onclick="showStructOptions('<?php echo $temp["type_id"]?>')"><i class="fa fa-pencil"></i></button></td>
                    </tr>
                  <? } ?>
              </table>
              <button class="btn btn-block btn-success" id="createType"><i class="fa fa-plus-circle"></i> создать новый тип товара</button>
          </div>
      </div>
      <div class="col-md-9" >
          <div id="options" hidden>&nbsp;</div>
      </div>
  </div>
</div>
<?php echo $modal;?>
<script type="text/javascript"><!--
    $('#myTabs a').click(function (e) {
      ckeditorInit('prodtypes', getURLVar('token'));
      e.preventDefault()
      $(this).tab('show')
    })
  //-->
</script>
<?php echo $footer; ?>
