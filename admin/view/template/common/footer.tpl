<footer id="footer"><?php echo $text_footer; ?><br /><?php echo $text_version; ?></footer></div>
<script type="text/javascript">
    $('#panel-body-f div div div input[name*=\'filter\']').on('keydown', function(e) {
            if (e.keyCode == 13) {
                    $('#button-filter').trigger('click');
            }
    });
</script>
</body></html>
