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
    <?php if($success){ ?>
        <div class='col-sm-12'>
            <div class="col-sm-12 alert alert-success">
                <?php echo $success; ?>
            </div>
        </div>
    <?php }?>
    <div class='col-sm-12'>
        <a href="index.php?route=common/createXML&token=<?php echo $token;?>&comm=true" class="btn btn-block btn-success btn-lg">Создать XML</a>
    </div>
    <?php $sup=0; foreach($xmls->Ad as $ad){
        echo date('Y-m-d', strtotime("+10 days")).'<br>';
        if(in_array('0004', (array)$ad)){
            echo 'Присутствует! '.$xmls->Ad[$sup]->Id.'<br>';
        } else{
            ++$sup;
            echo 'Отсутствует!'.date("Y-m-d").'<br>';
        }
    }?>
</div>
<?php echo $footer;?>