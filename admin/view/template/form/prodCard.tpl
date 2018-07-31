<div class="row">
    <div class="col-lg-12">
        <h3><?php echo $name.' | '.$vin.' | '.$location;?></h3>
        <div class="btn-group">
            <a class="btn btn-primary" target="_blank" href="<?php echo $edit; ?>"><i class="fa fa-pencil"></i> редактировать</a> 
            <a class="btn btn-success" target="_blank" href="<?php echo $go_site; ?>"><i class="fa fa-arrow-circle-right"></i> на витрине</a>
            <button type="button" data-toggle="tooltip" disabled class="btn btn-warning" title="Не работает"><i class="fa fa-copy"></i> копировать ссылку</button>
        </div>
    </div>
    <div class="col-lg-12"><p></p></div>
    <div class="col-md-6">
        <img src="<?php echo $image;?>" class="img-responsive img-rounded img-thumbnail thumbnail">
    </div>
    <div class="col-md-6">
        <table class="table table-striped">
            <tr>
                <td class="text-right h4">Внутренний номер: </td>
                <td class="text-right h4"><?php echo $vin;?></td>
            </tr>
            <tr>
                <td class="text-right h4">Количество: </td>
                <td class="text-right h4"><?php echo $quantity;?></td>
            </tr>
            <tr>
                <td class="text-right h4">Цена: </td>
                <td class="text-right h4"><?php echo $price;?></td>
            </tr>
            <tr>
                <td class="text-right h4">Расположение: </td>
                <td class="text-right h4"><?php echo $location?></td>
            </tr>
        </table>
    </div>
</div>
<div class="row" style="overflow-x: scroll;">
    <table class="table table-striped">
    <?php foreach($options as $option){ ?>    
        <tr>
            <td class="text-right col-sm-3"><?php echo $option['text']?>: </td>
            <td class="text-left h4 col-sm-9"><?php echo $option['value']?></td>
        </tr>
    <?php }?>    
    </table>
</div>
<script type="text/javascript">
    
</script>