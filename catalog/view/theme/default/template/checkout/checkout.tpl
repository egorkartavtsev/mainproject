<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row">
      
      <div id="orderInfo">
          <div class="col-md-4 well well-sm" step="1">
              <h4>Шаг 1: Информация о покупателе</h4>
              <div class="form-group">
                  <label>Фамилия:</label>
                  <input class="form-control" field_type="char" target_req="1" name="secondname"/>
              </div>
              <div class="form-group">
                  <label>Имя:</label>
                  <input class="form-control" field_type="char" target_req="1" name="firstname" />
              </div>
              <div class="form-group">
                  <label>Отчество:</label>
                  <input class="form-control" field_type="char" target_req="1" name="patron"/>
              </div>
              <div class="form-group">
                  <label>Телефон:</label>
                  <input class="form-control" field_type="varchar" name="telephone" target_req="1" placeholder="без восьмёрки. слитно"/>
              </div>
              <div class="form-group">
                  <label>Email:</label>
                  <input class="form-control" field_type="email" target_req="0" name="email"/>
              </div>
              <?php echo $captcha;?>
              <button class="btn btn-danger" btn_type="nextstep" disabled>Продолжить</button>
          </div>
          <div class="col-md-4 well well-sm hidden" step="2">
              <h4>Шаг 2: информация о доставке</h4>
              <div class="form-group">
                  <label>Регион:</label>
                  <input class="form-control" field_type="varchar" target_req="1" name="region" class="form-control" />
              </div>
              <div class="form-group">
                  <label>Населённый пункт:</label>
                  <input class="form-control" field_type="varchar" target_req="1" name="city" class="form-control" />
              </div>
              <div class="form-group">
                  <label>Адрес(Улица, Дом, квартира):</label>
                  <input class="form-control" field_type="varchar" target_req="1" name="street" class="form-control" />
              </div>
              <button class="btn btn-danger" btn_type="nextstep" disabled>Продолжить</button>
          </div>
          <div class="col-md-4 well well-sm hidden" step="3">
              <h4>Шаг 3: важная информация!</h4>
              <div>
                <p>Способы оплаты: </p>
                <ul>
                 <li> На карту Сбербанк </li>
                 <li> На карту Альфа-Банк </li>
                 <li> На карту ВТБ24 </li>
                </ul>
                <p>Оплата наличными возможна при получении товара путём самовывоза со склада</p>
                <p>(номер карты для оплаты за товар предоставляется на email или смс после оформления заказа)</p> 
                <p>Адрес и телефон с которого должна прийти информация по оплате </p>
                <p>e-mail: autorazbor174@mail.ru </p>
                <p>телефон: +7 (912) 475 08 70 +7 (951) 122 56 39 </p>
                <p>БУДЬТЕ ВНИМАТЕЛЬНЫ.</p>
                <p>ВНИМАНИЕ!!! С ДРУГИХ ТЕЛЕФОНОВ И E-MAIL МЫ НЕ ОТПРАВЛЯЕМ ИНФОРМАЦИЮ.</p>
              </div>
              <button class="btn btn-danger" btn_type="laststep">Оформить</button>
          </div>
      </div>
      
  </div>
</div>
<?php echo $footer; ?>
