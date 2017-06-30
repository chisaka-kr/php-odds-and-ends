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
	html,body {
	  height: 100%;
	  margin: 0;
	  padding:0;
	}
    .image_container {
	  position:relative;
	  width:100%;
	  height:90%;
	  line-height:90%;
	  text-align:center;
	  overflow:hidden;
    }
    .image {
      position: relative;
	  -webkit-animation: image-vanish 5s;
	  width:auto;
      max-width:45%;
	  height:auto;
	  max-height:100%;
  	  float:left;

	  margin-left:auto;
	  margin-right:auto;
	  opacity:0.8

    }
	.image:hover {
	  opacity:1;
	}
	#content {
	  height:100%;
	}
	#status:hover {
	  opacity:1;
	}
	#status {
	-webkit-animation: status-vanish 3s;
	position: fixed;
	top:0;
	min-height:30px;
	font-size:15pt;
	height:auto;
	width: 100%;
	overflow: hidden;
	z-index:5;
	background-color:#EEE;
	opacity:0;
	}	
	@-webkit-keyframes status-vanish {
		0% { opacity:0.8; }
		50% { opacity:0.8; }
		100% { opacity:0;}
	}
	@-webkit-keyframes image-vanish {
		0% { opacity:1; }
		90% { opacity:1; }
		100% { opacity:0.8;}
	}
  </style>
<body>
<div id="status">
<?php

$image_dir = "./image_files";

$command = $_GET['c'];
$sel = $_GET['s'];
$sel2 = $_GET['s2'];
$cnt = $_GET['cnt']; 
if(!$_GET['cnt']) { $cnt = 0; }

session_start();
?>
<a href="?c=init">초기화</a>

<?php

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
	echo "이미지 개수: ".count($files)."개 / ";

}

if($_SESSION['files'] != null) {

	//세션에서 값 가져오기
	$files = $_SESSION['files'];
	$image_dir = $_SESSION['image_dir'];
	$max = $_SESSION['max'];
	

	//골라지지 않은 것 삭제..
	if ($_SESSION['pass'] != 1) {
		foreach ($files as $f => $val) {
			if ($sel == $val) {
				echo "OK.";
				unset($files[$f]);
			}
			if ($sel2 == $val) {
				echo " 2 images removed. ";
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
		echo "</div>";
		echo "<div class='image_container'>";
		echo "<h1>Winner</h1>";
		echo "<img src='$image_dir/".$files[0]."'></a>\n";
		echo "</div>";
	}
	else {
		$for_count = 0;
		$file_name = array();
		if ($cnt == 0) {
			echo count($files)."강";
		}
		echo "($cnt/".count($files).")";
	echo "</div>";
	echo "<div id='content'>";
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
			echo "<div class=image_container>";
			echo "<a href='?s=".$file_name[0]."&cnt=$cnt'><img src='$image_dir/".$file_name[0]."' id='image'></a>\n";
			echo "</div>";
		}
		else {	
			echo "<div class='image_container'>";
			echo "<a href='?s=".$file_name[1]."&cnt=$cnt'><img src=$image_dir/".$file_name[0]." class='image'></a>\n";
			echo "<a href='?s=".$file_name[0]."&cnt=$cnt'><img src=$image_dir/".$file_name[1]." class='image'></a>\n";
			echo "</div>";
			if(count($files)>2) {
			echo "<a href='?s=".$file_name[0]."&s2=".$file_name[1]."'>둘 다 제거</a>";
			}
		}
		
	}
	$_SESSION['files'] = $files;
	echo "</div>";
}

?>
</div>
</body>
</html>
