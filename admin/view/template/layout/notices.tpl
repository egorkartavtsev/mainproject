<div class="notice toolbar events hidden-xs hidden-sm <?php echo $new?'pulse':'';?>">
    <div data-toggle="modal" data-target="#persModal">
        <span>
            <a href="#user" data-toggle="tab" aria-controls="user" role="tab">
                <i data-toggle="tooltip" data-placement="bottom" data-original-title="Профиль" class="fa fa-user-secret"></i>
            </a>
        </span>
    </div>
    <?php foreach($notices as $key => $notice){ ?>
        <div class="b-left" data-toggle="modal" data-target="#persModal">
            <span>
                <a href="#<?php echo $key;?>" data-toggle="tab" aria-controls="<?php echo $key;?>" role="tab" <?php echo (int)$notice['fastviewed']?'btn_type="upd-notice"':'';?>>
                    <i data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo $notice['text'];?>" class="<?php echo $notice['icon'];?>"></i>
                </a>
            </span>
            <?php if($notice['new']){ ?>
              <re>
                <a href="#<?php echo $key;?>" data-toggle="tab" aria-controls="<?php echo $key;?>" role="tab" <?php echo (int)$notice['fastviewed']?'btn_type="upd-notice"':'';?>>
                  <span class="hasNew">!</span>
                </a>
              </re>
            <?php }?>
        </div>
    <?php }?>
</div>
