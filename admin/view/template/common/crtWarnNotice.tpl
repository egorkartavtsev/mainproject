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
            <div class="h4 well well-sm"><i class="fa fa-warning"></i><?php echo $description;?></div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <form action="index.php?route=common/crtWarnNotice&token=<?php echo $token;?>" method="POST">
                <div class="col-md-6">
                    <div class="form-group well well-sm">
                          <label>Заголовок(может быть пустым):</label>
                          <input type="text" name="header" class="form-control" value="">
                    </div>
                    <div class="form-group well well-sm">
                          <label>Текст оповещения:</label>
                          <textarea name="text" data-lang="1" class="form-control summernote"></textarea>
                          <p></p>
                          <input type="hidden" name="autor" value="<?php echo $autor;?>">
                    </div>
                    <p></p>
                    <input type="submit" class='btn btn-primary' value="Сохранить">
                </div>
                <div class="col-md-3">
                    <div class="form-group well well-sm">
                          <label>Целевые пользователи:</label>
                          <select multiple name="users[]" style="min-height: 200px;" class="form-control">
                              <?php foreach($users as $usr){ ?>
                                  <option value="<?php echo $usr['user_id'];?>"><?php echo $usr['firstname'];?> <?php echo $usr['lastname'];?></option>
                              <?php }?>
                          </select>
                          <label><i class="fa fa-warning"></i> Если никто не выбран, то оповещение <b>НЕ&nbsp;БУДЕТ</b> создано</label>
                    </div>
                    <p></p>
                    <div class="form-group well well-sm">
                          <label>Текст ярлыка:</label>
                          <input type="text" name="text_label" class="form-control" value="ВНИМАНИЕ">
                    </div>
                    <p></p>
                    <div class="form-group well well-sm">
                          <label>Цвет ярлыка:</label>
                          <select name="color_label" class="form-control">
                              <option value="default" style="background-color: #777; color: mintcream;">Серый</option>
                              <option value="success" style="background-color: #8fbb6c; color: mintcream;">Зелёный</option>
                              <option value="danger" style="background-color: #f56b6b; color: mintcream;">Красный</option>
                              <option value="primary" style="background-color: #1e91cf; color: mintcream;">Синий</option>
                              <option value="info" style="background-color: #5bc0de; color: mintcream;">Голубой</option>
                              <option value="warning" style="background-color: #f38733; color: mintcream;">Жёлтый</option>
                          </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php echo $footer;?>