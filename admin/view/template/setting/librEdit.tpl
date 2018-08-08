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
      <div class="h4 well well-sm"><i class="fa fa-warning fw"></i> <?php echo $library['description']; ?> 
          <button class="btn btn-success" type="button" data-toggle="modal" data-target="#settings"><i class="fa fa-wrench fw"></i> настройки</button></div>
    </div>
  </div>
  <div class="container-fluid">
      <?php foreach($library['struct'] as $key => $item){ ?>
        <div class="<?php echo $library['divClass']?>">
            <h3><?php echo $item['text']; ?> <button class="btn btn-success" type="button" data-toggle="modal" data-target="#settingsLevel" btn_type="levelSettings" item="<?php echo $item['item_id'];?>"><i class="fa fa-wrench fw"></i></button></h3>
            <div class="well well-sm" library-id="<?php echo $library_id;?>">
                <table class="table table-bordered table-striped table-hover" id="<?php echo $item['name']?>" level="<?php echo $key+1;?>" item-id="<?php echo $item['item_id']?>">
                    <?php if($key==0){ ?> 
                    <?php foreach($library['mainFills'] as $row){ ?>
                    <tr id="fill<?php echo $row['id'];?>" fill_id='<?php echo $row["id"];?>' ><td td_type="fillName"><?php echo $row['name']?></td><td><button class="btn btn-info" btn_type="changeFill" type="button" data-toggle="modal" fill="<?php echo $row['id'];?>" data-target="#settingsLevel" btn_type="levelSettings" ><i class="fa fa-pencil" ></i></button><button class="btn btn-danger" btn_type="deleteFill"><i class="fa fa-trash-o"></i></button></td></tr>
                    <?php }?>
                    <tr><td class="text-center" colspan="2"><button class="btn btn-success" item_level="<?php echo $key + 1;?>" id="addItem<?php echo $key + 1;?>" fill-parent="0"><i class="fa fa-plus-circle"></i> добавить элемент</button></td></tr>
                    <?php }?>
                </table>
            </div>
        </div>
      <?php } ?>
  </div>
</div>
<div class="modal fade" id="settingsLevel" tabindex="-1" role="dialog" aria-labelledby="settingsLevelLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="settingsLevelLabel">Настройки</h4>
      </div>
      <div class="modal-body" id="level-settings">
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<!--//////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="settings" tabindex="-1" role="dialog" aria-labelledby="settingsLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="settingsLabel">Настройки</h4>
      </div>
      <div class="modal-body">
          <div class="col-lg-12 form-group">
            <div class="col-lg-8"><label for="librName">Наименование библиотеки:</label>
            <input class="form-control" id="librName" type="text" type_id="<?php echo $library['settings']['library_id'];?>" inp_type="librSettInp" value="<?php echo $library['settings']['name'];?>"/></div>
            <label>&nbsp;</label><br><button class="btn btn-success" target="text" disabled btn_type="librSetSave"><i class="fa fa-floppy-o"></i></button>
          </div>
          <div class="col-lg-12 form-group">
            <div class="col-lg-8"><label for="showNav">Отображение в верхнем меню витрины:</label>
            <select class="form-control" id="showNav" inp_type="librSettSlct" type_id="<?php echo $library['settings']['library_id'];?>">
                <option value="0" <?php if($library['settings']['top_nav']==='0'){echo 'selected';}?>>Не отображать</option>
                <option value="1" <?php if($library['settings']['top_nav']==='1'){echo 'selected';}?>>Отображать</option>
            </select>
            </div>
            <label>&nbsp;</label><br><button class="btn btn-success" target="top_nav" disabled btn_type="librSetSave"><i class="fa fa-floppy-o"></i></button>
          </div>
          <div class="col-lg-12 form-group">
            <div class="col-lg-8"><label for="showNav">Отображение в форме создания товара:</label>
            <select class="form-control" id="showNav" inp_type="librSettSlct" type_id="<?php echo $library['settings']['library_id'];?>">
                <option value="0" <?php if($library['settings']['smart']==='0'){echo 'selected';}?>>Выбор вариантов</option>
                <option value="1" <?php if($library['settings']['smart']==='1'){echo 'selected';}?>>"Умный" поиск</option>
            </select>
            </div>
            <label>&nbsp;</label><br><button class="btn btn-success" target="smart" disabled btn_type="librSetSave"><i class="fa fa-floppy-o"></i></button>
          </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').focus();
    });
</script>
<?php echo $footer; ?>
