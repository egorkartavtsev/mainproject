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
      <div class="col-sm-12">
        <div class="col-sm-3 form-group-sm">
            <label>Выберите тип создаваемого товара:</label>
            <?php echo $firstSelect;?>
        </div>
        <label>&nbsp;</label><br>
        <button class="btn btn-success btn-sm" num="0" btn_type="addProduct"><i class="fa fa-plus"></i> добавить товар</button>
        <button class="btn btn-info btn-lg pull-right" btn_type="saveProdList"><i class="fa fa-floppy-o"></i> Сохранить товары</button>
      </div>
      <div>&nbsp;</div>
      <div class="col-lg-12">
          <form action="<?php echo $formaction;?>" name="prodList" enctype="multipart/form-data" method="POST">
              <h3 id="prodListHeader">Список добавляемых продуктов:</h3>
          </form>
      </div>
  </div>
</div>
<!-- Modal -->
    <div class="modal fade" id="createFillModal" tabindex="-1" role="dialog" aria-labelledby="createFillModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="createFillLabel">Введите название нового элемента</h4>
          </div>
          <div class="modal-body">
            <div class="form-group-sm">
                <input type="text" class="form-control" id="fillname"/>
            </div>
            <div class="row" id="result"></div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="createFill" disabled>Создать</button>
          </div>
        </div>
      </div>
    </div>
<style>
    .cpbItem{
        cursor: pointer;
    }
    .searchItem{
        cursor: pointer;
        padding: 3px 8px;
    }
    .searchItem:hover{
        background-color: rgba(100, 100, 100 , 0.25);
    }
</style>
<?php echo $footer; ?>
