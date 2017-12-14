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
        <h3>История продаж</h3>
        <span class="label label-default">Отслеживайте продажи в магазине</span>
        <hr>
        <div class="col-lg-12">
            <table class="table table-striped"> 
                <thead> 
                    <tr> 
                        <th>Деталь</th> 
                        <th>Покупатель</th> 
                        <th>Цена</th> 
                        <th>Сумма</th> 
                        <th>Списание</th> 
                        <th>Менеджер</th> 
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                        foreach($res_sales as $res){
                            echo '<tr>'
                                    .'<td>'.$res['name'].' '.$res['sku'].'</td>'
                                    .'<td>'.$res['client'].'/'.$res['city'].'</td>'
                                    .'<td> Стоимость: '.$res['price'].'руб<br>'
                                        .'Цена продажи: '.$res['saleprice'].'руб<br>'
                                        .'Причина уценки: '.$res['reason'].' '
                                    .'</td>'
                                    .'<td>'.$res['summ'].'</td>'
                                    .'<td>Склад: '.$res['loc'].'<br>Продано: '. $res['date'] .'<br>Списано: '.$res['date_mod'].'</td>'
                                    .'<td>'.$res['manager'].'</td>'
                                .'</tr>';
                        }
                    ?> 
                </tbody> 
            </table>
         </div>
     </div>
</div>
<?php echo $footer;?>