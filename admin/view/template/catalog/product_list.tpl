<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading" id="panel-heading-f" style='cursor: pointer;' data-toggle="tooltip" title="" data-original-title="Показать/скрыть фильтр">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well" id="panel-body-f">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $column_category; ?></label>
                <select name="filter_category" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($categories as $category) { ?>
                  <?php if ($category['product_count'] >= 1) { ?>
                  <?php if ($category['category_id']==$filter_category) { ?>
                  <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</option>
                  <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>">&nbsp;&nbsp;<?php echo $category['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</option>
                  <?php } ?>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-vin">Внутренний номер</label>
                <input type="text" name="filter_vin" value="<?php echo $filter_vin; ?>" placeholder="Внутренний номер" id="input-vin" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-donor">Донор</label>
                <input type="text" name="filter_donor" value="<?php echo $filter_donor; ?>" placeholder="Донор" id="input-donor" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-brand">Марка, модель, модельный ряд</label>
                <input type="text" name="filter_brand" value="<?php echo $filter_brand; ?>" placeholder="Марка, модель, модельный ряд" id="input-brand" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-drom">Наличие на дром</label>
                <select name="filter_drom" id="input-drom" class="form-control"><option value=""> </option><option value="1" <?php if($filter_drom==='1'){echo 'selected';} ?>>Есть</option><option value="0" <?php if($filter_drom==='0'){echo 'selected';} ?>>Нет</option></select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status == '1') { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if ($filter_status == '2') { ?>
                  <option value="2" selected="selected"><?php echo $text_reserve; ?></option>
                  <?php } else { ?>
                  <option value="2"><?php echo $text_reserve; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-image"><?php echo $entry_image; ?></label>
                <select name="filter_image" id="input-image" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_image) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_image && !is_null($filter_image)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-catn">Каталожный номер</label>
                <input type="text" name="filter_catn" value="<?php echo $filter_catn; ?>" placeholder="Каталожный номер" id="input-catn" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-left"><i class="fa fa-filter"></i> Применить <?php echo $button_filter; ?></button>
              <button type="button" id='btn-clear-filters' class="btn btn-danger pull-left"><i class="fa fa-binoculars"></i> Очистить фильтры</button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-responsive">
              <thead>
                <tr>
                  <?php if($user['access'] == 19 || $user['access'] == 99999) { ?>
                    <td class="text-center" colspan="2" style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <?php } else { ?>
                    <td class="text-center"><i class="fa fa-android"></i></td>
                  <?php }?>

                  <td class="text-center col-sm-2"><?php echo $column_image; ?></td>
                  <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td>Внутренний номер</td>
                  <td><?php if ($sort == 'p.location') { ?>
                    <a href="<?php echo $sort_locate; ?>" class="<?php echo strtolower($order); ?>">Расположение</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_locate; ?>">Расположение</a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.price') { ?>
                    <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                    <?php } ?></td>
                  
                  <td class="text-left">Примечание</td>
                  <td class="text-left">Допинфо</td>
                  
                  <td>Донор</td>
                  
                  <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                    <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_quantity; ?>">Кол-во</a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <?php if($user['access'] == 19 || $user['access'] == 99999) { ?><td>Дата продажи</td><?php } ?>
                  <?php if($user['access'] == 19 || $user['access'] == 99999) { ?>
                  <td class="text-left"><?php if ($sort == 'p.manager') { ?>
                    <a href="<?php echo $sort_manager; ?>" class="<?php echo strtolower($order); ?>">Менеджер</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_manager; ?>">Менеджер</a>
                    <?php } ?>
                  </td>
                  <?php }?>
                  <td class="text-left"><?php if ($sort == 'p.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Дата создания</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>">Дата создания</a>
                    <?php } ?></td>
                  <?php if($user['access'] == 19 || $user['access'] == 99999) { ?><td class="text-left">Дней на складе</td><?php }?>
                </tr>
              </thead>
              <tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <?php if($user['access'] == 19 || $user['access'] == 99999) { ?><td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                    <?php } ?></td><?php }?>
                  <td class="text-center">
                      <a class="btn btn-success" btn_type="descText" desc-target="<?php echo $product['product_id']; ?>" title="Описание для ДРОМ" data-toggle="modal" data-target="#proDescription"><i class="fa fa-text-height"></i></a>
                      <a class="btn btn-warning" btn_type="showProd" target="<?php echo $product['product_id']; ?>" title="Информация о товаре" data-toggle="modal" data-target="#productInfoModal"><i class="fa fa-eye"></i></a>
                      <a href="<?php echo $product['edit'];?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                  </td>
                  <td class="text-center">
                    <div id="carousel-example-generic<?php echo $product['product_id']; ?>" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
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
                                       <div class="item ">
                                    <?php }?>
                                        <img src="<?php echo $image['thumb'];?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail d-block w-100" />
                                    </div>   
                                <?php }?>  
                        </div>
                        <a class="left carousel-control" href="#carousel-example-generic<?php echo $product['product_id']; ?>" role="button" data-slide="prev">
                            <span class="fa fa-angle-left fa-2x" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic<?php echo $product['product_id']; ?>" role="button" data-slide="next">
                            <span class="fa fa-angle-right fa-2x" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>            
                    </div>
                    <script type="text/javascript" async>
                            $('#carousel-example-generic<?php echo $product['product_id']; ?>').carousel();    
                    </script>     
                  </td>
                  
                  <td class="text-left"><a href="<?php echo $go_site; ?><?php echo $product['product_id']; ?>" target="blank" data-toggle="tooltip" title="" data-original-title="Перейти к продукту"><?php echo $product['name']; ?></a></td>

                  <td><?php echo $product['vin']; ?></td>
                  <td><?php echo $product['stock'].'/'.$product['stell'].'/'.$product['jar'].'/'.$product['shelf'].'/'.$product['box']; ?></td>
                  <td class="text-right"><?php echo $product['price']; ?></td>
                  <td class="text-left"><?php echo htmlspecialchars_decode($product['note']); ?></td>
                  <td class="text-left"><?php echo htmlspecialchars_decode($product['dop']);?></td>
                  <td class="text-left"><?php echo $product['donor'];?></td>
                  

                  <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $product['quantity']; ?></span>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['status']; ?></td>
                  <?php if($user['access'] == 19 || $user['access'] == 99999) { ?><td class="text-left"><?php echo $product['saled']; ?></td><?php }?>
                  <?php if($user['access'] == 19 || $user['access'] == 99999) { ?><td class="text-left"><?php echo $product['manager']; ?></td><?php }?>
                  <td class="text-left"><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $product['date_added'])->format('d.‌​m.Y'); ?></td>
                  <?php if($user['access'] == 19 || $user['access'] == 99999) { ?>
                    <td class="text-left">
                        <?
                            if($product['dateDif']>=361){$class='label label-danger';}
                            elseif($product['dateDif']<361 && $product['dateDif']>=181){$class='label label-warning';}
                            elseif($product['dateDif']<181 && $product['dateDif']>=91){$class='label label-info';}
                            elseif($product['dateDif']<91 && $product['dateDif']>=0){$class='';}
                        ?>
                        <span class="<?php echo $class;?>"><?php echo $product['dateDif'];?></span>
                    </td><?php }?>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="pag-block hidden-xs"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-left hidden-sm hidden-lg hidden-md"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="proDescription" tabindex="-1" role="dialog" aria-labelledby="proDescriptionLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="cpbModalLabel">Описание товара</h4>
            </div>
            <div class="modal-body">
                <p id="proddesctext"></p>
            </div>
          </div>
        </div>
    </div>                     
  <script type="text/javascript"><!--
      
      $('#btn-clear-filters').on('click', function(){
        var url = 'index.php?route=production/catalog&token=<?php echo $token; ?>';
        location = url;
      });
      
      $('#panel-heading-f').on('click', function(){
        $('#panel-body-f').toggle('slow', function(){});
      });
