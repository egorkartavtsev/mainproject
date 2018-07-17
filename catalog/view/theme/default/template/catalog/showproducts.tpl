<div id="filterResult">
    <?php if (count($products)){ ?>
        <div class="row">
          <div class="col-md-2 col-sm-6 hidden-xs">
            <div class="btn-group btn-group-sm">
              <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="Отобразить списком"><i class="fa fa-th-list"></i></button>
              <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="Отобразить сеткой"><i class="fa fa-th"></i></button>
            </div>
          </div>
          <div class="col-xs-6">
            <div class="form-group input-group input-group-sm">
              <label class="input-group-addon" for="input-sort">Сортировка:</label>
              <select id="input-sort" class="form-control" onchange="location = this.value;" data-toggle="tooltip" title="Внимание! При изменении порядка сортировки значения фильтра очистятся.">
                  <option disabled selected>Выберите порядок сортировки</option>
                  <option value="<?php echo $link?>&sort=price&order=ASC">Цена - сначала дешёвые</option>
                  <option value="<?php echo $link?>&sort=price&order=DESC">Цена - сначала дорогие</option>
                  <option value="<?php echo $link?>&sort=type&order=ASC">Тип - сначала Б/У</option>
                  <option value="<?php echo $link?>&sort=type&order=DESC">Тип - сначала Новые</option>
                  <option value="<?php echo $link?>&sort=date_added&order=ASC">Дата - сначала старые</option>
                  <option value="<?php echo $link?>">Дата - сначала новые</option>
              </select>
            </div>
          </div>

        </div>
        <div class="row">
          <?php foreach($products as $key => $product){ ?>
            <div class="product-layout product-list col-xs-12">
                <div class="label-thumd">
                    <?php if ($product['type']){ ?>
                        <?php if(strripos($product['type'], 'ВЫЙ') || strripos($product['type'], 'вый')){ ?>
                            <div class="eanb">Новый</div>
                        <?php } else { ?>
                            <div class="eanr">Б/У</div>
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
                    <?php if ($product['price'] == 0.00 || $product['quantity'] == 0)  { ?>
                        <?php if ($product['price'] == 0.00) { ?>
                            <button btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pname ="<?php echo $product['name'];?>" pvin="<?php echo $product['vin'];?>"><span class="hidden-xs hidden-sm hidden-md">Узнать стоимость</span></button>
                        <?php } else { ?>
                            <button btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pname ="<?php echo $product['name'];?>" pvin="<?php echo $product['vin'];?>"><span class="hidden-xs hidden-sm hidden-md">Заказать товар</span></button> 
                        <?php } ?>
                    <?php } else { ?>  
                        <button type="button" onclick="cart.add('<?php echo $key; ?>', '1');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"> В КОРЗИНУ</span></button>
                    <?php } ?>
                    <button type="button" data-toggle="tooltip" title="Добавить в избранное" onclick="wishlist.add('<?php echo $key; ?>');"><i class="fa fa-heart"></i></button>
                    <button type="button" data-toggle="tooltip" title="Добавить в список сравнения" onclick="compare.add('<?php echo $key; ?>');"><i class="fa fa-exchange"></i></button>
                  </div>
                <?php echo $modal_window; ?>  
                </div>
              </div>
            </div>
          <?php }?>
          <div class="clearfix"></div>
          <div class="col-lg-12">
              <?php echo $pagination; ?>
          </div>
        </div>     
    <?php } else { ?>
        <?php echo '<div class="col-lg-12 text-center"><img src="sad.png" width="150"/><br><h4>К сожалению, ничего не найдено. Позвоните нам, чтобы уточнить наличие детали по телефону.<br><b>+ ‎7 (912) 475 08 70</b></h4></div>';?>
    <?php }?>
</div>
