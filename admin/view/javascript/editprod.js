var cpbArr = new Array();
var counfOfCpbArr = 0;
$('#cpbModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('whatever') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    upsetModal(recipient)
    modal.find('.modal-body #forProd').val('cpb'+recipient)
});

function upsetModal($id){
    document.getElementById('cpbModalLabel').innerHTML = document.getElementById('input-name').value;
    var tag =  document.getElementById('input-compability').value;
    window.cpbArr = tag.split('; ');
    window.counfOfCpbArr = 0;
    var cpbList = '';
    window.cpbArr.forEach(function(item, i, arr){
        cpbList = cpbList+'<span id="cpbItem'+i+'" onclick="deleteItem(\'cpbItem'+window.counfOfCpbArr+'\');" class="label label-success cpbItem h5">'+item+'</span> ';
        window.counfOfCpbArr+=1;
    });
    document.getElementById('cpbProduct').innerHTML = cpbList;
}

function cpbApply(){
    var inp = 'input-compability';
    var text = '';
    window.cpbArr.forEach(function(item, i, arr) {
        if(item!=''){
            text+=item+'; ';
        }
    });
    document.getElementById(inp).value = text;
}

function chooseCpb($text){
    var item = document.getElementById('mrList'+$text).innerText;
    var search = document.getElementById('input-modRow').value;
    if(search != item){
        if(window.cpbArr.indexOf(item)==-1){
            document.getElementById('cpbProduct').innerHTML+= '<span id="cpbItem'+window.counfOfCpbArr+'" onclick="deleteItem(\'cpbItem'+window.counfOfCpbArr+'\');" class="label label-success cpbItem h5">'+item+'</span> ';
            window.cpbArr[window.counfOfCpbArr] = item;
            window.counfOfCpbArr+=1;
        }
    }
}

function deleteItem($id){
    var item = document.getElementById($id).innerText;
    document.getElementById($id).parentNode.removeChild(document.getElementById($id));
    window.cpbArr.splice(window.cpbArr.indexOf(item), 1);
}

function chooseCPC($id, $name){
    document.getElementById("podcat").value = $name;
    document.getElementById("pcat_id").value = $id;
    ajax({
        url:"index.php?route=common/addprod/get_cat&token=<?php echo $token_add; ?>",
        statbox:"status",
        method:"POST",
        data:
        {
                       podcat: $id
        },
       success:function(data){ document.getElementById("cat").innerHTML = data; $("#podcatDD").hide('fast', function(){});}
    })
}

function setCompl($item, $heading){
    ajax({
        url:"index.php?route=production/catalog/setCompl&token=" + getURLVar('token'),
        statbox:"status",
        method:"POST",
        data:
        {
            heading: $heading,
            item: $item
        },
        success:function(data){
            if(data == 0) {
                $("#result").html("<b>Произошла ошибка. Повторите попытку.</b>");
                $("#setComp").attr('disabled', 'true');
            } else {
//                $("#result").html(data);
                location.reload();
            }

        }
    })
}

function remCompl($item, $heading){
    ajax({
        url:"index.php?route=production/catalog/remCompl&token=" + getURLVar('token'),
        statbox:"status",
        method:"POST",
        data:
        {
            heading: $heading,
            item: $item
        },
        success:function(data){
            location.reload();
        }
    })
}