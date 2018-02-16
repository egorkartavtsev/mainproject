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
        </div>
    </div>
    <?php echo $result;?>
    <div class="container-fluid">
        <div class="col-lg-12" id="srcBox">
            <div class="form-group alert alert-success">
                <label for="searchProd">Введите внутренний номер товара</label>
                <input type="text" id="searchProd" class="form-control" />
                <p>&nbsp;</p>
                <button class="btn btn-success" id="btnSrc"><i class="fa fa-search-plus"></i> найти</button>
            </div>
        </div>
        <p>&nbsp;</p>
        <div class="col-lg-12">
            <div class="alert alert-success hide" id="prodInfo"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#btnSrc").on('click', function(){
        ajax({
            url: "index.php?route="+getURLVar('route')+"/getInfo&token="+getURLVar('token'),
            method: "POST",
            data:{
                sku: $("#searchProd").val()
            },
            success:function(data){
                $("#prodInfo").removeClass('hide');
                $("#prodInfo").html(data);
            }
        })
    })
</script>
<?php echo $footer;?>