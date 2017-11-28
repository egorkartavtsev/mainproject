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
        <script Language="JavaScript">
            function XmlHttp()
            {
                var xmlhttp;
                try{xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
                catch(e)
                {
                    try {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");} 
                    catch (E) {xmlhttp = false;}
                }
                if (!xmlhttp && typeof XMLHttpRequest!='undefined')
                {
                    xmlhttp = new XMLHttpRequest();
                }
                  return xmlhttp;
            }

            function ajax(param)
            {
                            if (window.XMLHttpRequest) req = new XmlHttp();
                            method=(!param.method ? "POST" : param.method.toUpperCase());

                            if(method=="GET")
                            {
                                           send=null;
                                           param.url=param.url+"&ajax=true";
                            }
                            else
                            {
                                           send="";
                                           for (var i in param.data) send+= i+"="+param.data[i]+"&";
                                           // send=send+"ajax=true"; // если хотите передать сообщение об успехе
                            }

                            req.open(method, param.url, true);
                            req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            req.send(send);
                            req.onreadystatechange = function()
                            {
                                           if (req.readyState == 4 && req.status == 200) //если ответ положительный
                                           {
                                                           if(param.success)param.success(req.responseText);
                                           }
                            }
            }
        </script>
            <!------------------------------------------------------------------------------->
            <h4>Выберите марку</h4>
            <div class="form-group col-lg-3" style="float: left;">
                <select class="form-control" name="brand_id" id='brand' onchange='
                        ajax({
                                                                   url:"index.php?route=common/edit_model/getModel&token=<?php echo $token_em; ?>",
                                                                   statbox:"status",
                                                                   method:"POST",
                                                                   data:
                                                                   {
                                                                                  brand: document.getElementById("brand").value,
                                                                                  token: "<?php echo $token_em; ?>"
                                                                   },
                                                                  success:function(data){document.getElementById("model_list").innerHTML=data; document.getElementById("model_row_list").innerHTML="";}

                                                   })
                        '>
                    <option selected="selected" disabled="disabled">Выберите марку</option>
                    <?php foreach ($brands as $brand) { ?>
                        <option value="<?php echo $brand['val']; ?>"><?php echo $brand['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class='clearfix'></div>
            
            <div class="form-group col-lg-6" style="float: left;" id="model_list"></div>
            <div class="form-group col-lg-6" style="float: left;" id="model_row_list"></div>
            <div class="clearfix"></div>
            <hr/>
            <!------------------------------------------------------------------------------->



                <!--<select type='text' value='Сохранить комментарий' onchange='
                               ajax({
                                                               url:"index.php?route=common/addprod/get_ajax&token=<?php echo $token_add; ?>",
                                                               statbox:"status",
                                                               method:"POST",
                                                               data:
                                                               {
                                                                              first_area: "1",
                                                                              second_area: "2"
                                                              },
                                                              success:function(data){document.getElementById("status").innerHTML=data;}

                                               })'
                                                               >
                    <option value="1">1</option>
                    <option value="2">2</option>
                
                </select>-->
    </div>
</div>
<!-- ModelRow -->
<div class="modal fade" id="myModelRow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Добавить модельный ряд</h4>
      </div>
      <div class="modal-body">
          <input type="text" id='mrname' />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary" onclick="addMR()">Сохранить</button>
      </div>
    </div>
  </div>
</div>  

<!-- Model -->
<div class="modal fade" id="myModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel1">Добавить модель</h4>
      </div>
      <div class="modal-body">
          <input type="text" id='mname' />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary" onclick="addM()">Сохранить</button>
      </div>
    </div>
  </div>
</div>

<!-- editModel -->
    <style>
        .mod:hover{
            background: antiquewhite;
        }}
    </style>
    <script>
      $('#myModelRow').on('shown.bs.modal', function () {
        $('#myInput').focus()
      })
      $('#myModel').on('shown.bs.modal', function () {
        $('#myInput').focus()
      })
    </script>
<?php echo $footer;?>