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
    <div id="content" class="col-sm-12"><?php echo $content_top; ?>
      
      <!--суда положу вывод моделей и годов-->
      <?php if($categ=='all') { ?>
        <h3>Выберите категорию</h3>
        <div class="row">
          <div class="col-sm-3">
            <ul>
              <li><a href="index.php?route=product/tiredisc/tirelist">Шины</a></li>
              <li><a href="index.php?route=product/tiredisc/disclist">Диски</a></li>
            </ul>
          </div>
        </div>
       <?php } else { ?>
       <h3>Уточните условия поиска:</h3>
        <?php foreach($filter_fields as $field => $info) { ?>
            <div class="col-md-3 form-group">
                <label for="filter-<?php echo $field;?>"><?php echo $info['name'];?></label>
                <select class="form-control" id="filter-<?php echo $field;?>">
                    <option selected disabled>Выберите значение</option>
                    <?php foreach($info['values'] as $value) { ?>
                        <?php if(!stristr($path, $field)){ ?>
                            <option value="<?php echo $path;?>&<?php echo $field;?>=<?php echo $value;?>"><?php echo $value;?></option>
                        <?php } elseif((stristr($path, $field)) && (!stristr($path, $value))) { ?>
                            <option value="<?php echo str_replace($curr_filter[$field],$value,$path)?>"><?php echo $value;?></option>
                        <?php } else { ?>
                            <option disabled selected><?php echo $value;?></option>
                        <?php }?>

                    <?php }?>
                </select>
            </div>
        <?php }?>
        <div class="clearfix"></div>
        <a class="btn btn-danger" href='<?php echo $reset;?>'>Сбросить фильтры</a>
        <div class="clearfix"></div>
       <?php }?>
        <hr>
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
      </div>
        <div class="row">
        <?php if (isset($products)) { ?>
          <?php foreach ($products as $product) { ?>
            <div class="product-layout product-list col-xs-12">
              <div class="product-thumb">
                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                <div>
                  <div class="caption">
                    <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                    <p><b>Внутренний номер:</b> <?php echo $product['vin']; ?></p>
                    <p><b>Тип:</b> <?php echo $product['type']; ?></p>
                    <p><b>Состояние:</b> <?php echo $product['cond']; ?></p>
                    <p class="price"><b>Цена: <?php echo $product['price']; ?></b></p>
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
        
        
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
<style>
    .product-thumb{
        overflow-x: hidden;
    }
</style>
<script>
    <?php foreach($filter_fields as $field => $info) { ?>
        $("#filter-<?php echo $field;?>").on('change', function(){
            var url = document.location.pathname+this.value;
            document.location.href = url;
        })
    <?php } ?>
</script>