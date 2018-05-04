<nav id="column-left">
  <div id="profile">
    <div>
      <?php if ($image) { ?>
      <img src="<?php echo $image; ?>" alt="<?php echo $firstname; ?> <?php echo $lastname; ?>" title="<?php echo $username; ?>" class="img-circle" />
      <?php } else { ?>
      <i class="fa fa-opencart"></i>
      <?php } ?>
    </div>
    <div>
      <h4><?php echo $firstname; ?> <?php echo $lastname; ?></h4>
      <small><?php echo $user_group; ?></small></div>
  </div>
  <ul id="menu">
    <?php if($uType === 'adm') { ?>
        <li id="menu-constructs">
            <a class="parent"><i class="fa fa-pencil fw"></i> <span>Конструкторы <label class="label label-default">NEW</label></span></a>
            <ul>
                <li>
                    <a href="index.php?route=setting/prodtypes&token=<?php echo $token_excel;?>"><span>Типы продуктов</span></a>
                </li>
                <li>
                    <a href="index.php?route=setting/fastCallMenu&token=<?php echo $token_excel;?>"><span>Меню быстрого доступа</span></a>
                </li>
                <li>
                    <a href="index.php?route=setting/menu&token=<?php echo $token_excel;?>"><span>Меню</span></a>
                </li>
                <li>
                    <a class="parent">Библиотеки</a>
                    <ul class="collapse">
                        <li>
                            <a href="index.php?route=setting/libraries&token=<?php echo $token_excel;?>"><span>Создать библиотеку</span></a>
                        </li>
                        <?php foreach($librs as $lib) { ?>
                            <li>
                                <a href="index.php?route=setting/libraries/edit&lib=<?php echo $lib['library_id']?>&token=<?php echo $token_excel;?>"><span><b>Библиотека:</b> <?php echo $lib['text'];?></span></a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </li>
        <li id="menu-reports">
            <a class="parent"><i class="fa fa-pencil-square-o fw"></i> <span>Отчёты <label class="label label-default">NEW</label></span></a>
            <ul>
                <li>
                    <a href="index.php?route=report/donor&token=<?php echo $token_excel;?>"><span>Донор</span></a>
                </li>
            </ul>
        </li>
        <li id="menu-avito">
            <a class="parent"><i class="fa fa-diamond fw"></i> <span>Работа с автозагрузкой</span></a>
            <ul>
                <li>
                    <a href="index.php?route=common/avito/settings&token=<?php echo $token_excel;?>"><span>Настройки Авито</span></a>
                </li>
                <li>
                    <a href="index.php?route=common/avito/categories&token=<?php echo $token_excel;?>"><span>Категории Авито</span></a>
                </li>
                <li>
                    <a href="index.php?route=common/excel/downloadAllProds&flag=drom&token=<?php echo $token_excel;?>"><span>Скачать прайс-лист Drom</span></a>
                </li>
                <li>
                    <a href="index.php?route=common/excel/downloadAllProds&flag=aru&token=<?php echo $token_excel;?>"><span>Скачать прайс-лист Auto.RU</span></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if(0){ ?><li id="menu-prodtdup">
        <a class="parent"><i class="fa fa-arrow-circle-o-up fw"></i> <span><?php echo $tiresdisc['main'];?></span></a>
        <ul>
            <li>
                <a href="index.php?route=tiresdisc/param&token=<?php echo $token_excel;?>"><span><?php echo $tiresdisc['td-lib'];?></span></a>
            </li>
            <li>
                <a href="index.php?route=tiresdisc/create&token=<?php echo $token_excel;?>"><span><?php echo $tiresdisc['td-create'];?></span></a>
            </li>
            <li>
                <a href="index.php?route=tiresdisc/list&token=<?php echo $token_excel;?>"><span><?php echo $tiresdisc['main'];?></span></a>
            </li>
        </ul>
            
    </li><?php }?>
    <li id="menu-donor">
        <a class="parent"><i class="fa fa fa-car fw"></i> <span>Работа с донорами</span></a>
        <ul>
            <li>
                <a href="index.php?route=donor/create&token=<?php echo $token_excel;?>"><span>Создать донора</span></a>
            </li>
            <li>
                <a href="index.php?route=donor/list&token=<?php echo $token_excel;?>"><span>Список доноров</span></a>
            </li>
        </ul>
            
    </li>
    <li id="menu-produp">
        <a class="parent"><i class="fa fa-umbrella fw"></i> <span><?php echo $edit_prod['adm_prod'];?></span></a>
        <ul>
            <li>
                <a class="parent">Товары</a>
                <ul class="collapse">
                    <li>
                        <a href="index.php?route=common/setphotos&token=<?php echo $token_excel;?>"><span><?php echo $edit_prod['add_photo'];?></span></a>
                    </li>
                    <li>
                        <a href="index.php?route=common/addprod&token=<?php echo $token_excel;?>"><?php echo $edit_prod['add_prod'];?></span></a>
                    </li>
                    <li>
                        <a href="index.php?route=common/write_off&token=<?php echo $token_excel;?>"><?php echo $edit_prod['write-off'];?></span></a>
                    </li>
                    <li>
                        <a href="index.php?route=common/refund&token=<?php echo $token_excel;?>"><?php echo $edit_prod['refund'];?></span></a>
                    </li>
                    <li>
                        <a href="index.php?route=common/excel&token=<?php echo $token_excel;?>"><span><?php echo $edit_prod['excel'];?></span></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="index.php?route=complect/complect&token=<?php echo $token_excel;?>"><span><?php echo $edit_prod['complect'];?></span></a>
            </li>
            <li>
                <a href="index.php?route=common/edit_model&token=<?php echo $token_excel;?>"><span><?php echo $edit_prod['edit_model'];?></span></a>
            </li>
            <li>
                <a href="index.php?route=common/write_off/saleList&token=<?php echo $token_excel;?>"><span><?php echo $edit_prod['list'];?></span></a>
            </li>
            <li>
                <a href="index.php?route=common/desctemp&token=<?php echo $token_excel;?>"><span><?php echo $edit_prod['tempdesc'];?></span></a>
            </li>
            <li>
                <a href="index.php?route=common/alternative&token=<?php echo $token_excel;?>"><span>Работа с синонимами</span></a>
            </li>
        </ul>
    </li>
    <?php foreach ($menus as $menu) { ?>
    <li id="<?php echo $menu['id']; ?>">
      <?php if ($menu['href']) { ?>
      <a href="<?php echo $menu['href']; ?>"><i class="fa <?php echo $menu['icon']; ?> fw"></i> <span><?php echo $menu['name']; ?></span></a>
      <?php } else { ?>
      <a class="parent"><i class="fa <?php echo $menu['icon']; ?> fw"></i> <span><?php echo $menu['name']; ?></span></a>
      <?php } ?>
      <?php if ($menu['children']) { ?>
      <ul>
        <?php foreach ($menu['children'] as $children_1) { ?>
        <li>
          <?php if ($children_1['href']) { ?>
          <a href="<?php echo $children_1['href']; ?>"><?php echo $children_1['name']; ?></a>
          <?php } else { ?>
          <a class="parent"><?php echo $children_1['name']; ?></a>
          <?php } ?>
          <?php if ($children_1['children']) { ?>
          <ul>
            <?php foreach ($children_1['children'] as $children_2) { ?>
            <li>
              <?php if ($children_2['href']) { ?>
              <a href="<?php echo $children_2['href']; ?>"><?php echo $children_2['name']; ?></a>
              <?php } else { ?>
              <a class="parent"><?php echo $children_2['name']; ?></a>
              <?php } ?>
              <?php if ($children_2['children']) { ?>
              <ul>
                <?php foreach ($children_2['children'] as $children_3) { ?>
                <li><a href="<?php echo $children_3['href']; ?>"><?php echo $children_3['name']; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
  <div id="stats">
    <ul>
      <li>
        <div><?php echo $text_complete_status; ?> <span class="pull-right"><?php echo $complete_status; ?>%</span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $complete_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $complete_status; ?>%"> <span class="sr-only"><?php echo $complete_status; ?>%</span></div>
        </div>
      </li>
      <li>
        <div><?php echo $text_processing_status; ?> <span class="pull-right"><?php echo $processing_status; ?>%</span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $processing_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $processing_status; ?>%"> <span class="sr-only"><?php echo $processing_status; ?>%</span></div>
        </div>
      </li>
      <li>
        <div><?php echo $text_other_status; ?> <span class="pull-right"><?php echo $other_status; ?>%</span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $other_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $other_status; ?>%"> <span class="sr-only"><?php echo $other_status; ?>%</span></div>
        </div>
      </li>
    </ul>
  </div>
</nav>
