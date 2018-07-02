  <div class="container-fluid" id="createcontract" num="createauto">
      <div class="col-md-6 form-group-sm">
          <label>Тип обращения</label>
          <select class="form-control" id="select-handl" select_type="librSelect" child="subagent">
              <option disabled selected>-</option>
              <?php foreach($handl_types as $htype){ ?>
                <option value="<?php echo $htype['id'];?>" <?php echo (isset($handl_type) && $handl_type==$htype['name'])?'selected':''?>><?php echo $htype['name'];?></option>
              <?php }?>
          </select>
      </div>
      <div class="col-md-6 form-group-sm" id="subagent">
          <label>Контрагент</label>
          <?php if(isset($model)){ ?>
            <select class="form-control" id="select-model">
              <option value="<?php echo $model['id'];?>"><?php echo $model['name'];?></option>
            </select>
          <?php } else { ?>
            <select class="form-control">
            </select>
          <?php }?>
      </div>
      <div class="col-md-6 form-group-sm" id="serv_type">
          <label>Услуга</label>
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
        <label>&nbsp;</label>
        <?php if(isset($contract)){ ?><a class="col-md-6 btn btn-success btn-block btn-sm">ДОГОВОР</a><?php }?>
      </div>
      <div class="clearfix"></div>
      <div class="form-group-sm">
          <label>Примечание к услуге:</label>
          <input type="text" class="form-control" id="contract-note" value="" />
          <input type="hidden" class="form-control" id="contract-handling" value="<?php echo $handling;?>" />
      </div>
      <button btn_type="addcontract" class="col-md-6 btn btn-success btn-block btn-sm">СОХРАНИТЬ</button>
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
