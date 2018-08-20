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

function copyLinkToSend(btn, text){
    btn.parent().before('<div id="sendLink"></div>');
    var sLink = btn.parent().parent().find('#sendLink');
    var target = document.createElement("textarea");
    sLink.append(target);
    target.class = 'form-control';
    target.style.width = '100%';
    target.textContent = text;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    var secceed = false;
    secceed = document.execCommand("copy");
    if(!secceed){
        alert('Не удалось скопировать ссылку! Возможно, Ваш браузер не поддерживает этот функционал!');
    } else {
        console.log(secceed);
    }
    sLink.remove();
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
    
    $(document).on('click', '[btn_type=upd-notice]', function(){
        var btn = $(this);
        ajax({
            url:"index.php?route=tool/formTool/fastviewed&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {
                target: btn.attr('aria-controls')
            },
            success:function(data){
                btn.parent().parent().find('.hasNew').remove();
                btn.parent().parent().parent().removeClass('pulse');
            }
        });
    }) 
    
    $(document).on('click', '[data-toggle=tab]', function(){
        var target = $(this).attr('aria-controls');
        $(document).find('li[role=presentation]').each(function(){
            $(this).removeClass('active');
            if($(this).find('a[aria-controls='+target+']').html()){
                $(this).addClass('active');
            };
        });
    })    
    
    $(document).on('click', '[li-type=simItem]', function(){
        var liItem = $(this);
        var pardiv = liItem.parent().parent().parent();
        var target = pardiv.find('input').attr('target');
        var tmp = liItem.attr('li-target');
        var field = pardiv.find('input').attr('field');
        var opt = pardiv.find('input').attr('opt');
        var num = pardiv.parent().attr('num');
        var type = pardiv.parent().attr('type');
        pardiv.find('input').val(liItem.attr('item_id'));
        liItem.parent().parent().hide('slow', function(){});
        ajax({
            url:"index.php?route=tool/formTool/autoload&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            datatype: "json",
            data: {
                req: liItem.attr('item_id'),
                type: type,
                tmp: tmp,
                target: target,
                opt: opt,
                num: num,
                field: field
            },
            success:function(data){
                sup = JSON.parse(data);
                $.each(sup, function(key, val){
                    var tmp = pardiv.parent().find('[link='+key+']');
                    if(tmp.html()){
                        var tmp1 = tmp.find('[inp_type=smart]');
                        if(tmp1.parent().html()){
                            tmp1.val(val);
                            tmp1.parent().addClass('attention');
                        } else {
                            tmp.find('[name*=info]').val(val);
                        }
                    }
                })
            }
        });
    })
    
    $(document).on('click', '#closeDd', function(){
        $(this).parent().parent().hide('slow', function(){});
    })
    
    $(document).on('click', '[btn_type=remFromList]', function(){
        $(this).parent().parent().remove();
    })
    
    $(document).on('change', '#chooseWay', function(){
        var sel = $(this);
        if(sel.val()==='1'){
            sel.parent().parent().find('#similarOption').parent().remove();
            sel.parent().parent().find('#sim_showlistOption').parent().remove();
            ajax({
                url:"index.php?route=setting/prodtypes/getFields&token="+getURLVar('token'),
                    statbox:"status",
                    method:"POST",
                    data:{target: sel.attr('target')},
                   success:function(data){
                       sel.parent().after(data);
                   }
            })            
        } else {
            sel.parent().parent().find('#similarOption').parent().remove();
            sel.parent().parent().find('#sim_showlistOption').parent().remove();
            sel.parent().after('<div><input type="hidden" value="" id="similarOption"></div>');
            sel.parent().after('<div><input type="hidden" value="" id="sim_showlistOption"></div>');
        }
    })
    
    $(document).on('input', '[inp_type=chooseSim]', function(){
        var inp = $(this);
        var dD = inp.parent().find("#dropD");
        if(inp.val()!==''){
            ajax({
                    url:"index.php?route=tool/formTool/getSimilarVariants&token="+getURLVar('token'),
                    statbox:"status",
                    method:"POST",
                    data:
                    {
                        value: inp.val(),
                        target: inp.attr('target'),
                        opt: inp.attr('opt'),
                        field: inp.attr('field')
                    },
                   success:function(data){
                       dD.find("#devList").html(data); 
                       dD.show('slow', function(){});
                   }
                })
        } else{
            dD.find("#devList").html(); 
            dD.hide('slow', function(){});
        }
    })
    
    $(document).on('click', '[inp_type=smart]', function(){
        $(this).trigger('input');
    });
    
    $(document).on('input', '[inp_type=smart]', function(){
        var inp = $(this);
        var dD = inp.parent().find("#dropD");
        if(inp.val()!==''){
            ajax({
                    url:"index.php?route=tool/formTool/getSmartVariants&token="+getURLVar('token'),
                    statbox:"status",
                    method:"POST",
                    data:
                    {
                        value: inp.val(),
                        item: inp.attr('item')
                    },
                   success:function(data){
                       dD.find("#devList").html(data); 
                       dD.show('slow', function(){});
                   }
                })
        } else{
            dD.hide('fast', function(){});
        }
    })
     
    $(document).on('click', '[li-type="smartItem"]', function(){
        $(this).parent().parent().hide('fast', function(){});
        var item = $(this);
        var totalPar = item.parent().parent().parent().parent();
        var formgroup = item.parent().parent().parent();
        ajax({
            url:"index.php?route=tool/formTool/getSmartVarParents&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:{
                fill: item.attr('fill'),
                num: totalPar.attr('num')
            },
           success:function(data){
               formgroup.find('input').val(item.attr('fill'));
               formgroup.removeClass('attention');
               formgroup.find('[inp_type=smart]').val(item.text());
               $('.temp'+item.attr('library')).each(function(){
                   $(this).remove();
               });
               formgroup.after(data);
           }
        })
    })
    
    $(document).on('click', '[btn_type=copyToSend]', function(){
        copyLinkToSend($(this), $(this).attr('data-text'));
    })
    
    $(document).on('click', '[btn_type=showProd]', function(){
        var prod = $(this).attr('target');
        ajax({
            url:"index.php?route=tool/formTool/getProdCard&token="+getURLVar('token'),
            method:"POST",
            data:{prod:prod},
            success:function(data){
                $("#prodinfocard").html(data)
            }
        })
    });
    
    $(document).on('click', '[btn_type=label_settings]', function(){
        var target = $(this).attr('target');
        var old = $(this).attr('old_field');
        var field = $(this).parent().find('[name=field]').val();
        var color = $(this).parent().find('[name=color]').val();
        ajax({
            url:"index.php?route=setting/prodtypes/saveTypeLabel&token="+getURLVar('token'),
            method:"POST",
            data: {
                label: target,
                field: field,
                old: old,
                color: color
            },
            success:function(data){
                alert(data);
            }
        })
    });
    
    
    $(document).on('click', '[btn_type=saveExcelTempl]', function(){
        var templ = '';
        $(this).parent().parent().parent().find('input').each(function(){
            templ = templ + $(this).attr('target') + ':' + $(this).val() + ',';
        })
        ajax({
            url:"index.php?route=setting/prodtypes/saveExcelTempl&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {templ: templ},
            success:function(data){
                alert(data);
            }
        });
    })
    
    
    $(document).on('click', '[btn_type=sets_opt]', function(){
        var opt = $(this).attr('option');
        ajax({
                url:"index.php?route=setting/prodtypes/getSetsOpt&token="+getURLVar('token'),
                statbox:"status",
                method:"POST",
                data: {opt: opt},
                success:function(data){
                    $('#sets_option').html(data);
                }
            });
    });
    
    $(document).on('change', '[select_type=handling_acc]', function(){
        if($(this).val()==='1'){
            ajax({
                url:"index.php?route=service/client_handling/getAccidentForm&token="+getURLVar('token'),
                statbox:"status",
                method:"POST",
                data: {},
                success:function(data){
                    $('#accident_info').html(data);
                }
            });
        } else {
            $('#accident_info').html('');
        }
    });
    
    $(document).on('click', '[btn_type=addcontract]', function(){
        var form = '';
        $(this).parent().find('select').each(function(){
            form = form + $(this).attr('id') + ":" + $(this).val()+";"
        })
        $(this).parent().find('input').each(function(){
            form = form + $(this).attr('id') + ":" + $(this).val()+";"
        })
        /*ajax({
            url:"index.php?route=service/client_handling/showContract&token="+getURLVar('token')+"&handling="+getURLVar('handling'),
            statbox:"status",
            method:"POST",
            data: {contract: contract},
            success:function(data){
                $('#contract').html(data);
            }
        });*/
    });
    $(document).on('click', '[btn_type=contract]', function(){
        var contract = $(this).attr('target-contract')
        ajax({
            url:"index.php?route=service/client_handling/showContract&token="+getURLVar('token')+"&handling="+getURLVar('handling'),
            statbox:"status",
            method:"POST",
            data: {contract: contract},
            success:function(data){
                $('#contract').html(data);
            }
        });
    });
    
    $(document).on('click', '[btn_type=contract]', function(){
        var contract = $(this).attr('contract')
        ajax({
            url:"index.php?route=service/client_handling/showContract&token="+getURLVar('token')+"&handling="+getURLVar('handling'),
            statbox:"status",
            method:"POST",
            data: {contract: contract},
            success:function(data){
                $('#contract').html(data);
            }
        });
    });
    $(document).on('click', '[div_type=client]', function(){
        $(document).find('[div_type=client]').removeClass('client_item_choosen');
        $(this).addClass('client_item_choosen');
        var client = $(this).attr('client_id')
        ajax({
            url:"index.php?route=service/client_show&token="+getURLVar('token')+"&client="+client,
            statbox:"status",
            method:"POST",
            data: {},
            success:function(data){
                $('#client_info').html(data);
            }
        });
    });
    
    $(document).on('click', '[btn_type=addAuto]', function(){
        var client = $(this).attr('client');
        var result = '';
        $(this).parent().parent().parent().find('input').each(function(){
            if($(this).attr('id')=='datepts' || $(this).attr('id')=='datesor'){
                var lastIndex = $(this).val().lastIndexOf(" ");       // позиция последнего пробела
                var text = $(this).val().substring(0, lastIndex);
                result = result + $(this).attr('id') + ':' + text + ';';
            } else {
                result = result + $(this).attr('id') + ':' + $(this).val() + ';';
            }
        });
        $(this).parent().find('select').each(function(){
            result = result + $(this).attr('id') + ':' + $(this).val()+';';
        });
        ajax({
            url:"index.php?route=service/auto/addAuto&token="+getURLVar('token')+"&client="+client,
            statbox:"status",
            method:"POST",
            data: {arr: result},
            success:function(data){
                $('#autoHeader').after(data);
            }
        });
//        $('#createautoform').html(result);
    });
    $(document).on('click', '[btn_type=createAuto]', function(){
        $("#auto").html('<div class="form-group">\n\
                            <label>Введите VIN(frame) автомобиля</label>\n\
                            <input type="text" id="vin" class="form-control"/></div>\n\
                            <div id="autocreateresult"><button class="btn btn-primary" client="'+$(this).attr('client')+'" btn_type="tryVIN">проверить</button></div>');
    });
    $(document).on('click', '[btn_type=tryVIN]', function(){
        var vin = $(this).parent().parent().find('#vin').val();
        var client = $(this).attr('client');
        if(vin!=''){
            ajax({
                url:"index.php?route=service/auto/tryVIN&token="+getURLVar('token')+"&client="+client,
                statbox:"status",
                method:"POST",
                data: {vin: vin},
                success:function(data){
                    $("#autocreateresult").html(data);
                }
            });
        }
    });
    
    
    $(document).on('change', '[select_type=client_type]', function(){
        var type = $(this).val();
        ajax({
            url:"index.php?route=service/client/getAddForm&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {legal: type},
            success:function(data){
                $("#form_client").html(data);
            }
        });
    })
    $(document).on('click', '[type=setLevelVal]', function(){
        $(this).parent().parent().find('input').val($(this).text());
        $(document).find('[name='+$(this).attr('child')+']').attr('target-kladr', $(this).attr('kladr'));
        $(document).find('[name='+$(this).attr('child')+']').attr('data-toggle', 'dropdown');
        $(document).find('[name='+$(this).attr('child')+']').attr('aria-expanded', 'true');
        $(document).find('[name='+$(this).attr('child')+']').val('');
        $(document).find('[name='+$(this).attr('child')+']').after('<ul class="dropdown-menu dropdown-menu-left" id="'+$(this).attr('child')+'">Введите значение</ul>');
    });
    $(document).on('input', '[type=adress]', function(){
        if($(this).attr('target-kladr')!=='none'){
            var ul = $(this).parent().find('ul');
            ul.html('<img src="../wait.gif"/>');
            var type = $(this).val();
            var level = $(this).attr('target-level');
            var child = $(this).attr('target-child');
            var kladr = $(this).attr('target-kladr');
            ajax({
                url:"index.php?route=service/client/getAdress&token="+getURLVar('token'),
                statbox:"status",
                method:"POST",
                data: {req: type, kladr: kladr, child: child, lvl: level},
                success:function(data){
                    ul.html('');
                    ul.html(data);
                }
            });
        } else {
            $(this).val('');
        }
    })
    
    $(document).on('click', '[btn_type=createFill]', function(){
        var item = $(this).parent().parent().parent().attr('id');      
        var parent = '';
        if($(this).attr('parent') == '0'){
            parent = '0';
        } else {
            parent = $('#'+$(this).attr('parent')).find('select').val();
        }
        $(document).find('#createFill').attr('parent', parent);
        $(document).find('#createFill').attr('parent_div', $(this).attr('parent'));
        $(document).find('#createFill').attr('item', item); 
    })
    $(document).on('click', '[btn_type=hidenotice]', function(){
        var vin = $(this).attr('target-arg');
        var btn = $(this);
        ajax({
            url:"index.php?route=avito/avito_tool/hideNotice&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                vin: vin
            },
            success:function(data){
                btn.parent().parent().removeClass('elderAD');
                btn.remove();
            }
        })
    })
    
    $(document).on('click', '[btn_type=react]', function(){
        var vin = $(this).attr('target-arg');
        var btn = $(this);
        ajax({
            url:"index.php?route=avito/avito_tool/react&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                vin: vin
            },
            success:function(data){
                btn.remove();
            }
        })
    })
    
    $(document).on('click', '[btn_type=dropAd]', function(){
        var vin = $(this).attr('target-arg');
        var btn = $(this);
        ajax({
            url:"index.php?route=avito/avito_tool/drop&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                vin: vin
            },
            success:function(data){
                btn.parent().parent().remove();
            }
        })
    })
    
    $(document).on('click', '[btn_type=deact]', function(){
        var vin = $(this).attr('target-arg');
        var btn = $(this);
        ajax({
            url:"index.php?route=avito/avito_tool/deact&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                vin: vin
            },
            success:function(data){
                btn.parent().parent().addClass('elderAD');
                btn.remove();
            }
        })
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
        var item = $(this).attr('item');
        ajax({
            url:"index.php?route=tool/formTool/createFill&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:{
                parent: parent, 
                name: name,
                item: item
            },
            success:function(data){
                if(data === 'exists'){
                    alert('Такой элемент уже существует');
                    return FALSE;
                } else {  
                    if(parent_div!=='0'){
                        $('#'+parent_div).find('select').trigger('change');
                    } else {
                        alert('Данное значение будет доступно после перезагрузки страницы');
                    }
                button.parent().parent().find('input').val('');
                button.attr('disabled', '');
                }   
            }
        })
    })
    
    $(document).on('change', '[name*="complect"]', function(){
        if($(this).val()==='set'){
            $(this).parent().find('#cHeader').attr('type', 'text');
        } else {
            $(this).parent().find('#cHeader').attr('type', 'hidden');
        }
    })
    
    $(document).on('click', '[btn_type=descText]', function(){
        var target = $(this).attr('desc-target');
        ajax({
            url:"index.php?route=production/catalog/getDesc&token="+getURLVar('token'),
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
    $(document).on('change', '[alert_triger=prompt]', function(){
        var select = $(this);
        var prompt = select.find('option:selected').attr('prompt');
        if (prompt !== '' && prompt !== undefined) {
            alert('Напоминание: '+prompt);
        }   
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
        var oldname = $(this).attr('oldname');
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
            data: {
                fields: fields, 
                fill: fill,
                oldname: oldname 
            },
            success:function(data){
                if(data === 'exists'){
                    alert('Элемент с таким именем уже существует');
                    return FALSE;
                } else{  
                    alert('Сохранено');
                }
            }
        });
    })
    
    $(document).on('click', '[btn_type=save_comp_info]', function(){
        var val_comp_null = [];
        ajax({
            url:"index.php?route=complect/complect/editComplect&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                complect: val_comp_null,                
                id: $(this).parent().parent().parent().parent().find("#heading").attr('comp_id'),
                heading: $(this).parent().parent().parent().parent().find("#heading").attr('val'),
                name: $(this).parent().parent().parent().parent().find("#name").attr('val'),
                price: $(this).parent().parent().parent().parent().find("#price").val(),
                whole: $(this).parent().parent().parent().parent().find("#whole").val(),
                sale: $(this).parent().parent().parent().parent().find("#sale").val(),
                token: $(this).attr('token')
            },
            success:function(data){
                alert('Сохранено');
            }
        });
    }); 
     //change fill name
    $(document).on( "click", "[btn_type=changeFill]", function() {
        var button = $(this);
        var oldname = $(this).parent().parent().find("[td_type=fillName]").text();
        ajax({
            url:"index.php?route=setting/libraries/getFillSets&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {
                fill_id: button.attr('fill'),
                oldname: oldname
            },
            success:function(data){
                $('#level-settings').html(data);
            }
        });
    });
    $(document).on( "click", "[btn_type=changeFillprod]", function() {
        var fill_id = $(this).parent().parent().parent().find("select").val();
        var oldname = $(this).parent().parent().parent().find("option:selected").text();
        if (fill_id !== "") {
            ajax({
                url:"index.php?route=setting/libraries/getFillSets&token="+getURLVar('token'),
                statbox:"status",
                method:"POST",
                data: {
                    fill_id: fill_id,
                    oldname: oldname
                },
                success:function(data){
                    $('#level-settings').html(data);
                }
            });
        } else {
            alert('Элемент не выбран');
        }
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
        });
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
    
    $(document).on('input', '[inp_type=librSettInp]', function(){
        if($(this).val()!==''){
            $(this).parent().parent().find('button').removeAttr('disabled');
        }
    })
    $(document).on('change', '[inp_type=librSettSlct]', function(){
        $(this).parent().parent().find('button').removeAttr('disabled');
    })
    
    //option templName save
    $(document).on("click", "[btn_type=librSetSave]", function(){
        var button = $(this);
        var target = button.attr('target');
        if(button.parent().find('input').val()){
            var value = button.parent().find('input').val();
            var type_id = button.parent().find('input').attr('type_id');
        } else {
            var value = button.parent().find('select').val();
            var type_id = button.parent().find('select').attr('type_id');
        }
        ajax({
            url:"index.php?route=setting/libraries/librSetSave&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {value: value, library_id: type_id, target:target},
            success:function(data){
                button.attr('disabled', 'disabled');
                alert(data);
            }
        });
    })
    
    //add new product
    $(document).on("click", "[btn_type=addProduct]", function(){
        var button = $(this);
        var type = button.parent().find('select').val();
        var num = button.attr('num');
        ajax({
            url:"index.php?route=production/addition/addToList&token="+getURLVar('token'),
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
            formArr = formArr + " " + $( this ).attr('id') + ": " + ($( this ).val()===''?$( this ).text():$( this ).val()) + "; ";
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
            if(data === 'exists'){
                alert('Такой элемент уже существует');
                return FALSE;
            }  
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
    $(document).on( "click", "[id=rep_vin]", function() {
        var vin = $(this).parent().find('#vin').val();       
        var vin = $.trim(vin.replace(/\s+/g,""));
        $(this).parent().find('#vin').val(vin);   ;
    });
    $(document).on( "click", "[id=button-rotate]", function() {
        var image_src = $(this).parent().parent().parent().find('[data-toggle="image"]').attr('image_src');
        var src_now = $(this).parent().parent().parent().find('[class="img-responsive"]').attr('src');
        ajax({
            url:"index.php?route=production/catalog/rotate_image&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data: {
              image_src: image_src,
              src_now: src_now
            },
        });
    });
    $(document).on( "click", "[id=button-rotate]", function() {
        var angle = $(this).parent().parent().parent().find('[data-toggle="image"]').attr('rotate_now');
        var image_id = $(this).parent().parent().parent().find('[data-toggle="image"]').attr('id'); 
        angle = (angle - 90) % 360;
        $("[id="+ image_id +"]").css({'-moz-transform':'rotate(' + angle + 'deg)',
                                    '-ms-transform':'rotate(' + angle + 'deg)',
                                    '-webkit-transform':'rotate(' + angle + 'deg)',
                                    '-o-transform':'rotate(' + angle + 'deg)',
                                    'transform':'rotate(' + angle + 'deg)'});
        $("[id="+ image_id +"]").attr('rotate_now',angle);                        
    });
    $(document).ready(function(){
         $("#sort_image").sortable({update: function () {
            $("[data-trigger=image_elm]").each(function(indx){
                $(this).find("[data-trigger=sort_input]").val(indx);
            });
        }});
    });
    $(document).ready(function(){
        $('.icon-plus-pupup').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		image: {
			verticalFit: false
		}
	});
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
