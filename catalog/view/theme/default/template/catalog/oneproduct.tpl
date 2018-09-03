<div class="container">
    <?php if (isset($suc_text)) { ?>
        <div class="col-lg-12 alert alert-success">
            <?php echo $suc_text;?>
        </div>
    <?php } ?>   
    <div class="row">
        <div class="col-sm-8">
            <div class="label-thumd">
            <?php if (count($labels)){ 
                for($i = 1; $i < 4; ++$i){ 
                    if(isset($labels[$i]) && $labels[$i]['value']!=='' && $labels[$i]['value']!=='-'){ ?>
                    <div class="<?php echo $labels[$i]['color']; ?>"><?php echo $labels[$i]['value']; ?></div>
                <?php }
                }
            }?>
            <?php if ($product['comp']){ ?>
                <?php if ($product['comp'] == 1){ ?>
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
            <?php if (isset($images) || isset($thumbs)) { ?>
                <?php if ($thumb || $images) { ?>
                    <div class="sp-wrap">
                        <?php if ($images) { ?>
                            <?php foreach ($images as $image) { ?>
                                <a href="<?php echo $image['popup'].'?'.time(); ?>" title="<?php echo $product['name']; ?>"> <img src="<?php echo $image['popup'].'?'.time(); ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                 <?php } ?>
            <?php } ?>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
            </ul>
            <div class="tab-content">
                <?php if(isset($complect) && $c_id!='') { ?>
                    <div class="alert alert-danger" id="complect">
                        <h3><?php if(!isset($whole) || !$whole) { echo $entry_compl;} else { echo "Деталь продаётся только в комплекте: ";} ?></h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Название:</th>
                                    <?php if(!$whole && $whole != 0.00) { ?>
                                        <?php  if (!in_array(0, array_column($complect, 'price'))) { ?>
                                            <th>Цена:</th>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <?php $summ = 0; foreach($complect as $acc) { ?>
                                <tr>
                                    <td><a href="index.php?route=catalog/product&product_id=<?php echo $acc['product_id'];?>" target="blank"><?php echo $acc['name'];?></a></td>
                                    <?php  if (!in_array(0, array_column($complect, 'price'))) { ?>
                                        <?php if ( $product['price'] != 0.00 ) { ?>
                                            <?php $summ+= $acc['price'];?>
                                            <?php if(!$whole) { ?>
                                                <td><?php echo $acc['price'];?> руб.</td>
                                                <?php if($acc['status'] != '2') { ?>
                                                    <td><button class="btn btn-primary" onclick="cart.add('<?php echo $acc['product_id']; ?>', '<?php echo $acc['minimum']; ?>');"><i class="fa fa-cart-plus"></i></button></td>
                                                <?php } else { ?>
                                                    <td>В резерве</td>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>    
                                </tr>
                            <?php }?>
                            <?php  if (!in_array(2, array_column($complect, 'status'))) { ?>
                                <?php  if (!in_array(0, array_column($complect, 'price'))) { ?>
                                        <?php if ( $product['price'] != 0.00 ) { ?>
                                            <?php if(!$whole) { ?>
                                                <tr>
                                                   <td style="text-align: right; font-size: 10pt;">Итого:</td>
                                                   <td colspan='2' style="text-align: left; font-size: 14pt;"><s><?php echo $summ; ?> руб.</s></td>
                                                </tr>
                                            <?php } ?>
                                        <tr>
                                            <td colspan='3' style="text-align: center; font-size: 14pt;">Цена комплекта <?php if(!$whole) { ?>со скидкой<?php } ?>: <span class="label label-danger"><?php echo $c_price; ?> руб.</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan='3' style="text-align: center;"><button class="btn btn-primary" onclick="cart.add('<?php echo $id_comp_ref; ?>', '<?php echo $acc['minimum']; ?>');"><i class="fa fa-cart-plus"></i> Купить весь комплект</button></td>
                                        </tr>
                                        <?php }?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan='2' style="text-align: center;"><button type="button" class="btn btn-primary btn-lg" btn_type="reqPrice" data-toggle="modal" data-target="#myModal" pcause="4">Узнать стоимость комплекта</button></td>
                                    </tr>                                       
                                <?php }?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan='2' style="text-align: center;"><button type="button" class="btn btn-primary btn-lg" btn_type="reqPrice" data-toggle="modal" data-target="#myModal" pcause="1">Уточнить наличие</button></td>                                
                                </tr> 
                            <?php }?>
                        </table>
                        
                    </div>
                <?php }?>
                <div class="tab-pane active" id="tab-description"><?php echo $description; ?></div>
                <?php if ($youtube!=='') { ?>
                    <h3>Видеопрезентация:</h3>
                    <iframe style="width: 100%; min-height: 300px;" src="https://www.youtube.com/embed/<?php echo $youtube;?>?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                <?php } ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="btn-group">
                <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
                <button type="button" data-toggle="tooltip" class="btn btn-default" title="Скопировать ссылку" id="send_prod"><i class="fa fa-copy"></i></button>
                <button type="button" class="btn btn-info" btn_type="reqPrice" data-toggle="modal" data-target="#myModal" pid="<?php echo $product['product_id'];?>" pcause="4">Задать вопрос о товаре</button>
            </div>
            <?php if(isset($images[0]['popup'])){ ?>
                <div style="margin-top:5%">
                   <script type="text/javascript">document.write(VK.Share.button({title:"<?php echo $product['name']; ?>",image:"<?php echo $images[0]['popup'].'?'.time(); ?>",},{type: "round", text: "Сохранить"}))</script>
                </div>
            <?php } ?>
            <h1><?php echo $product['name']; ?></h1>
            <ul class="list-unstyled">
                <li>Артикул: <?php echo $product['vin']; ?></li>  
                <?php foreach($options as $option){ ?>
                    <?php if (isset($option['viewed'])) { ?>
                        <?php if (($option['viewed'] == 1 ||  $option['viewed'] == 3) && $option['value'] !== '' && isset($option['text']) && $option['text'] !=='') { ?>
                                <?php if($option['value']!='-'){ ?><li><?php echo $option['text'];?>: <?php echo htmlspecialchars_decode($option['value']);?></li><?php }?>
                        <?php }?>
                    <?php }?>
                <?php }?>
                <li><?php echo $text_stock; ?> <?php echo $stock; ?></li>
            </ul>
          <!-------------------------------------------->
            <hr>
            <?php if ($status == '2') { ?>
                <button class="btn btn-primary btn-lg" btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pid="<?php echo $product['product_id'];?>" pcause="1">Уточнить наличие</button>
            <?php } elseif ($product['price'] == 0.00 && $stock !== 0) { ?>                                           
                <button class="btn btn-primary btn-lg" btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pid="<?php echo $product['product_id'];?>" pcause="2">Узнать стоимость</button>
            <?php } elseif ($product['price'] !== 0.00 && $stock == 0) { ?>
                <button class="btn btn-primary btn-lg" btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pid="<?php echo $product['product_id'];?>" pcause="3">Заказать товар</button> 
            <?php } else { ?>
                <?php if(!isset($whole) || !$whole) { ?>
                    <?php if ($product['price']) { ?>
                        <ul class="list-unstyled">
                            <h2><?php echo $product['price']; ?></h2>
                        </ul>
                    <?php } ?>
                <div id="product">
                    <?php if ($recurrings) { ?>
                        <hr>
                        <h3><?php echo $text_payment_recurring; ?></h3>
                        <div class="form-group required">
                            <select name="recurring_id" class="form-control">
                                <option value=""><?php echo $text_select; ?></option>
                                <?php foreach ($recurrings as $recurring) { ?>
                                    <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>
                                <?php } ?>
                            </select>
                            <div class="help-block" id="recurring-description"></div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label class="control-label" for="input-quantity"><?php echo $entry_qty; ?></label>
                        <input type="text" name="quantity" value="<?php echo $product['minimum']; ?>" size="2" id="input-quantity" class="form-control" />
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
                        <br />
                        <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg btn-block"><?php echo $button_cart; ?></button>
                    </div>
                    <?php if ($product['minimum'] > 1) { ?>
                        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
                    <?php } ?>
                </div>
                <?php }?>
            <?php }?>
            <div class="col-lg-12"<p>&nbsp;</p></div>
            <div id="vk_poll"></div>
            <div class="col-lg-12"<p>&nbsp;</p></div>
            <div id="vk_groups"></div>
        </div>
    <?php  if (isset($column_right)) { ?>
        <?php echo $column_right; ?>
    <?php } ?> 
    </div>
    <?php echo $modal_window; ?>
</div> 
<script src="catalog/view/javascript/clipboard.min.js" type="text/javascript"></script>
        <!------------------------------------Стоит вынести скрипты в отдельный файл------------------------------------------------------------->
<script type="text/javascript"><!--
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('#button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}

			if (json['success']) {
				$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('#cart > ul').load('index.php?route=common/cart/info ul li');
			}
		},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').val(json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
<script type="text/javascript"><!--
$('#review').delegate('.pagination a', 'click', function(e) {
    e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: $("#form-review").serialize(),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
			}
		}
	});
    grecaptcha.reset();
});


$(document).ready(function() {
	var hash = window.location.hash;
	if (hash) {
		var hashpart = hash.split('#');
		var  vals = hashpart[1].split('-');
		for (i=0; i<vals.length; i++) {
			$('div.options').find('select option[value="'+vals[i]+'"]').attr('selected', true).trigger('select');
			$('div.options').find('input[type="radio"][value="'+vals[i]+'"]').attr('checked', true).trigger('click');
		}
	}
})
//--></script>

<script type="text/javascript">
     $(window).load(function() {
		$('.sp-wrap').smoothproducts();
	});
</script>

<script type="text/javascript">
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
    function getChild($parent, $childs){
        ajax({
            url:"index.php?route=product/product/getChilds",
            statbox:"status",
            method:"POST",
            data:
            {
                parent: $parent 
            },
            success:function(data){
                    document.getElementById($childs).innerHTML = data;                    
            }
        })
    }
    
    $("#send_prod").on('click', function(){
        var copyLink = '<?php echo $sendLink; ?>';
        var clipboard = new Clipboard('#send_prod', {
            text: function() {
                return copyLink.replace("&amp;", "&");
            }
        });

        clipboard.on('success', function(e) {
            console.log(e);
        });

        clipboard.on('error', function(e) {
            console.log(e);
        });
        alert(copyLink.replace("&amp;", "&"));
    })
</script>
