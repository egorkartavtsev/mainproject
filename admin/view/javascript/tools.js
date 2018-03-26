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
    //show cilds of items fill
    $(document).on( "click", "tr[id*='fill']", function() {
        var level = $(this).attr('item_level');
        var parent = $(this).attr('fill_id');
        var curRow = $(this);
        level = ++level;
        var cObject = $("[level="+level+"]");
        ajax({
          url:"index.php?route=setting/libraries/getChilds&token="+getURLVar('token'),
          statbox:"status",
          method:"POST",
          data: {
              parent: parent,
              level: level
          },
          success:function(data){
              curRow.parent().find("tr[id*='fill']").removeAttr('style');
              curRow.css('background-color', '#ffa6a680');
              cObject.html(data);
          }
        })
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
    
    //chenge fill name
    $(document).on( "click", "[btn_type=changeFill]", function() {
        var old_name = $(this).parent().parent().find("td[td_type='fillName']");
        old_name.html('<input type="text" class="form-control" id="newName" value="'+old_name.text()+'">');
        $(this).parent().html('<button class="btn btn-success" btn_type="saveChangeFillName"><i class="fa fa-floppy-o" ></i></button>');
    });
    //save new fill name
    $(document).on( "click", "[btn_type=saveChangeFillName]", function() {
        var field = $(this).parent().parent().parent().parent().attr('id');
        var fill_id = $(this).parent().parent().attr('fill_id');
        var parent = $(this).parent();
        ajax({
          url:"index.php?route=setting/libraries/saveChangeFillName&token="+getURLVar('token'),
          statbox:"status",
          method:"POST",
          data: {
              id: fill_id,
              field: field,
              name: parent.parent().find("#newName").val()
          },
          success:function(data){
            if(data === '0'){
              alert('Возникла внутренняя ошибка');
              return FALSE;
            } else {
              parent.html('<button class="btn btn-info" btn_type="changeFill"><i class="fa fa-pencil" ></i></button><button class="btn btn-danger" btn_type="deleteFill"><i class="fa fa-trash-o"></i></button>');
              parent.parent().find("td[td_type='fillName']").html(parent.parent().find("#newName").val());
            }
          }
        })
        $(this).parent().html('<button class="btn btn-success" btn_type="saveChangeFillName"><i class="fa fa-floppy-o" ></i></button>');
    });
    //delete fill
    $(document).on( "click", "[btn_type=deleteFill]", function() {
            ajax({
              url:"index.php?route=setting/libraries/deleteFill&token="+getURLVar('token'),
              statbox:"status",
              method:"POST",
              data: {
                  id: $(this).parent().parent().attr('fill_id')
              },
              success:function(data){
                  alert(data);
              }
            })
    })
    //add new fill on item
    $(document).on( "click", "[id*='addItem']", function() {
        $(this).parent().parent().before('<tr><td td_type="fillName" parent="'+$(this).attr("fill-parent")+'"><input type="text" class="form-control" id="newName"></td><td><button class="btn btn-success" btn_type="saveNewFillName"><i class="fa fa-floppy-o" ></i></button></td></tr>');
    })
    //save new fill on item
    $(document).on( "click", "[btn_type=saveNewFillName]", function() {
        var parent = $(this).parent();
        ajax({
          url:"index.php?route=setting/libraries/saveNewFillName&token="+getURLVar('token'),
          statbox:"status",
          method:"POST",
          data: {
              parent: parent.parent().find("td[td_type='fillName']").attr("parent"),
              itemId: parent.parent().parent().parent().attr("item-id"),
              libraryId: parent.parent().parent().parent().parent().attr("library-id"),
              name: parent.parent().find("#newName").val()
          },
          success:function(data){
            if(data === '0'){
              alert('Возникла внутренняя ошибка');
              return FALSE;
            } else {
              parent.html('<button class="btn btn-info" btn_type="changeFill"><i class="fa fa-pencil" ></i></button><button class="btn btn-danger" btn_type="deleteFill"><i class="fa fa-trash-o"></i></button>');
              parent.parent().find("td[td_type='fillName']").html(parent.parent().find("#newName").val());
              parent.parent().attr('fill_id', data);
              parent.parent().attr('id', 'fill'+data);
            }
          }
        })
        $(this).parent().html('<button class="btn btn-success" btn_type="saveChangeFillName"><i class="fa fa-floppy-o" ></i></button>');
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