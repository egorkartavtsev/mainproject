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
    <div id="content" class=" <?php echo $class; ?> "><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
        <table class="table table-bordered table-hover">
          <tbody>
           <div action="<?php echo $action; ?>" id="formIdentification" method="post" enctype="multipart/form-data" class="form-horizontal">
          <div class="form-group required">
           <fieldset>
            <div class="col-sm-10">
                     <label type="text" name="emailUser"  id=""><?php echo $textIdentificdtion; ?> </label>
                     <input type="text" name="codeEmail" value="" placeholder="" id="input-codeEmail" class="form-control" />
            </div>
           </fieldset>
          </div>
          </div>
                 <div class="buttons clearfix" >
            <div class="pull-right"><input class="btn btn-primary" type="submit" name="button1" id="btn-codeEmail"></div>
            </div>
          </tbody>
        </table>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '#btn-codeEmail', function(){  
             $.ajax({
                    url: 'index.php?route=account/identification/bottonCompleteClick',
                    method: 'post',
                    data:{},
                    success:function(data){
                        if(data){
                            //location.href = 'index.php?route=account/account';  
                            console.log(data)
                            alert(''<?php echo $textTIdentificdtion; ?>'');
                            alert(data);
                            
                        } else {
                            alert('Введен неверно код идентификации');
                        }
                    }
                });
            });
        });
    </script>
<?php echo $footer; ?>