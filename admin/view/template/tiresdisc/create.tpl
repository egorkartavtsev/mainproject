<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <script type="text/javascript" src="view/javascript/tiresdisc.js"></script>
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
    <div class="container-fluid">
        <form class="form" method="post" enctype="multipart/form-data" action="<?php echo $action;?>" id="formCreate">
            <div class="clearfix" id="afterFields"></div>
            <button type="submit" class="btn btn-success" id="saveButton" disabled><i class="fa fa-floppy-o"></i> Сохранить товары</button>
        </form>
        <div class="clearfix"></div>
        <div class="clearfix"><p></p></div>
        <button class="btn btn-primary" id="createField"><i class="fa fa-plus-circle fw"></i> добавить товар</button>
    </div>
    <script type="text/javascript">
        $('#myTabs a').click(function (e) {
          e.preventDefault()
          $(this).tab('show')
        })
        
        $('#createField').click(function() {
            $form = getNewPart()
            $($form).insertBefore('#afterFields')
        })
    </script>
<?php echo $footer;?>