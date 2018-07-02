    <div class="container-fluid">
        <div class="form-group">
            <label>Дата ДТП</label>
            <div class='input-group date' id='datetimepicker1'>
                <input type='text' name="date" class="form-control" value="<?php echo isset($date)?date('d.m.Y', strtotime($date)):''; ?>"/>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label>Обстоятельства ДТП: </label>
            <textarea type="text" name="descript" class="form-control"><?php echo isset($descript)?$descript:''; ?></textarea>
        </div>
        <div class="form-group">
            <label>Повреждения: </label>
            <textarea type="text" name="damage" class="form-control"><?php echo isset($damage)?$damage:''; ?></textarea>
        </div>
        <div class="form-group-sm col-md-6">
            <label>Выберите ОСАГО потерпевшего</label>
            <select class="form-control" name="vict_insurence">
                <?php foreach($insurences as $ins){ ?>
                    <option value="<?php echo $ins['name'];?>"><?php echo $ins['name'];?></option>
                <?php }?>
            </select>
        </div>
        <div class="form-group-sm col-md-6">
            <label>Статус ОСАГО потерпевшего</label>
            <select class="form-control" name="try_vict_ins">
                <option value="Действителен">Действителен</option>
                <option value="Недействителен">Недействителен</option>
                <option value="Не проверялся">Не проверялся</option>
            </select>
        </div>
        <h4>Виновник ДТП: </h4>
        <div class="form-group-sm col-md-6">
            <label>Фамилия виновника ДТП: </label>
            <input type="text" name="sname_causer" class="form-control" value="<?php echo isset($sname_causer)?$sname_causer:''; ?>">
        </div>
        <div class="form-group-sm col-md-6">
            <label>Имя виновника ДТП</label>
            <input type="text" name="fname_causer" class="form-control" value="<?php echo isset($fname_causer)?$fname_causer:''; ?>">
        </div>
        <div class="form-group-sm col-md-6">
            <label>Отчество виновника ДТП</label>
            <input type="text" name="patr_causer" class="form-control" value="<?php echo isset($patr_causer)?$patr_causer:''; ?>">
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6 form-group-sm">
            <label>Регион:</label>
            <input data-toggle="dropdown" value="<?php echo isset($causer_reg)?$causer_reg:''; ?>" name="causer_reg"  aria-expanded="true" type="adress" target-level="29" target-child="causer_area" target-kladr="0" class="form-control" type="text">
            <ul class="dropdown-menu dropdown-menu-left" id="lregion">
                <li class="dropdown-header">Выберите значение:</li>
            </ul>
        </div>
        <div class="col-md-6 form-group-sm">
            <label>Район/Город:</label>
            <input type="adress" target-level="30" value="<?php echo isset($causer_area)?$causer_area:''; ?>" name="causer_area" target-child="causer_city" target-kladr="none" class="form-control" type="text">
        </div>
        <div class="col-md-6 form-group-sm">
            <label>Населённый пункт:</label>
            <input type="adress" target-level="31" value="<?php echo isset($causer_city)?$causer_city:''; ?>" name="causer_city" target-child="causer_street" target-kladr="none" class="form-control" type="text">
        </div>
        <div class="col-md-6 form-group-sm">
            <label>Улица:</label>
            <input type="adress" target-level="32" value="<?php echo isset($causer_street)?$causer_street:''; ?>" name="causer_street" target-child="0" target-kladr="none" class="form-control" type="text">
        </div>
        <div class="col-md-6 form-group-sm">
            <label>Дом, квартира/офис:</label>
            <input class="form-control" type="text" value="<?php echo isset($causer_home)?$causer_home:''; ?>" name="causer_home" value="<?php echo isset($causer_home)?$causer_home:''; ?>">
        </div>
        <div class="clearfix"></div>
        <div class="form-group col-md-6">
            <label>Выберите ОСАГО виновника ДТП</label>
            <select class="form-control" name="causer_ins">
                <?php foreach($insurences as $ins){ ?>
                    <option value="<?php echo $ins['name'];?>"><?php echo $ins['name'];?></option>
                <?php }?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>ОСАГО виновника(годен до)</label>
            <div class='input-group date' id='datetimepicker6'>
                <input type='text' name="causer_ins_date" class="form-control" value="<?php echo isset($causer_ins_date)?date('d.m.Y H:i', strtotime($causer_ins_date)):''; ?>"/>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="clearfix"></div>
        <h4>Данные владельца виновного ТС: </h4>
        <div class="form-group-sm col-md-6">
            <label>Фамилия владельца виновного ТС: </label>
            <input type="text" name="c_owner_sname" class="form-control" value="<?php echo isset($c_owner_sname)?$c_owner_sname:''; ?>">
        </div>
        <div class="form-group-sm col-md-6">
            <label>Имя владельца виновного ТС</label>
            <input type="text" name="c_owner_fname" class="form-control" value="<?php echo isset($c_owner_fname)?$c_owner_fname:''; ?>">
        </div>
        <div class="form-group-sm col-md-6">
            <label>Отчество владельца виновного ТС</label>
            <input type="text" name="c_owner_patr" class="form-control" value="<?php echo isset($c_owner_patr)?$c_owner_patr:''; ?>">
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6 form-group-sm">
            <label>Регион:</label>
            <input data-toggle="dropdown" value="<?php echo isset($c_owner_reg)?$c_owner_reg:''; ?>" aria-expanded="true" type="adress" target-level="29" target-child="c_owner_area" target-kladr="0" class="form-control" type="text" name="c_owner_reg">
            <ul class="dropdown-menu dropdown-menu-left" id="fregion">
                <li class="dropdown-header">Выберите значение:</li>
            </ul>
        </div>
        <div class="col-md-6 form-group-sm">
            <label>Район/Город:</label>
            <input type="adress" value="<?php echo isset($c_owner_area)?$c_owner_area:''; ?>" target-level="30" target-child="c_owner_city" target-kladr="none" class="form-control" type="text"  name="c_owner_area">
        </div>
        <div class="col-md-6 form-group-sm">
            <label>Населённый пункт:</label>
            <input type="adress" value="<?php echo isset($c_owner_city)?$c_owner_city:''; ?>" target-level="31" target-child="c_owner_street" target-kladr="none" class="form-control" type="text"  name="c_owner_city">
        </div>
        <div class="col-md-6 form-group-sm">
            <label>Улица:</label>
            <input type="adress" value="<?php echo isset($c_owner_street)?$c_owner_street:''; ?>" target-level="32" target-child="0"  target-kladr="none" class="form-control" type="text"  name="c_owner_street">
        </div>
        <div class="col-md-6 form-group-sm">
            <label>Дом, квартира/офис:</label>
            <input class="form-control" type="text" name="c_owner_home" value="<?php echo isset($c_owner_home)?$c_owner_home:''; ?>">
        </div>
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="form-group col-md-6">
            <label>Протокол</label>
            <input type="text" name="protocol" class="form-control" value="<?php echo isset($protocol)?$protocol:''; ?>">
        </div>
        <div class="form-group col-md-6">
            <label>Дата протокола</label>
            <div class='input-group date' id='datetimepicker2'>
                <input type='text' name="dateprot" class="form-control" value="<?php echo isset($dateprot)?date('d.m.Y', strtotime($dateprot)):''; ?>"/>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>Постановление</label>
            <input type="text" name="decree" class="form-control" value="<?php echo isset($decree)?$decree:''; ?>">
        </div>
        <div class="form-group col-md-6">
            <label>Дата постановления</label>
            <div class='input-group date' id='datetimepicker3'>
                <input type='text' name="datedecree" class="form-control" value="<?php echo isset($datedecree)?date('d.m.Y', strtotime($datedecree)):''; ?>"/>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>Справка ГИБДД</label>
            <input type="text" name="reference" class="form-control" value="<?php echo isset($reference)?$reference:''; ?>">
        </div>
        <div class="form-group col-md-6">
            <label>Дата выдачи справки ГИБДД</label>
            <div class='input-group date' id='datetimepicker4'>
                <input type='text' name="dateref" class="form-control" value="<?php echo isset($dateref)?date('d.m.Y', strtotime($dateref)):''; ?>"/>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>Определение ГИБДД</label>
            <input type="text" name="specif" class="form-control" value="<?php echo isset($specif)?$specif:''; ?>">
        </div>
        <div class="form-group col-md-6">
            <label>Дата выдачи определения ГИБДД</label>
            <div class='input-group date' id='datetimepicker5'>
                <input type='text' name="datespec" class="form-control" value="<?php echo isset($datespec)?date('d.m.Y', strtotime($datespec)):''; ?>"/>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group-sm">
            <label>Коммисар</label>
            <select class="form-control" name="vict_insurence">
                <?php foreach($commissars as $com){ ?>
                    <option value="<?php echo $com['name'];?>"><?php echo $com['name'];?></option>
                <?php }?>
            </select>
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
