function showStructOptions($parent){
    ajax({
      url:"index.php?route=setting/prodtypes/showOptions&token="+getURLVar('token'),
      statbox:"status",
      method:"POST",
      data: {id: $parent},
      success:function(data){
          $("#options").removeAttr('hidden');
          $("#options").html(data);
      }
    })
}
function addOption(){
    ajax({
      url:"index.php?route=setting/prodtypes/addOption&token="+getURLVar('token'),
      statbox:"status",
      method:"POST",
      data: {},
      success:function(data){
          $("#options").find("#newOpt").attr('disabled', 'true');
          $("#options").find("h4").after(data);
      }
    })
}
$(document).ready(function() {
    //save option of product
    $(document).on( "click", "#saveOpt", function() {
        
    });
    //translate option name
    $(document).on( "input", "#textOption", function() {
        ajax({
          url:"index.php?route=setting/prodtypes/translateOption&token="+getURLVar('token'),
          statbox:"status",
          method:"POST",
          data: {
              text: $(this).val()
          },
          success:function(data){
              alert($(this).parent().find("input[id*='name']").attr('disabled'));
              $(this).parent().find("#nameOption").removeAttr('disabled');
              $(this).parent().find("#nameOption").val(data);
          }
        })
    });
    //translate libr name
    $(document).on( "input", "#libr_text", function() {
        ajax({
          url:"index.php?route=setting/prodtypes/translateOption&token="+getURLVar('token'),
          statbox:"status",
          method:"POST",
          data: {
              text: $(this).val()
          },
          success:function(data){
              $("#libr_name").val(data);
          }
        })
    });
    $(document).on( "change", "#typeOption", function() {
        $(this).parent().parent().find("#valsOption").removeAttr('disabled');
        $(this).parent().parent().find("#defOption").removeAttr('placeholder');
        $(this).parent().parent().find("#defOption").attr('disabled', 'true');
    });
    $(document).on( "change", "#typeOption", function() {
        $(this).parent().parent().find("#valsOption").removeAttr('disabled');
        $(this).parent().parent().find("#defOption").removeAttr('placeholder');
        $(this).parent().parent().find("#defOption").attr('disabled', 'true');
    });
})

function addLibItem(){
    var field = '<div class="form-group col-lg-3"><div class="col-lg-10"><input type="text" class="form-control" fattr="libr_text" placeholder="Введите название поля(по-русски)" name="field[][text]"></div><div class="col-lg-1"><br><i class="fa fa-arrow-circle-o-right" style="font-size: 14pt;"></i></div></div>';
    $("#openline").before(field);
}

function saveLib(){
    $("#libr_name").removeAttr('disabled');
    $("#libraryForm").submit();
}