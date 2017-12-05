<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <script type="text/javascript" src="view/javascript/tiresdisc.js"></script>
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
        <div class="alert alert-success" id="filters">
            <select id="filter-categ" class="form-control">
                <option value="all">Все товары</option>
                <option value="disc" <?php if($curr_filter=='disc') { echo 'selected'; }?>>Диски</option>
                <option value="tires" <?php if($curr_filter=='tires') { echo 'selected'; }?>>Шины</option>
            </select>
            <div class="col-sm-12" id="filter_fields"></div>
        </div>
        <table class="table table-bordered table-responsive table-striped">
            <thead>
                <tr>
                    <td>Изображение</td>
                    <td>Категория</td>
                    <td><a href='<?php echo $link; ?>&sort=pd.name'>Название</a></td>
                    <td><a href='<?php echo $link; ?>&sort=p.sku'>Внутренний номер</a></td>
                    <td><a href='<?php echo $link; ?>&sort=p.weight'>Расположение</a></td>
                    <td><a href='<?php echo $link; ?>&sort=p.price'>Цена</a></td>
                    <td><a href='<?php echo $link; ?>&sort=p.quantity'>Количество</a></td>
                    <td><a href='<?php echo $link; ?>&sort=p.status'>Статус</a></td>
                    <td><a href='<?php echo $link; ?>&sort=p.date_added'>Дата создания</a></td>
                    <td>Действие</td>
                </tr>
            </thead>
            <tbody id="listProducts">
                <?php foreach($list as $prod){ ?>
                    <tr id='item<?php echo $prod['link'];?>'>
                        <td class="text-center"><?php echo '<img src="'.$prod['image'].'" />';?></td>
                        <td><?php echo $prod['cat_name'];?></td>
                        <td><?php echo $prod['name'];?></td>
                        <td><?php echo $prod['vin'];?></td>
                        <td><?php echo $prod['locate'];?></td>
                        <td><?php echo $prod['price'];?></td>
                        <td><?php echo $prod['quant'];?></td>
                        <td><?php echo $prod['stat']=='1'?'<span class="label label-success">Включено</span>':'<span class="label label-warning">Отключено</span>';?></td>
                        <td><?php echo $prod['date'];?></td>
                        <td>
                            <a class="btn btn-primary" href="<?php echo $prod['linkEdit'];?>"><i class="fa fa-pencil fw"></i></a>
                            <button class="btn btn-danger" onclick="confirm('Вы уверены?') ? deleteItem('<?php echo $prod['link'];?>') : false;"><i class="fa fa-trash-o fw"></i></button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script>
        $("#filter-categ").on('change', function(){
            getFilters($("#filter-categ").val());
        })
    </script>
<?php echo $footer;?>
