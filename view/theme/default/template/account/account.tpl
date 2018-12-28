<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row" >
    <?php echo $column_left; ?>
    <div class="col-md-8">
        <?php if(!$approved){ ?>
            <div class="row">
                <div class="alert alert-danger">
                    <p>Ваша аккаунт не прошел проверку. Нажмите на кнопку и пройдите по ссылке</p>
                       <div class="buttons clearfix" >
                       <div class="pull-right"><input class="btn btn-primary" type="submit" name="button1" id="btn-codeEmail"></div>
                       </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-md-5">
            <div id="content" class="panel panel-default">
              <div class="panel-heading"><h2><?php echo $text_my_account; ?></h2></div>
              <div class="btn-group-vertical panel-body col-sm-12" role="group">
                <a class="btn btn-block btn-danger" href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a>
                <a class="btn btn-block btn-danger" href="<?php echo $password; ?>"><?php echo $text_password; ?></a>
                <a class="btn btn-block btn-danger" href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a>
                <a class="btn btn-block btn-danger" href="<?php echo $order; ?>"><?php echo $text_order; ?></a>
                <?php if ($reward) { ?>
                <a class="btn btn-block btn-danger" href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a>
                <?php } ?>
                <a class="btn btn-block btn-danger" href="<?php echo $return; ?>"><?php echo $text_return; ?></a>
                <a class="btn btn-block btn-danger" href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a>
              </div>
              <?php echo $content_bottom; ?>
            </div>
        </div>
        <div class="col-md-7">
           <div id="contentInfo" class="panel panel-default"> 
            <div class="panel-heading"><h2><?php echo "Информация о вас" ?></h2></div>    
                <div class="panel-body" role="group">
                    <p style="font-weight: bold">Имя: <label class="control-label"><?php echo $lastname; ?> </label></p>  
                    <p style="font-weight: bold">Фамилия: <label class="control-label"><?php echo $firstname; ?> </label></p>   
                    <p style="font-weight: bold">Отчество: <label class="control-label"><?php echo $patron; ?> </label></p>   
                    <p style="font-weight: bold">Email: <label class="control-label"><?php echo $email; ?></label>
                        <?php if($approved) { ?>
                            <label class="label label-success">Подтверждено</label>
                        <?php } else { ?>
                            <label class="label label-danger">Не подтверждено</label>
                        <?php } ?>
                        </p>   
                </div>    
            </div>
        </div>
    </div>    
    <?php echo $column_right; ?>      
    
  </div>
</div>
<script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '#btn-codeEmail', function(){
            var btn = $(this);
            $.ajax({
                    url: 'index.php?route=account/identification/bottonCompleteClick',
                    type: 'post',
                    dataType: 'json',
                    success:function(json){
                        btn.parent().parent().parent().removeClass("alert-danger");
                        btn.parent().parent().parent().addClass("alert-success");
                        btn.parent().parent().parent().html(json['message']);
                    }
                });
                return false;
            });
        });
    </script>
<?php echo $footer; ?> 
