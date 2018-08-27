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
        <div class="well well-sm col-sm-12">
            <label>Поиск проводится по марке, модели, модельному ряду, году выпуска, VIN, ДВС и внутреннему номеру донора</label>
            <input type="text" id="searchd" class="form-control" placeholder="Введите условия поиска" />
        </div>
        <div class="col-sm-12" style="overflow-x: scroll;">
            <table class="table table-bordered table-responsive table-striped">
                <thead>
                    <tr>
                        <td>Действие</td>
                        <td>Изображение</td>
                        <td>Название</td>
                        <td>Внутренний номер</td>
                        <td>Модельный ряд</td>
                        <td>Тип кузова</td>
                        <td>Год выпуска</td>
                        <td>Пробег</td>
                        <td>VIN</td>
                        <td>ДВС</td>
                        <td>Трансмиссия</td>
                        <td>Привод</td>
                        <td>Цвет кузова</td>
                        <?php if($utype) { ?>
                            <td>Стоимость</td>
                            <td>К-во деталей</td>
                            <td>Цена деталей</td>
                        <?php }?>
                        
                    </tr>
                </thead>
                <tbody id="listDonors">
                    <?php foreach($donors as $donor) { ?>
                        <tr>
                            <td>
                                <a href="<?php echo $donor['show'];?>" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                                <a href="<?php echo $donor['edit'];?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                <a href="<?php echo $donor['delete'];?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                            </td>
                            <td class="text-center"><img src="<?php echo $donor['image'];?>"></td>
                            <td><?php echo $donor['name'];?></td>
                            <td><?php echo $donor['numb'];?></td>
                            <td><?php echo $donor['mod_row'];?></td>
                            <td><?php echo $donor['ctype'];?></td>
                            <td><?php echo $donor['year'];?></td>
                            <td><?php echo $donor['kmeters'];?></td>
                            <td><?php echo $donor['vin'];?></td>
                            <td><?php echo $donor['dvs'];?></td>
                            <td><?php echo $donor['trmiss'];?></td>
                            <td><?php echo $donor['priv'];?></td>
                            <td><?php echo $donor['color'];?></td>
                            <?php if($utype) { ?>
                                <td><?php echo $donor['price'];?></td>
                                <td><?php echo $donor['quant'];?></td>
                                <td><?php echo $donor['totalp'];?></td>
                            <?php }?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="view/javascript/donor.js"></script>
<script type="text/javascript">
    $("#searchd").on("input", function(){
        filterDonorList();
    })
</script>
<?php echo $footer;?>