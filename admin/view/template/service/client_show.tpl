<div class="row">
    <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#allinfo" aria-controls="home" role="tab" data-toggle="tab">Общая информация</a></li>
    <li role="presentation"><a href="#accident" aria-controls="profile" role="tab" data-toggle="tab">Данные по ДТП</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="allinfo">
          <div class="col-md-4 alert alert-success">
            <h4>Информация о клиенте</h4>
            <table class="table table-bordered">
                <?php foreach($client as $info){ ?>
                  <tr>
                      <td><?php echo $info['text'];?></td>
                      <td><?php echo $info['value'];?></td>
                  </tr>
                <?php }?>
            </table>
          </div>
          <div class="col-md-4 alert alert-success">
            <h4 id="autoHeader">Автомобили клиента</h4>
            <?php foreach($auto as $key => $car){ ?>
              <div class="well a2cItem" target="<?php echo $key;?>">
                  <p><?php echo $car['brand'];?> <?php echo $car['model'];?> <?php echo $car['year'];?>г.в.</p>
                  <p>VIN: <?php echo $car['vin'];?>, <?php echo $car['numb'];?></p>
                  <?php if($car['status']=='0'){ ?><p><span class="label label-success">Продано</span></p><?php }?>
              </div>
            <?php }?>
            <button class="btn btn-block btn-success" btn_type="createAuto" data-toggle="modal" data-target="#autocreateModal"><i class="fa fa-plus-circle"></i> добавить</button>
          </div>
          <div class="col-md-4 alert alert-success">
            <h4>Страховые полисы</h4>
          </div>
      </div>
    <div role="tabpanel" class="tab-pane" id="accident">
        <div class="col-md-4 alert alert-success">
          <h4>Данные по ДТП</h4>
        </div>
    </div>
  </div>
</div>
<?php echo $modal_auto;?>
<script>
    $('#myTabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })
</script>