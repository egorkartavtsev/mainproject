  <div class="container-fluid" id="createautoform" num="createauto">      
      <div class="col-md-6 form-group-sm">
          <label>Марка</label>
          <select class="form-control" id="select-brand" select_type="librSelect" child="model">
              <option disabled selected>-</option>
              <?php foreach($brands as $mark){ ?>
                <option value="<?php echo $mark['id'];?>" <?php echo (isset($brand) && $brand==$mark['name'])?'selected':''?>><?php echo $mark['name'];?></option>
              <?php }?>
          </select>
      </div>
      <div class="col-md-6 form-group-sm" id="model">
          <label>Модель</label>
          <?php if(isset($model)){ ?>
            <select class="form-control" id="select-model">
              <option value="<?php echo $model['id'];?>"><?php echo $model['name'];?></option>
            </select>
          <?php } else { ?>
            <select class="form-control">
            </select>
          <?php }?>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Цвет</label>
          <select class="form-control" id="color">
              <option disabled selected>-</option>
              <?php foreach($colors as $col){ ?>
                <option value="<?php echo $col['name'];?>" <?php echo (isset($color) && $color==$col['name'])?'selected':''?>><?php echo $col['name'];?></option>
              <?php }?>
          </select>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Категория ТС</label>
          <select class="form-control" id="category">
              <option disabled selected>-</option>
              <?php foreach($categ as $cat){ ?>
                <option value="<?php echo $cat['name'];?>" <?php echo (isset($category) && $category==$cat['name'])?'selected':''?>><?php echo $cat['name'];?></option>
              <?php }?>
          </select>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Экогогический класс</label>
          <select class="form-control" id="eco_class">
              <option disabled selected>-</option>
              <?php foreach($eclass as $ec){ ?>
                <option value="<?php echo $ec['name'];?>" <?php echo (isset($eco_class) && $eco_class==$ec['name'])?'selected':''?>><?php echo $ec['name'];?></option>
              <?php }?>
          </select>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Тип ТС</label>
          <select class="form-control" id="ctype">
              <option disabled selected>-</option>
              <?php foreach($types as $type){ ?>
                <option value="<?php echo $type['name'];?>" <?php echo (isset($ctype) && $ctype==$type['name'])?'selected':''?>><?php echo $type['name'];?></option>
              <?php }?>
          </select>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Регистрационный номер</label>
          <input type="hidden" id="owner" class="form-control" value="<?php echo $owner;?>" />
          <?php if(isset($id)){ ?><input type="hidden" id="auto_id" class="form-control" value="<?php echo $id;?>" /><?php }?>
          <input type="text" id="numb" class="form-control" value="<?php echo isset($numb)?$numb:'' ?>"/>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Год выпуска</label>
          <select id="year" class="form-control">
              <option disabled selected>-</option>
              <?php for($i=(int)date("Y"); $i>1950; --$i){ ?>
                <option value="<?php echo $i;?>" <?php echo (isset($year) && $year==$i)?'selected':''?>><?php echo $i;?></option>
              <?php }?>
          </select>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Номер шасси</label>
          <input type="text" id="chassis" class="form-control" value="<?php echo isset($chassis)?$chassis:'' ?>"/>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Номер кузова</label>
          <input type="text" id="carcase" class="form-control" value="<?php echo isset($carcase)?$carcase:'' ?>"/>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Модель и номер двигателя</label>
          <input type="text" id="engine" class="form-control" value="<?php echo isset($engine)?$engine:'' ?>"/>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Мощность ДВС</label>
          <input type="text" id="dvs_power" class="form-control" value="<?php echo isset($dvs_power)?$dvs_power:'' ?>"/>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Рабочий объём</label>
          <input type="text" id="capacity" class="form-control" value="<?php echo isset($capacity)?$capacity:'' ?>"/>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>Пробег</label>
          <input type="text" id="kmeters" class="form-control" value="<?php echo isset($kmeters)?$kmeters:'' ?>"/>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>ПТС(серия номер)</label>
          <input type="text" id="pts" class="form-control" value="<?php echo isset($pts)?$pts:'' ?>"/>
      </div>
      <div class="col-md-6 form-group-sm">
          <label>СОР(серия номер)</label>
          <input type="text" id="sor" class="form-control" value="<?php echo isset($sor)?$sor:'' ?>"/>
      </div>
      <div class="col-md-6 form-group">
        <label>Дата выдачи ПТС:</label>
        <div class='input-group date' id='datetimepicker1'>
            <input type='text' class="form-control" id="datepts" value="<?php echo isset($datepts)?$datepts:'' ?>"/>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
      </div>
      <div class="col-md-6 form-group">
        <label>Дата выдачи СОР:</label>
        <div class='input-group date' id='datetimepicker2'>
            <input type='text' class="form-control" id="datesor" value="<?php echo isset($datepts)?$datepts:'' ?>"/>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
      </div>
      <div class="clearfix"><p></p></div>
      <div class="clearfix"></div>
      <div class="clearfix"><p></p></div>
      <button class="btn btn-block btn-success" btn_type="addAuto" data-dismiss="modal" client="<?php echo $owner;?>" aria-label="Close">прикрепить</button>
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
