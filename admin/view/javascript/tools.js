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
    });
}

function saveControllerInfo($id){
    ajax({
      url:"index.php?route=setting/menu/saveControllerInfo&token="+getURLVar('token'),
      statbox:"status",
      method:"POST",
      data: {id: $id, name: $('#itemName-'+$id).val(), icon: $('#itemIcon-'+$id).val()},
      success:function(data){
          alert('Сохранено');
      }
    });
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
    });
}
$(document).ready(function() {
    
    $(document).on('click', '[btn_type=createFill]', function(){
        var parent = '';
        if($(this).attr('parent') == '0'){
            parent = '0';
        } else {
            parent = $('#'+$(this).attr('parent')).find('select').val();
        }
        $(document).find('#createFill').attr('parent', parent);
        $(document).find('#createFill').attr('parent_div', $(this).attr('parent'));
        //alert(parent);
    })
    
    $(document).on('input', '[id=fillname]', function(){
        if($(this).val()==''){
            $('#createFill').attr('disabled', '');
        } else {
            $('#createFill').removeAttr('disabled');            
        }
    })
    
    $(document).on('click', '[btn_type=levelSISave]', function(){
        var item_id = $(this).attr('item');
        var si = $(this).parent().find('select').val();
        ajax({
            url:"index.php?route=setting/libraries/levelSISave&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                SI: si, item_id: item_id
            },
            success:function(data){
                alert('Сохранено');
            }
        })
    })
    
    $(document).on('click', '[id=createFill]', function(){
        var button = $(this);
        var parent_div = $(this).attr('parent_div');
        var parent = $(this).attr('parent');
        var name = $(this).parent().parent().find('input').val();
        ajax({
            url:"index.php?route=tool/formTool/createFill&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                parent: parent, name: name
            },
            success:function(data){
//                alert(data);
                if(parent_div!=='0'){
                    $('#'+parent_div).find('select').trigger('change');
                } else {
                    alert('Данное значение будет доступно после перезагрузки страницы');
                }
                button.parent().parent().find('input').val('');
                button.attr('disabled', '');
            }
        })
    })
    
    $(document).on('change', '[name*="complect"]', function(){
        if($(this).val()==='set'){
            $('#cHeader').attr('type', 'text');
        } else {
            $('#cHeader').attr('type', 'hidden');
        }
    })
    
    $(document).on('click', '[btn_type=descText]', function(){
        var target = $(this).attr('desc-target');
        ajax({
            url:"index.php?route=catalog/product/getDesc&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                id: target
            },
            success:function(data){
                $('#proddesctext').html(data);
            }
        })
    })
    
    $(document).on('click', '[btn_type=deleteCompl]', function(){
        var id = $(this).attr('complId');
        var button = $(this);
        if(confirm('Вы уверены?')){
            ajax({
                url:"index.php?route=complect/complect/writeOff&token="+getURLVar('token'),
                statbox:"status",
                method:"POST",
                data:
                {
                    id: id
                },
                success:function(data){
                    //document.getElementById('comp'+$id).parentNode.removeChild(document.getElementById('comp'+$id));
                    button.parent().parent().html('');
                }
            })
        }
    })
    $(document).on('input', '[name*="heading"]', function(){
        var heading = $(this).val();
        var input = $(this);
        if(heading!==''){
            ajax({
              url:"index.php?route=tool/formTool/isComplect&token="+getURLVar('token'),
              statbox:"status",
              method:"POST",
              data: {heading: heading},
              success:function(data){
                  if(data){
                      input.attr('class', 'form-control alert-success');
                  } else {
                      input.attr('class', 'form-control alert-danger');
                  }
              }
            });
        } else {
            input.attr('class', 'form-control');
        }
    })
    $(document).on('click', '[btn_type=compability]', function(){
        var tc = $(this).parent().parent().find('input').val();
        $('#totalCpb').text(tc);
    });
    //choose cpbItem
    $(document).on('click', '[span_type=cpbItem]', function(){
        var tc = $(this).parent().parent().parent().parent().find('#totalCpb');
        if (tc.text().replace( /[.?*+^$[\]\\(){}|-]/g, "" ).search($(this).text().replace( /[.?*+^$[\]\\(){}|-]/g, "" )) == -1) {
        tc.text(tc.text()+$(this).text()+', ');
        }
    });
    $(document).on('click', '[btn_type=applyCpb]', function(){
        var totalCpb = $(this).parent().find('p').text();
        $('#'+$(this).attr('cpbfield_name')+$(this).attr('cpbfield_id')).val(totalCpb);
    })
    //libr select change
    $(document).on('change', '[select_type=librSelect]', function(){
        var select = $(this);
        var fill_id = select.val();
        var child = select.attr('child');
        var num = select.parent().parent().attr('num');
        ajax({
          url:"index.php?route=tool/formTool/libraryFields&token="+getURLVar('token'),
          statbox:"status",
          method:"POST",
          data: {parent: fill_id, parentName: child, num: num},
          success:function(data){
              select.parent().parent().find('[id='+child+']').html(data);
          }
        });
    })
    
    //validate unique fields
    $(document).on('input', '[unique=unique]', function(){
        var search = $(this).val();
        if(search!==''){
            var field = $(this).attr('field');
            var num = $(this).parent().parent().attr('num');
            var num = $(this).parent().parent().attr('num');
            var uDiv = $(this).parent().parent();
            var fieldText = $(this).parent().find('label').text();
            var unique = true;
            $(document).find('input[field='+field+']').each(function(){
                if($(this).val() === search && $(this).parent().parent().attr('num') !== num){
                    unique = false;
                }
            });
            if(unique){
                ajax({
                    url:"index.php?route=tool/formTool/isUnique&token="+getURLVar('token'),
                    statbox:"status",
                    method:"POST",
                    data: {search: search, field: field},
                    success:function(data){
                        unique = data;
                        if(unique==='true'){
                            uDiv.attr('class', 'col-lg-12 alert alert-success');
                        } else {
                            uDiv.attr('class', 'col-lg-12 alert alert-warning');
                            alert('Такое значение "'+fieldText+'" уже есть в базе');
                        }
                    }
              });
            } else {
                uDiv.attr('class', 'col-lg-12 alert alert-warning');
                alert('Такое значение "'+fieldText+'" уже есть на странице');
            }
        }
    })
    //save prodList
    $(document).on('click', '[btn_type*="saveProd"]', function(){
        var haveError = true;
        var errorText = '';
        var form = $(document).find('form[name*="prod"]');
        form.find('div[type="product"]').each(function(index){
            var currDiv = $(this);
            $(this).find('input[required="required"]').each(function(){
               if($(this).val()===''){
                    currDiv.attr('class', 'col-md-12 alert alert-warning');
                    alert('Не заполнено поле '+$(this).parent().find('label').text());
                    haveError = false;
               } else {
                   currDiv.attr('class', 'col-md-12 alert alert-success');
               }
            });
        });
        if(haveError){
            form.submit();
        }
    });
    
    //option tempName
    $(document).on('input', '#templName', function(){
        $(this).parent().parent().find('button').removeAttr('disabled');
    })
    
    //option templName save
    $(document).on("click", "[btn_type=tempNameSave]", function(){
        var button = $(this);
        var tempName = button.parent().find('input').val();
        var type_id = button.parent().find('input').attr('type_id');
        ajax({
            url:"index.php?route=setting/prodtypes/saveTempName&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {tempName: tempName, type_id: type_id},
            success:function(data){
                button.attr('disabled', 'disabled');
            }
        });
    })
    //option tempName
    $(document).on('input', '#typeName', function(){
        $(this).parent().parent().find('button').removeAttr('disabled');
    })
    
    //option templName save
    $(document).on("click", "[btn_type=typeNameSave]", function(){
        var button = $(this);
        var typeName = button.parent().find('input').val();
        var type_id = button.parent().find('input').attr('type_id');
        ajax({
            url:"index.php?route=setting/prodtypes/saveTypeName&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {typeName: typeName, type_id: type_id},
            success:function(data){
                button.attr('disabled', 'disabled');
            }
        });
    })
    
    $(document).on('click', '[btn_type=fillSetsSave]', function(){
        var fields = '';
        var fill = $(this).attr('fill');
        $(this).parent().parent().find('input').each(function(){
            fields+= $(this).attr('id')+': '+$(this).val()+'; ';
        })
        fields+= $(this).parent().parent().find('textarea').attr('id')+': '+$(this).parent().parent().find('textarea').val()+'; ';
        ajax({
            url:"index.php?route=setting/libraries/saveFillSets&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {fields: fields, fill: fill},
            success:function(data){
                alert('Сохранено');
            }
        });
    })
    
     //change fill name
    $(document).on( "click", "[btn_type=changeFill]", function() {
        var button = $(this);
        ajax({
            url:"index.php?route=setting/libraries/getFillSets&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {fill_id: button.attr('fill')},
            success:function(data){
                $('#level-settings').html(data);
            }
        });
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
    //libr level settings
    $(document).on("click", "[btn_type=levelSettings]", function(){
        var button = $(this);
        ajax({
            url:"index.php?route=setting/libraries/getLevelSets&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {item_id: button.attr('item')},
            success:function(data){
                $('#level-settings').html(data);
            }
        });
    })
    
    //libr change Name
    $(document).on('input', '#librName', function(){
        $(this).parent().parent().find('button').removeAttr('disabled');
    })
    
    //option templName save
    $(document).on("click", "[btn_type=librNameSave]", function(){
        var button = $(this);
        var tempName = button.parent().find('input').val();
        var type_id = button.parent().find('input').attr('type_id');
        ajax({
            url:"index.php?route=setting/libraries/savelibrName&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {librName: tempName, library_id: type_id},
            success:function(data){
                button.attr('disabled', 'disabled');
            }
        });
    })
    
    //option tempName
    $(document).on('change', '#showNav', function(){
        $(this).parent().parent().find('button').removeAttr('disabled');
    })
    
    //option show to top-navigation save
    $(document).on("click", "[btn_type=showNavSave]", function(){
        var button = $(this);
        var show = button.parent().find('select').val();
        var type_id = button.parent().find('select').attr('type_id');
        ajax({
            url:"index.php?route=setting/prodtypes/saveShowNav&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {show: show, type_id: type_id},
            success:function(data){
                button.attr('disabled', 'disabled');
            }
        });
    })
    //option show to top-navigation save
    $(document).on("click", "[btn_type=librShowNavSave]", function(){
        var button = $(this);
        var show = button.parent().find('select').val();
        var library_id = button.parent().find('select').attr('library_id');
        ajax({
            url:"index.php?route=setting/libraries/saveShowNav&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {show: show, library_id: library_id},
            success:function(data){
                button.attr('disabled', 'disabled');
            }
        });
    })
    
    //add new product
    $(document).on("click", "[btn_type=addProduct]", function(){
        var button = $(this);
        var type = button.parent().find('select').val();
        var num = button.attr('num');
        ajax({
            url:"index.php?route=product/product_add/addToList&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {type: type, num},
            success:function(data){
                $("#prodListHeader").after(data);
                ++num;
                button.attr('num', num);
            }
        });
    })
    //add FC item
    $(document).on("click", "[btn_type=addFCItem]", function(){
        var thisDiv = $(this).parent();
        ajax({
            url:"index.php?route=setting/fastCallMenu/addItem&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {item: thisDiv.find('button').attr('nameItem')},
            success:function(data){
                thisDiv.find('button').attr('class', 'btn btn-success');
                thisDiv.find('button').attr('btn_type', 'dropFCItem');
                $("#FCPrev").html($("#FCPrev").html()+' '+thisDiv.html());
                thisDiv.find('button').attr('disabled', 'disabled');
            }
        });
    })
    
   //drop FC item
    $(document).on("click", "[btn_type=dropFCItem]", function(){
        var thisDiv = $(this).parent();
        var button = $(this);
        var item = $(this).attr('nameItem');
        ajax({
            url:"index.php?route=setting/fastCallMenu/dropItem&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {item: item},
            success:function(data){
                button.attr('class', 'btn btn-info');
                button.attr('disabled', 'disabled');
            }
        });
    })
    
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
            case 'compability':
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
        var optionDiv = $(this).parent().parent();
        var fieldText = optionDiv.find("[id=textOption]").val();
        var formArr = '';
        optionDiv.find("[id*=Option]").each(function(){
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