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
        <div class="row">
            <div class="col-lg-12">
                <input class="form-control" type="libSearch" placeholder="Быстрый поиск по библиотеке" lib_id="<?php echo $libId;?>">
            </div>
            <div class="clearfix"><p></p></div>
            <div class="clearfix"><p></p></div>
            <?php echo $items; ?>
        </div>
        <?php echo $content_bottom; ?>
    </div>
    </div>
</div>
<?php echo $footer; ?>