<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row">
      <div id="content" class="col-lg-12">
          <h1><?php echo $heading_title; ?></h1>
          <p><?php echo $text_account_already; ?></p>
          
          <div action="<?php echo $action; ?>" id="formRegist" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div id="account" class="col-md-4">
              <legend>Расскажите о себе</legend>
              <div class="well well-lg">
                  <div class="form-group">
                    <label for="input-firstname">Имя: </label>
                    <input type="text" name="firstname" id="input-firstname" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label for="input-lastname">Фамилия:</label>
                    <input type="text" name="lastname" id="input-lastname" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label for="input-patron">Отчество:</label>
                    <input type="text" name="patron" id="input-patron" class="form-control" />
                  </div>
                  <div class="form-group required">
                    <label for="input-email">Email:</label>
                    <input type="email" name="email" id="input-email" class="form-control" />
                  </div>
                  <div class="form-group required">
                    <label for="input-telephone">Телефон: </label>
                    <input type="tel" name="telephone" id="input-telephone" class="form-control" />
                  </div>
                  <?php echo $captcha; ?>
                  <div class="form-group">
                    <!-- Large modal -->
                    <p> Нажимая на кнопу "Продолжить" вы даете <a data-toggle="modal" data-target=".bd-example-modal-lg">согласие на обработку персональных данных.</a></p>
                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Соглашение на обработку персональных данных.</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Предоставляя свои персональные данные Покупатель даёт согласие на обработку, хранение и использование своих персональных данных на основании ФЗ № 152-ФЗ «О персональных данных» от 27.07.2006 г. в следующих целях: 
                                        <ul>
                                            <li>Регистрации Пользователя на сайте</li>  
                                            <li>Осуществление клиентской поддержки</li>  
                                            <li>Получения Пользователем информации о маркетинговых событиях</li>  
                                            <li>Выполнение Продавцом обязательств перед Покупателем</li>  
                                            <li>Проведения аудита и прочих внутренних исследований с целью повышения качества предоставляемых услуг.</li>  
                                        </ul>     
                                    Под персональными данными подразумевается любая информация личного характера, позволяющая установить личность Покупателя такая как: 
                                        <ul>
                                            <li>Фамилия, Имя, Отчество</li> 
                                            <li>Дата рождения</li> 
                                            <li>Контактный телефон</li> 
                                            <li>Адрес электронной почты</li> 
                                            <li>Почтовый адрес</li>
                                        </ul>    
                                        Персональные данные Покупателей хранятся исключительно на электронных носителях и обрабатываются с использованием автоматизированных систем, за исключением случаев, когда неавтоматизированная обработка персональных данных необходима в связи с исполнением требований законодательства. 
                                        Продавец обязуется не передавать полученные персональные данные третьим лицам, за исключением следующих случаев:
                                        <ul>
                                            <li>По запросам уполномоченных органов государственной власти РФ только по основаниям и в порядке, установленным законодательством РФ</li>  
                                            <li>Стратегическим партнерам, которые работают с Продавцом для предоставления продуктов и услуг, или тем из них, которые помогают Продавцу реализовывать продукты и услуги потребителям. Мы предоставляем третьим лицам минимальный объем персональных данных, необходимый только для оказания требуемой услуги или проведения необходимой транзакции.</li>  
                                        </ul>     
                                        Продавец оставляет за собой право вносить изменения в одностороннем порядке в настоящие правила, при условии, что изменения не противоречат действующему законодательству РФ. Изменения условий настоящих правил вступают в силу после их публикации на Сайте. 
                                    </p>
                               </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-danger" disabled id="nextStep" aria-data="address">Продолжить</button>
                  </div>
              </div>
            </div>
            <div id="address" class="col-md-4" style="display: none;">
              <legend>Укажите информацию о доставке</legend>
              <div class="well well-lg">
                  <div class="form-group">
                    <label for="input-zone"><?php echo $entry_zone; ?></label>
                    <input type="text" name="zone" id="input-zone" disabled class="form-control" />
                  </div>
                  <div class="form-group">
                    <label for="input-city"><?php echo $entry_city; ?></label>
                    <input type="text" name="city" id="input-city" disabled class="form-control" />
                  </div>
                  <div class="form-group">
                    <label for="input-address"><?php echo $entry_address_1; ?></label>
                    <input type="text" name="address" id="input-address" disabled class="form-control" />
                  </div>
                  <div class="form-group">
                    <button class="btn btn-danger" id="nextStep" aria-data="password">Продолжить</button>
                  </div>
              </div>
            </div>
            <div id="password" class="col-md-4" style="display: none;">
              <legend>Придумайте пароль</legend>
              <div class="well well-lg">
                  <div class="form-group required">
                    <label for="input-password">Введите пароль: </label>
                    <input type="password" data-type="latin" name="password" id="input-password" disabled class="form-control" />
                  </div>
                  <p class="alert alert-info">Пароль должен состоять из букв латинского алфавита и цифр.<br>Пароль не должен содержать специальных символов и знаков пробела.</p>
                  <div class="form-group required" disabled>
                    <label for="input-confirm">Подтвердите пароль:</label>
                    <input type="password" data-type="latin" name="confirm" id="input-confirm" disabled class="form-control" />
                  </div>
                  <div class="form-group">
                    <button class="btn btn-danger" disabled id="lastStep">Продолжить</button>
                  </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '#formRegist #nextStep', function(){
            var allow = false;
            var btn = $(this);
            $(this).parent().parent().find('input').each(function(){
                if($(this).parent().hasClass('required') && $(this).val()===''){
                    $(this).parent().addClass('invalid');
                    alert('Заполните поле '+$(this).parent().find('label').text()+'!');
                    allow = false;
                } else {
                    $(this).parent().removeClass('invalid');
                    allow = true;
                }
            });
            if(allow){
                var email = $(this).parent().parent().find('[name=email]');
                $.ajax({
                    url: 'index.php?route=account/register/checkEmail',
                    method: 'post',
                    data:{email: email.val()},
                    success:function(data){
                        if(!data){
                            allow = false;
                            alert('На этот адрес электронной почты уже зарегистрирован аккаунт. Если Вы забыли пароль, то воспользуйтесь формой восстановления пароля.');
                        } else {
                            $(document).find('#'+btn.attr('aria-data')).find('input').each(function(){
                                $(this).removeAttr('disabled');
                            });
                            btn.parent().parent().find('input').each(function(){
                                $(this).attr('disabled', 'true');
                            });
                            $('#'+btn.attr('aria-data')).show('slow');
                            $(document).find('#input-confirm').attr('disabled', 'disabled');
                            btn.parent().remove();
                        }
                    }
                });
            }
        });
        $(document).on('input', '#formRegist #input-password', function(){
            $('#errorPass').remove();
            $(this).parent().parent().find('#input-confirm').attr('disabled', 'disabled');
            if($(this).val().length>=6){
                if(validation($(this).attr('data-type'), $(this).val())){
                    $(this).parent().parent().find('#input-confirm').removeAttr('disabled');
                } else {
                    $(this).after('<p class="alter alert-danger" id="errorPass">Пароль содержит запрещённые символы!</p>');
                    $(this).parent().parent().find('#lastStep').attr('disabled', 'true');
                }
            } else {
                $(this).after('<p class="alter alert-danger" id="errorPass">Пароль слишком короткий!</p>');
                $(this).parent().parent().find('#lastStep').attr('disabled', 'true');
            }
        });
        $(document).on('input', '#formRegist #input-confirm', function(){
            $('#errorConf').remove();
            if($(this).val() === $(this).parent().parent().find('#input-password').val()){
                $(this).parent().parent().find('#lastStep').removeAttr('disabled');
            } else {
                $(this).parent().parent().find('#lastStep').attr('disabled', 'true');
                $(this).after('<p class="alter alert-danger" id="errorConf">Введённые пароли не совпадают!</p>');
            }
        });
        $(document).on('click', '#formRegist #lastStep', function(){
            var form = '';
            $("#formRegist").find('input').each(function(){
                if($(this).val()!==''){
                    form = form + $(this).attr('name')+ '=' + $(this).val() + ';';
                }
            });
            $.ajax({
                url: 'index.php?route=account/register/createAccount',
                method: 'post',
                data:{form:form},
                success:function(data){
                    if(data){
                        location.href = 'index.php?route=account/login';                        
                    } else {
                        alert('Возникла ошибка на сервере. Пожалуйста повторите попытку. Надеемся на Ваше понимание.');
                    }
                }
            });
        })
    });
</script>
<?php echo $footer; ?>