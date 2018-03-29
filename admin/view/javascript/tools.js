function showStructOptions($parent){
    ajax({
      url:"index.php?route=setting/prodtypes/showOptions&token="+getURLVar('token'),
      statbox:"status",
      method:"POST",
      data: {id: $parent},
      success:function(data){
          $("#options").removeAttr('hidden');
          $("#options").attr('type_id', $parent);
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
          $("#newOpt").after(data);
      }
    })
}
$(document).ready(function() {
    //change type of option
    $(document).on( "change", "[id=field_typeOption]", function() {
        var mainDiv = $(this).parent().parent();
        switch($(this).val()){
            case 'input':
                mainDiv.find("[id=def_valOption]").removeAttr('disabled');
                mainDiv.find("[id=librariesOption]").attr('disabled', 'true');
                mainDiv.find("[id=librariesOption]").attr('hidden', 'true');
                mainDiv.find("[id=valsOption]").attr('disabled', 'true');
                break
            case 'select':
                mainDiv.find("[id=librariesOption]").attr('disabled', 'true');
                mainDiv.find("[id=librariesOption]").attr('hidden', 'true');
                mainDiv.find("[id=def_valOption]").attr('disabled', 'true');
                mainDiv.find("[id=valsOption]").removeAttr('disabled');
                break
            case 'library':
                mainDiv.find("[id=librariesOption]").removeAttr('disabled');
                mainDiv.find("[id=librariesOption]").removeAttr('hidden');
                mainDiv.find("[id=def_valOption]").attr('disabled', 'true');
                mainDiv.find("[id=valsOption]").attr('disabled', 'true');
                break
        }
    })
    //show cilds of items fill
    $(document).on( "click", "td[td_type=fillName]", function() {
        var level = $(this).parent().parent().parent().attr('level');
        var parent = $(this).parent().attr('fill_id');
        var curRow = $(this).parent();
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
        if($(this).parent().parent().find("[id=oldOption]").val()==='0'){
            var goal = $(this).parent().find("[id=nameOption]");
            ajax({
              url:"index.php?route=setting/prodtypes/translateOption&token="+getURLVar('token'),
              statbox:"status",
              method:"POST",
              data: {
                  text: $(this).val()
              },
              success:function(data){
                  goal.text(data);
              }
            })
        }
    });
    //delete new option
    $(document).on('click', '[id=delNewOpt]', function(){
        $(this).parent().parent().remove('div');
        $("#newOpt").removeAttr('disabled');
    })
    //create new type
    $(document).on('click', '[id=createType]', function(){
        var table = $(this).parent().parent().find("tbody");
        var tableHTML = table.html();
        table.html(tableHTML+'<tr><td><input id="newTypeName" type="text" class="form-control" placeholder="Введите название типа продукта."/></td><td><button class="btn btn-info" id="saveNewType"><i class="fa fa-floppy-o"></i></button></td></tr>');
    })
    //save new type
    $(document).on('click', '[id=saveNewType]', function(){
        var input = $(this).parent().parent().find("input[id=newTypeName]");
        ajax({
            url:"index.php?route=setting/prodtypes/saveNewType&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {
                data: input.val(),
            },
            success:function(data){
                input.parent().parent().html('<td>'+input.val()+'</td><td><button class="btn btn-block btn-info" onclick="showStructOptions(\''+data+'\')"><i class="fa fa-pencil"></i></button></td>');
            }
        });
    })
    //save option
    $(document).on('click', '#saveOpt', function(){
        var optionDiv = $(this).parent();
        var fieldText = optionDiv.find("[id=textOption]").val();
        var formArr = '';
        $(this).parent().find("[id*=Option]").each(function(){
            formArr = formArr + " " + $( this ).attr('id') + ": " + ($( this ).val()===''?$( this ).text():$( this ).val()) + ", ";
        });
        ajax({
            url:"index.php?route=setting/prodtypes/saveOption&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {
                data: formArr,
                type_id: $('#options').attr('type_id')
            },
            success:function(data){
                if(optionDiv.find("[id=oldOption]").val()=='0'){
                    if(optionDiv.find("[id=field_typeOption]").val()!=='library'){
                        $("#optHeader").after('<span class="label label-success">'+fieldText+'</span>&nbsp;');
                    } else {
                        $("#optHeader").after('<span class="label label-success">Новое библиотечное свойство добавлено</span>&nbsp;');
                        optionDiv.html(data);
                    }
                    $("#newOpt").removeAttr('disabled');
                }
                optionDiv.attr('class', 'alert alert-success');
                alert('Сохранено');
            }
        });
    })
    $(document).on( "input", "[id*='Option']", function() {
        $(this).parent().parent().attr('class', 'alert alert-warning');
    })
    $(document).on( "change", "[id*='Option']", function() {
        $(this).parent().parent().attr('class', 'alert alert-warning');
    })
    
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
    
    //change fill name
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
        var row = $(this).parent().parent();
        var fill_id = row.attr('fill_id');
            ajax({
              url:"index.php?route=setting/libraries/deleteFill&token="+getURLVar('token'),
              method:"POST",
              data: {
                  id: fill_id
              },
              success:function(result){
                  if(result === '1'){
                      alert('Успешно удалено. Данный элемент больше не будет отображаться в списках.');
                      row.remove('tr');
                  } else {
                      alert('Удаление данного элемента невозможно. В базе существуют связи с этим элементом.');
                  }
              }
            });
    })
    //add new fill on item
    $(document).on( "click", "[id*='addItem']", function() {
        $(this).parent().parent().before('<tr><td td_type="newfillName" parent="'+$(this).attr("fill-parent")+'"><input type="text" class="form-control" id="newName"></td><td><button class="btn btn-success" btn_type="saveNewFillName"><i class="fa fa-floppy-o" ></i></button></td></tr>');
    })
    //delete Option from type
    $(document).on( "click", "[id='delOpt']", function() {
        ajax({
              url:"index.php?route=setting/prodtypes/deleteOption&token="+getURLVar('token'),
              statbox:"status",
              method:"POST",
              data: {
                  name: $(this).parent().find('[id="nameOption"]'),
                  type_id: $('#options').attr('type_id')
              },
              success:function(data){
                  $(this).parent().remove('div');
                  alert('Свойство успешно отвязано от данной структуры');
              }
            })
    })
    //save new fill on item
    $(document).on( "click", "[btn_type=saveNewFillName]", function() {
        var parent = $(this).parent();
        ajax({
          url:"index.php?route=setting/libraries/saveNewFillName&token="+getURLVar('token'),
          statbox:"status",
          method:"POST",
          data: {
              parent: parent.parent().find("td[td_type='newfillName']").attr("parent"),
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
              parent.parent().find("td[td_type='newfillName']").html(parent.parent().find("#newName").val());
              parent.parent().attr('fill_id', data);
              parent.parent().find('[td_type=newfillName]').attr('td_type', 'fillName');
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