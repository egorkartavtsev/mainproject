<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid well">
        <h4>Измените название модели/модельного ряда</h4>
        <form action="index.php?route=common/edit_model&token=<?php echo $token_em;?>" method='post'>
            <input type='text' name='newname' value="<?php echo $modname; ?>"/>
            <input type='hidden' name='pointer' value="<?php echo $modID; ?>"/>
            <input type="submit" value="Сохранить" />
        </form>
    </div>
</div>
<?php echo $footer;?>