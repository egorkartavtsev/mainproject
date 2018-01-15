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
        <?php echo $form;?>
            <h3>Фотографии:</h3>
            <div class="well col-sm-12">
                <?php $count = 0; ?>
                <?php foreach($images as $img) { ?>
                    <div style="float: left;" class="col-sm-2">
                        <a href="" id="thumb-image<?php echo $img['lid']?>" data-toggle="image" class="img-thumbnail" data-toggle="popover" <?php if($img['main']){echo 'style="box-shadow: 0px 0px 50px #4CAF50;"';} ?>>
                            <img src="<?php echo $img['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                        </a>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="image[<?php echo $img['lid']?>][sort-order]" value="<?php echo $img['sort_order'];?>">
                        </div>
                        <input type="hidden" data-toggle='input-image' id="input-image<?php echo $img['lid']?>" name="image[<?php echo $img['lid']?>][img]" value="<?php echo $img['image']; ?>"/>
                    </div>
                <?php ++$count; ?>
                <?php } ?>
                <div class="text-center" style="float: left; padding: 3.5%;">
                    <button id="button-add-image" data-toggle="tooltip" data-original-title="Добавить фото" data-pointer="<?php echo $count;?>" class="btn btn-success btn-lg"><i class="fa fa-plus-circle"></i></button>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> сохранить изменения</button>
            </div>
        </form>
        <div class="well">
            <p>
                Справка:<br><br>
                <button class="btn btn-primary"><i class="fa fa-pencil"></i> - изменить фотографию </button>
                <button class="btn btn-danger"><i class="fa fa-trash-o"></i> - удалить фотографию </button>
                <button class="btn btn-warning"><i class="fa fa-exclamation-circle"></i>  - сделать фотографию главной</button>
            </p>
        </div>
    </div>
<?php echo $footer;?>