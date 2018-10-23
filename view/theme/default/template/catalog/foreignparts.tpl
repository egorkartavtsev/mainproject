<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb hidden">
       <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <div class="row">
        <div id="content" class="col-sm-12">
            <div id="motorland_div"></div>
            <script type="text/javascript">
              (function () {
                var h = document.getElementsByTagName("head")[0];
                var s = document.createElement("script");
                s.type = "text/javascript";
                s.async = true;
                s.src = "http://motorlandby.ru/UISite/wd.js";
                h.appendChild(s);
              })();
            </script>
        </div>  
    </div>
</div>
<?php echo $footer; ?>