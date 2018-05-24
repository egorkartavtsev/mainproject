<nav id="column-left">
  <div id="profile">
    <div>
      <?php if ($image) { ?>
      <img src="<?php echo $image; ?>" alt="<?php echo $firstname; ?> <?php echo $lastname; ?>" title="<?php echo $firstname; ?> <?php echo $lastname; ?>" class="img-circle" />
      <?php } else { ?>
      <i class="fa fa-opencart"></i>
      <?php } ?>
    </div>
    <div>
      <h4><?php echo $firstname; ?> <?php echo $lastname; ?></h4>
      <small><?php echo $user_group; ?></small></div>
  </div>
  <ul id="menu">
    <?php foreach($main_menu as $parent) { ?>
        <li id="menu-<?php echo $parent['name'];?>">
            <a class="parent"><i class="<?php echo $parent['icon'];?>"></i> <span><?php echo $parent['text'];?></span></a>
            <ul>
                <?php foreach($parent['childs'] as $item) { ?>
                    <li>
                        <a href="index.php?route=<?php echo $item['href']?>&token=<?php echo $token;?>"><span><?php echo $item['text']?></span></a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
  </ul>
</nav>
