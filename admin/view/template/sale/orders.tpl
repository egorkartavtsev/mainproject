<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Заказы</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Заказы с витрины</h3>
      </div>
      <div class="panel-body">
          <div class="alert alert-success">
              <div class="form-group-sm col-sm-4">
                  <label for="filter-order_id">Номер заказа</label>
                  <input class="form-control" type="text" id="filter-order_id" value="<?php echo $filter_order_id; ?>" placeholder="Введите номер заказа ...">
              </div>
              <div class="form-group-sm col-sm-4">
                  <label for="filter-lastname">Покупатель</label>
                  <input class="form-control" type="text" id="filter-lastname" value="<?php echo $filter_lastname; ?>" placeholder="Введите фамилию...">
              </div>
              <div class="form-group-sm col-sm-4">
                  <label for="filter-telephone">Телефон</label>
                  <input class="form-control" type="text" id="filter-telephone" value="<?php echo $filter_telephone; ?>" placeholder="Введите телефон ...">
              </div>
              <div class="clearfix"></div>
              <div class="clearfix"><p></p></div>
              <div class="col-sm-6">
                <button btn_type="order_filter" id="button-filter" class="btn btn-sm btn-primary">Применить фильтр</button>
                <button btn_type="clear_filter" class="btn btn-sm btn-danger">Очистить фильтр</button>
              </div>
          </div>
          <div class="row">
              <table class="table table-bordered table-hover table-responsive">
                  <thead>
                      <tr>
                          <td>№</td>
                          <td>Заказчик</td>
                          <td>Контакты</td>
                          <td>Населенный пункт</td>
                          <td>Сумма</td>
                          <td>Статус</td>
                          <td>Дата заказа</td>
                          <td>Действие</td>
                      </tr>
                  </thead>
                  <tbody>
                    <?php if(isset($orders) && $total_orders>0){ ?>
                      <?php foreach($orders as $key => $order){ ?>
                        <tr <?php echo $order['viewed']==0?'class="neworder"':'notnew'?>>
                          <td><?php echo $key; ?></td>
                          <td><?php echo $order['customer']; ?></td>
                          <td><?php echo $order['contacts']; ?></td>
                          <td><?php echo $order['payment_city']; ?></td>
                          <td><?php echo (int)$order['total']; ?></td>
                          <td><?php echo $order['status']; ?></td>
                          <td><?php echo $order['date_added']; ?></td>
                          <td>
                              <a href="<?php echo $href;?>&order_id=<?php echo $key;?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                              <?php if($utype=='adm'){ ?><button class="btn btn-danger"><i class="fa fa-trash-o"></i></button><?php }?>
                          </td>
                        </tr>
                      <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="7" style="text-align: center;">Ничего не найдено</td></tr>
                    <?php } ?>
                  </tbody>
              </table>
          </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination;?></div>
          <div class="col-sm-6 text-right">Всего заказов: <?php echo $total_orders;?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
      $('[btn_type=clear_filter]').click(function(){
          location = 'index.php?route=report/orders&token='+getURLVar('token');
      });
      $('[btn_type=order_filter]').click(function(){
          var url = '';
          $(this).parent().parent().find("input[id*='filter']").each(function(){
              if($(this).val()!==''){
                  url = url+'&'+$(this).attr('id')+'='+$(this).val();
              }
          })
          location = 'index.php?route=report/orders&token='+getURLVar('token')+url;
      });
      $('input[id*=\'filter\']').on('keydown', function(e) {
            if (e.keyCode == 13) {
                    $('#button-filter').trigger('click');
            }
      });
  </script>
  <style>
      .neworder{
          background-color: #f7caca;
          font-style: italic;
      }
  </style>
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>