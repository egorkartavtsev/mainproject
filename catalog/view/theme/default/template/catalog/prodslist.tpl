<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row">
    <div id="content" class="col-sm-12">
        <?php echo $content_top; ?>
        <div class="well well-sm col-lg-3">
            <?php echo $filterDiv; ?>
        </div>
        <div class="col-lg-9">
            <?php echo $productsDiv; ?>
        </div>
        <?php echo $content_bottom; ?>
    </div>
    </div>
</div>
<?php echo $footer; ?>