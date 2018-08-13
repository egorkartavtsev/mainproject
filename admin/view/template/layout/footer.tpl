<?php echo $product_modal; ?>
<div class="modal fade" id="persModal" tabindex="-1" role="dialog" aria-labelledby="persInfoModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="persInfoLabel">Важное</h4>
      </div>
      <div class="modal-body" id="persInfo">
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<footer id="footer"><?php echo $text_footer; ?><br /><?php echo $text_version; ?></footer></div>
<script type="text/javascript">
    $('#panel-body-f div div div input[name*=\'filter\']').on('keydown', function(e) {
            if (e.keyCode == 13) {
                    $('#button-filter').trigger('click');
            }
    });
</script>
</body></html>
