<div id="sSearchResult">
    
    <?php if(count($items)){ foreach($items as $item){ ?>
      <?php if(in_array(0, array_column($items, 'showImg'))) { ?>
          <a href='<?php echo $item["href"];?>' class="col-lg-4 col-md-4 col-xs-6 text-center">
              <div  class="btn btn-block btn-danger">
                <?php echo $item['name']; ?>
              </div><br>
          </a>
      <?php } else { ?>
          <a href='<?php echo $item["href"];?>' class="col-lg-2 col-xs-4 text-center">
              <div style="min-height: 110px;">
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>"/><br>
                <?php echo $item['name']; ?>
              </div>
          </a>
      <?php }?>
    <?php } } else { echo 'По вашему запросу ничего не найдено. Попробуйте изменить формулировку.'; }?>
</div>