var newParam = 0;
var newProd = 0;
function getLibr($id, $libr){
     ajax({
            url:"index.php?route=tiresdisc/param/getValues&token=" + getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                param: $id
            },
            success:function(data){
                document.getElementById($libr).innerHTML = data;
            }
        })
}

function createParam($type){
    document.getElementById($type).innerHTML+='<tr><td id="np'+window.newParam+'"><input type="text" class="form-control" placeholder="Введите название параметра"></td><td><button class="btn btn-success" onclick="editParam(\'0\', \'np'+window.newParam+'\', \''+$type+'\')"><i class="fa fa-floppy-o"></i></button></td></tr>';
    window.newParam+=1;
}

function editParam($param, $id, $table){
    text = $("#"+$id).find('input').val();
    $url = "index.php?route=tiresdisc/param/editParam&token=" + getURLVar('token');
    ajax({
        url: $url,
        statbox:"status",
        method:"POST",
        data:
        {
            param: $param,
            value: text,
            table: $table
        },
        success:function(data){
            $("#"+$id).parent().html(data);
        }
    })
}

function getEdit($id, $param, $table){
    $value = $('#'+$id).text();
    $('#'+$id).parent().html('<td id="'+$id+'"><input type="text" class="form-control" value="'+$value+'"></td><td><button class="btn btn-success" onclick="editParam(\''+$param+'\', \''+$id+'\', \''+$table+'\')"><i class="fa fa-floppy-o"></i></button></td>');
}

function deleteParam($param, $id){
    ajax({
        url: "index.php?route=tiresdisc/param/delParam&token=" + getURLVar('token'),
        statbox:"status",
        method:"POST",
        data:
        {
            param: $param
        },
        success:function(data){
            if(data){
                $("#"+$id).parent().remove();
            } else {
                alert('Произошла ошибка');
            }
        }
    })
}

function createPValue($param){
    document.getElementById('pv'+$param).innerHTML+='<tr><td id="npv'+window.newParam+'"><input type="text" class="form-control" placeholder="Введите значение"></td><td><button class="btn btn-success" onclick="editPVal(\''+$param+'\', \'npv'+window.newParam+'\', \'0\')"><i class="fa fa-floppy-o"></i></button></td></tr>';
    window.newParam+=1;
}

function editPVal($parent, $id, $type){
    $value = $('#'+$id).find('input').val();
    $url = "index.php?route=tiresdisc/param/editPValue&token=" + getURLVar('token');
    ajax({
        url: $url,
        statbox:"status",
        method:"POST",
        data:
        {
            param: $parent,
            value: $value,
            type: $type
        },
        success:function(data){
            $("#"+$id).parent().html(data);
        }
    })
}

function getEditPV($id, $pvid){
    $value = $('#'+$id).text();
    $('#'+$id).parent().html('<td id="'+$id+'"><input type="text" class="form-control" value="'+$value+'"></td><td><button class="btn btn-success" onclick="editPVal(\'0\', \'rpv'+$pvid+'\', \''+$pvid+'\')"><i class="fa fa-floppy-o"></i></button></td>'); 
}

function deletePVal($pvid, $id){
    ajax({
        url: "index.php?route=tiresdisc/param/delPVal&token=" + getURLVar('token'),
        statbox:"status",
        method:"POST",
        data:
        {
            pv: $pvid
        },
        success:function(data){
            if(data){
                $("#"+$id).parent().remove();
            } else {
                alert('Произошла ошибка');
            }
        }
    })
}

/*************************************************************************************************/

function getNewPart(){
    var form = '';
    form+='<div class="alert alert-info" style="margin-right: 2px;">';
        form+='<div class="form-group">';
            form+='<label for="cat'+window.newProd+'">Выберите категорию</label>';
            form+='<select id="cat'+window.newProd+'" name="info['+window.newProd+'][cat]" class="form-control" onchange="getFormPart(\''+window.newProd+'\');">';
                form+='<option disabled selected>---</option>';
                form+='<option value="disk">Диски</option>';
                form+='<option value="tire">Шины</option>';
            form+='</select>';
        form+='</div>';
        form+='<div id="ins'+window.newProd+'"></div>';
    form+='</div>';
    window.newProd+=1;
    return form;
}

function getFormPart($id){
    $cat = $("#cat"+$id).val();
    $form = '';
    ajax({
        url: "index.php?route=tiresdisc/create/getField&token=" + getURLVar('token'),
        statbox:"status",
        method:"POST",
        data:
        {
            cat: $cat,
            id: $id
        },
        success:function(data){
            $('#ins'+$id).html(data);
            $('#saveButton').removeAttr('disabled');
        }
    })
}

function deleteItem($id){
    ajax({
        url: "index.php?route=tiresdisc/list/deleteItem&token=" + getURLVar('token'),
        statbox:"status",
        method:"POST",
        data:
        {
            id: $id
        },
        success:function(data){
            if(data){
                $('#item'+$id).remove();
            } else {
                alert('Произошла ошибка удаления. Повторите попытку.');
            }
        }
    });
}
    
function getFilters($categ){
    ajax({
        url: "index.php?route=tiresdisc/list/getFilter&token=" + getURLVar('token'),
        statbox:"status",
        method:"POST",
        data:
        {
            categ: $categ
        },
        success:function(data){
            $("#filter_fields").html(data);
        }
    });
}
