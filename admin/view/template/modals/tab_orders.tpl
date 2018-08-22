<div role="tabpanel" class="tab-pane" id="orders">
    <h3>Заказы: </h3>
    <?php foreach($orders as $order){ ?>
        <div class="alert alert-<?php echo (int)$order['viewed']?'success':'danger';?>">
            <div class="col-sm-1 text-left"><b><?php echo $order['order_id'];?></b></div>
            <div class="col-sm-3 text-left"><b><?php echo $order['lastname'].'<br>'.$order['firstname'].' '.$order['patron'];?></b></div>
            <div class="col-sm-3 text-center"><b><?php echo $order['telephone'].'<br>'.$order['email'].'<br>'.$order['payment_city'];?></b></div>
            <div class="col-sm-3 text-right"><b><?php echo date("d.m.Y H:i:s", strtotime($order['date_added'])).'<br>'.$order['name'];?></b></div>
            <div class="col-sm-2 text-right"><a class="btn btn-info" href="index.php?route=report/orders_info&token=<?php echo $token;?>&order_id=<?php echo $order['order_id'];?>"><i class="fa fa-eye"></i></a></div>
        </div>
    <?php }?>
    <div class="col-sm-12 text-center">
        <a class="btn btn-info" href="index.php?route=report/orders&token=<?php echo $token;?>">Перейти ко всем заказам</a>
    </div>
</div>