<div role="tabpanel" class="tab-pane" id="warnings">
    <h3>Оповещения: </h3>
    <?php foreach($warns as $warn){ ?>
        <div class="<?php echo $warn['viewed']?'well':'alert alert-warning';?>">
            <h3>
                <label class="label label-<?php echo $warn['color_label'];?>"><?php echo $warn['text_label'];?></label>
                <?php echo $warn['header'];?>
            </h3>
            <p>Автор: <b><?php echo $warn['autor'];?></b> - <b><?php echo date("d.m.Y H:s:i", strtotime($warn['date_added']));?></b></p>
            <hr>
            <?php echo htmlspecialchars_decode($warn['text']);?>
            <hr>
        </div>
    <?php }?>
</div>