<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Tournament</title>
  <style>
    #image {
      max-width:45%;
	  max-height:80%;
	  margin:10px;
    }
	#image:hover {
	  border-style:dashed;
	  border-width:3px;
	  border-color:#CAA;
	}
	#content {
	  
	}
  </style>
<body>
<?php

$image_dir = "./image_files";

$command = $_GET['c'];
$sel = $_GET['s'];
$cnt = $_GET['cnt']; 
if(!$_GET['cnt']) { $cnt = 0; }

session_start();

if ($command == 'init') {
	
	$handle = opendir($image_dir);
	$files = array(); 

	while (false !== ($filename = readdir($handle))) {
		if($filename == "." || $filename == ".."){
			continue;
		}
		$files[] = $filename;
	}

	closedir($handle);
	shuffle($files);
	$_SESSION['image_dir'] = $image_dir;
	$_SESSION['files'] = $files;
	$_SESSION['max'] = count($files);
	$_SESSION['pass'] = 0;
	echo "<h1>이미지 개수: ".count($files)."</h1>";

}


if($_SESSION['files'] != null) {

	echo "<div id='content'>";
	//세션에서 값 가져오기
	$files = $_SESSION['files'];
	$image_dir = $_SESSION['image_dir'];
	$max = $_SESSION['max'];
	

	//골라지지 않은 것 삭제..
	if ($_SESSION['pass'] != 1) {
		foreach ($files as $f => $val) {
			
			if ($sel == $val) {
				echo "<h2>OK</h2>";
				unset($files[$f]);
			}
		}
	}
	$_SESSION['pass'] = 0;
	
	//array index 초기화
	$temp_files = array();
	foreach ($files as $f) {
		$temp_files[] = $f;
	}
	$files = $temp_files;

	//debug용 $files 출력
    echo "<!--";
	print_r($files);
	echo "-->";
	
	//한바퀴 다 돈 경우 다시 초기화
	if(count($files) <= $cnt) {
		$cnt = 0;
		shuffle($files);
	}
	//배열 하나 남았을 때
	if(count($files) <= 1) {
		echo "<h1>Champion</h1>";
		echo "<img src='$image_dir/".$files[0]."'  style='width:50%;float: left;'></a>\n";
	}
	else {
		$for_count = 0;
		$file_name = array();
		if ($cnt == 0) {
			echo "<h1>".count($files)."강</h1>";
		}
		echo "<h3>image count:".$cnt."</h3>";
		for($i=$cnt;$i<=count($files);$i++) {
			if(!$files[$i]) { continue; }
			$file_name[] = $files[$i];
			$for_count++;
			if($for_count == 2) { break; }
		}
		$cnt += 1;
		if($for_count == 1 ) { 
			$_SESSION['pass'] = 1;
			echo "<h1>라운드 종료. (아래 그림은 부전승 처리)</h1>";
			echo "<a href='?s=".$file_name[0]."&cnt=$cnt'><img src='$image_dir/".$file_name[0]."' id='image'></a>\n";
		}
		else {	
			echo "<a href='?s=".$file_name[1]."&cnt=$cnt'><img src='$image_dir/".$file_name[0]."' id='image'></a>\n";
			echo "<a href='?s=".$file_name[0]."&cnt=$cnt'><img src='$image_dir/".$file_name[1]."' id='image'></a>\n";
		}
		echo "<br />";
	}
	$_SESSION['files'] = $files;
	echo "</div>";
}

?>
<div id='footer'>
<hr>
<a href="?c=init">초기화</a>
</div>
</body>
</html>
