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
                    <div class="col-lg-12"><div class="pull-right">
                        <input type="submit" class="btn btn-success" value="сохранить изменения" />
                    </div></div>
                    <?php echo $form;?>
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
                        <input type="hidden" name="info[image]" value="<?php echo $mainimage; ?>" id="input-main-image" />
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
