<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="row">
        <div>
            <div class="col-sm-8 input-group">
                <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_keyword; ?>" id="input-search" class="form-control" />
                <span class="input-group-btn"><input type="button" value="<?php echo $button_search; ?>" id="button-search" class="btn btn-danger " /></span>
            </div>
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
            <?php if ($product['ean']){ ?>
                <?php if ($product['ean']=='Б/У') { ?> 
                    <div class="eanr">Б/У</div>
                <?php }?>
                <?php if ($product['ean']=='Новый') { ?> 
                    <div class="eanb">Новый</div>
                <?php }?>
            <?php }?>
            <?php if ($product['comp']){ ?>
                <?php if ($product['com_whole'] == 1){ ?>
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
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div>
              <div class="caption">
                  <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                    <?php if ($product['ean'] && $product['ean']=='Б/У') { ?>
                        <p><b>Внутренний номер:</b> <?php echo $product['description']; ?></p>
                        <?php if ($product['catN']) { ?>
                            <p><b>Каталожный номер:</b> <?php echo $product['catN']; ?></p>
                        <?php }?>
                    <?php }?>
                    <?php if ($product['note']) { ?>
                        <p><b>Примечание:</b> <?php echo $product['note']; ?></p>
                    <?php }?>
                    <?php if ($product['ean']) { ?>
                        <p><b>Тип:</b> <?php echo $product['ean']; ?></p>
                    <?php }?>
                    <?php if ($product['compability']) { ?>
                        <p><b>Применимость:</b> <?php echo $product['compability']; ?></p>
                    <?php }?>
                    <?php if (isset($product['cond']) && $product['cond']!='-') { ?>
                        <p><b>Состояние:</b> <?php echo $product['cond']; ?></p>
                    <?php }?>
                    <?php if ($product['com_whole'] == 1 && $product['com_price'] != 0)  { ?>
                        <p class="price"><b>Цена комплекта: <?php echo $product['com_price']; ?></b></p>
                    <?php } elseif ($product['price'] != 0.00) { ?>
                        <p class="price"><b>Цена: <?php echo $product['price']; ?></b></p>
                    <?php }?>   
              </div>
              <div class="button-group">
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row">
        <!--<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>-->
      </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
$('#button-search').bind('click', function() { <!--
	url = 'index.php?route=product/search';

	var search = $('#content input[name=\'search\']').prop('value');

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
-->
</script>
<?php echo $footer; ?>