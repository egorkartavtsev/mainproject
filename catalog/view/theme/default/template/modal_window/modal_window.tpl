<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Вы можете заполнить форму ниже и наши менеджеры свяжутся с Вами.</h4>
      </div>
        <form method='POST' action="">
          <div class="modal-body">
            <div class="form-group">
              <label >Ваше имя: </label>
              <input type="text" class="form-control" name='name' placeholder="Имя...">
            </div>
            <div class="form-group">
              <label >Ваш e-mail: </label>
              <input type="email" class="form-control" name='email' placeholder="E-mail...">
            </div>
            <div class="form-group">
              <label >Телефон: </label>
              <input type="text" class="form-control" name='phone' placeholder="Телефон...">
            </div>
             <label>Комментарий: </label>
              <textarea class="form-control" name='comment' placeholder="Комментарий к заявке..."></textarea>
            </div>
              <input type="hidden" name="suc" value="1" />
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
              <button type="submit" class="btn btn-danger">Отправить</button>
            </div>
          </form>
    </div>   
  </div>
</div>
<script type='text/javascript'>
      $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
      })
</script>