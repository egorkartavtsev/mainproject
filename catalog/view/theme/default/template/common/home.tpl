<?php echo $header; ?>
<div class="container">
  <div class="row">
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="col-sm-12">
        <?php echo $content_top; ?>
        <div class="row">  
            <?php if ($brands) { ?>
                <?php foreach($brands as $brand) { ?>
                    <div style="min-height: 110px;" class="col-lg-1 col-md-2 col-sm-2 col-xs-2 text-center">
                       <a href='<?php echo $brand["href"];?>'>
                           <img src="<?php echo $brand['img']; ?>" class="img-responsive center-block" alt="<?php echo $brand['name']; ?>"/>
                           <?php echo $brand['name']; ?>
                       </a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        
        <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>