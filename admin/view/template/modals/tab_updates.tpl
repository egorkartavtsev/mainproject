<div role="tabpanel" class="tab-pane" id="updates">
    <h3>Обновления: </h3>
    <?php foreach($logs as $log){ ?>
        <div class="<?php echo $log['viewed']?'well':'alert alert-warning';?>">
            <?php echo htmlspecialchars_decode($log['text']);?>
            <hr>
            <p>Автор: <b><?php echo $log['autor'];?></b></p>
            <p><b><?php echo date("d.m.Y", strtotime($log['date_added']));?></b></p>
        </div>
    <?php }?>
</div>