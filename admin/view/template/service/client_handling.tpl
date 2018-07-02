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
        <?php if(!(int)$client['legal']){ ?>
            <h3><?php echo $client['name'];?></h3>
        <?php } else { ?>
            <h3><?php echo $client['secondname'];?> <?php echo $client['firstname'];?> <?php echo $client['patronymic'];?></h3>
        <?php }?>
        <form action="<?php echo $action;?>" method="POST">
        <div class="col-md-6">
            <div class="form-group">
                <label>Выберите авто</label>
                <select class="form-control" name="handling[auto]" id="auto_id">
                    <?php foreach($auto as $car){ ?>
                    <option value="<?php echo $car['id'];?>" <?php echo (isset($handling) && $handling['auto']==$car['id'])?'selected':''; ?>><?php echo $car['numb'];?> <?php echo $car['brand'];?> <?php echo $car['model'];?></option>
                    <?php }?>
                </select>
            </div>
            <div class="form-group">
                <label>Примечание</label>
                <input type="text" name="handling[note]" class="form-control" id="note" value="<?php echo isset($handling)?$handling['note']:''; ?>">
                <input type="hidden" name="handling[client]" class="form-control" id="client" value="<?php echo $client['id'];?>">
            </div>
            <div class="form-group">
                <label>Тип обращения</label>
                <select select_type="handling_acc" class="form-control" id="auto_id" <?php echo (isset($handling) && isset($accident))?'disabled':''; ?>>
                    <option value="0">Обычное обращение</option>
                    <option value="1" <?php echo (isset($handling) && isset($accident))?'selected':''; ?>>Обращение по ДТП</option>
                </select>
            </div>
            <div class="clearfix"></div>
            <div class="clearfix"><p></p></div>
            <button class="btn btn-success" title="Сохраняет обращение в базу. После сохранения можно прикреплять услуги">СОХРАНИТЬ</button>
            <a class="btn btn-primary" btn_type="enable_edit">РЕДАКТИРОВАТЬ</a>
            <div class="clearfix"></div>
            <div class="clearfix"><p></p></div>
            <h3>Список услуг, оказанных по обращению: </h3>
            <?php if(isset($contracts)){ ?>
                <div class="row">
                </div>
            <?php } ?>
            <a class="btn btn-block btn-success" btn_type="contract" client="<?php echo $client['id']?>" data-toggle="modal" data-target="#contractcreateModal" target-contract="0"><i class="fa fa-plus-circle"></i> добавить</a>
        </div>
            <div class="col-md-6" id="accident_info">
                <?php if(isset($accident_form)){ echo $accident_form; }?>
            </div>
        </form>
    </div>
</div>
<?php echo isset($modal_contract)?$modal_contract:''; ?>
<?php echo $footer; ?>
