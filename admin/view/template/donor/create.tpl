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
        <form action="<?php echo $action;?>" method="post" id="donorform" enctype="multipart/form-data">
            
            <div class="col-md-3">
                <div class="form-group">
                    <label for="number">Внутренний номер</label>
                    <input class="form-control" name="number" id="number" type="text" />
                </div>
                <div class="form-group">
                    <label for="cuzov">Выберите Тип кузова</label>
                    <select class="form-control" id="cuzov" name="cuzov">
                        <option value="седан" >седан</option>
                        <option value="хетчбек">хетчбек</option>
                        <option value="универсал">универсал</option>
                        <option value="купе">купе</option>
                        <option value="внедорожник">внедорожник</option>
                        <option value="кроссовер">кроссовер</option>
                        <option value="пикап">пикап</option>
                        <option value="минивэн">минивэн</option>
                        <option value="лифтбек">лифтбек</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="vin">VIN</label>
                    <input class="form-control" name="vin" id="vin" type="text" />
                </div>
                <div class="form-group">
                    <label for="price">Стоимость</label>
                    <input class="form-control" name="price" id="price" type="text" />
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label for="brand">Выберите марку</label>
                    <select class="form-control" name="brand_id" id='brand' onchange='
                        ajax({
                            url:"index.php?route=common/addprod/get_model&token=<?php echo $token_add; ?>",
                            statbox:"status",
                            method:"POST",
                            data:
                            {
                                           brand: document.getElementById("brand").value,
                                           token: "<?php echo $token_add; ?>"
                            },
                           success:function(data){document.getElementById("model1").innerHTML=data;}
                        })
                    '>
                        <option selected="selected" disabled="disabled">Выберите марку</option>
                        <?php foreach ($brands as $brand) { ?>
                            <option value="<?php echo $brand['val']; ?>"><?php echo $brand['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dvs">ДВС</label>
                    <input class="form-control" name="dvs" id="dvs" type="text" />
                </div>
                <div class="form-group">
                    <label for="color">Цвет кузова</label>
                    <input class="form-control" name="color" id="color" type="text" />
                </div>
                <div class="form-group" >
                    <label for="photos">Фотографии</label>
                    <input name="photo[]" class="form-control" id="photos" type="file" multiple="true">
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label for="model1">Выберите модель</label>
                    <div id="model1"><select class="form-control"></select></div>
                </div>
                <div class="form-group">
                    <label for="year">Год выпуска</label>
                    <input class="form-control" name="year" id="year" type="text" />
                </div>
                <div class="form-group">
                    <label for="trans">Трансмиссия</label>
                    <select class="form-control" id="trans" name="trans">
                        <option value="MT">MT</option>
                        <option value="AT">AT</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="note">Примечание</label>
                    <input class="form-control" name="note" id="note" type="text" />
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label for="model_row">Выберите модель</label>
                    <div id="model_row"><select class="form-control"></select></div>
                </div>
                <div class="form-group">
                    <label for="kilometers">Пробег</label>
                    <input class="form-control" name="kilometers" id="kilometers" type="text" />
                </div>
                <div class="form-group">
                    <label for="privod">Привод</label>
                    <select class="form-control" id="privod" name="privod">
                        <option value="2WD">2WD</option>
                        <option value="4WD">4WD</option>
                    </select>
                </div>
            </div>
                        <div class="clearfix"></div>
                        <div class="clearfix"></div>
        </form>
        <button id="donorSubmit" class="btn btn-success col-md-3">Сохранить</button>
    </div>
</div>
<script src="view/javascript/donor.js"></script>
<script type="text/javascript">
    $("#donorSubmit").on("click", function(){
        validateDonorForm();
    });
</script>
<?php echo $footer;?>