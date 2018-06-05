  <div class="container-fluid">
      <div class="row">
          <form action="index.php?route=service/client/create&token=<?php echo $token;?>" method="POST">
                <h4>Данные о клиенте: </h4>
                    <div class="col-md-3 form-group">
                        <input type="hidden" name="legal" value="<?php echo $legal;?>">
                        <label>Фамилия:</label>
                        <input class="form-control" type="text"  name="secondname">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Имя:</label>
                        <input class="form-control" type="text"  name="firstname">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Отчество:</label>
                        <input class="form-control" type="text"  name="patronymic">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Дата рождения:</label>
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' class="form-control" name="bdate"/>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
                    </div>
                <div class="clearfix"></div>
                <h4>Паспорт: </h4>
                    <div class="col-md-3 form-group">
                        <label>Серия Номер:</label>
                        <input class="form-control" type="text"  name="numpas">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Кем выдан:</label>
                        <input class="form-control" type="text"  name="officepas">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Дата выдачи:</label>
                        <div class='input-group date' id='datetimepicker2'>
                            <input type='text' class="form-control date" name="datepas"/>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
                    </div>
                
                <div class="clearfix"></div>
                <h4>Водительское удостоверение: </h4>
                    <div class="col-md-3 form-group">
                        <label>Серия Номер:</label>
                        <input class="form-control" type="text"  name="dlicense">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Дата выдачи:</label>
                        <div class='input-group date' id='datetimepicker2'>
                            <input type='text' class="form-control date" name="datedlicense"/>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
                    </div>
                
                <div class="clearfix"></div>
                <h4>Адрес регистрации: </h4>
                    <div class="col-md-3 form-group">
                        <label>Регион:</label>
                        <input data-toggle="dropdown" aria-expanded="true" type="adress" target-level="29" target-child="farea" target-kladr="0" class="form-control" type="text"  name="fregion">
                        <ul class="dropdown-menu dropdown-menu-left" id="fregion">
                            <li class="dropdown-header">Введите значение</li>
                        </ul>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Район/Город:</label>
                        <input type="adress" target-level="30" target-child="fcity"  target-kladr="none" class="form-control" type="text"  name="farea">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Населённый пункт:</label>
                        <input type="adress" target-level="31" target-child="fstreet"  target-kladr="none" class="form-control" type="text"  name="fcity">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Улица:</label>
                        <input type="adress" target-level="32" target-child="0"  target-kladr="none" class="form-control" type="text"  name="fstreet">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Дом, квартира/офис:</label>
                        <input class="form-control" type="text" name="fhome">
                    </div>
                    <div class="clearfix"></div>
                <h4>Контакты: </h4>
                    <div class="col-md-3 form-group">
                        <label>Телефон 1:</label>
                        <input class="form-control" type="text" name="phone1">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Телефон 2:</label>
                        <input class="form-control" type="text" name="phone2">
                    </div>
                <div class="clearfix"></div>
                <div class="clearfix"><p></p></div>
                <input type="submit" class="btn btn-success" value="Сохранить">
          </form>
      </div>
  </div>
<script type="text/javascript">
    $(function () {
        $(document).find("[id*='datetimepicker']").each(function(){
            $(this).datetimepicker();
        })
        $('.date').datetimepicker({
			pickTime: false
		});
    });
</script>
