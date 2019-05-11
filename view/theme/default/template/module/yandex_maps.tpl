<?php 
	if ($ymap_width == 'auto') {
		$ymapwidth = 'auto';
	} else if (is_numeric($ymap_width)) {
		$ymapwidth = $ymap_width . 'px';
	} else {
		$ymapwidth = '425px';
	}
	
	if ($ymap_height == 'auto') {
		$ymapheight = 'auto';
	} else if (is_numeric($ymap_height)) {
		$ymapheight = $ymap_height . 'px';
	} else {
		$ymapheight = '350px';
	}
?>
<style type="text/css">
#yamap<?php echo $module_map; ?>{
	width:<?php echo $ymapwidth; ?>;
	height:<?php echo $ymapheight; ?>;
	margin:0;
	padding:0;
	margin-bottom:10px;
}
</style>
<?php if ($ymap_showbox) {?>
<div class="box">
  <div class="box-heading"><?php echo $ymap_boxtitle; ?></div>
  <div class="box-content">
	<div id="yamap<?php echo $module_map; ?>">&nbsp;</div>
  </div>
</div>
<?php } else {?>
<div id="yamap<?php echo $module_map; ?>">&nbsp;</div>
<?php }?>

<script src="//api-maps.yandex.ru/2.1/?lang=<?php echo $ymap_maplang; ?>" type="text/javascript"></script>
<script>ymaps.ready(init);

function init () {
    var myMap = new ymaps.Map("yamap<?php echo $module_map; ?>", {
            center: [<?php echo $ymap_flatlong; ?>],
            zoom: <?php echo $ymap_zoom; ?>,
			type: 'yandex#map',
			controls: ['smallMapDefaultSet','rulerControl']
        });
		
    myMap.geoObjects
        <?php 
	$aa = 0;
	foreach ($ymaps as $ymap) {
		$aa += 1;
	?>.add(new ymaps.Placemark([<?php echo $ymap['latlong']; ?>], {
            balloonContent: '<?php if (strlen($ymap['onelinetext']) > 0){
			echo str_replace(chr(11),"",str_replace(chr(13),"",str_replace(chr(10),"",str_replace(chr(9),"",$ymap['onelinetext']))));
			}
			else {
				echo str_replace(chr(11),"",str_replace(chr(13),"",str_replace(chr(10),"",str_replace(chr(9),"",$ymap['maptext']))));
			}?>'
        }, {
			preset: '<?php echo $ymap['icontype']; ?>',
            iconColor: '<?php echo $ymap['iconcolor']; ?>'
        }))<?php } ?>;
		
}</script>