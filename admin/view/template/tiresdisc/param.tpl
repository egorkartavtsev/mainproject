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
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#disk" aria-controls="disk" role="tab" data-toggle="tab">Диски</a></li>
            <li role="presentation"><a href="#tires" aria-controls="tires" role="tab" data-toggle="tab">Шины</a></li>
         </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="disk">
                <div class="col-md-12">
                    <div class="col-md-6" >
                        <table class="table table-bordered table-hover table-responsive table-striped">
                            <thead>
                                <tr>
                                    <td>Параметр</td>
                                    <td>Действие</td>
                                </tr>
                            </thead>
                            <tbody id="discparams">
                                <?php foreach($parameters['disc'] as $param => $pdisc){ ?>
                                    <tr>
                                        <td style="cursor: pointer;" id='dp<?php echo $param;?>' onclick="getLibr('<?php echo $param;?>', 'dlibr')"><?php echo $pdisc; ?></td>
                                        <td> 
                                            <button class="btn btn-primary" onclick="getEdit('dp<?php echo $param;?>', '<?php echo $param;?>', 'discparams')"><i class="fa fa-pencil fw"></i></button>
                                             
                                            <button class="btn btn-danger" onclick="confirm('Вы уверены?') ? deleteParam('<?php echo $param;?>', 'dp<?php echo $param;?>') : false;"><i class="fa fa-trash-o fw"></i></button>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tboby>
                        </table>
                        <button class="btn btn-primary btn-block" onclick="createParam('discparams')"><i class="fa fa-plus-circle"></i> Добавить параметр</button>
                    </div>
                    <div class="col-md-6" id="dlibr">
                        
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tires">
                <div class="col-md-12">
                    <div class="col-md-6" >
                        <table class="table table-bordered table-hover table-responsive table-striped">
                            <thead>
                                <tr>
                                    <td>Параметр</td>
                                    <td>Действие</td>
                                </tr>
                            </thead>
                            <tbody id="tireparams">
                                <?php foreach($parameters['tire'] as $param => $ptires){ ?>
                                    <tr>
                                        <td style="cursor: pointer;" id='tp<?php echo $param;?>' onclick="getLibr('<?php echo $param;?>', 'tlibr')"><?php echo $ptires; ?></td>
                                        <td> 
                                            <button class="btn btn-primary" onclick="getEdit('tp<?php echo $param;?>', '<?php echo $param;?>', 'tireparams')"><i class="fa fa-pencil fw"></i></button>
                                             
                                            <button class="btn btn-danger" onclick="confirm('Вы уверены?') ? deleteParam('<?php echo $param;?>', 'tp<?php echo $param;?>') : false;"><i class="fa fa-trash-o fw"></i></button>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tboby>
                        </table>
                        <button class="btn btn-primary btn-block" onclick="createParam('tireparams')"><i class="fa fa-plus-circle"></i> Добавить параметр</button>
                    </div>
                    <div class="col-md-6" id="tlibr">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#myTabs a').click(function (e) {
          e.preventDefault()
          $(this).tab('show')
        })
    </script>
<?php echo $footer;?>