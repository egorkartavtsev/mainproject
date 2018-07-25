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
        <div class="row">
            <div class="alert col-lg-4">
                <a href="index.php?route=complect/complect/create&token=<?php echo $token; ?>" class="btn btn-success">Создать комплект</a>
            </div>
            <div class="alert col-lg-12">
                <input class="form-control col-lg-4" type="text" id="nameComp" placeholder='Введите название комплекта' oninput="searchComplects('<?php echo $token;?>');"/>
                <div class="clearfix"></div>
                <div class="clearfix"><p></p></div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead> 
                          <tr> 
                            <th>id</th> 
                            <th>Наименование комплекта</th>
                            <th>Внутренний номер</th>
                            <th>Головной товар</th>
                            <th>Цена</th> 
                            <th>Действие</th>
                          </tr> 
                        </thead>
                        <tbody id="totalCompl">
                            <?php if(isset($complects)) { ?>
                                <?php foreach($complects as $complect) { ?>
                                    <tr id="comp<?php echo $complect['id'];?>">
                                        <td><?php echo $complect['id'];?></td>
                                        <td><?php echo $complect['name'];?></td>
                                        <td><?php echo $complect['link'];?></td>
                                        <td><?php echo $complect['heading'];?></td>
                                        <td><?php echo $complect['price'];?></td>
                                        <td>
                                            <a href="<?php echo $complect['href'];?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Редактировать">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <button btn_type="deleteCompl" complId="<?php echo $complect['id']; ?>"  data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Раскомплектация">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td></td>
                                <td>
                                    Нет созданных комплектов!
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer;?>