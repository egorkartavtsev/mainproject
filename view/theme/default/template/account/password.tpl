<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend><?php echo $text_password; ?></legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
     <script type="text/javascript">
    $(document).ready(function() {
         $(document).on('input', '#formRegist #input-password', function(){
            $('#errorPass').remove();
            $(this).parent().parent().find('#input-confirm').attr('disabled', 'disabled');
            if($(this).val().length>=6){
                if(validation($(this).attr('data-type'), $(this).val())){
                    $(this).parent().parent().find('#input-confirm').removeAttr('disabled');
                } else {
                    $(this).after('<p class="alter alert-danger" id="errorPass">Пароль содержит запрещённые символы!</p>');
                    $(this).parent().parent().find('#lastStep').attr('disabled', 'true');
                }
            } else {
                $(this).after('<p class="alter alert-danger" id="errorPass">Пароль слишком короткий!</p>');
                $(this).parent().parent().find('#lastStep').attr('disabled', 'true');
            }
        });
        $(document).on('input', '#formRegist #input-confirm', function(){
            $('#errorConf').remove();
            if($(this).val() === $(this).parent().parent().find('#input-password').val()){
                $(this).parent().parent().find('#lastStep').removeAttr('disabled');
            } else {
                $(this).parent().parent().find('#lastStep').attr('disabled', 'true');
                $(this).after('<p class="alter alert-danger" id="errorConf">Введённые пароли не совпадают!</p>');
            }
        });
         $.ajax({
                url: 'index.php?route=account/password',
                method: 'post',
                data:{form:form},
                success:function(data){
                    if(data){
                        location.href = 'index.php?route=account/password';                        
                    } else {
                        alert('Возникла ошибка на сервере. Пожалуйста повторите попытку. Надеемся на Ваше понимание.');
                    }
                }
            });
    }
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>