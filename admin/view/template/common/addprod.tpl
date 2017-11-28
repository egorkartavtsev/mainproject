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
    <div id="add-status-bar"></div>
    <div class="clearfix"></div>
    <div class="container-fluid" id="forms">
        <form name="uploader" target="blank" enctype="multipart/form-data" method="POST">
            <div class="form-group-sm col-lg-3">
                <input type="text" id="vin" name="vin" class="form-control" placeholder='Внутренний номер'>
            </div>
            <div class="form-group-sm col-lg-3" >
                <select class="form-control" name="brand_id" id='brand' onchange='
                    ajax({
                        url:"index.php?route=common/addprod/get_model&token=<?php echo $token_add; ?>",
                        statbox:"status",
                        method:"POST",
                        data:
                        {
                                       brand: document.getElementById("brand").value,
                                       token: "<?php echo $token_add; ?>"
                        },
                       success:function(data){document.getElementById("model1").innerHTML=data;}
                    })
                '>
                    <option selected="selected" disabled="disabled">Выберите марку</option>
                    <?php foreach ($brands as $brand) { ?>
                        <option value="<?php echo $brand['val']; ?>"><?php echo $brand['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group-sm col-lg-3"  id="model1"></div>
            <div class="form-group-sm col-lg-3"  id="model_row"></div>
            <div class="clearfix"></div>
            <div class="clearfix"><p></p></div>
            <!------------------------------------------------------------------------------->
            <div class="form-group-sm col-lg-3" >
                <input name="photo[]" id="photos" type="file" multiple="true">
            </div>
            <!------------------------------------------------------------------------------->
            <div class="form-group-sm col-lg-3" >
                <input type="text" class="form-control" placeholder="Введите подкатегорию" id="podcat"/>
                <ul class="dropdown-menu" id="podcatDD" style="left: 15px; display: none;">
                    <li class="dropdown-header">Выберите подкатегорию</li>
                    <li class="devider"></li>
                    <div id="devList"></div>
                    
                </ul>
                <input type="hidden" name="podcat_id" id="pcat_id" value=''/>
            </div>
            <div class="form-group-sm col-lg-3" id="cat"><input type="hidden" id="category_id" name="category_id" value=""></div>
        </form>
        <div class="form-group-sm col-lg-3">
            <button class="btn btn-sm btn-block btn-success" onclick="validateADD('<?php echo $token_add; ?>');">Создать</button>
        </div>
        <div class="clearfix"></div>
        <div class="clearfix"><p></p></div>
        <hr>
        <div id="listProds">
            <?php if($message!=''){
                    echo $message;
            } else { ?>
                <form method="post" onsubmit="refr();" name="updateForm" action="index.php?route=common/addprod&token=<?php echo $token_add; ?>" id="updateForm">
                    <div id="data-form"></div>
                    <div class="clearfix"></div>
                    <div class="clearfix"><p></p></div>
                    <input type="submit" class="btn btn-sm col-lg-4 btn-success" style="display: none;" id="upbut" value="Сохранить"/>
                </form>
            <?php } ?>
            <div class="modal fade" id="cpbModal" tabindex="-1" role="dialog" aria-labelledby="cpbModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="cpbModalLabel">Выберите преминимость детали</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="form-group-sm col-lg-6" >
                                <select class="form-control" id='modalBrand' onchange='
                                    ajax({
                                        url:"index.php?route=common/addprod/get_modalModel&token=<?php echo $token_add; ?>",
                                        statbox:"status",
                                        method:"POST",
                                        data:
                                        {
                                                       brand: document.getElementById("modalBrand").value,
                                                       token: "<?php echo $token_add; ?>"
                                        },
                                       success:function(data){document.getElementById("modalModel1").innerHTML=data;}
                                    })
                                '>
                                    <option selected="selected" disabled="disabled">Выберите марку</option>
                                    <?php foreach ($brands as $brand) { ?>
                                        <option value="<?php echo $brand['val']; ?>"><?php echo $brand['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group-sm col-lg-6"  id="modalModel1"></div>
                            <div class="clearfix"></div>
                            <div class="clearfix"><p></p></div>
                            <div class="col-lg-12" id="model_rows"></div>
                        </div>
                        <input type="hidden" id="forProd" />
                        <div class="clearfix"></div>
                        <div class="clearfix"><p></p></div>
                        <hr>
                        <div>
                            <h5>Применимость:</h5>
                            <p id="cpbProduct"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" onclick="cpbApply();" class="btn btn-default" data-dismiss="modal">Сохранить</button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
</div> 
<script>
    
    var cpbArr;
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
        document.getElementById('cpbModalLabel').innerHTML = document.getElementById('name'+$id).innerHTML;
        var tag =  document.getElementById('cpb'+$id).value;
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
        var inp = document.getElementById('forProd').value;
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
        document.getElementById('cpbProduct').innerHTML+= '<span id="cpbItem'+window.counfOfCpbArr+'" onclick="deleteItem(\'cpbItem'+window.counfOfCpbArr+'\');" class="label label-success cpbItem h5">'+item+'</span> ';
        window.cpbArr[window.counfOfCpbArr] = item;
        window.counfOfCpbArr+=1;
    }
    
    function deleteItem($id){
        var item = document.getElementById($id).innerText;
        document.getElementById($id).parentNode.removeChild(document.getElementById($id));
        window.cpbArr.splice(window.cpbArr.indexOf(item), 1);
    }
    
    $("#podcat").click(function(){
        $("#podcatDD").toggle('fast', function(){});
    });
    $("#podcat").on('input', function(){
        ajax({
                url:"index.php?route=common/addprod/get_pcs&token=<?php echo $token_add; ?>",
                statbox:"status",
                method:"POST",
                data:
                {
                               podcat: document.getElementById("podcat").value,
                               token: "<?php echo $token_add; ?>"
                },
               success:function(data){ document.getElementById("devList").innerHTML = data; }
            })
    });
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
    
    function tryCompl($id){
        $head = document.getElementById("heading"+$id).value;
        ajax({
            url:"index.php?route=common/addprod/tryCompl&token=<?php echo $token_add; ?>",
            statbox:"status",
            method:"POST",
            data:
            {
                           head: $head
            },
           success:function(data){
               if(data!='1'){
                $("#heading"+$id).css('box-shadow', '0px 0px 15px #f55');
               } else {
                $("#heading"+$id).css('box-shadow', '0px 0px 15px #8f5');
               }
           }
        })
    }
</script>
<style>
    .cpbItem{
        cursor: pointer;
    }
    .searchItem{
        cursor: pointer;
        padding: 3px 8px;
    }
    .searchItem:hover{
        background-color: rgba(100, 100, 100 , 0.25);
    }
</style>
<?php echo $footer;?>