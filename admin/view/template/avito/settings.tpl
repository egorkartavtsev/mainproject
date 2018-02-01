<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <div class="pull-right">
                <button class="btn btn-success" onclick="$('#formSet').submit();"><i class="fa fa-floppy-o"></i> сохранить настройки</button>
            </div>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="col-sm-12">
        
        <form method='POST' action="index.php?route=common/avito/saveSettings&token=<?php echo $token;?>" id="formSet">
            <div class="col-sm-6">
                <div class="alert alert-info form-group col-sm-12">
                    <label for="price"><?php echo $libr['price'][0]['description'];?></label>
                    <input type='text' name="price" class="form-control" id="price" value="<?php echo $settings['price'];?>"/>
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"><p></p></div>
                <div class="alert alert-info form-group col-sm-12">
                    <label for="sdate"><?php echo $libr['sdate'][0]['description'];?></label>
                    <input type='text' name="sdate" class="form-control" id="sdate" value="<?php echo $settings['sdate'];?>"/>
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"><p></p></div>
                <div class="alert alert-info form-group col-sm-12">
                    <label for="edate"><?php echo $libr['edate'][0]['description'];?></label>
                    <input type='text' name="edate" class="form-control" id="edate" value="<?php echo $settings['edate'];?>"/>
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"><p></p></div>
                <div class="alert alert-info form-group col-sm-12">
                    <label for="managerName"><?php echo $libr['managerName'][0]['description'];?></label>
                    <input type='text' name="managerName" class="form-control" id="managerName" value="<?php echo $settings['managername'];?>"/>
                </div>
                
            </div>
            <div class="col-sm-6">
                <div class="alert alert-info form-group col-sm-12">
                    <label for="listingfree">Способ размещения объявдений</label>
                    <select name="listingfree" class="form-control" id="listingfree">
                        <?php foreach($libr['listingfree'] as $lfr){ ?>
                        <option value="<?php echo $lfr['value'];?>" <?php if($lfr['value'] === $settings['listingfree']){ echo 'selected'; }?>><?php echo $lfr['value'];?> - <?php echo $lfr['description'];?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"><p></p></div>
                <div class="alert alert-info form-group col-sm-12">
                    <label for="adstatus">Платные услуги</label>
                    <select name="adstatus" class="form-control" id="adstatus">
                        <?php foreach($libr['adstatus'] as $lfr){ ?>
                            <option value="<?php echo $lfr['value'];?>" <?php if($lfr['value'] === $settings['adstatus']){ echo 'selected'; }?>><?php echo $lfr['value'];?> - <?php echo $lfr['description'];?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"><p></p></div>
                <div class="alert alert-info form-group col-sm-12">
                    <label for="allowemail"><?php echo $libr['allowemail'][0]['description'];?></label>
                    <select name="allowemail" class="form-control" id="allowemail">
                        <?php foreach($libr['allowemail'] as $lfr){ ?>
                            <option value="<?php echo $lfr['value'];?>" <?php if($lfr['value'] === $settings['allowemail']){ echo 'selected'; }?>><?php echo $lfr['value'];?></option>
                        <?php }?>
                    </select>
                </div>
                
            </div>
            <div class="clearfix"></div>
            <div class="clearfix"><p></p></div>
            <div class="alert alert-info form-group col-sm-12">
                <label for="title"><?php echo $libr['title'][0]['description'];?></label>
                <input type='text' name="title" class="form-control" id="title" value="<?php echo $settings['title'];?>"/>
            </div>
            <div class="clearfix"></div>
            <div class="clearfix"><p></p></div>
            <div class="alert alert-info form-group col-sm-12">
                <label for="description"><?php echo $libr['title'][0]['description'];?></label>
                <textarea name="description" class="form-control" id="description"><?php echo $settings['description'];?></textarea>
            </div>
            <div class="clearfix"></div>
            <div class="clearfix"><p></p></div>
        </form>
    </div>
</div>
<?php echo $footer; ?>
