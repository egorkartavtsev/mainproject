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
      
      <!--суда положу вывод моделей и годов-->
        
        <?php if (isset($models)) { ?>
        <h3>Уточнить поиск</h3>
        <?php if (count($models) <= 5) { ?>
        <div class="row">
          <div class="col-sm-3">
            <ul>
              <?php foreach ($models as $model) { ?>
              <li><a href="<?php echo $model['href']; ?>"><?php echo $model['brand_name']; ?></a></li>
              <?php } ?>
            </ul>
          </div>
        </div>
        <?php } else { ?>
        <div class="row">
          <?php foreach (array_chunk($models, ceil(count($models) / 4)) as $models) { ?>
          <div class="col-sm-3">
            <ul>
              <?php foreach ($models as $model) { ?>
              <li><a href="<?php echo $model['href']; ?>"><?php echo $model['brand_name']; ?></a></li>
              <?php } ?>
            </ul>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
        <?php } ?>  
      <!-----------------------------------------------------------------------------------------------> 
      <h1><?php echo $heading_title; ?></h1>
      <?php if (isset($description)) { ?>
      <div class="row">
        <?php if ($thumb) { ?>
        <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
        <?php } ?>
                
        <?php if (isset($description)) { ?>
        <div class="col-sm-10"><?php echo $description; ?></div>
        <?php } ?>
      </div>
      <hr>
      <?php } ?>
      <?php if (isset($products)) { ?>
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
        <div class="col-md-4 col-xs-6">
          <div class="form-group input-group input-group-sm">
            <label class="input-group-addon" for="input-sort">Категория: </label>
            <select id="categ" class="form-control">
                <option selected="selected" disabled="disabled"><?php echo $curr_cat; ?></option>
                <?php foreach ($category as $cat) { ?>
                  <option value="<?php echo $cat['href']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
              
            </select>
          </div>
        </div>
        <div class="col-md-3 col-xs-6">
          <div class="form-group input-group input-group-sm">
            <label class="input-group-addon" for="input-limit">Подкатегория: </label>
            <select id="podcat" class="form-control">
              <option selected="selected" disabled="disabled"><?php echo $curr_podcat; ?></option>
              <?php if (isset($podcat)) { ?>
                <?php foreach ($podcat as $pc) { ?>
                    <option value="<?php echo $pc['href']; ?>"><?php echo $pc['name']; ?></option>
                <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <?php if ($products) { ?>
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
                        <div class="whole">Комплект</div>
                    <?php }?>
                </div>  
                <div class="product-thumb">
                    <?php if ($product['ean']){ ?>
                        <?php if ($product['ean']=='Б/У') { ?> 
                            <div class="eanr">
                            <p><?php echo $product['ean']; ?><p>
                            </div>
                        <?php }?>
                        <?php if ($product['ean']=='Новый') { ?> 
                            <div class="eanb">
                            <p><?php echo $product['ean']; ?><p>
                            </div>
                        <?php }?>
                   <?php }?>
                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                <div>
                  <div class="caption">
                    <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                    <?php if ($product['ean'] && $product['ean']=='Б/У') { ?>
                        <p><b>Внутренний номер:</b> <?php echo $product['description']; ?></p>
                        <?php if ($product['catN']) { ?>
                            <p>Каталожный номер: <?php echo $product['catN']; ?></p>
                        <?php }?>
                    <?php }?>
                    <?php if ($product['note']) { ?>
                        <p>Примечание: <?php echo $product['note']; ?></p>
                    <?php }?>
                    <?php if ($product['compability']) { ?>
                        <p>Применимость: <?php echo $product['compability']; ?></p>
                    <?php }?>
                    <?php if ($product['ean']) { ?>
                        <p>Тип: <?php echo $product['ean']; ?></p>
                    <?php }?>
                    <?php if ($product['cond'] && $product['cond']!='-') { ?>
                        <p><b>Состояние:</b> <?php echo $product['cond']; ?></p>
                    <?php }?>
                    <?php if ($product['com_whole'] == 1 && $product['com_price'] != 0)  { ?>
                        <p class="price"><b>Цена комплекта: <?php echo $product['com_price']; ?></b></p>
                    <?php } elseif ($product['price'] != 0.00) { ?>
                        <p class="price"><b>Цена: <?php echo $product['price']; ?></b></p>
                    <?php }?> 
                    <p><b><?php echo $product['comp']; ?></b></p>
                    <!--<?php if ($product['rating']) { ?>
                    <div class="rating">
                      <?php for ($i = 1; $i <= 5; $i++) { ?>
                      <?php if ($product['rating'] < $i) { ?>
                      <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <?php } else { ?>
                      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <?php } ?>
                      <?php } ?>
                    </div>
                    <?php } ?>-->
                  </div>
                  <div class="button-group">
                    <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                    <button type="button" data-toggle="tooltip" title="Хочу" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                    <button type="button" data-toggle="tooltip" title="В сравнение" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
      <!--<div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>-->
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
<style>
    .product-thumb{
        overflow-x: hidden;
    }
</style>
<script type="text/javascript">
  $('#categ').on('change', function() {
    var url = document.location.pathname+this.value;
    document.location.href = url;
  });
  $('#podcat').on('change', function() {
    //var url = document.location.href+'&podcat='+this.value;
    //document.location.href = url;
    document.location.href = this.value;
  });
</script>