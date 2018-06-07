  <div class="container-fluid">
      <div class="row">
          <form action="index.php?route=service/client/create&token=<?php echo $token;?>" method="POST">
              <h4>Общая информация: </h4>
                <div class="col-md-3 form-group">
                    <input type="hidden" name="legal" value="<?php echo $legal;?>">
                    <label>Название компании:</label>
                    <input class="form-control" type="text"  name="name">
                </div>
                <div class="col-md-3 form-group">
                    <label>ИНН</label>
                    <input class="form-control" type="text"  name="INN">
                </div>
                <div class="col-md-3 form-group">
                    <label>КПП</label>
                    <input class="form-control" type="text"  name="KPP">
                </div>
                <div class="col-md-3 form-group">
                    <label>ОГРН</label>
                    <input class="form-control" type="text"  name="OGRN">
                </div>
                <div class="clearfix"></div>
                
                <h4>Юридический адрес: </h4>
                <div class="col-md-3 form-group">
                    <label>Регион:</label>
                    <input data-toggle="dropdown"  aria-expanded="true" type="adress" target-level="29" target-child="larea" target-kladr="0" class="form-control" type="text"  name="lregion">
                    <ul class="dropdown-menu dropdown-menu-left" id="lregion">
                        <li class="dropdown-header">Выберите значение:</li>
                    </ul>
                </div>
                <div class="col-md-3 form-group">
                    <label>Район/Город:</label>
                    <input type="adress" target-level="30" target-child="lcity" target-kladr="none" class="form-control" type="text"  name="larea">
                </div>
                <div class="col-md-3 form-group">
                    <label>Населённый пункт:</label>
                    <input type="adress" target-level="31" target-child="lstreet" target-kladr="none" class="form-control" type="text"  name="lcity">
                </div>
                <div class="col-md-3 form-group">
                    <label>Улица:</label>
                    <input type="adress" target-level="32" target-child="0" target-kladr="none" class="form-control" type="text"  name="lstreet">
                </div>
                <div class="col-md-3 form-group">
                    <label>Дом, квартира/офис:</label>
                    <input class="form-control" type="text" name="lhome">
                </div>
                <div class="clearfix"></div>
                
                <h4>Фактический адрес: </h4>
                <div class="col-md-3 form-group">
                    <label>Регион:</label>
                    <input data-toggle="dropdown" aria-expanded="true" type="adress" target-level="29" target-child="farea" target-kladr="0" class="form-control" type="text"  name="fregion">
                    <ul class="dropdown-menu dropdown-menu-left" id="fregion">
                        <li class="dropdown-header">Выберите значение:</li>
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
                    <label>Телефон 1(без "8" и слитно. Н-р: 951458...):</label>
                    <input class="form-control" type="text" name="phone1">
                </div>
                <div class="col-md-3 form-group">
                    <label>Телефон 2(без "8" и слитно. Н-р: 951458...):</label>
                    <input class="form-control" type="text" name="phone2">
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"><p></p></div>
                <input type="submit" class="btn btn-success" value="Сохранить">
          </form>
      </div>
  </div>
