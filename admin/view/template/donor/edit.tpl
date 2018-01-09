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
        <form action="<?php echo $action;?>" method="post" enctype="multipart/form-data">
            
            <div class="col-md-3">
                <div class="form-group-sm">
                    <label for="number">Внутренний номер</label>
                    <input class="form-control" name="number" id="number" type="text" value="<?php echo $donor['numb'];?>"/>
                </div>
                <div class="form-group-sm">
                    <label for="cuzov">Выберите Тип кузова</label>
                    <select class="form-control" id="cuzov" name="cuzov">
                        <option value="седан" >седан</option>
                        <option value="хетчбек" <?php if ($donor['ctype']==='хетчбек') {echo "selected";} ?>>хетчбек</option>
                        <option value="универсал" <?php if ($donor['ctype']==='универсал') {echo "selected";} ?>>универсал</option>
                        <option value="купе" <?php if ($donor['ctype']==='купе') {echo "selected";} ?>>купе</option>
                        <option value="внедорожник" <?php if ($donor['ctype']==='внедорожник') {echo "selected";} ?>>внедорожник</option>
                        <option value="кроссовер" <?php if ($donor['ctype']==='кроссовер') {echo "selected";} ?>>кроссовер</option>
                        <option value="пикап" <?php if ($donor['ctype']==='пикап') {echo "selected";} ?>>пикап</option>
                        <option value="минивэн" <?php if ($donor['ctype']==='минивэн') {echo "selected";} ?>>минивэн</option>
                        <option value="лифтбек" <?php if ($donor['ctype']==='лифтбек') {echo "selected";} ?>>лифтбек</option>
                    </select>
                </div>
                <div class="form-group-sm">
                    <label for="vin">VIN</label>
                    <input class="form-control" name="vin" id="vin" type="text" value="<?php echo $donor['vin'];?>"/>
                </div>
                <div class="form-group-sm">
                    <label for="price">Стоимость</label>
                    <input class="form-control" name="price" id="price" type="text" value="<?php echo $donor['price'];?>"/>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group-sm">
                    <label for="brand">Выберите марку</label>
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
                            <option value="<?php echo $brand['val']; ?>" <?php if ($brand['name']===$donor['brand']){echo 'selected';} ?>><?php echo $brand['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group-sm">
                    <label for="dvs">ДВС</label>
                    <input class="form-control" name="dvs" id="dvs" type="text" value="<?php echo $donor['dvs'];?>"/>
                </div>
                <div class="form-group-sm">
                    <label for="color">Цвет кузова</label>
                    <input class="form-control" name="color" id="color" type="text" value="<?php echo $donor['color'];?>"/>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group-sm">
                    <label for="model1">Выберите модель</label>
                    <div id="model1"><select class="form-control" name='model_id' class='form-control' id='model' onchange='
                        ajax({
                            url:"index.php?route=common/addprod/get_modelRow&token=<?php echo $token_add; ?>",
                            statbox:"status",
                            method:"POST",
                            data:
                            {
                                           model: document.getElementById("model").value,
                                           token: "<?php echo $token_add; ?>"
                            },
                           success:function(data){document.getElementById("model_row").innerHTML=data;}
                        })'>
                            <?php foreach ($models as $model) { ?>
                                <option value="<?php echo $model['val']; ?>" <?php if ($model['name']===$donor['model']){echo 'selected';} ?>><?php echo $model['name']; ?></option>
                            <?php } ?>
                        </select></div>
                </div>
                <div class="form-group-sm">
                    <label for="year">Год выпуска</label>
                    <input class="form-control" name="year" id="year" type="text" value="<?php echo $donor['year'];?>"/>
                </div>
                <div class="form-group-sm">
                    <label for="trans">Трансмиссия</label>
                    <select class="form-control" id="trans" name="trans">
                        <option value="MT">MT</option>
                        <option value="AT" <?php if($donor['trmiss']==='AT') {echo 'selected';}?>>AT</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group-sm">
                    <label for="model_row">Выберите модельный ряд</label>
                    <div id="model_row">
                        <select name='modelRow_id' id='model_row_id' class='form-control'>
                            <?php foreach ($model_rows as $model_row) { ?>
                                <option value="<?php echo $model_row['val']; ?>" <?php if ($model_row['name']===$donor['mod_row']){echo 'selected';} ?>><?php echo $model_row['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group-sm">
                    <label for="kilometers">Пробег</label>
                    <input class="form-control" name="kilometers" id="kilometers" type="text" value="<?php echo $donor['kmeters'];?>"/>
                </div>
                <div class="form-group-sm">
                    <label for="privod">Привод</label>
                    <select class="form-control" id="privod" name="privod">
                        <option value="2WD">2WD</option>
                        <option value="4WD" <?php if ($donor['priv']==='4WD') {echo 'selected';} ?>>4WD</option>
                    </select>
                </div>
            </div>
                        <div class="clearfix"></div>
                        <div class="clearfix"><p></p></div>
            <div class="well col-sm-12">
                <?php $count = 0; ?>
                <?php foreach($images as $img) { ?>
                    <div style="float: left;">
                        <a href="" id="thumb-image<?php echo $img['lid']?>" data-toggle="image" class="img-thumbnail" data-toggle="popover" <?php if($img['main']){echo 'style="box-shadow: 0px 0px 50px #4CAF50;"';} ?>>
                            <img src="<?php echo $img['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                        </a>
                        <input type="hidden" id="input-image<?php echo $img['lid']?>" name="image[]" value="<?php echo $img['image']; ?>" />
                    </div>
                <?php ++$count; ?>
                <?php } ?>
                <input type="hidden" name="main-image" value="<?php echo $mainimage; ?>" id="input-main-image" />
                <div class="text-center" style="float: left; padding: 3.5%;">
                    <button id="button-add-image" data-toggle="tooltip" data-original-title="Добавить фото" data-pointer="<?php echo $count;?>" class="btn btn-success btn-lg"><i class="fa fa-plus-circle"></i></button>
                </div>
            </div>
                        <div class="clearfix"></div>
                    <div class="well">
                        <p>
                            Справка:<br><br>
                            <button class="btn btn-primary"><i class="fa fa-pencil"></i> - изменить фотографию </button>
                            <button class="btn btn-danger"><i class="fa fa-trash-o"></i> - удалить фотографию </button>
                            <button class="btn btn-warning"><i class="fa fa-exclamation-circle"></i>  - сделать фотографию главной</button></p>
                    </div>
                        <div class="clearfix"></div>
            <button type="submit" class="btn btn-success col-md-3">Сохранить</button>
        </form>
                        <div class="clearfix"></div>
                        <div class="clearfix"><p></p></div>
        <div class="well well-sm col-sm-12">
            <p>Общее количество деталей: <span class="label label-success"><?php echo $donor['quant'];?></span></p>
            <p>Общяя стоимость деталей: <span class="label label-success"><?php echo $donor['totalp'];?></span></p>
        </div>
        <table class="table table-bordered table-hover table-responsive">
              <thead>
                <tr>
                  
                  <td class="text-center">Изображение</td>
                  <td>Название</td>
                  <td>Внутренний номер</td>
                  <td>Расположение</td>
                  <td>Цена</td>
                  <td>Категория</td>
                  <td>Количество</td>
                  <td class="text-left">Статус</td>
                  <td class="text-left">Дата создания</td>
                  <td class="text-right">Действие</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-center"><?php if ($product['image']) { ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
                  <td class="text-left"><a href="<?php echo $go_site; ?><?php echo $product['product_id']; ?>" target="blank" data-toggle="tooltip" title="" data-original-title="Перейти к продукту"><?php echo $product['name']; ?></a></td>
                  <td><?php echo $product['vin']; ?></td>
                  <td><?php echo $product['stock']?>/<?php echo $product['location']; ?></td>
                  <td><?php echo $product['price']?></td>
                  <td class="text-left"><?php echo $product['category']; ?></td>

                  <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $product['quantity']; ?></span>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['status']; ?></td>
                  <td class="text-left"><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $product['date_added'])->format('d.‌​m.Y'); ?></td>
                  <td class="text-right"><a href="<?php echo $product['edit'];?>" data-toggle="tooltip" title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10">За донором не закреплены детали</td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
    </div>
</div>
<?php echo $footer;?>