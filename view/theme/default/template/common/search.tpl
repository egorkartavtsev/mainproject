<div id="search" class="input-group dropdown">
  <input type="text" name="search" id="srcField" value="<?php echo $search; ?>" placeholder="<?php echo $text_search; ?>" class="form-control input-lg" />
  <span class="input-group-btn">
    <button type="button" class="btn btn-danger btn-lg" name="butsearch"><i class="fa fa-search"></i></button>
    <button type="button" class="btn btn-danger btn-lg" name="buttrash"><i class="glyphicon glyphicon-trash"></i></button>
  </span>
  <div class="clearfix"></div>
  <ul class="dropdown-menu dropdown-menu-left alerts-dropdown" id="searchResult">
      <li class="dropdown-header" >Варианты поиска: </li>
  </ul>
</div>