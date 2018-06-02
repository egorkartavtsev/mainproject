<?php echo $header; ?>
<script type="text/javascript" src="view/javascript/editprod.js"></script>
<?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <?php if (isset($product_page)) { ?><a class="btn btn-info" href="<?php echo $product_page; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_view; ?>"><i class="fa fa-eye"></i></a><?php } ?>
                <button type="submit" form="form-product" data-toggle="tooltip" title="Сохранить" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Отмена" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $name; ?></h1>
            <?php if($complect != '') { ?>
                <br>
                <?php if($comp_price=='') { ?>
                    <h4><span class="label label-primary">комплектующее</span> - <span class="label label-primary"><a target="blank" href="<?php echo $clink;?>" style="color: #FFFFFF!important;"><?php echo $cname;?></a></span></h4>
                <?php } else { ?>
                    <h4><span class="label label-primary">головной товар в комплекте</span></h4>
                <?php } ?>
            <?php } ?>
            <?php if($complect === '') { ?><br><button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#compModal"><i class="fa fa-plus-circle"></i> прикрепить к комплекту</button><?php } ?>
            <?php if($complect != '' && $comp_price=='') { ?>
                <button class="btn btn-sm btn-danger" id="remCopml"><i class="fa fa-minus-circle"></i> открепить</button>
            <?php } ?>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Редактирование товара</h3>
            </div>
            <div class="panel-body">
                <form class="form" id="form-update" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
                    <h3>Данные:</h3>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="input-name">Наименование: </label>
                            <input type="text" name="name" id="input-name" class="form-control" value="<?php echo $name; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <!---------col-1-------------------->
                        <div class=" col-sm-4">
                            <div class="form-group">
                                <label for="input-brand">Марка:</label>
                                <select class="form-control" id="input-brand" name="brand">
                                    <option value="univ">Универсальный</option>
                                    <?php foreach($brands as $brand) { ?>
                                        <?php if($brand['val']==$brand_id){ ?>
                                            <option value="<?php echo $brand['val'];?>" selected ><?php echo $brand['name'];?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $brand['val'];?>"><?php echo $brand['name'];?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="input-categ">Категория:</label>
                                <select class="form-control" id="input-categ" name="category">
                                    <?php foreach($category as $cat) { ?>
                                        <?php if($cat['name']==$categ){ ?>
                                            <option value="<?php echo $cat['val'];?>" selected ><?php echo $cat['name'];?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $cat['val'];?>"><?php echo $cat['name'];?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="input-quant">Количество:</label>
                                <input type="text" name="quant" id="input-quant" class="form-control" value="<?php echo $quantity; ?>">
                            </div>
                            <div class="form-group">
                                <label for="input-price">Цена:</label>
                                <input type="text" name="price" id="input-price" class="form-control" value="<?php echo $price; ?>">
                            </div>
                        </div>
                        <!---------col-2-------------------->
                        <div class=" col-sm-4">
                            <div class="form-group">
                                <label for="input-model">Модель:</label>
                                <select class="form-control" id="input-model" name="model">
                                    <option value="univ">Универсальный</option>
                                    <?php foreach($models as $mod){ ?>
                                        <option id='bef-pcat' value="<?php echo $mod; ?>" <?php if($mod == $model) {echo 'selected';} ?> ><?php echo $mod; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="input-podcat">Подкатегория:</label>
                                <select class="form-control" id="input-podcat" name="podcat">
                                    <?php foreach($podcategs as $pc){ ?>
                                        <option id='bef-pcat' value="<?php echo $pc; ?>" <?php if($pc == $pcat) {echo 'selected';} ?> ><?php echo $pc; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="input-type">Тип:</label>
                                <select class="form-control" id="input-type" name="type">
                                    <option id='bef-type' value="Б/У" <?php if($type == 'Б/У') { echo 'selected'; } ?>>Б/У</option>
                                    <option id='bef-type' value="Новый" <?php if($type == 'Новый') { echo 'selected'; } ?>>Новый</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="input-cond">Состояние:</label>
                                <?php echo $cond_list; ?>
                            </div>
                            <div class="form-group col-sm-8">
                                <label for="input-stock">Склад:</label>
                                <select class="form-control" id="input-stock" name="stock">
                                    <option value="-">---</option>
                                    <?php foreach($stocks as $st) { ?>
                                        <?php if($st==$stock){ ?>
                                            <option value="<?php echo $st;?>" selected ><?php echo $st;?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $st;?>"><?php echo $st;?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="input-stell">Стеллаж:</label>
                                <input type="text" name="stell" id="input-stell" class="form-control" value="<?php echo $stell; ?>">
                            </div>
                        </div>
                        <!---------col-3-------------------->
                        <div class=" col-sm-4">
                            <div class="form-group">
                                <label for="input-modRow">Модельный ряд:</label>
                                <select class="form-control" id="input-modRow" name="modRow">
                                    <option value="univ">Универсальный</option>
                                    <?php foreach($modRs as $mr){ ?>
                                        <option id='bef-pcat' value="<?php echo $mr; ?>" <?php if($mr == $modRow) {echo 'selected';} ?> ><?php echo $mr; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="input-vin">Внутренний номер:</label>
                                <input type="text" name="vin" id="input-vin" class="form-control" value="<?php echo $vin; ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="input-catN">Каталожный номер:</label>
                                <input type="text" name="catN" id="input-catN" class="form-control" value="<?php echo $catN; ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="input-status">Статус:</label>
                                <select class="form-control" id="input-status" name="status">
                                    <option value="0" <?php if($status == '0') {echo 'selected';} ?> >Отключен</option>
                                    <option value="1" <?php if($status == '1') {echo 'selected';} ?> >Включен</option>
                                    <option value="2" <?php if($status == '2') {echo 'selected';} ?> >В резерве</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="input-jar">Ярус:</label>
                                <input type="text" name="jar" id="input-jar" class="form-control" value="<?php echo $jar; ?>">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="input-shelf">Полка:</label>
                                <input type="text" name="shelf" id="input-shelf" class="form-control" value="<?php echo $shelf; ?>">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="input-box">Коробка:</label>
                                <input type="text" name="box" id="input-box" class="form-control" value="<?php echo $box; ?>">
                            </div>
                        </div>
                        <!---------------------------------->
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="input-avito">Avito:</label>
                                <input type="text" name="avito" id="input-avito" class="form-control" value="<?php echo $avito; ?>">
                            </div>
                            <div class="form-group">
                                <label for="input-note">Примечание:</label>
                                <input type="text" name="note" id="input-note" class="form-control" value="<?php echo $note; ?>">
                            </div>
                            <div class="form-group">
                                <label for="input-dop">Доп.информация:</label>
                                <input type="text" name="dop" id="input-dop" class="form-control" value="<?php echo $dop; ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="input-drom">Drom:</label>
                                <input type="text" name="drom" id="input-drom" class="form-control" value="<?php echo $drom; ?>">
                            </div>
                            <div class="form-group">
                                <label for="input-compability">Применимость:</label>
                                <input type="text" name="compability" placeholder="Применимость" data-toggle="modal" data-target="#cpbModal" data-whatever="<?php echo $pid;?>" id="input-compability" class="form-control" value="<?php echo $compability; ?>">
                            </div>
                            <div class="form-group">
                                <label for="input-donor">Донор:</label>
                                <input type="text" name="donor" placeholder="Донор" id="input-donor" class="form-control" value="<?php echo $donor; ?>">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="complect" value="<?php echo $complect;?>">
                    <h3>Фотографии:</h3>
                    <div class="well col-sm-12">
                        <?php $count = 0; ?>
                        <?php foreach($images as $img) { ?>
                            <div style="float: left;" class="col-sm-2">
                                <a href="" id="thumb-image<?php echo $img['lid']?>" data-toggle="image" class="img-thumbnail" data-toggle="popover" <?php if($img['main']){echo 'style="box-shadow: 0px 0px 50px #4CAF50;"';} ?>>
                                    <img src="<?php echo $img['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                                </a>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="image[<?php echo $img['lid']?>][sort-order]" value="<?php echo $img['sort_order']; ?>" />
                                </div>
                                <input type="hidden" data-toggle='input-image' id="input-image<?php echo $img['lid']?>" name="image[<?php echo $img['lid']?>][img]" value="<?php echo $img['image']; ?>"/>
                            </div>
                        <?php ++$count; ?>
                        <?php } ?>
                        <input type="hidden" name="main-image" value="<?php echo $mainimage; ?>" id="input-main-image" />
                        <div class="text-center" style="float: left; padding: 3.5%;">
                            <button id="button-add-image" data-toggle="tooltip" data-original-title="Добавить фото" data-pointer="<?php echo $count;?>" class="btn btn-success btn-lg"><i class="fa fa-plus-circle"></i></button>
                        </div>
                    </div>
                    <div class='clearfix'></div>
                    <div class='clearfix'><p></p></div>
                    <div class="alert alert-success col-sm-12">
                        <h3>Индивидуальные настройки для Авито</h3>
                        <div class="col-md-6">
                            <div class="form-group-sm">
                                <label for="avitoname">Заголовок объявления отображаемый на авито(лимит: 50 символов). Оставьте пустым для автоматической генерации.</label>
                                <input type="text" name="avitoname" class="form-control" id="avitoname" maxlength="50" value="<?php echo $avitoname;?>" />
                            </div>
                            <div class="form-group-sm">
                                <label for="allowavito">Разместить товар на авито?</label>
                                <select name="allowavito" class="form-control" id="allowavito" maxlength="50">
                                    <option value="нет">Нет</option>
                                    <option value="да">Да</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='clearfix'></div>
                    <div class='clearfix'><p></p></div>
                </form>
                    <div class="well">
                        <p>
                            Справка:<br><br>
                            <button class="btn btn-primary"><i class="fa fa-pencil"></i> - изменить фотографию </button>
                            <button class="btn btn-danger"><i class="fa fa-trash-o"></i> - удалить фотографию </button>
                            <button class="btn btn-warning"><i class="fa fa-exclamation-circle"></i>  - сделать фотографию главной</button></p>
                    </div>
            </div>
        </div>
    </div>
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
                                url:"index.php?route=common/addprod/get_modalModel&token=<?php echo $token; ?>",
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
    <!-- Modal -->
    <div class="modal fade" id="compModal" tabindex="-1" role="dialog" aria-labelledby="compModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Выберите комплект</h4>
          </div>
          <div class="modal-body">
            <div class="form-group-sm">
                <label for="heading">Введите внутренний номер головного товара</label>
                <input type="text" class="form-control" id="heading"/>
            </div>
            <div class="row" id="result"></div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="setComp" disabled>Прикрепить</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
        $("#input-brand").on('change', function(){
            document.getElementById("input-model").innerHTML = '';
            document.getElementById("input-modRow").innerHTML = '';
            if($("#input-brand").val()==='univ'){
                $("#input-model").html('<option val="univ">Универсальный</option>');
                $("#input-modRow").html('<option val="univ">Универсальный</option>');
            } else {
                ajax({
                    url:"index.php?route=product/product_edit/get_model&token=<?php echo $token; ?>",
                    statbox:"status",
                    method:"POST",
                    data:
                    {
                                   brand: document.getElementById("input-brand").value,
                                   currMod: '<?php echo $model; ?>'
                    },
                    success:function(result){
                        document.getElementById("input-model").innerHTML = result;
                        document.getElementById("input-modRow").innerHTML = '';
                    }
                })
            }
        })
        
        $("#input-model").on('change', function(){
            if($("#input-model").val()==='univ'){
                $("#input-modRow").html('<option val="univ">Универсальный</option>');
            } else {
                ajax({
                    url:"index.php?route=product/product_edit/get_model&token=<?php echo $token; ?>",
                    statbox:"status",
                    method:"POST",
                    data:
                    {
                                   brand: document.getElementById("input-model").value,
                                   currMod: '<?php echo $modRow; ?>',
                                   mr: '1'
                    },
                    success:function(result){
                        document.getElementById("input-modRow").innerHTML = '';
                        document.getElementById("input-modRow").innerHTML = result;
                    }
                })
            }
        })
        
        $("#input-categ").on('change', function(){
            ajax({
                url:"index.php?route=product/product_edit/get_podcat&token=<?php echo $token; ?>",
                statbox:"status",
                method:"POST",
                data:
                {
                               cat: document.getElementById("input-categ").value,
                               currPC: '<?php echo $pcat; ?>'
                },
                success:function(result){
                    document.getElementById("input-podcat").innerHTML = result;
                }
            })
        })
        
        
        $("#heading").on('input', function(){
            ajax({
                url:"index.php?route=product/product_edit/getCompl&token=" + getURLVar('token'),
                statbox:"status",
                method:"POST",
                data:
                {
                    heading: $("#heading").val()
                },
                success:function(data){
                    if(data == 0) {
                        $("#result").html("<b>Такого комплекта не существует</b>");
                        $("#setComp").attr('disabled', 'true');
                    } else {
                        $("#result").html(data);
                        $("#setComp").removeAttr('disabled');
                    }
                    
                }
            })
        })
        
        $("#setComp").on('click', function(){
            var heading = $('#heading').val();
            var item = getURLVar('product_id');
            setCompl(item, heading);
        })
        
        $('#remCopml').on('click', function(){
            var heading = '<?php echo $complect; ?>';
            var item = getURLVar('product_id');
            remCompl(item, heading);
        })
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
</div>
<?php echo $footer; ?>
