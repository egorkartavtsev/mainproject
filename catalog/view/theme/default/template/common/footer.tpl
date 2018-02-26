<footer>
  <div class="container">
    <p style="text-align: center;"><?php echo $powered; ?></p>
  </div>
</footer>
<div class="modal fade" id="wapp_viber" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Viber</h4>
      </div>
      <div class="modal-body">
        Чтобы передать нам изображения деталей либо другую необходимую информацию, 
        используйте программу Viber. Контактный телефон: +7 (912) 475-08-70
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>
<!--<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>-->
<script type="text/javascript">
    VK.Widgets.Group("vk_groups", {mode: 3}, 91414223);
    function fillSearchField($id){
        var text = $("#srcItem"+$id).text();
        $("#srcField").val(text);
        $('header #search input[name=\'search\']').parent().find('button').trigger('click');
    }
</script>
<style>
    .srcItem{
        padding: 3px 15px;
        cursor: pointer;
    }
    .srcItem:hover{
        padding: 5px 15px;
        background: #cccccc;
        color: #444040;
    }
</style>

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'mQhzsRiHsA';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->
</body></html>