<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row">
    <div id="content" class="col-sm-12"><?php echo $content_top; ?>
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
            <div id="carousel-example-generic<?php echo $product['product_id']; ?>" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators" style="display: none;">
                    <?php foreach($product['image'] as $image) { ?>
                        <?php if (isset($image['main']) && $image['main'] == true) { ?> 
                            <li data-target="#carousel-example-generic<?php echo $product['product_id']; ?>" data-slide-to="<?php echo $image['lid']?>" class="active"></li>
                        <?php } else { ?>
                            <li data-target="#carousel-example-generic<?php echo $product['product_id']; ?>" data-slide-to="<?php echo $image['lid']?>"></li>
                        <?php }?>
                    <?php }?> 
                </ol>
                <div class="carousel-inner" role="listbox">
                    <?php foreach($product['image'] as $image) { ?> 
                        <?php if (isset($image['main']) && $image['main'] == true) { ?> 
                           <div class="item active">
                        <?php } else { ?>
                           <div class="item">
                        <?php }?>
                        <div class="image"><a href="<?php echo $product['href'];?>"><img src="<?php echo $image['thumb'].'?'.time();?>" alt="<?php echo $product['name'];?>" title="<?php echo $product['name']; ?>" class="img-thumbnail d-block w-100 img-responsive" /></a></div>
                           </div>   
                    <?php }?>  
                </div>
                <a class="left carousel-control" href="#carousel-example-generic<?php echo $product['product_id']; ?>" role="button" data-slide="prev">
                    <span class="fa fa-angle-left" style="font-size: 2em; margin-top: 200%" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic<?php echo $product['product_id']; ?>" role="button" data-slide="next">
                    <span class="fa fa-angle-right" style="font-size: 2em; margin-top: 200%" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>            
            </div>
            <script type="text/javascript" async>
                $('#carousel-example-generic<?php echo $product['product_id']; ?>').carousel();    
            </script>
            <div>
                <div class="col-lg-12 nameProd">
                    <h4><a href="<?php echo $product['href'];?>" ><?php echo $product['name'];?></a></h4>
                </div>
                <div class="caption text-center">
                    <p>Артикул: <?php echo $product['vin'];?></p>
                    <?php foreach($product['options'] as $option){ ?>
                        <?php if($option['value']!='-'){ ?><p><?php echo $option['text'];?>: <?php echo htmlspecialchars_decode($option['value']);?></p><?php }?>
                    <?php }?>
                </div>
                <div class="col-lg-12 text-center priceProd">
                    <p>
                        <?php if ($product['price'] !== '0' && $product['comp_whole'] !== '1') { ?>
                            <b>Цена: <?php echo $product['price'];?> &#8381;</b>
                        <?php } else { ?>
                            &nbsp;
                        <?php }?>
                    </p>
                </div>
                <?php echo $modal_window; ?>
                <div class="col-lg-12">
                    <div class="btn-group center-block" style="width: fit-content">
                        <?php if ($product['status'] == '2') { ?>
                            <button class="btn btn-lg btn-danger center-block checkPr" btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pid="<?php echo $product['product_id'];?>" pcause="1">Уточнить наличие</button>
                        <?php } elseif ($product['price'] == 0.00 && $product['quantity'] !== 0) { ?>                                           
                            <button class="btn btn-lg btn-danger center-block checkPr" btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pid="<?php echo $product['product_id'];?>" pcause="2">Узнать стоимость</button>
                        <?php } elseif ($product['price'] !== 0.00 && $product['quantity'] == 0) { ?>
                            <button class="btn btn-lg btn-danger center-block checkPr" btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pid="<?php echo $product['product_id'];?>" pcause="3">Товара нет в наличии - Заказать товар</button> 
                        <?php } elseif ($product['comp_whole'] == '1') { ?>
                            <a href="<?php echo $product['href'];?>"><button class="btn btn-lg btn-danger center-block checkPr">Посмотреть комплект</button></a>
                        <?php } else { ?>     
                            <button class="btn btn-lg btn-danger center-block checkPr" type="button" onclick="cart.add('<?php echo $product['product_id'];; ?>', '1');"><i class="fa fa-shopping-cart"></i> В КОРЗИНУ</button>
                        <?php } ?> 
                         <button type="button" style="margin-left: 5px" data-toggle="tooltip" btn_type="copyToSend" data-text="<?php echo $product['href'];?>"  trigger="clipb" class="btn btn-lg btn-default checkPr" title="Скопировать ссылку"><i class="fa fa-copy"></i></button>
                    </div>
                </div>
               </div>
              </div>
            </div>
        <?php } ?>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      </div>
      <?php } else { ?>
        <div class="col-lg-12 text-center">
            <img src="sad.png" width="150"/><br>
            <h4>
                К сожалению, ничего не найдено. Позвоните нам, чтобы уточнить наличие детали по телефону.<br>
                <a href="tel: +79124750870" class="hidden-md hidden-lg btn btn-danger col-lg-6"><span><i class="fa fa-phone"></i><b>+ ‎7 (912) 475 08 70</b></span></a>
                <span class="hidden-xs hidden-sm"><b>+ ‎7 (912) 475 08 70</b></span>
                <div class="col-sm-12 text-center">
                    Или любым удобным для Вас способом:<br>
                    <a style="cursor: pointer;" href="viber://chat?number=+79124750870"><img src="<?php echo $viber;?>" width="50"></a>
                    <a style="cursor: pointer;" href="https://wa.me/79124750870"><img src="<?php echo $whatsapp;?>" width="50"></a>
                    <a style="cursor: pointer;" href="tg://resolve?domain=Autorazbor174"><img src="<?php echo $tg;?>" width="50"></a>
                    <a  target="_blank" href="https://vk.com/mgnautorazbor"><img src="<?php echo $lvk;?>" width="50"></a>
                    <a  target="_blank" href="https://www.instagram.com/autorazbor174"><img src="<?php echo $linst;?>" width="50"></a>
                    <a  target="_blank" href="https://www.youtube.com/channel/UCNgBC4t07efN7qMYUls0fcw"><img src="<?php echo $lyt;?>" width="50"></a>
                    <a  target="_blank" href="https://baza.drom.ru/user/AUTORAZBOR174RU"><img src="<?php echo $ldrom;?>" width="50"></a>
                    <a  target="_blank" href="https://www.avito.ru/autorazbor174"><img src="<?php echo $lavito;?>" width="50"></a>
                </div>
            </h4>
        </div>
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
