<?php foreach($types as $type){ ?>
    <li><a href="<?php echo $type['href'];?>"><?php echo $type['text'];?></a></li>
<?php }?>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        Каталог товаров <i class="fa fa-chevron-circle-down"></i>
    </a>
    <ul class="dropdown-menu">
        <?php foreach($librs as $libr){ ?>
            <li><a href="<?php echo $libr['href'];?>"><?php echo $libr['text'];?></a></li>
        <?php }?>
    </ul>
</li>

<?php if ($informations) { 
    foreach ($informations as $information) { ?>
        <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
    <?php } 
  } ?>