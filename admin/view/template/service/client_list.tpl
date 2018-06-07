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
      <div class="col-lg-12">
          <div class="alert alert-success">
              <div class="form-group-sm col-md-4">
                  <label>Введите Фамилию, Имя и(или) Отчество клиента</label>
                  <input type="text" id="filter_fio" class="form-control" value="<?php echo isset($filter_fio)?$filter_fio:'';?>">
              </div>
              <div class="form-group-sm col-md-4">
                  <label>Введете телефон клиента(без "8" и слитно. Н-р: 951458...)</label>
                  <input type="text" id="filter_phone" class="form-control" value="<?php echo isset($filter_phone)?$filter_phone:'';?>">
              </div>
              <div class="form-group-sm col-md-4">
                  <label>Введите город(населённый пункт) клиента</label>
                  <input type="text" id="filter_city" class="form-control" value="<?php echo isset($filter_city)?$filter_city:'';?>">
              </div>
              <div class="clearfix"></div>
              <div class="clearfix"><p></p></div>
              <button class="btn btn-sm btn-primary" id="button-filter" btn_type="apply_filter"><i class="fa fa-filter"></i>Применить фильтр</button>
              <button class="btn btn-sm btn-danger" btn_type="clear_filter"><i class="fa fa-trash-o"></i>Очистить фильтр</button>
          </div>
      </div>
      <div class="row">
          <div class="col-md-4">
            <?php if(count($clients)){ ?>
                <?php foreach($clients as $key => $row){ ?>
                  <div class="client_item" div_type="client" client_id="<?php echo $key;?>">
                      <p><?php echo $key;?> - <?php echo $row['name'];?></p>
                      <p><?php echo $row['adress'];?></p>
                      <p><?php echo $row['phone'];?></p>
                      <p><span class="label label-success"><?php echo $row['legal'];?></span></p>
                  </div>
                <?php }?>
            <?php echo $pagination;?>
            <?php } else { ?>
            <div class="alert alert-danger">
                <h3 style="font-style: italic;">К сожалению список пуст. 
                                                Вы можете изменить значения фильтров или 
                                                <a class="label label-success" href="index.php?route=service/client&token=<?php echo $token;?>">
                                                    <i class="fa fa-plus-circle"></i> завести нового клиента
                                                </a>
                </h3></div>
            <?php }?>
          </div>
          <div class="col-md-8" id="client_info"></div>
      </div>
      <div class="clearfix"><p></p></div>
      <div class="clearfix"></div>
  </div>
</div>
<script type="text/javascript">
  $('[btn_type=clear_filter]').click(function(){
      location = 'index.php?route=service/client_list&token='+getURLVar('token');
  });
  $('[btn_type=apply_filter]').click(function(){
      var url = '';
      $(this).parent().find("input[id*='filter']").each(function(){
          if($(this).val()!==''){
              url = url+'&'+$(this).attr('id')+'='+$(this).val();
          }
      })
      location = 'index.php?route=service/client_list&token='+getURLVar('token')+url;
  });
  $('input[id*=\'filter\']').on('keydown', function(e) {
        if (e.keyCode == 13) {
                $('#button-filter').trigger('click');
        }
  });
</script>
<?php echo $footer; ?>
