<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $library['text']; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <div class="h4 well well-sm"><i class="fa fa-warning fw"></i> <?php echo $library['description']; ?></div>
    </div>
  </div>
  <div class="container-fluid">
      <?php foreach($library['struct'] as $key => $item){ ?>
        <div class="<?php echo $library['divClass']?>">
            <h3><?php echo $item['text']; ?></h3>
            <div class="well well-sm" library-id="<?php echo $library_id;?>">
                <table class="table table-bordered table-striped table-hover" id="<?php echo $item['name']?>" level="<?php echo $key+1;?>" item-id="<?php echo $item['item_id']?>">
                    <?php if($key==0){ ?> 
                    <?php foreach($library['mainFills'] as $row){ ?>
                    <tr id="fill<?php echo $row['id'];?>" fill_id='<?php echo $row["id"];?>' ><td td_type="fillName"><?php echo $row['name']?></td><td><button class="btn btn-info" btn_type="changeFill"><i class="fa fa-pencil" ></i></button><button class="btn btn-danger" btn_type="deleteFill"><i class="fa fa-trash-o"></i></button></td></tr>
                    <?php }?>
                    <tr><td class="text-center" colspan="2"><button class="btn btn-success" item_level="<?php echo $key + 1;?>" id="addItem<?php echo $key + 1;?>" fill-parent="0"><i class="fa fa-plus-circle"></i> добавить элемент</button></td></tr>
                    <?php }?>
                </table>
            </div>
        </div>
      <?php } ?>
  </div>
</div>

<?php echo $footer; ?>
