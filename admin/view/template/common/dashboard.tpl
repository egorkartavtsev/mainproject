<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <?php if($messages){ ?>
      <div class="row">
          <div class="col-lg-12">
              <div class="alert alert-danger">
              </div>
          </div>
      </div>
    <?php }?>
    <div class="row">
        <div class="col-md-6">
            <div class="alert alert-success">
                <h3>Привет, <b><?php echo $user['first'];?></b>! Добро пожаловать в ASM.</h3><h4><?php echo $version;?></h4>
                <div class="col-md-6">
                    <img src="<?php echo $user['avatar'];?>" class="img-responsive img-thumbnail img-circle" title="Тут должен быть аватар">
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <td class="h4"><?php echo $user['first']." ".$user['last'];?></td>
                        </tr>
                        <tr>
                            <td class="h4"><?php echo $user['group'];?></td>
                        </tr>
                        <tr>
                            <td class="h4"><?php echo $user['email'];?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-success">
                <h3><b>Меню быстрого доступа:</b></h3>
                <?php foreach($fcItems as $fc){ ?>
                    <a class="btn btn-success" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo $fc['text'];?>" href="<?php echo $fc['href'];?>"><i class="fa <?php echo $fc['icon'];?>"></i> <?php echo $fc['text'];?></a> 
                <?php }?>
                <a href="index.php?route=setting/fastCallMenu&token=<?php echo $ses_token;?>" class="btn btn-info">настроить</a>
            </div>
            <?php if(isset($notice['order'])){ 
                      echo $notice['order'];
                  }
                  if(isset($notice['avito'])){ 
                      echo $notice['avito'];
                  }  
            ?>
        </div>
    </div>
    <div class="row">
    </div>
  </div>
</div>
<?php echo $footer; ?>