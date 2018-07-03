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
    <?php if ($error_install) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_install; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row">
        <div class="alert alert-success col-sm-12">
            <?php foreach($fcItems as $fc){ ?>
            <a class="btn btn-success" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo $fc['text'];?>" href="<?php echo $fc['href'];?>"><i class="fa <?php echo $fc['icon'];?>"></i> <?php echo $fc['text'];?></a> 
            <?php }?>
            <a href="index.php?route=setting/fastCallMenu&token=<?php echo $ses_token;?>" class="btn btn-info">настроить</a>
        </div>
    </div>
    <div class="row">
      <?php if(isset($notice['order'])){ 
                echo $notice['order'];
            }
            if(isset($notice['avito'])){ 
                echo $notice['avito'];
            }  
      ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>