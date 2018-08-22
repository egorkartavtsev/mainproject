<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<link rel="shortcut icon" href="../image/icoadm.png" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js?t=<?php echo(microtime(true)); ?>"></script>
<script type="text/javascript" src="view/javascript/complect.js?t=<?php echo(microtime(true)); ?>"></script>
<script type="text/javascript" src="view/javascript/tools.js?t=<?php echo(microtime(true)); ?>"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="view/javascript/jquery/datetimepicker/locale/<?php echo $code; ?>.js" type="text/javascript"></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/stylesheet.css?t=<?php echo(microtime(true)); ?>" rel="stylesheet" media="screen" />
<?php foreach ($styles as $style) { ?>
<link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="view/javascript/common.js?t=<?php echo(microtime(true)); ?>" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter46331919 = new Ya.Metrika({
                    id:46331919,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js?t=<?php echo(microtime(true)); ?>";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/46331919" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</head>
<body>
<div id="container">
<header id="header" class="navbar navbar-static-top">
  <div class="navbar-header">
    <?php if ($logged) { ?>
    <a type="button" id="button-menu" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
    <div class="hidden-xs pull-left">
        <div class="col-sm-12 ">
        <img src="<?php echo $logo;?>" style="margin-right: 15px;" class="pull-left">
            <?php foreach($fcItems as $fc){ ?>
            <a class="btn btn-success" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo $fc['text'];?>" href="<?php echo $fc['href'];?>"><i class="fa <?php echo $fc['icon'];?>"></i></a> 
            <?php }?>
            <a href="index.php?route=setting/fastCallMenu&token=<?php echo $ses_token;?>" class="btn btn-info">настроить</a>
            
        </div>
    </div>
        <?php if(isset($notice['order'])){ 
                echo $notice['order'];
            }  ?>
        <?php if(isset($notice['avito'])){ 
                echo $notice['avito'];
            }  ?>
    <?php }  ?>
   </div>
  <?php if ($logged) { ?>
    <ul class="nav pull-right">
        <li class="dropdown">
            <div class="pull-left form-inline dropdown-toggle" data-toggle="dropdown" style="padding: 9px 10px;">
                <div class="form-group-sm">
                     <label for="searchInp"><i class="fa fa-search fa-lg"></i> Быстрый поиск по каталогу</label>
                     <input type="text" class="form-control" oninput="searchingProds('<?php echo $ses_token; ?>');" id="searchInp">
                 </div>
           </div>
            
            <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
                <li class="dropdown-header" style="max-width: 100%;">
                    <label for="searchInp">Результаты поиска</label>
                </li>
                <li id="headSearchBox" style="overflow-y: scroll; max-height: 300px;">Введите подкатегорию/марку/модель</li>
            </ul>
        </li>
    <li class="dropdown"><a href="<?php echo $stores[0]['href']; ?>" target="_blank"><i class="fa fa-home fa-lg"></i></a></li>
    <li><a href="<?php echo $logout; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span> <i class="fa fa-sign-out fa-lg"></i></a></li>
  </ul>
  <?php } ?>
  <?php if(isset($notices)){echo $notices;} ?>
</header>
<div class="modal fade bs-example-modal-lg" id="persModal" tabindex="-1" role="dialog" aria-labelledby="persInfoModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="persInfoLabel">Важное</h4>
      </div>
      <div class="modal-body" id="persInfo">
          <div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#user" aria-controls="user" role="tab" data-toggle="tab"><i class="fa fa-user"></i> Пользователь</a></li>
              <?php foreach($persMod as $ntc){
                echo $ntc['link'];
              }?>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="user"><h3 class="text-center"><i class="fa fa-lock"></i> Раздел в разработке разработке.</h3></div>
              <?php foreach($persMod as $ntc){
                echo $ntc['tab'];
              }?>              
            </div>
          </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
