<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
    <?php if (isset($suc_text)) { ?>
        <div class="col-lg-12 alert alert-success">
            <?php echo $suc_text;?>
        </div>
    <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div id="success_box"></div>
      <div class="row">
        <?php if ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-8'; ?>
        <?php } else { ?>
            <?php $class = 'col-sm-8'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
            <div class="label-thumd">
            <?php if ($ean){ ?>
                <?php if ($ean=='Б/У') { ?> 
                    <div class="eanr">
                        Б/У
                    </div>
                <?php }?>
                <?php if ($ean=='Новый') { ?> 
                    <div class="eanb">
                        Новый
                    </div>
                <?php }?>
            <?php }?>
            <?php if ($comp){ ?>
                <?php if ($whole == 1){ ?>
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
                                <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                 <?php } ?>
            <?php } ?>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
                <?php if ($attribute_groups) { ?>
                    <li><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
                <?php } ?>
                <?php if ($review_status) { ?>
                    <li><a href="#tab-review" data-toggle="tab"><?php echo $tab_review; ?></a></li>
                <?php } ?>
            </ul>
          <div class="tab-content">
            <?php if(isset($complect) && $c_id!='') { ?>
                <div class="alert alert-danger" id="complect">
                    <h3><?php if(!isset($whole) || !$whole) { echo $entry_compl;} else { echo "Деталь продаётся только в комплекте: ";} ?></h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Название:</th>
                                <?php if(!$whole && $price != 0.00) { ?>
                                    <?php  if (!in_array(0, array_column($complect, 'price'))) { ?>
                                        <th>Цена:</th>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        </thead>
                        <?php $summ = 0; foreach($complect as $acc) { ?>
                            <tr>
                                <td><a href="index.php?route=product/product&product_id=<?php echo $acc['product_id'];?>" target="blank"><?php echo $acc['name'];?></a></td>
                                <?php  if (!in_array(0, array_column($complect, 'price'))) { ?>
                                    <?php if ( $price != 0.00 ) { ?>
                                        <?php $summ+= $acc['price'];?>
                                        <?php if(!$whole) { ?>
                                            <td><?php echo $acc['price'];?> руб.</td>
                                            <td><button class="btn btn-primary" onclick="cart.add('<?php echo $acc['product_id']; ?>', '<?php echo $acc['minimum']; ?>');"><i class="fa fa-cart-plus"></i></button></td>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>    
                            </tr>
                        <?php }?>
                        <?php  if (!in_array(0, array_column($complect, 'price'))) { ?>
                                <?php if ( $price != 0.00 ) { ?>
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
                                    <td colspan='2' style="text-align: center;"><button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal"> Узнать стоимость комплекта</button></td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Вы можете заполнить форму ниже и наши менеджеры свяжутся с Вами.</h4>
                                      </div>
                                        <form method='POST' action="">
                                          <div class="modal-body">
                                            <div class="form-group">
                                              <label >Ваше имя: </label>
                                              <input type="text" class="form-control" name='name' placeholder="Имя...">
                                            </div>
                                            <div class="form-group">
                                              <label >Ваш e-mail: </label>
                                              <input type="email" class="form-control" name='email' placeholder="E-mail...">
                                            </div>
                                            <div class="form-group">
                                              <label >Телефон: </label>
                                              <input type="text" class="form-control" name='phone' placeholder="Телефон...">
                                            </div>
                                             <label>Комментарий: </label>
                                              <textarea class="form-control" name='comment' placeholder="Комментарий к заявке..."></textarea>
                                            </div>
                                              <input type="hidden" name="suc" value="1" />
                                          </div>   
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                            <button type="submit" class="btn btn-danger">Отправить</button>
                                          </div>
                                        </form>

                                    </div>
                                  </div>
                                </div>
                                <script type='text/javascript'>
                                      $('#myModal').on('shown.bs.modal', function () {
                                        $('#myInput').focus()
                                      })
                                </script>
                        <?php }?>
                    </table>
                </div>
            <?php }?>
            <div class="tab-pane active" id="tab-description"><?php echo $description; ?></div>
            <?php if ($attribute_groups) { ?>
            <div class="tab-pane" id="tab-specification">
              <table class="table table-bordered">
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                <thead>
                  <tr>
                    <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                  <tr>
                    <td><?php echo $attribute['name']; ?></td>
                    <td><?php echo $attribute['text']; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
                <?php } ?>
              </table>
            </div>
            <?php } ?>
            <?php if ($review_status) { ?>
            <div class="tab-pane" id="tab-review">
              <form class="form-horizontal" id="form-review">
                <div id="review"></div>
                <h2><?php echo $text_write; ?></h2>
                <?php if ($review_guest) { ?>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                    <input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
                    <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                    <div class="help-block"><?php echo $text_note; ?></div>
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label"><?php echo $entry_rating; ?></label>
                    &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                    <input type="radio" name="rating" value="1" />
                    &nbsp;
                    <input type="radio" name="rating" value="2" />
                    &nbsp;
                    <input type="radio" name="rating" value="3" />
                    &nbsp;
                    <input type="radio" name="rating" value="4" />
                    &nbsp;
                    <input type="radio" name="rating" value="5" />
                    &nbsp;<?php echo $entry_good; ?></div>
                </div>
                <?php echo $captcha; ?>
                <div class="buttons clearfix">
                  <div class="pull-right">
                    <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
                  </div>
                </div>
                <?php } else { ?>
                <?php echo $text_login; ?>
                <?php } ?>
              </form>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-4'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-4'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <div class="btn-group">
            <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i></button>
            <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>');"><i class="fa fa-exchange"></i></button>
            <button type="button" data-toggle="tooltip" class="btn btn-default" title="Скопировать ссылку" id="send_prod"><i class="fa fa-copy"></i></button>
          </div>
          <h1><?php echo $heading_title; ?></h1>
          <ul class="list-unstyled">
            <?php if ($manufacturer) { ?>
                <li>Марка: <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></li>
            <?php } ?>
            <?php if (isset($models)) { ?>
                <li>Модель: <a href="<?php echo $models; ?>"><?php echo $model; ?></a></li>
            <?php } else { ?>
                <li>Модель: <?php echo $model; ?></li>
            <?php }?>
            <?php if (isset($model_rows)) { ?>
                <li>Модельный ряд: <a href="<?php echo $model_rows; ?>"><?php echo $model_row; ?></a></li>
            <?php } else { ?>
                <li>Модельный ряд: <?php echo $model_row; ?></li>
            <?php }?>
            <?php if ($ean && $ean=='Б/У') { ?>
                <li>Внутренний номер: <?php echo $vin; ?></li>
                <?php if ($cat_numb) { ?>
                    <li>Каталожный номер: <?php echo $cat_numb; ?></li>
                <?php }?>
            <?php } ?>
            <?php if ($compability) { ?>
                <li>Применимость: <?php echo $compability; ?></li>
            <?php }?>
            <?php if ($note) { ?>
                <li>Примечание: <?php echo $note; ?></li>
            <?php }?>
            <?php if ($dop) { ?>
                <li>Дополнительная информация: <?php echo $dop; ?></li>
            <?php }?>
            <?php if ($ean) { ?>
                <li>Тип: <?php echo $ean; ?></li>
            <?php }?>
            <?php if ($condition) { ?>
                <li>Состояние: <?php echo $condition; ?></li>
            <?php }?>
            <?php if((isset($adress)) && ($adress!='')) { ?>
                <li>Склад: <a href="index.php?route=information/information&information_id=5" target="blank"><?php echo $adress; ?></a></li>
            <?php } ?>
            <?php if ($reward) { ?>
            <li><?php echo $text_reward; ?> <?php echo $reward; ?></li>
            <?php } ?>
            <li><?php echo $text_stock; ?> <?php echo $stock; ?></li>
          </ul>
          <!-------------------------------------------->
          <?php if ($price == 0.00 || $stock == 0)  { ?>
                <?php if ($price == 0.00) { ?>
                    <hr>
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                    Узнать стоимость товара
                    </button>
                <?php } else { ?>
                    <h2><?php echo $price; ?></h2>
                    <hr>
                    <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#myModal">
                    Товара нет в наличии - Заказать товар
                    </button>
                <?php } ?>
                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Вы можете заполнить форму ниже и наши менеджеры свяжутся с Вами.</h4>
                      </div>
                        <form method='POST' action="">
                          <div class="modal-body">
                            <div class="form-group">
                              <label >Ваше имя: </label>
                              <input type="text" class="form-control" name='name' placeholder="Имя...">
                            </div>
                            <div class="form-group">
                              <label >Ваш e-mail: </label>
                              <input type="email" class="form-control" name='email' placeholder="E-mail...">
                            </div>
                            <div class="form-group">
                              <label >Телефон: </label>
                              <input type="text" class="form-control" name='phone' placeholder="Телефон...">
                            </div>
                             <label>Комментарий: </label>
                              <textarea class="form-control" name='comment' placeholder="Комментарий к заявке..."></textarea>
                            </div>
                              <input type="hidden" name="suc" value="1" />
                          </div>   
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-danger">Отправить</button>
                          </div>
                        </form>

                    </div>
                  </div>
                </div>
                <script type='text/javascript'>
                      $('#myModal').on('shown.bs.modal', function () {
                        $('#myInput').focus()
                      })
                </script>
          <?php } else { ?>
          <?php if(!isset($whole) || !$whole) { ?>
            <?php if ($price) { ?>
            <ul class="list-unstyled">
                <h2><?php echo $price; ?></h2>
            </ul>
            <?php } ?>

            <hr>
                <div id="product">
                  <!--
                  СТАРАЯ ВЕРСИЯ
-->  
                  <?php if (isset($options)) { ?>
                  <hr>
                  <h3><?php echo $text_option; ?></h3>
                  <?php foreach ($options as $option) { ?>
                  <?php if ($option['type'] == 'select') { ?>
                  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                    <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
                      <option value=""><?php echo $text_select; ?></option>
                      <?php foreach ($option['product_option_value'] as $option_value) { ?>
                      <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                      <?php if ($option_value['price']) { ?>
                      (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                      <?php } ?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>
                  <?php } ?>
                  <?php if ($option['type'] == 'radio') { ?>
                  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                    <label class="control-label"><?php echo $option['name']; ?></label>
                    <div id="input-option<?php echo $option['product_option_id']; ?>">
                      <?php foreach ($option['product_option_value'] as $option_value) { ?>
                      <div class="radio">
                        <label>
                          <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                          <?php if ($option_value['image']) { ?>
                          <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
                          <?php } ?>                    
                          <?php echo $option_value['name']; ?>
                          <?php if ($option_value['price']) { ?>
                          (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                          <?php } ?>
                        </label>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>
                  <?php if ($option['type'] == 'checkbox') { ?>
                  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                    <label class="control-label"><?php echo $option['name']; ?></label>
                    <div id="input-option<?php echo $option['product_option_id']; ?>">
                      <?php foreach ($option['product_option_value'] as $option_value) { ?>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                          <?php if ($option_value['image']) { ?>
                          <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
                          <?php } ?>
                          <?php echo $option_value['name']; ?>
                          <?php if ($option_value['price']) { ?>
                          (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                          <?php } ?>
                        </label>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>
                  <?php if ($option['type'] == 'text') { ?>
                  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                  </div>
                  <?php } ?>
                  <?php if ($option['type'] == 'textarea') { ?>
                  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                    <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
                  </div>
                  <?php } ?>
                  <?php if ($option['type'] == 'file') { ?>
                  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                    <label class="control-label"><?php echo $option['name']; ?></label>
                    <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                    <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
                  </div>
                  <?php } ?>
                  <?php if ($option['type'] == 'date') { ?>
                  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                    <div class="input-group date">
                      <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                      <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                      </span></div>
                  </div>
                  <?php } ?>
                  <?php if ($option['type'] == 'datetime') { ?>
                  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                    <div class="input-group datetime">
                      <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                      <span class="input-group-btn">
                      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                      </span></div>
                  </div>
                  <?php } ?>
                  <?php if ($option['type'] == 'time') { ?>
                  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                    <div class="input-group time">
                      <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                      <span class="input-group-btn">
                      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                      </span></div>
                  </div>
                  <?php } ?>
                  <?php } ?>
                  <?php } ?>
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
                    <input type="text" name="quantity" value="<?php echo $minimum; ?>" size="2" id="input-quantity" class="form-control" />
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                    <br />
                    <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg btn-block"><?php echo $button_cart; ?></button>
                  </div>
                  <?php if ($minimum > 1) { ?>
                  <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
                  <?php } ?>
                </div>
                <?php } ?>
            <?php } ?>
          
          <!------------------------------------------------------------------------------------------------->
          
        </div>
      </div>
      <?php if ($products) { ?>
      <h3><?php echo $text_related; ?></h3>
      <div class="row">
        <?php $i = 0; ?>
        <?php foreach ($products as $product) { ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-xs-8 col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-xs-6 col-md-4'; ?>
        <?php } else { ?>
        <?php $class = 'col-xs-6 col-sm-3'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          
          <div class="product-thumb transition">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div class="caption">
              <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
              <p><?php echo $product['description']; ?></p>
              <?php if ($product['rating']) { ?>
              <div class="rating">
                <?php for ($j = 1; $j <= 5; $j++) { ?>
                <?php if ($product['rating'] < $j) { ?>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <?php } else { ?>
                <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
                <?php } ?>
                <?php } ?>
              </div>
              <?php } ?>
              <?php if ($product['price']) { ?>
              <p class="price">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                <?php } ?>
                <?php if ($product['tax']) { ?>
                <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                <?php } ?>
              </p>
              <?php } ?>
            </div>
            
                <div class="button-group">
                  <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span> <i class="fa fa-shopping-cart"></i></button>
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
                </div>
            
          </div>
        </div>
        <?php if (($column_left && $column_right) && (($i+1) % 2 == 0)) { ?>
        <div class="clearfix visible-md visible-sm"></div>
        <?php } elseif (($column_left || $column_right) && (($i+1) % 3 == 0)) { ?>
        <div class="clearfix visible-md"></div>
        <?php } elseif (($i+1) % 4 == 0) { ?>
        <div class="clearfix visible-md"></div>
        <?php } ?>
        <?php $i++; ?>
        <?php } ?>
      </div>
      <?php } ?>
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
<script src="view/javascript/clipboard.min.js" type="text/javascript"></script>


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
