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
        <div class="container-fluid well">
            <h4><?php echo $lable_vn; ?></h4>
                <input type='text' name='vin' id="vin"/>
                <button class="btn btn-success" onclick="findprod('<?php echo $token_wo; ?>');">
                    Найти
                </button>
        </div>
        <div class="col-lg-6">
            <div class="col-lg-12" id="prodinfo"></div>
        </div>
        <div class="col-lg-12 alert alert-success">
            <form onsubmit="refr();" action="index.php?route=production/writeoff&token=<?php echo $token_wo; ?>" method="post" class="form-group" id="formInvoice">
                    <div class="col-lg-12" id="wo-form" style="display: none;">
                        <div id="formwo" display='block'>
                            <div class="form-group col-lg-6">
                                <lable for="client" class="control-label">Покупатель<span style="color: #E83737;">*</span></lable>
                                <input type="text" class="form-control" name="client" id="client" placeholder="Введите покупателя"/>
                            </div>
                            <div class="form-group col-lg-6">
                                <lable for="city" class="control-label">Город покупателя<span style="color: #E83737;">*</span></lable>
                                <input type="text" class="form-control" name="city" id="city" placeholder="Введите город покупателя"/>
                            </div>
                            <div class="form-group col-lg-6">
                                <lable for="date" class="control-label">Дата покупки<span style="color: #E83737;">*</span></lable>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' class="form-control" name="date" id="date" placeholder="Введите дату покупки"/>
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <lable for="wherefrom" class="control-label">Откуда пришёл покупатель</lable>
                                <select class="form-control" name="wherefrom" id="wherefrom">
                                    <option value="Неизвестно">Неизвестно</option>
                                    <option value="юла">Юла</option>
                                    <option value="avito">avito</option>
                                    <option value="сайт">сайт</option>
                                    <option value="2gis">2gis</option>
                                    <option value="drom">drom</option>
                                    <option value="autoru">Auto.ru</option>
                                    <option value="vk">vk</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <td>Наименование</td>
                                <td>Внутренний номер</td>
                                <td>К-во на складе</td>
                                <td>Цена</td>
                                <td>Факт.цена</td>
                                <td>Факт.к-во</td>
                                <td>Причина уценки</td>
                                <td>#</td>
                            </tr>
                        </thead>
                        <tbody id="listProds"></tbody>
                    </table>
                    <button type="submit" id="subm" class="btn btn-info" style="display: none;">Списать</button>
                </form>
            </div>
    </div>
</div>   
    </div>
                <script type="text/javascript">
                    $(function () {
                        $('#datetimepicker1').datetimepicker();
                    });
                    $('#myTabs a').click(function (e) {
                        e.preventDefault()
                        $(this).tab('show')
                      })
                    $('#subm').click(function(){
                        if(document.getElementById('client').value!==''){
                            if(document.getElementById('city').value!==''){
                                if(document.getElementById('date').value!==''){
                                    alert('Нажмите ОК для продолжения');
                                } else{
                                    alert('Заполните поле ДАТА');
                                    return false;
                                }
                            } else {
                                alert('Заполните поле ГОРОД');
                                return false;
                            }
                        } else{
                            alert('Заполните поле ПОКУПАТЕЛЬ');
                            return false;
                        }
                        
                    });
                </script>
<?php echo $footer;?>