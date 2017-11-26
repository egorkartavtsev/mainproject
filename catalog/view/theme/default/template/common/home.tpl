<?php echo $header; ?>
<div class="container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
        
        <div class="row">  
            <?php if ($brands) { ?>
                <?php foreach($brands as $brand) { ?>
                    <div style="min-height: 110px;" class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
                        <div class="center-block col-lg-12">
                               <a href='<?php echo $brand["href"];?>'>
                                   <div class="col-lg-12">
                                       <img src="<?php echo $brand['img']; ?>" alt="<?php echo $brand['name']; ?>"/>
                                   </div>
                                   <div class="col-lg-12" style="text-align: center;">
                                       <?php echo $brand['name']; ?>
                                   </div>
                               </a>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <?php echo $content_top; ?>
        <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>