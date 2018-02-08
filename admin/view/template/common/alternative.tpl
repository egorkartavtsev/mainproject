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
  <div class="container-fluid">
      <h4>Выберите категорию:</h4>
      <div class="col-md-12 alert-success">
          <div class="form-group col-md-6">
              <select id="mCat" class="form-control">
                  <option disabled selected>Выберите категорию</option>
                  <?php foreach($categories as $cat){ ?>
                      <option value="<?php echo $cat['category_id'];?>"><?php echo $cat['name'];?></option>
                  <?php } ?>
              </select>
          </div>
      </div>
      <p>&nbsp;</p>
      <h4>(!!!)Важно: Для разделения синонимов используйте ";". Все специальные знаки ("*", "/", "+", "<", ">", ".", "$" и т.д.) будут стёрты.</h4>
      <p>&nbsp;</p>
      <div class="col-md-12" id="subCats"></div>
  </div>
</div>
<script>
    $("#mCat").on('change', function(){
        ajax({
            url: "index.php?route=common/alternative/getSupCat&token="+getURLVar('token'),
            statbox: "status",
            method: "POST",
            data:
            {
                parent: $(this).val()
            },
            success:function(data){
                $("#subCats").html(data);
            }
        })
    });
    
    function getAlters($id){
        if($("#alts"+$id).val()!==''){
            $("#save"+$id).removeAttr('disabled');
        } else {
            $("#save"+$id).attr('disabled', 'true');
        }
    }
    
    function saveAlters($id){
        ajax({
            url: "index.php?route=common/alternative/saveAlter&token="+getURLVar('token'),
            statbox: "status",
            method: "POST",
            data:
            {
                id: $id,
                alters: $("#alts"+$id).val()
            },
            success:function(data){
                $("#save"+$id).attr('disabled', 'true');
                $("#alts"+$id).val(data);
            }
        })
    }
</script>
<?php echo $footer; ?>