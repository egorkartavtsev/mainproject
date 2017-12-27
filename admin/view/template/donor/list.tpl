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
        <table class="table table-bordered table-responsive table-striped">
            <thead>
                <tr>
                    <td>Изображение</td>
                    <td>Название</td>
                    <td>Внутренний номер</td>
                    <td>Марка</td>
                    <td>Модель</td>
                    <td>Модельный ряд</td>
                    <td>Тип кузова</td>
                    <td>Год выпуска</td>
                    <td>Пробег</td>
                    <td>VIN</td>
                    <td>ДВС</td>
                    <td>Трансмиссия</td>
                    <td>Привод</td>
                    <td>Цвет кузова</td>
                    <td>Стоимость</td>
                    <td>Действие</td>
                </tr>
            </thead>
            <tbody id="listDonors">
                <?php foreach($donors as $donor) { ?>
                    <tr>
                        <td class="text-center"><img src="<?php echo $donor['image'];?>"></td>
                        <td><?php echo $donor['name'];?></td>
                        <td><?php echo $donor['numb'];?></td>
                        <td><?php echo $donor['brand'];?></td>
                        <td><?php echo $donor['model'];?></td>
                        <td><?php echo $donor['mod_row'];?></td>
                        <td><?php echo $donor['ctype'];?></td>
                        <td><?php echo $donor['year'];?></td>
                        <td><?php echo $donor['kmeters'];?></td>
                        <td><?php echo $donor['vin'];?></td>
                        <td><?php echo $donor['dvs'];?></td>
                        <td><?php echo $donor['trmiss'];?></td>
                        <td><?php echo $donor['priv'];?></td>
                        <td><?php echo $donor['color'];?></td>
                        <td><?php echo $donor['price'];?></td>
                        <td>***</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php echo $footer;?>