<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
    
  <div class="row">
    <div id="content" class="col-sm-12"><?php echo $content_top; ?>
      <div id="success_box"></div>
      <div class="row">
        <div class="col-sm-8">
          <?php if (isset($images) || isset($thumbs)) { ?>
            <?php if ($thumb || $images) { ?>
            <div class="sp-wrap">   
                <?php if ($images) { ?>
                <?php foreach ($images as $image) { ?>
                <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
                <?php } ?>
                <?php } ?>
            </div>
            <?php } ?>
          <?php } ?>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-description"><?php echo htmlspecialchars_decode($description); ?></div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="btn-group">
            <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i></button>
            <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>');"><i class="fa fa-exchange"></i></button>
            <button type="button" data-toggle="tooltip" class="btn btn-default" title="Скопировать ссылку" id="send_prod"><i class="fa fa-copy"></i></button>
          </div>
          <h1><?php echo $heading_title; ?></h1>
          <ul class="list-unstyled">
            <li><b>Внутренний номер:</b> <?php echo $vin; ?></li>
            <li><b>Состояние:</b> <?php echo $cond!==""?$cond:"---"; ?></li>
            <?php foreach($parameters as $field => $param) { ?>
                <li><b><?php echo $param; ?>:</b> <?php echo $$field; ?></li>
            <?php }?>
            <?php if((isset($adress)) && ($adress!='')) { ?>
                <li>Склад: <a href="index.php?route=information/information&information_id=5" target="blank"><?php echo $adress; ?></a></li>
            <?php } ?>
            <li><b><?php echo $text_stock; ?></b> <?php echo $stock; ?></li>
          </ul>
          <?php if ($price) { ?>
          <ul class="list-unstyled">
            <li>
              <h2>Цена: <?php echo $price; ?></h2>
            </li>
          </ul>
          <?php } ?>
          
          <hr>
          <!-------------------------------------------->
            <div id="product">
              <div class="form-group">
                <label class="control-label" for="input-quantity"><?php echo $entry_qty; ?></label>
                <input type="text" name="quantity" value="<?php echo $minimum; ?>" size="2" id="input-quantity" class="form-control" />
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                <br />
                <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg btn-block"><?php echo $button_cart; ?></button>
              </div>
              <?php if ($minimum > 1) { ?>
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
              <?php } ?>
            </div>
          
          <!------------------------------------------------------------------------------------------------->
          
        </div>
      </div>
      <?php if ($tags) { ?>
      <p><?php echo $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script src="catalog/view/javascript/clipboard.min.js" type="text/javascript"></script>
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
    
<?php echo $footer; ?>
