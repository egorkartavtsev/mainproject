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
    </div>
  </div>
  <div class="container-fluid">
      <table class="table table-bordered table-striped">
          <thead>
            <tr>
                <td>Контроллер</td>
                <td>Наименование</td>
                <td>Иконка</td>
                <td>Действие</td>
            </tr>
          </thead>
          <tbody>
              <?php foreach($controllers as $controller){ ?>
                <tr>
                    <td><?php echo $controller['controller'];?></td>
                    <td><input type="text" class="form-control" id="itemName-<?php echo $controller['control_id'];?>" value="<?php echo $controller['name'];?>"></td>
                    <td>
                        <select id="itemIcon-<?php echo $controller['control_id'];?>" class="form-control">
                            <?php foreach($icons as $icon){ ?>
                                <?php if(trim($icon['icon'])===trim($controller['icon'])){ ?>
                                  <option value="<?php echo $icon['icon'];?>" selected>
                                    <?php echo trim($icon['icon']);?>
                                  </option>
                                  <?php } else { ?>
                                  <option value="<?php echo $icon['icon'];?>">
                                    <?php echo trim($icon['icon']);?>
                                  </option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </td>
                    <td><button class="btn btn-success" onclick="saveControllerInfo('<?php echo $controller['control_id'];?>')"><i class="fa fa-floppy-o"></i></button></td>
                </tr>
              <?php }?>
          </tbody>
      </table>
  </div>
</div>

<?php echo $footer; ?>
