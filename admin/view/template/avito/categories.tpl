<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <div class="pull-right">
                <button class="btn btn-success" id="subBtn" onclick="$('#categForm').submit();" disabled><i class="fa fa-floppy-o"></i> сохранить изменения</button>
            </div>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="col-sm-12">
        <form method="POST" action="index.php?route=common/avito/saveForm&token=<?php echo $token; ?>" id="categForm">
            <div class="col-sm-5 form-group">
                <label for="categories">Выберите категорию</label>
                <select class="form-control" id="categories" name="maincategory">
                    <option value="none">Выберите категорию</option>
                    <?php foreach($categories as $cat){ ?>
                        <option value="<?php echo $cat['id'];?>"><?php echo $cat['name']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-7 form-group" id="divCA" hidden>
                <label for="catAvito">Введите id с Авито. Этот id применится для всех подкатегорий. Либо оставьте пустым.</label>
                <input type="text" name="catAvito" class="form-control" id="catAvito" />
            </div>
            <div class="col-sm-12" id="catTable">
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $("#categories").on('change', function(){
        if($("#categories").val() === 'none'){
            $("#catTable").html('');
            $("#divCA").attr('hidden', 'true');
            $("#subBtn").attr('disabled', 'true');
        } else {
            $("#divCA").removeAttr('hidden');
            $("#subBtn").removeAttr('disabled');
            ajax({
                url:"index.php?route=common/avito/getSubCat&token=" + getURLVar('token'),
                statbox:"status",
                method:"POST",
                data:
                {
                    par: $("#categories").val()
                },
                success:function(data){
                    $("#catTable").html(data);
                }
            })
        }
    })
</script>
<?php echo $footer; ?>
