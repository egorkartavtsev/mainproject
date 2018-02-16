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
        <div class="col-xs-12">
        <div class="col-md-3 alert alert-success">
            <table class="table table-hover table-bordered table-condensed">
                <tr><td><b>Внутренний номер: </b></td><td><?php echo $donor['numb'];?></td></tr>
                <tr><td><b>Тип кузова: </b></td><td><?php echo $donor['ctype']; ?></td></tr>
                <tr><td><b>VIN: </b></td><td><?php echo $donor['vin'];?></td></tr>
                <tr><td><b>Стоимость: </b></td><td><?php echo $donor['price'];?></td></tr>
                <tr><td><b>Марка: </b></td><td><?php echo $donor['brand'];?></td></tr>
                <tr><td><b>Модель: </b></td><td><?php echo $donor['model'];?></td></tr>
                <tr><td><b>Модельный ряд: </b></td><td><?php echo $donor['mod_row'];?></td></tr>
                <tr><td><b>ДВС: </b></td><td><?php echo $donor['dvs'];?></td></tr>
                <tr><td><b>Цвет: </b></td><td><?php echo $donor['color'];?></td></tr>
                <tr><td><b>Трансмиссия: </b></td><td><?php echo $donor['trmiss'];?></td></tr>
                <tr><td><b>Пробег: </b></td><td><?php echo $donor['kmeters'];?></td></tr>
                <tr><td><b>Привод: </b></td><td><?php echo $donor['priv'];?></td></tr>
                <tr><td><b>Год выпуска: </b></td><td><?php echo $donor['year'];?></td></tr>
            </table>
            <p><b>Примечание: </b><?php echo $donor['note'];?></p>
        </div>
        <div class="col-md-6">
            <?php if (isset($images) || isset($thumbs)) { ?>
                <?php if ($thumb || $images) { ?>
                    <ul class="thumbnails">
                        <?php if ($thumb) { ?>
                            <li><a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
                        <?php } ?>
                        <?php if ($images) { ?>
                            <?php foreach ($images as $image) { ?>
                                <li class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
            <?php } ?>
        </div>
        <?php if($utype=='adm') { ?>
            <div class="well well-sm col-md-3">
                <p>Общее количество деталей: <span class="label label-success"><?php echo $donor['quant'];?></span></p>
                <p>Общяя стоимость деталей: <span class="label label-success"><?php echo $donor['totalp'];?></span></p>
            </div>
        <?php }?>
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
<script src="view/javascript/jquery/magnific/jquery.magnific-popup.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/magnific/magnific-popup.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript">
    $(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
    });
</script>
<?php echo $footer;?>