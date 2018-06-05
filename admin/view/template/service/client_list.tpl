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
      <?php if(isset($success)){ ?><div class="h4 alert alert-success"><i class="fa fa-warning fw"></i> <?php echo $success; ?></div><?php }?>
    </div>
  </div>
  <div class="container-fluid">
      <div class="col-lg-12">
          <div class="alert alert-success"><p>Тут будет фильтр</p></div>
      </div>
      <div class="row">
          <table class="table table-bordered table-condensed table-hover">
              <thead>
                  <tr>
                      <th>№</th>
                      <th>Статус</th>
                      <th>ФИО/Наименование</th>
                      <th>Адрес</th>
                      <th>Контакты</th>
                      <th>Действие</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach($clients as $key => $row){ ?>
                  <tr>
                      <td><?php echo $key;?></td>
                      <td><?php echo $row['legal'];?></td>
                      <td><?php echo $row['name'];?></td>
                      <td><?php echo $row['adress'];?></td>
                      <td><?php echo $row['phone'];?></td>
                      <td><a href="<?php echo $row['action'];?>" class="btn btn-success"><i class="fa fa-eye"></i></a></td>
                  </tr>
                  <?php }?>
              </tbody>
          </table>
      </div>
      <div class="clearfix"><p></p></div>
      <div class="clearfix"></div>
  </div>
</div>
<?php echo $footer; ?>
