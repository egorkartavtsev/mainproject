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
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 form-group">
                <form action="index.php?route=common/crtUpdNotice&token=<?php echo $token;?>" method="POST">
                  <label>Шаблон описания продуктов данного типа:</label>
                  <textarea name="update_info" data-lang="1" class="form-control summernote"></textarea>
                  <p></p>
                  <input type="hidden" name="autor" value="<?php echo $autor;?>">
                  <input type="submit" class='btn btn-primary' value="Сохранить">
                </form>
            </div>
        </div>
    </div>
<?php echo $footer;?>