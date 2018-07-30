<?php echo $header; ?>
<script>
    <?php $i = 0; ?>
    <?php foreach($complect['accessories'] as $acc) { ?>
        window.complect[<?php echo $i; ?>] = '<?php echo $acc['vin']; ?>';
        <?php ++$i; ?>
    <?php } ?>
</script>
<?php echo $column_left; ?>
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
    <div class="alert alert-success" id="statbox"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="alert alert-success">
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <label>Название комплекта</label>
                        <input class="form-control" name="name" id="name" value="<?php echo $complect['name'];?>"/>
                        <input type="hidden" id="id" value="<?php echo $id; ?>">
                    </div>
                    <div class="col-lg-4">
                        <label>Головной товар(комплектообразующий)</label>
                        <input value="<?php echo $complect['heading'];?>" class="form-control" oninput="validVin('heading-crt', 'result_box', '<?php echo $token; ?>')" name="" id="heading-crt" />
                        <div id='result_box'></div>
                    </div>
                    <div class="col-lg-4">
                        <label>Цена комплекта</label>
                        <input class="form-control" name="price" id="price" value="<?php echo $complect['price'];?>"/>
                    </div>
                </div>
                <div class="col-lg-12"><p></p></div>
                <div  class="col-lg-12">
                    <div class="col-lg-4">
                        <label>Способ продажи комплекта</label>
                        <select id="whole" class="form-control">
                            <option value="0">Обычная продажа</option>
                            <option value="1">Только комплект целиком</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Скидка(в процентах. при значении 0, скидка = 15%)</label>
                        <div class="input-group">
                            <input class="form-control" name="sale" id="sale" value="<?php echo $complect['sale']?>"/>
                            <div class="input-group-addon">%</div>
                        </div>
                    </div>
                    <div class="col-lg-3"><button class='btn btn-info btn-block' onclick="editComplect('<?php echo $token; ?>');">Сохранить</button></div>
                </div>
            </div>
            <div class="alert alert-success">
                <h4>Комплектующие</h4>
                <div class="col-lg-3">
                    <div class="col-lg-9">
                        <input id="compl" oninput="validVin('compl', 'complres', '<?php echo $token; ?>')" class="form-control col-lg-6" type="text" placeholder="Введите внутренний номер детали" />
                    </div>
                    <div class='col-lg-3'>
                        <button onclick="addAccss('<?php echo $token; ?>');" data-toggle="tooltip" data-original-title="Добавить деталь в комплект" class="btn btn-success"><i class="fa fa-plus"></i></button>
                    </div>
                    <div id="complres"></div>
                </div>
                <div class='col-lg-9'>
                    <h4>Комплект: </h4>
                    <?php $i = 0; ?>
                    <table class="table" id='complect'>
                        <?php foreach($complect['accessories'] as $acc) { ?>
                            <tr id="c<?php echo $i;?>">
                                <td id="accss<?php echo $i;?>">
                                    <?php echo $acc['vin']?>
                                </td>
                                <td>
                                    <?php echo $acc['name']?>
                                </td>
                                <td>
                                    <?php echo $acc['price']?>
                                </td>
                                <td>
                                    <button class="btn btn-danger" onclick="deleteAccss('<?php echo $acc['vin'];?>', 'c<?php echo $i;?>', '<?php echo $i;?>', '<?php echo $token; ?>')"><i class="fa fa-minus fw"></i></button>
                                </td>
                            </tr>


                                           
                            <?php ++$i; ?>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer;?>