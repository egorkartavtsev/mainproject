<div class="row">
    <div class="col-lg-12">
        <h3><?php echo $name.' | '.$vin.' | '.$location;?></h3>
        <div class="btn-group">
            <a class="btn btn-primary" target="_blank" href="<?php echo $edit; ?>"><i class="fa fa-pencil"></i> редактировать</a> 
            <a class="btn btn-success" target="_blank" href="<?php echo $go_site; ?>"><i class="fa fa-arrow-circle-right"></i> на витрине</a>
            <button btn_type="copyToSend" data-text="<?php echo $go_site; ?>" type="button" data-toggle="tooltip" class="btn btn-warning"><i class="fa fa-copy"></i> копировать ссылку</button>
        </div>
    </div>
    <div class="col-lg-12"><p></p></div>
    <div class="col-md-6">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators" style="display: none;">
                <?php foreach($images as $img) { ?>
                    <?php if (isset($img['main']) && $img['main'] == true) { ?> 
                        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $img['lid']?>" class="active"></li>
                    <?php } else { ?>
                        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $img['lid']?>"></li>
                    <?php }?>
                <?php }?> 
            </ol>
                <div class="carousel-inner" role="listbox">
                        <?php foreach($images as $img) { ?> 
                            <?php if (isset($img['main']) && $img['main'] == true) { ?> 
                               <div class="item active">
                            <?php } else { ?>
                               <div class="item ">
                            <?php }?>
                                <a class="plus-popup" href="<?php echo $img['popup'].'?'.time();?>"><img src="<?php echo $img['thumb'];?>"class="img-thumbnail img-responsive d-block w-100" /></a>
                            </div>   
                        <?php }?>  
                </div>
                <a class="left carousel-control center-btn" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="fa fa-angle-left fa-2x" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control center-btn" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="fa fa-angle-right fa-2x" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>            
            </div>
            <script type="text/javascript">
                    $('#carousel-example-generic').carousel();    
            </script> 
        </div>
    <div class="col-md-6">
        <table class="table table-striped">
            <tr>
                <td class="text-right h4">Внутренний номер: </td>
                <td class="text-right h4"><?php echo $vin;?></td>
            </tr>
            <tr>
                <td class="text-right h4">Количество: </td>
                <td class="text-right h4"><?php echo $quantity;?></td>
            </tr>
            <tr>
                <td class="text-right h4">Цена: </td>
                <td class="text-right h4"><?php echo $price;?></td>
            </tr>
            <tr>
                <td class="text-right h4">Расположение: </td>
                <td class="text-right h4"><?php echo $location?></td>
            </tr>
            <tr>
                <?php if($date_sale){ ?>
                    <td class="text-right h4">Продан: </td>
                    <td class="text-right h4"><?php echo $date_sale;?></td>
                <?php } else { ?>
                    <td colspan="2" class="text-center h4">В наличии</td>
                <?php }?>
            </tr>
        </table>
    </div>
</div>
<div class="row" style="overflow-x: scroll;">
    <div class="col-lg-12">

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#totalInfo" aria-controls="home" role="tab" data-toggle="tab">Общее</a></li>
        <?php if($complect){ ?><li role="presentation"><a href="#compInfo" aria-controls="profile" role="tab" data-toggle="tab">Комплектность</a></li><?php }?>
        <li role="presentation"><a href="#histInfo" aria-controls="messages" role="tab" data-toggle="tab">История</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="totalInfo">
          <table class="table table-striped">
            <?php foreach($options as $option){ ?>    
                <tr>
                    <td class="text-right col-sm-3"><?php echo $option['text']?>: </td>
                    <td class="text-left h4 col-sm-9"><?php echo $option['value']?></td>
                </tr>
            <?php }?>    
          </table>
        </div>
        <?php if($complect){ ?>
            <div role="tabpanel" class="tab-pane" id="compInfo">
                <div class="col-sm-12">
                    <div class ="row">
                        <label id="name" val="<?php echo $complect['complect']['name'];?>">Название комплекта:</label><h4><span class="label label-primary " title="Открыть редактирование комплекта в новом окне"><a style="color: #FFFFFF!important;" target="_blank" href="<?php echo $complect['clink'];?>"><i class="fa fa-pencil-square-o"></i>  <?php echo $complect['complect']['name'];?></a></span></h4>
                    </div>
                </div>
                <table class="table table-striped">
                    <tr>
                        <td class="text-center col-sm-4">Цена комплекта</td>
                        <td class="text-center col-sm-4">Способ продажи комплекта</td>
                        <td class="text-center col-sm-4">Скидка</td>
                    </tr>
                    <tr>
                        <td class="text-center h4"><?php echo $complect['complect']['price'];?> &#8381;</td>
                        <td class="text-center h4"><?php echo (int)$complect['complect']['price']?'Комплект целиком':'Обычная продажа';?></td>
                        <td class="text-center h4"><?php echo (int)$complect['complect']['sale']?$complect['complect']['sale'].'%':'15%';?></td>
                    </tr>      
                </table>

                <h4>Комплектующие:</h4>
                <table class="table table-striped">
                    <?php foreach($complect['accs'] as $acc) { ?>
                        <tr>
                            <td class="col-sm-3 text-left h4">
                                <?php echo $acc['vin'];?>
                            </td>
                            <td class="col-sm-6 text-center">
                                <a href="<?php echo $acc['cp_link'];?>" target="_blank">
                                    <i class="fa fa-pencil-square"></i> <?php echo $acc['name'];?> <?php echo $acc['heading']?'(головной)':'';?>
                                </a>
                            </td>
                            <td class="col-sm-3 text-right h4">
                                <?php echo $acc['price'];?> &#8381;
                            </td>
                        </tr>
                    <?php } ?>
                </table>                
            </div>
        <?php }?>
        <div role="tabpanel" class="tab-pane" id="histInfo">
            <table class="table table-striped table-responsive">
                <?php foreach($history as $histItem){ ?>
                    <tr>
                        <td><?php echo $histItem['label'];?></td>
                        <td><?php echo $histItem['type'];?></td>
                        <td><?php echo $histItem['manager'];?></td>
                        <td><?php echo $histItem['date'];?></td>
                    </tr>
                <?php }?>
            </table>
        </div>
      </div>

    </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/magnific/jquery.magnific-popup.min.js"></script>
<link href="view/javascript/jquery/magnific/magnific-popup.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">
    $('.plus-popup').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        image: {
                verticalFit: false
        }
    });
</script>      
<script type="text/javascript">
    $('#myTabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })
</script>