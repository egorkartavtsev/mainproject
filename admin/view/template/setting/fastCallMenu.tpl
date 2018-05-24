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
      
      <div class="alert alert-success" id="FCPrev">
          <h4>Предварительный вид меню быстрого доступа: </h4>
          <?php foreach($fcItems as $key => $fc){ ?>
              <button class="btn btn-success" nameItem="<?php echo $key;?>" btn_type="dropFCItem"><i class="fa <?php echo $fc['icon'];?>"></i> <?php echo $fc['text']==''?$fc['name']:$fc['text'];?></button> 
          <?php }?>
      </div>
      
      <div id="totalAvalItems">
          <h4>Доступные пункты меню: </h4>
          <?php foreach($items as $fc){ ?>
            <?php foreach($fc['childs'] as $id => $item){ ?>
                <div class="pull-left"><button class="btn btn-info" nameItem="<?php echo $id;?>" btn_type="addFCItem"><i class="fa <?php echo $item['icon'];?>"></i> <?php echo $item['text'];?></button> </div> 
            <?php }?>
          <?php }?>
      </div>
  </div>
</div>

<?php echo $footer; ?>
