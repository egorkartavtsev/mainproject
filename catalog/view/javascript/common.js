function getURLVar(key) {
	var value = [];

	var query = document.location.search.split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

function validation(field_type, value, req){
    var allow = false;
    var reg = '';
    switch(field_type){
        case 'char':
            reg = /^([a-zа-яё]+)$/i;
        break;
        case 'varchar':
            reg = /^([a-zа-яё\d\s\\\.\,\-\,\+\/]+)$/i;
        break;
        case 'numeric':
            reg = /^\d+$/;
        break;
        case 'email':
            reg = /^[a-z0-9_-]+@[a-z0-9-]+\.[a-z]{2,6}$/i;
        break;
    }
    
    if(reg.test(value)) {
        allow = true;
    } 
    
    return allow;
}
function copyLinkToSend(btn, text){
    btn.parent().before('<div id="sendLink"></div>');
    var sLink = btn.parent().parent().find('#sendLink');
    var target = document.createElement("textarea");
    sLink.append(target);
    target.class = 'form-control';
    target.style.width = '100%';
    target.textContent = text;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    var secceed = false;
    secceed = document.execCommand("copy");
    if(!secceed){
        alert('Не удалось скопировать ссылку! Возможно, Ваш браузер не поддерживает этот функционал!');
    } else {
        console.log(secceed);
    }
    sLink.remove();
}

$(document).ready(function() {
    
    $(document).on('input', '[name=captcha]', function(){
        var inp = $(this);
        if(inp.val().length=='6'){
            $.ajax({
                url: "index.php?route="+getURLVar('route')+"/validate",
                method: "POST",
                data:
                {
                    captcha: inp.val()
                },
                success:function(data){
                    if(data){
                        alert('Проверочный код введён верно!')
                        inp.parent().parent().remove();
                        $(document).find("[btn_type=nextstep]").removeAttr('disabled');
                    } else {
                        $(document).find("[btn_type=nextstep]").attr('disabled', 'disabled');
                        alert('Введён неверный проверочный код!')
                    }
                }      
            });
        } else {
            $(document).find("[btn_type=nextstep]").attr('disabled', 'disabled');
        }
    });
    
    //nextStep button in checkout
    
        $(document).on('click', "[btn_type=laststep]", function(){
            $.ajax({
                url: "index.php?route="+getURLVar('route')+"/applyStep",
                method: "POST",
                data:
                {
                    step: 'last'
                },
                success:function(data){
                    location = 'index.php?route=checkout/success';
                }
            });
        })
        
        $(document).on('click', "[btn_type=nextstep]", function(){
            if(confirm('Вы указали верные данные?')){
                $(this).text('загрузка...');
                var btn = $(this);
                var inval;
                var step = $(this).parent().attr('step');
                var nextstep = parseInt(step)+1;
                var form = [];
                var allow = [];
                
                btn.parent().find('input').each(function(){
                    if($(this).val()===''){
                        if(parseInt($(this).attr('target_req'))){
                            allow.push(false);
                            inval = $(this).parent();
                            $(this).text('Продолжить');
                        } else {
                            allow.push(true);
                            $(this).parent().removeClass('invalid');
                            $(this).text('Продолжить');
                        }
                    } else{
                        allow.push(true);
                        $(this).parent().removeClass('invalid');
                        form.push($(this).attr('name') + ':' + $(this).val());
                    }
                });
                if ($.inArray(false, allow)<0){
                    $.ajax({
                        url: "index.php?route="+getURLVar('route')+"/applyStep",
                        method: "POST",
                        data:
                        {
                            step: step,
                            form: form
                        },
                        success:function(data){
                            $(document).find("[step="+step+"]").html(data);
                            $(document).find("[step="+nextstep+"]").removeClass('hidden');
                        }
                    });
                } else {
                    alert('Вы ввели некорректные или неполные данные.');
                    inval.addClass('invalid');
                }
                
            } else {
                $(this).text('Продолжить');
                false;
            }
        })
    
    
    
        //Send data to the modal window for mail
        $(document).on('click',"[btn_type=reqPrice]", function(){
            var pid = $(this).attr('pid');
            $('#pid').val(pid);
            var pcause = $(this).attr('pcause');
            $('#pcause').val(pcause);
        });
        
        //smartSearch
        $(document).on('input', "[type=libSearch]", function(){
            var input = $(this);
            $.ajax({
                url: "index.php?route=catalog/catalog/search",
                statbox: "igdhje83565",
                method: "POST",
                data:
                {
                    request: input.val(),
                    lib_id: input.attr('lib_id')
                },
                success:function(data){
                    $("#sSearchResult").html(data);
                }      
            });
        })
        
        //libr childs show
        $(document).on('change', "[select_type=library]", function(){
            var select = $(this);
            if(select.val()!=='Все товары'){
                $.ajax({
                    url: "index.php?route=catalog/catalog/showLibrChild",
                    statbox: "igdhje83565",
                    method: "POST",
                    data:
                    {
                        parent: select.val()
                    },
                    success:function(data){
                        select.parent().parent().find("#"+select.attr('child')).html(data);
                    }      
                });                
            }
        })
        //filter fields change
        $(document).on('input', "input[id*='filter']", function(){
            $(this).parent().parent().find('button').removeAttr('disabled');
        })
        $(document).on('change', "select[id*='filter']", function(){
            $(this).parent().parent().find('button').removeAttr('disabled');
        })
        
        //apply filter
        $(document).on('click', '[btn_type=filter]', function(){
            var filter = '';
            var cond = '';
            if(getURLVar('type') != ""){
                cond = '&type='+getURLVar('type')
            } else {
                cond = '&libr='+getURLVar('libr')
            }
            if(getURLVar('order') != ""){
                cond+= '&sort='+getURLVar('sort')+'&order='+getURLVar('order')
            }
            $("#filterResult").html('<div class="col-lg-12 text-center"><img src="wait.gif" width="100"/><br><h4>Подбираем товары по фильтру. Спасибо за ожидание</h4></div>');
            $(this).parent().parent().find("[id*='filter']").each(function(){
                filter = filter + $(this).attr('id')+': '+$(this).val()+'; ';
            })
            $.ajax({
                url: "index.php?route=catalog/catalog/applyFilter"+cond,
                statbox: "igdhje83565",
                method: "POST",
                data:
                {
                    filter: filter
                },
                success:function(data){
                    $("#filterResult").html(data);
                    $('[btn_type=filter]').attr('disabled', 'disabled');
                }      
            });
        })
        
        // Searchbox
        $("input[name*='search']").on('input', function(){
            if($(this).val()!==''){
                $.ajax({
                    url: "index.php?route=product/search/getVariants",
                    statbox: "igdhje83565",
                    method: "POST",
                    data:
                    {
                        request: $.trim($(this).val())
                    },
                    success:function(data){
                        $("#searchResult").html(data);
                    }      
                });
                $("#search").addClass('open');
            } else {
                $("#search").removeClass('open');
            }
        });
        
    
	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	// Currency
	$('#form-currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#form-currency input[name=\'code\']').val($(this).attr('name'));

		$('#form-currency').submit();
	});

	// Language
	$('#form-language .language-select').on('click', function(e) {
		e.preventDefault();

		$('#form-language input[name=\'code\']').val($(this).attr('name'));

		$('#form-language').submit();
	});

	/* Search */
        $('#search input[name=\'search\']').parent().find('button').on('click', function() {
                var valuetrim = $('header #search input[name=\'search\']').val();
                var valuetrim = valuetrim.replace(/\s+/g," ");
                $('header #search input[name=\'search\']').val($.trim(valuetrim))
        });
	$('#search input[name=\'search\']').parent().find('button').on('click', function() {
		var url = $('base').attr('href') + 'index.php?route=product/search';

		var value = $('header #search input[name=\'search\']').val();
                
		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('#search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header #search input[name=\'search\']').parent().find('button').trigger('click');
		}
	});

	// Menu
	$('#menu .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 10) + 'px');
		}
	});

	// Product List
	$('#list-view').click(function() {
		$('#content .product-grid > .clearfix').remove();

		$('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
		$('#grid-view').removeClass('active');
		$('#list-view').addClass('active');

		localStorage.setItem('display', 'list');
	});

	// Product Grid
	$('#grid-view').click(function() {
		// What a shame bootstrap does not take into account dynamically loaded columns
		var cols = $('#column-right, #column-left').length;

		if (cols == 2) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
		} else if (cols == 1) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
		} else {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
		}

		$('#list-view').removeClass('active');
		$('#grid-view').addClass('active');

		localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
		$('#list-view').addClass('active');
	} else {
		$('#grid-view').trigger('click');
		$('#grid-view').addClass('active');
	}

	// Checkout
	$(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
		if (e.keyCode == 13) {
			$('#collapse-checkout-option #button-login').trigger('click');
		}
	});

	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body',trigger: 'hover'});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});
        
        $(document).on('click', '[btn_type=copyToSend]', function(){
        copyLinkToSend($(this), $(this).attr('data-text'));
        });
        //Отправка формы из модального окна к функцие отправки писем. 
         $(document).on('submit', '[id=myModal]', function() { //событие
            var form_data = $(this).find("form").serialize(); //собераем все данные из формы
            $.ajax({
                url: "index.php?route=tool/sendmail/send",
                statbox: "igdhje83565",
                method: "POST",
                data: form_data,
                success: function() {
                        alert('Ваш запрос отправлен');
                }
            });
        });
});

// Cart add remove functions
var cart = {
    
//        'addComp': function($complect_id){
//            $.ajax({
//                url:"index.php?route=checkout/cart/addComplect",
//                statbox:"status",
//                method:"POST",
//                data:
//                {
//                    compl: $complect_id
//                },
//                success:function(data){
//                    //$('#complect').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
//                    document.getElementById('complect').innerHTML = 'Комплект полностью добавлен в корзину';
//                },
//                error:function(){
//                    document.getElementById('complect').innerHTML = 'Ошибка какая-то';
//                }
//            });
//        },
    
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				$('.alert, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					// Need to set timeout otherwise it wont update the total
					setTimeout(function () {
						$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
					}, 100);

					$('html, body').animate({ scrollTop: 0 }, 'slow');

					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);
				
				var now_location = String(document.location.pathname);

				if ((now_location == '/cart/') || (now_location == '/checkout/') || (getURLVar('route') == 'checkout/cart') || (getURLVar('route') == 'checkout/checkout')) {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				$('#wishlist-total span').html(json['total']);
				$('#wishlist-total').attr('title', json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#compare-total').html(json['total']);

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function() {
				this.request();
			});

			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);
