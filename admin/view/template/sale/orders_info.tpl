<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <a href="<?php echo $btn_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i> вернуться к списку заказов</a></div>
      <h1>Информация о заказе</h1>
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
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> Заказ №<?php echo $order['id'];?></h3>
      </div>
      <div class="panel-body">
          <div class="row">
              <h3>Информация о заказе:</h3>
              <div class="col-md-4">
                  <table class="table table-bordered table-striped">
                      <tr>
                          <td>Дата заказа</td>
                          <td><?php echo $order['date_added'] ?></td>
                      </tr>
                      <tr>
                          <td>Фамилия</td>
                          <td><?php echo $order['lastname'] ?></td>
                      </tr>
                      <tr>
                          <td>Имя</td>
                          <td><?php echo $order['firstname'] ?></td>
                      </tr>
                      <tr>
                          <td>Отчество</td>
                          <td><?php echo $order['patron'] ?></td>
                      </tr>
                      <tr>
                          <td>Регион</td>
                          <td><?php echo $order['zone'] ?></td>
                      </tr>
                      <tr>
                          <td>Город</td>
                          <td><?php echo $order['city'] ?></td>
                      </tr>
                      <tr>
                          <td>Адрес</td>
                          <td><?php echo $order['address'] ?></td>
                      </tr>
                      <tr>
                          <td>Email</td>
                          <td><?php echo $order['email'] ?></td>
                      </tr>
                      <tr>
                          <td>Телефон</td>
                          <td><?php echo $order['telephone'] ?></td>
                      </tr>
                  </table>
                  <div class="clearfix"></div>
                  <div class="clearfix"><p></p></div>
                  <div class="well">
                      <h3>Информация о доставке: <button id="save_shipinfo" disabled class="btn btn-success"><i class="fa fa-floppy-o"></i></button></h3>
                      <div class="form-group">
                          <label>Выберите транспортную компанию:</label>
                          <select class="form-control" info-target="shipinfo" id="ship_comp">
                              <option value="">-</option>
                              <?php foreach($ship as $key => $sc){ ?>
                                <option value="<?php echo $key; ?>" <?php echo ($sc['name']==$order['ship_comp'])?'selected':''; ?>><?php echo $sc['name']; ?></option>
                              <?php }?>
                          </select>
                      </div>
                      <div id="ship_href"><a target="blank" href="<?php echo $order['ship_href']; ?>" class="bnt btn-sm btn-info">Отслеживание по трекномеру</a></div>
                      <div class="form-group">
                          <label>Трек-номер отправления:</label>
                          <input class="form-control" value="<?php echo $order['track_id']; ?>" info-target="shipinfo" id="track_id">
                      </div>
                      
                      <div class="form-group">
                          <label>Дата отправки:</label>
                            <div class='input-group date' id='datetimepicker2'>
                                <input type='text' value="<?php echo date("d.m.Y H:i", strtotime($order['ship_date'])); ?>" class="form-control date" info-target="shipinfo" id="ship_date"/>
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                            </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-5">
                  <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td>Наименование</td>
                            <td>Артикул</td>
                            <td>Стоимость</td>
                            <td>Количество</td>
                            <td>Итого</td>
                            <td class="text-center"><i class="fa fa-android"></i></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($order['products']) && count($order['products'])){ ?>
                      <?php foreach($order['products'] as $prod){ ?>
                        <tr>
                            <td><a href="<?php echo $catalog.'&product_id='.$prod['product_id'];?>"><?php echo $prod['name'];?></a></td>
                            <td><?php echo $prod['vin']?></td>
                            <td><?php echo (int)$prod['price']?></td>
                            <td><?php echo (int)$prod['factquantity']?></td>
                            <td><?php echo (int)$prod['total']?></td>
                            <td><button class="btn btn-danger" btn_type="delete_prod" prod="<?php echo $prod['product_id']?>"><i class="fa fa-trash-o"></i></button></td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                      <tr><td colspan="4" style="text-align: right;">Итого:</td><td colspan="2"><?php echo $order['total'];?></td></tr>
                      <tr>
                          <td colspan="4">
                              <label for="addedVin">Добавить товар к заказу</label>
                              <input class="form-control" type="text" placeholder="Введите артикул товара..." id="addedVin">
                          </td>
                          <td colspan="2">
                              <button class="btn btn-success" btn_type="added_prod"><i class="fa fa-plus"></i></button>
                          </td>
                      </tr>
                    </tbody>
                  </table>
              </div>
              <div class="col-md-3" id="debug">
                  <div class="col-lg-8">
                      <label>Состояние заказа:</label>
                      <select id="order-status" class="form-control">
                          <?php foreach($statuses as $status){ ?>
                              <option value="<?php echo $status['id'];?>" <?php echo ($status['id']==$order['order_status_id'])?'selected':''?>><?php echo $status['name']?></option>
                          <?php }?>
                      </select>
                  </div>
                  <div class="col-lg-4">
                      <label for="save_order">&nbsp;</label>
                      <button id="save_order" disabled class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i></button>
                  </div>
              </div>
              <div class="col-md-8">
                  <div class="well">
                      <h3>История действий с заказом: </h3>
                      <?php if(count($order_history)){ ?>
                      <table class="table table-striped table-responsive">
                          <?php foreach($order_history as $oHist){ ?>
                              <tr>
                                  <td class="col-sm-4">
                                      <button class="btn btn-sm btn-<?php echo $oHist['color'];?>">
                                          <i class="<?php echo $oHist['icon'];?>"></i>
                                      </button> 
                                      <?php echo $oHist['name'];?>
                                  </td>
                                  <td class="col-sm-4"><?php echo $oHist['comment'];?></td>
                                  <td class="col-sm-2"><?php echo date("d.m.Y H:i:s", strtotime($oHist['date_added']));?></td>
                                  <td class="col-sm-2"><?php echo $oHist['manager'];?></td>
                              </tr>
                          <?php }?>
                      </table>
                      <?php } else{ ?>
                              <p>С заказом не производилось действий..</p>
                      <?php }?>
                  </div>
              </div>
          </div>
          <div class="clearfix"></div>
          <div class="clearfix"><p></p></div>
          <div class="row">
            <?php if(count($complects)){ ?>
                <h3>Включенные конплекты:</h3>
                <?php foreach($complects as $c_name => $prods){ ?>
                    <div class="col-md-4">
                        <div class="alert alert-danger">
                            <h4><b>Комплект:</b> <?php echo $c_name;?></h4>
                            <table class="table table-striped">
                                <?php foreach($prods as $prod){ ?>
                                    <tr>
                                        <td><?php echo $prod['name']?> <br>(<?php echo ($prod['comp_price']=='')?'Комплектующее':'Головной'; ?>)</td>
                                        <td><?php echo $prod['vin']?></td>
                                        <td><b><?php echo $prod['price']?></b></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
          </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $('#order-status').change(function(){
        $('#save_order').removeAttr('disabled');
    });
    
    $('#save_order').click(function(){
        ajax({
            url: 'index.php?route=report/orders_info/save_status&token='+getURLVar('token'),
            method: 'post',
            data: {
                stat: $('#order-status').val(),
                order: getURLVar('order_id')
            },
            success:function(data){
                $('#save_order').attr('disabled', 'true');
                alert(data);
                alert('Состояние изменено.');
            }
        })
    });
    
    $('[btn_type=added_prod]').click(function(){
        var vin = $(this).parent().parent().find('input[id=addedVin]').val();
        ajax({
            url: 'index.php?route=report/orders_info/added_prod&token='+getURLVar('token'),
            method: 'post',
            data: {
                vin: vin,
                order: getURLVar('order_id')
            },
            success:function(data){
                if(data){
//                    $('#debug').html(data);
                    location.reload();
                } else {
                    alert('Товар с таким артикулом не найден либо отсутствует в наличии.');
                    return false;
                }
            }
        })
    });
    $(document).find("[id*='datetimepicker']").each(function(){
        $(this).datetimepicker();
    })
    $('[btn_type=delete_prod]').click(function(){
        var prod = $(this).attr('prod');
        ajax({
            url: 'index.php?route=report/orders_info/delete_prod&token='+getURLVar('token'),
            method: 'post',
            data: {
                prod: prod,
                order: getURLVar('order_id')
            },
            success:function(data){
                if(data){
                   //$('#debug').html(data);
                   location.reload();
                } else {
                    alert('Не удалось убрать  товар из заказа. Попробуйте снова или обратитесь к администратору.');
                    return false;
                }
            }
        })
    });
</script>
<?php echo $footer; ?> 
