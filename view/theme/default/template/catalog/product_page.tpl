<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb hidden">
       <?php /*foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php }*/ ?>
    </ul>
    <div class="row">
        <div id="content" class="col-sm-12">
            <?php echo $content_top; ?>
            <?php echo $productpage; ?>
            <?php echo $content_bottom; ?>
        </div>  
    </div>
</div>
<?php echo $footer; ?>