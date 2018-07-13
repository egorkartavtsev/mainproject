<div class="container-fluid">
    <div class="row">
        <div class="form-group-sm col-md-6">
            <label>Название поля:</label>
            <input type="text" class="form-control" id="textOption" value="<?php echo $text;?>"/>
            <input type="hidden" class="form-control" id="field_typeOption" value="<?php echo $field_type;?>"/>
            <input type="hidden" class="form-control" id="nameOption" value="<?php echo $name;?>"/>
            <input type="hidden" class="form-control" id="oldOption" value="1"/>
        </div>
        <div class="form-group-sm col-md-6">
            <label>Библиотека:</label>
            <input type="text" class="form-control" disabled value="<?php echo $lib_name;?>"/>
        </div>
        <div class="form-group-sm col-md-6">
            <label>Описание свойства:</label>
            <input type="text" class="form-control" id="descriptionOption" value="<?php echo $description;?>"/>
        </div>
        <div class="form-group-sm col-md-6">
            <label>Порядок сортировки:</label>
            <input type="text" class="form-control" id="sort_orderOption" value="<?php echo $sort_order;?>"/>
        </div>
        <div class="form-group-sm col-md-6">
            <label>Видимость:</label>
            <select class="form-control" id="viewedOption">
                <option value="1">Отображать веезде на витрине</option>
                <option value="2" <?php echo $viewed=='2'?'selected':'';?>>Отображать только в списке товаров</option>
                <option value="3" <?php echo $viewed=='3'?'selected':'';?>>Отображать тоьлко на странице товара</option>
                <option value="0" <?php echo $viewed=='0'?'selected':'';?>>Не отображать на витрине</option>
            </select>
        </div>
        <div class="form-group-sm col-md-6">
            <label>Участие в поиске:</label>
            <select class="form-control" id="searchingOption">
                <option value="0">Не учавствует в поиске</option>
                <option value="1" <?php echo $searching=='1'?'selected':'';?>>Учавствует в поиске</option>
            </select>
        </div>
        <div class="form-group-sm col-md-6">
            <label>Участие в фильтрах:</label>
            <select class="form-control" id="filterOption">
                <option value="0">Не отображать в фильтрах</option>
                <option value="1" <?php echo $filter=='1'?'selected':'';?>>Отображать в фильтрах</option>
            </select>
        </div>
        <div class="clearfix"></div>
        <div class="clearfix"><p></p></div>
        <div class="col-lg-12">
            <button id="saveOpt" class="btn btn-info"><i class="fa fa-floppy-o"></i> сохранить</button>
        </div>
    </div>
</div>