<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <div class="h4 well well-sm"><i class="fa fa-warning fw"></i> <?php echo $description; ?></div>
      <?php if(isset($success)) { ?><div class="h4 alert alert-success"><i class="fa fa-life-ring fw"></i> <?php echo $success; ?></div><? } ?>
    </div>
  </div>
  <div class="container-fluid">
      <form method="post" class="form" action="index.php?route=setting/libraries&token=<?php echo $token;?>">
          <div class="form-group">
              
          </div>
      </form>
  </div>
</div>

<?php echo $footer; ?>
