<?php
echo '<h1>CAUTION: This code should not be used in a production environment.</h1>';
if (isset($_POST['dir'])) {
    $dir=$_POST['dir'];
} elseif (isset($_GET['dir'])) {
    $dir=$_GET['dir'];
} else {
    $dir = "./";
}
echo '<form method="post">';
echo '<table border="2">';
echo '<tr><td>Directory</td><td><input type="text" size="50" name="dir" value="'.$dir.'"></td></tr>';
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
			if(filetype($dir . $file) == 'file' && ereg("\.DBF",strtoupper($file))){
				echo '<tr><td>'.filesize($dir . $file).'</td><td>'
                    .'<a href="'.$PHP_SELF.'?f='.$file.'&dir='.$dir.'">'.$file.'</a></td></tr>';
			}
        }
        closedir($dh);
    }
}
echo '<tr><td>File</td><td><input type="text" size="50" name="f" value=""></td></tr>';
echo '<tr><td>&nbsp;</td><td><input type="submit" name="doit" value="Show file"></td></tr>';
echo '</table>';
echo '</form>';
if ($_GET['f']) {
    $thefile=$_GET['f'];
} elseif ($_POST['f']) {
    $thefile=$_POST['f'];
}
if($thefile){
    include('./dbf_class.php');
    $timer = new timerClass();
    $timer ->start();
    $dbf = new dbf_class($dir.$thefile);
    $num_rec=$dbf->dbf_num_rec;
    $field_num=$dbf->dbf_num_field;
    $endexct = $timer->end();
    //exit("$field_num $num_rec");
    echo("<blockquote>File Name : $f<br>Number of Records : $num_rec<br>Number of Fields : $field_num <br>Executed Time : $endexct</blockquote>");
    echo('<table border=1 cellspacing=0>');
    echo('<tr>');
    echo('<td>No.&nbsp;</td>');	
    for($j=0; $j<$field_num; $j++){
	    echo '<td>&nbsp;'.$dbf->dbf_names[$j]['name'];
        if ($dbf->dbf_names[$j]['type']!='M') { //Length is meaningless for a memo field.
            echo '<br>Length='.$dbf->dbf_names[$j]['len'];
        }
        echo '<br>Type='.$dbf->dbf_names[$j]['type'].'</td>';
    }
    echo '</tr>';
    for($i=0; $i<$num_rec; $i++){
        if ($row = $dbf->getRow($i)) {
	        echo('<tr>');
        	echo('<td align="right">'.str_pad($i+1, 3, "0", STR_PAD_LEFT).'</td>');
	        for($j=0; $j<$field_num; $j++){
                if ($dbf->dbf_names[$j]['type']=='N') {
                    echo '<td align="right">';
                } else {
                    echo '<td align="left">';
                }
  		        echo htmlentities($row[$j]).'&nbsp;</td>';
    	    }
        	echo '<tr>';
        }
    }
    echo('</table>');
}
class timerClass {
	var $startTime;
	var $started;
	function timerClass($start=true) {
		$this->started = false;
		if ($start)
			$this->start();
	}
	function start() {
		$startMtime = explode(' ',microtime());
		$this->startTime = (double)($startMtime[0])+(double)($startMtime[1]);
		$this->started = true;
	}
	function end($iterations=1) {
		$endMtime = explode(' ',microtime());
		if ($this->started) {
			$endTime = (double)($endMtime[0])+(double)($endMtime[1]);
			$dur = $endTime - $this->startTime;
			$avg = 1000*$dur/$iterations;
			$avg = round(1000*$avg)/1000;
			return "$avg milliseconds";
		} else {
			return "timer not started";
		}
	}
}
?>
