<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php if($column_left) { echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } } else {$class = 'col-sm-12';} ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="col-sm-12">
            <div class="row input-group">
                <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_keyword; ?>" id="input-search" class="form-control" />
                <span class="input-group-btn"><input type="button" value="<?php echo $button_search; ?>" id="button-search" class="btn btn-danger " /></span>
            </div>
      </div>
      <h2><?php echo $text_search; ?></h2>
      <?php if ($products) { ?>
      <div class="row">
        <div class="col-md-2 col-sm-6 hidden-xs">
          <div class="btn-group btn-group-sm">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="form-group">
            <a href="<?php echo $compare; ?>" id="compare-total" class="btn btn-link"><?php echo $text_compare; ?></a>
          </div>
        </div>
        <div class="col-sm-6 text-right"><h4>По вашему запросу в базе найден(-о) <b><?php echo $results; ?></b> товар(-ов):</h4></div>
      </div>
      <div class="row">
        <?php foreach ($products as $product) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="label-thumd">
            <?php if (isset($product['labels']) && count($product['labels'])){ 
                for($i = 1; $i < 4; ++$i){ 
                    if(isset($product['labels'][$i]) && $product['labels'][$i]['value']!=='' && $product['labels'][$i]['value']!=='-'){ ?>
                    <div class="<?php echo $product['labels'][$i]['color']; ?>"><?php echo $product['labels'][$i]['value']; ?></div>
                <?php }
                }
            }?>
            <?php if ($product['comp']){ ?>
                <?php if ($product['comp_whole'] == 1){ ?>
                <div class="whole" title="На фото изображен полный комплект, содержащий данную деталь. &#013Цена указана за полный комплект.">
                        Продажа комплектом
                    </div>
                <?php } else { ?>
                <div class="whole" title="На фото изображен комплект деталей.&#013При продаже деталь будет снята с комплетка.&#013Цена указана за «голую» деталь">
                        Деталь из комплекта
                    </div>
                <?php }?>
            <?php }?>
            </div>  
          <div class="product-thumb">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb'].'?'.time(); ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div>
              <div class="caption">
                  <h4><a href="<?php echo $product['href'];?>" ><?php echo $product['name'];?></a></h4>
                  <p>Артикул: <?php echo $product['vin'];?></p>
                  <?php foreach($product['options'] as $option){ ?>
                    <?php if($option['value']!='-'){ ?><p><?php echo $option['text'];?>: <?php echo htmlspecialchars_decode($option['value']);?></p><?php }?>
                  <?php }?>
                  <?php if ($product['price'] !== '0') { ?>
                  <p><b>Цена: <?php echo $product['price'];?></b></p>
                  <?php }?>
              </div>
              <div class="button-group">
                <?php if ($product['status'] == '2') { ?>
                    <button btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pname ="<?php echo $product['name'];?>" pvin="<?php echo $product['vin'];?>"><span class="hidden-xs hidden-sm hidden-md">Уточнить наличие</span></button>
                <?php } else { ?>                   
                    <?php if ($product['price'] == 0.00 || $product['quantity'] == 0)  { ?>
                        <?php if ($product['price'] == 0.00) { ?>
                            <button btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pname ="<?php echo $product['name'];?>" pvin="<?php echo $product['vin'];?>"><span class="hidden-xs hidden-sm hidden-md">Узнать стоимость</span></button>
                        <?php } else { ?>
                            <button btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pname ="<?php echo $product['name'];?>" pvin="<?php echo $product['vin'];?>"><span class="hidden-xs hidden-sm hidden-md">Заказать товар</span></button> 
                        <?php } ?>
                    <?php } else { ?>  
                        <button type="button" onclick="cart.add('<?php echo $key; ?>', '1');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"> В КОРЗИНУ</span></button>
                    <?php } ?>
                <?php } ?>    
                <button type="button" data-toggle="tooltip" title="Добавить в избранное" onclick="wishlist.add('<?php echo $key; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="Добавить в список сравнения" onclick="compare.add('<?php echo $key; ?>');"><i class="fa fa-exchange"></i></button>
              </div>
            <?php echo $modal_window; ?>  
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row">
        <!--<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>-->
      </div>
      <?php } else { ?>
      <p><div class="col-lg-12 text-center"><img src="sad.png" width="150"/><br><h4>К сожалению, ничего не найдено. Попробуйте изменить условия поиска или позвоните нам, чтобы уточнить наличие детали по телефону.<br><b>+ ‎7 (912) 475 08 70</b></h4></div></p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
$('#button-search').bind('click', function() {
	url = 'index.php?route=product/search';

	var trimsearch = $('#content input[name=\'search\']').prop('value').replace(/\s+/g," ");
        var search = $.trim(trimsearch);
        $('#content input[name=\'search\']').val(search);

	if (search) {
		url += '&search=' + encodeURIComponent(search);
	}

	var category_id = $('#content select[name=\'category_id\']').prop('value');

	if (category_id > 0) {
		url += '&category_id=' + encodeURIComponent(category_id);
	}

	var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');

	if (sub_category) {
		url += '&sub_category=true';
	}

	var filter_description = $('#content input[name=\'description\']:checked').prop('value');

	if (filter_description) {
		url += '&description=true';
	}

	location = url;
});

$('#content input[name=\'search\']').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('select[name=\'category_id\']').on('change', function() {
	if (this.value == '0') {
		$('input[name=\'sub_category\']').prop('disabled', true);
	} else {
		$('input[name=\'sub_category\']').prop('disabled', false);
	}
});

$('select[name=\'category_id\']').trigger('change');
</script>
<?php echo $footer; ?>