<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; if (isset($_GET['page'])) { echo " - ". ((int) $_GET['page'])." ".$text_page;} ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; if (isset($_GET['page'])) { echo " - ". ((int) $_GET['page'])." ".$text_page;} ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<link rel="shortcut icon" href="image/icoshop.png" type="image/x-icon">
<meta property="og:title" content="<?php echo $title; if (isset($_GET['page'])) { echo " - ". ((int) $_GET['page'])." ".$text_page;} ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo $og_url; ?>" />
<?php if ($og_image) { ?>
<meta property="og:image" content="<?php echo $og_image; ?>" />
<?php } else { ?>
<meta property="og:image" content="<?php echo $logo; ?>" />
<?php } ?>
<meta property="og:site_name" content="<?php echo $name; ?>" />
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!--<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />-->
<link href="catalog/view/theme/default/stylesheet/stylesheet.css?t=<?php echo(microtime(true)); ?>" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js?t=<?php echo(microtime(true)); ?>" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script type="text/javascript" src="https://vk.com/js/api/openapi.js?159"></script>
<script type="text/javascript" src="https://vk.com/js/api/share.js?93" charset="windows-1251"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
</head>
<body>
<nav id="top">
  <!--<div class="container">
    <div id="top-links" class="nav pull-right">
      <ul class="list-inline">
        <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
          
        </li>
        </ul>
    </div>
  </div>-->

<div class="container">
    <div class="row">
        <div class="col-sm-2">
            <div id="logo">
              <?php if ($logo) { ?>
                <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" width="200" alt="<?php echo $name; ?>" class="img-responsive center-block" /></a>
              <?php } else { ?>
                <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
              <?php } ?>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="text-right">
                <a style="cursor: pointer;" href="viber://chat?number=+79124750870"><img src="<?php echo $viber;?>" width="40"></a>
                <a style="cursor: pointer;" href="https://wa.me/79124750870"><img src="<?php echo $whatsapp;?>" width="40"></a>
                <a style="cursor: pointer;" href="tg://resolve?domain=Dimadv"><img src="<?php echo $tg;?>" width="40"></a>
                <a target="_blank" href="https://vk.com/mgnautorazbor"><img src="<?php echo $lvk;?>" width="40"></a>
                <a target="_blank" href="https://www.instagram.com/autorazbor174"><img src="<?php echo $linst;?>" width="40"></a>
                <a target="_blank" href="https://www.youtube.com/channel/UCNgBC4t07efN7qMYUls0fcw"><img src="<?php echo $lyt;?>" width="40"></a>
                <a target="_blank" href="https://baza.drom.ru/user/AUTORAZBOR174RU"><img src="<?php echo $ldrom;?>" width="40"></a>
                <a target="_blank" href="https://www.avito.ru/autorazbor174"><img src="<?php echo $lavito;?>" width="40"></a>
            </div>
            <div><p></p></div>
            <div class="">
                <?php echo $search; ?>
            </div>
            <div class="row">
                <div class="col-sm-4 pull-right">
                  <button class="dropdown-toggle btn btn-danger btn-block btn-lg" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-user"></i> личный кабинет
                  </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <?php if ($logged) { ?>
                            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
                            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
                            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
                        <?php } else { ?>
                            <li><a href="<?php echo $register; ?>"><i class="fa fa-user-plus"></i> <?php echo $text_register; ?></a></li>
                            <li><a href="<?php echo $login; ?>"><i class="fa fa-user-secret"></i> <?php echo $text_login; ?></a></li>
                        <?php } ?>
                        <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <?php echo $text_checkout; ?></a></li>
                    </ul>
                </div>
                <div class="col-sm-4 pull-right">
                  <?php echo $cart; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<header>
  <div class="container">
      
  </div>
</header>

<div class="container">
  <nav id="menu" class="navbar">
    <div class="navbar-header"><span id="category" class="visible-xs">Меню</span>
      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav">
        <?php echo $menu; ?>
        <li><a href="index.php?route=catalog/foreignparts">Контрактные автозапчасти</a></li>
      </ul>
    </div>
  </nav>
</div>
</nav>
