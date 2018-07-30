<?php echo $header; ?>
<script type="text/javascript" src="view/javascript/editprod.js"></script>
<?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
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
                <form method="POST" action="<?php echo $action;?>" name="form-product">
                    <div class="col-md-6">
                        <?php echo $form;?>
                        <div class="alert alert-success col-sm-12">
                        <h3>Индивидуальные настройки для Авито</h3>
                            <div class="form-group-sm">
                                <label for="avitoname">Заголовок объявления отображаемый на авито(лимит: 50 символов). Оставьте пустым для автоматической генерации.</label>
                                <input type="text" name="info[avitoname]" class="form-control" id="avitoname" maxlength="50" value="<?php echo $avitoname;?>" />
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
                    <div class="col-md-6">                     
                        <div class="well col-sm-12">
                            <h3>Фотографии:</h3>
                            <hr>
                            <?php $count = 0; ?>
                            <?php if(isset($images)) { foreach($images as $img) { ?>
                                <div style="float: left;" class="col-sm-4">
                                    <a href="" id="thumb-image<?php echo $img['lid']?>" data-toggle="image" class="img-thumbnail" data-toggle="popover" <?php if($img['main']){echo 'style="box-shadow: 0px 0px 50px #4CAF50;"';} ?>>
                                        <img src="<?php echo $img['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                                    </a>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="image[<?php echo $img['lid']?>][sort-order]" value="<?php echo $img['sort_order']; ?>" />
                                    </div>
                                    <input type="hidden" data-toggle='input-image' id="input-image<?php echo $img['lid']?>" name="image[<?php echo $img['lid']?>][img]" value="<?php echo $img['image']; ?>"/>
                                </div>
                            <?php ++$count; ?>
                            <?php } } ?>
                            <input type="hidden" name="info[image]" value="<?php echo $mainimage; ?>" id="input-main-image" />
                            <div class="text-center" style="float: left; padding: 3.5%;">
                                <button id="button-add-image" data-toggle="tooltip" data-original-title="Добавить фото" data-pointer="<?php echo $count;?>" class="btn btn-success btn-lg"><i class="fa fa-plus-circle"></i></button>
                            </div>
                            <div class='clearfix'></div>
                            <div class='clearfix'><p></p></div>
                            <div>
                                <p>
                                    Справка:<br><br>
                                    <a class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> - изменить фотографию </a><br>
                                    <a class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i> - удалить фотографию </a><br>
                                    <a class="btn btn-sm btn-warning"><i class="fa fa-exclamation-circle"></i>  - сделать фотографию главной</a><br>
                                </p>
                            </div>
                        </div>
                        <?php if ($complect != '') { ?>             
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="alert alert-success">
                                        <div class="col-sm-12">
                                            <div class ="row">
                                                <label id="name" val="<?php echo $kit['name'];?>">Название комплекта:<h3><span class="label label-primary " title="Открыть редактирование комплекта в новом окне"><a style="color: #FFFFFF!important;" target="_blank" href="<?php echo $clink;?>"><i class="fa fa-pencil-square-o"></i>  <?php echo $kit['name'];?></a></span></h3></label>
                                                <br>
                                                <label id="heading" val="<?php echo $kit['heading'];?>" comp_id="<?php echo $kit['id'];?>">Головной товар(комплектообразующий):<h3><span class="label label-warning" title="Открыть редактирование товара в новом окне"><a style="color: #FFFFFF!important;" target="_blank" href="<?php echo $plink;?>"><i class="fa fa-pencil-square-o"></i>  <?php echo $kit['heading'];?></a></span></h3></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class ="row">
                                                <div class="col-sm-3">
                                                    <label>Цена комплекта</label>
                                                    <input class="form-control" name="price" id="price" value="<?php echo $kit['price'];?>"/>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Способ продажи комплекта</label>
                                                    <select id="whole" class="form-control">
                                                        <option value="0">Обычная продажа</option>
                                                        <option value="1">Только комплект целиком</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Скидка(в процентах. при значении 0, скидка = 15%)</label>
                                                    <div class="input-group">
                                                        <input class="form-control" name="sale" id="sale" value="<?php echo $kit['sale']?>"/>
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </div>   
                                                <div class="col-sm-3">
                                                    <a class='btn btn-info btn-block' token="<?php echo $token ?>" btn_type="save_comp_info">Сохранить</a>
                                                </div>    
                                            </div>
                                        </div>      
                                    </div>
                                    <div class="alert alert-success">
                                        <h4>Комплектующие:</h4>
                                            <?php $i = 0; ?>
                                            <table class="table" id='complect'>
                                                <?php foreach($kit['accessories'] as $acc) { ?>
                                                    <tr id="c<?php echo $i; ?>">
                                                        <td id="accss<?php echo $i; ?>">
                                                            <?php echo $acc['vin']?>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo $acc['cp_link'];?>" target="_blank"><i class="fa fa-pencil-square"></i> <?php echo $acc['name']?></a>
                                                        </td>
                                                        <td>
                                                            <?php echo $acc['price']?>
                                                        </td>
                                                    </tr>
                                                    <?php ++$i; ?>
                                                <?php } ?>
                                            </table>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>                      
                </form>
            </div>                            
        </div>
    </div>
    <div class="modal fade" id="settingsLevel" tabindex="-1" role="dialog" aria-labelledby="settingsLevelLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="settingsLevelLabel">Настройки</h4>
          </div>
          <div class="modal-body" id="level-settings">
          </div>
          <div class="modal-footer">
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
    <!-- Modal -->
    <div class="modal fade" id="createFillModal" tabindex="-1" role="dialog" aria-labelledby="createFillModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="createFillLabel">Введите название нового элемента</h4>
          </div>
          <div class="modal-body">
            <div class="form-group-sm">
                <input type="text" class="form-control" id="fillname"/>
            </div>
            <div class="row" id="result"></div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="createFill" disabled>Создать</button>
          </div>
        </div>
      </div>
    </div>   
    <script type="text/javascript">
        $("#heading").on('input', function(){
            ajax({
                url:"index.php?route=production/catalog/getCompl&token=" + getURLVar('token'),
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