$('#button-filter').on('click', function() {
	var url = 'index.php?route=production/catalog&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();
        var filter_name = filter_name.replace(/\s+/g," ");
        $('input[name=\'filter_name\']').val($.trim(filter_name));

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
      
	var filter_donor = $('input[name=\'filter_donor\']').val();

	if (filter_donor) {
		url += '&filter_donor=' + encodeURIComponent(filter_donor);
	}
        
	var filter_catn = $('input[name=\'filter_catn\']').val();

	if (filter_catn) {
		url += '&filter_catn=' + encodeURIComponent(filter_catn);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_price = $('input[name=\'filter_price\']').val();
        var filter_price = filter_price.replace(/\s+/g," ");
        $('input[name=\'filter_price\']').val($.trim(filter_price));

	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}
        
        var filter_vin = $('input[name=\'filter_vin\']').val();
        var filter_vin = filter_vin.replace(/\s+/g," ");
        $('input[name=\'filter_vin\']').val($.trim(filter_vin));
	if (filter_vin) {
		url += '&filter_vin=' + encodeURIComponent(filter_vin);
	}
	
        var filter_wocat = $('input[name=\'filter_wocat\']').val();
        
        if (filter_wocat) {
		url += '&filter_wocat=' + filter_wocat;
	}
        
        var filter_brand = $('input[name=\'filter_brand\']').val();
        var filter_brand = filter_brand.replace(/\s+/g," ");
        $('input[name=\'filter_brand\']').val($.trim(filter_brand));
	if (filter_brand) {
		url += '&filter_brand=' + encodeURIComponent(filter_brand);
	}
        
        var filter_drom = $('select[name=\'filter_drom\']').val();

	if (filter_drom) {
		url += '&filter_drom=' + encodeURIComponent(filter_drom);
	}

	var filter_category = $('select[name=\'filter_category\']').val();

  if (filter_category != '*') {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}

	var filter_quantity = $('input[name=\'filter_quantity\']').val();

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_image = $('select[name=\'filter_image\']').val();

  if (filter_image != '*') {
    url += '&filter_image=' + encodeURIComponent(filter_image);
  }

	location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=production/catalog/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=production/catalog/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});
//--></script></div>
<?php echo $footer; ?>
