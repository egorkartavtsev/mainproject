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
          </div
        </div>
        <div class="row">
          <?php foreach($products as $key => $product){ ?>
            <div class="product-layout product-grid col-xs-12 col-sm-6 col-lg-4">
                <div class="label-thumd center-block">
                    <?php if (isset($product['labels']) && count($product['labels'])){ 
                        for($i = 1; $i < 4; ++$i){ 
                            if(isset($product['labels'][$i]) && $product['labels'][$i]['value']!=='' && $product['labels'][$i]['value']!=='-'){ ?>
                            <div class="<?php echo $product['labels'][$i]['color']; ?>"><?php echo $product['labels'][$i]['value']; ?></div>
                        <?php }
                        }
                    }?>
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
                <div class="image"><a href="<?php echo $product['href'].'?'.time(); ?>"><img src="<?php echo $product['thumb'].'?'.time(); ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                  <div class="col-lg-12 nameProd">
                    <h4><a href="<?php echo $product['href'];?>" ><?php echo $product['name'];?></a></h4>
                  </div>
                <div>
                    <div class="caption text-center">
                        <p>Артикул: <?php echo $product['vin'];?></p>
                        <?php foreach($product['options'] as $option){ ?>
                          <?php if($option['value']!='-'){ ?><p><?php echo $option['text'];?>: <?php echo htmlspecialchars_decode($option['value']);?></p><?php }?>
                        <?php }?>
                    </div>
                    <div class="col-lg-12 text-center priceProd">
                        <p>
                            <?php if ($product['price'] !== '0') { ?>
                                <b>Цена: <?php echo $product['price'];?> &#8381;</b>
                            <?php } else { ?>
                                &nbsp;
                            <?php }?>
                        </p>
                    </div>
                </div>
                <?php echo $modal_window; ?>  
                    <?php if ($product['status'] == '2') { ?>
                      <button class="btn btn-lg btn-danger center-block checkPr" btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pname ="<?php echo $product['name'];?>" pvin="<?php echo $product['vin'];?>">Уточнить наличие</button>
                    <?php } else { ?>                   
                        <?php if ($product['price'] == 0.00 || $product['quantity'] == 0)  { ?>
                            <?php if ($product['price'] == 0.00) { ?>
                                <button class="btn btn-lg btn-danger center-block checkPr" btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pname ="<?php echo $product['name'];?>" pvin="<?php echo $product['vin'];?>">Узнать стоимость</button>
                            <?php } else { ?>
                                <button class="btn btn-lg btn-danger center-block checkPr" btn_type = "reqPrice" type="button" data-toggle="modal" data-target="#myModal" pname ="<?php echo $product['name'];?>" pvin="<?php echo $product['vin'];?>">Заказать товар</button> 
                            <?php } ?>
                        <?php } else { ?>  
                            <button class="btn btn-lg btn-danger center-block checkPr" type="button" onclick="cart.add('<?php echo $key; ?>', '1');"><i class="fa fa-shopping-cart"></i> В КОРЗИНУ</button>
                        <?php } ?>
                    <?php } ?> 
              </div>
            </div>
          <?php }?>
          <div class="clearfix"></div>
          <div class="col-lg-12 text-center">
              <?php echo $pagination; ?>
          </div>
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
                    <a  target="_blank" href="https://vk.com/mgnautorazbor"><img src="<?php echo $lvk;?>" width="50"></a>
                    <a  target="_blank" href="https://www.instagram.com/autorazbor174"><img src="<?php echo $linst;?>" width="50"></a>
                    <a  target="_blank" href="https://www.youtube.com/channel/UCNgBC4t07efN7qMYUls0fcw"><img src="<?php echo $lyt;?>" width="50"></a>
                    <a  target="_blank" href="https://baza.drom.ru/user/AUTORAZBOR174RU"><img src="<?php echo $ldrom;?>" width="50"></a>
                    <a  target="_blank" href="https://www.avito.ru/autorazbor174"><img src="<?php echo $lavito;?>" width="50"></a>
                </div>
            </h4>
        </div>
    <?php }?>
</div>
