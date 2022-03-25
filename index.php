<?php

function check($str) {
	if (strpos($str, "..") === false) {
		
	} else {
		die("No .. allowed, fuck you.");
	}
}

function secsToAgo($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%ad, %h:%i:%s');
}

function array_search_partial($arr, $keyword) {
    for ($i = 0; $i < sizeof($arr); $i++) {
		$string = $arr[$i];
        if (strpos($string, $keyword) !== FALSE)
            return $string;
    }
	return "null";
}

if (!isset($_GET["type"])) {
	$_GET["type"] = "MCIR-precip";
}

check($_GET["type"]);

$type = $_GET['type'];

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/style.css">
        <title>NOAA GSIM</title>
		<style>
			.content {
				//text-align: center;
				margin: auto;
				width: 50%;
			}
			
			.text {
				text-align: left;
				margin-bottom: 30px;
			}
		</style>
	</head>
	<body>
		<h1>NOAA Ground Station Image Viewer</h1>
		<p>Ground station location: IES Ramon Arcas [37.6765, -1.6942, 330.0]</p>
		<hr>
		<a class="home" href="/">Home</a><br>
		<a class="text" href="?type=HVC">HVC</a>
		<a class="text" href="?type=HVC-precip">HVC-precip</a>
		<a class="text" href="?type=HVCT">HVCT</a>
		<a class="text" href="?type=HVCT-precip">HVCT-precip</a>
		<a class="text" href="?type=MCIR">MCIR</a>
		<a class="text" href="?type=MCIR-precip">MCIR-precip</a>
		<a class="text" href="?type=MSA">MSA</a>
		<a class="text" href="?type=MSA-precip">MSA-precip</a>
		<a class="text" href="?type=therm">therm</a>
		<a class="text" href="?type=ZA">ZA</a>
		<a class="text" href="?type=CC">CC</a>
		<a class="text" href="?type=HE">HE</a>
		<a class="text" href="?type=HF">HF</a>
		<a class="text" href="?type=MD">MD</a>
		<a class="text" href="?type=BD">BD</a>
		<a class="text" href="?type=MB">MB</a>
		<a class="text" href="?type=JF">JF</a>
		<a class="text" href="?type=JJ">JJ</a>
		<a class="text" href="?type=LC">LC</a>
		<a class="text" href="?type=TA">TA</a>
		<a class="text" href="?type=WV">WV</a>
		<a class="text" href="?type=NO">NO</a>
		<a class="text" href="?type=sea">sea</a>
		<hr>
		<div class="content">

		<?php
			$imgdir = "/d/FTPServer/satimg/apt/";
			
			$files = array_filter(scandir($imgdir), function($item) {
				global $imgdir;
				return !is_dir($imgdir.$item);
			});
			
			$files = array_filter(scandir($imgdir), function($item) {
				global $type;
				return substr(substr($item, 24), 0, -4) == $type;
			});
			
			$files = array_values($files);
			
			//print_r($files);
			
			$times = array();
			
			foreach ($files as $file)
				array_push($times, substr($file, 8, 15));
			
			rsort($times);
			//array_reverse($times);
			
			//print_r($times);
			
			foreach ($times as $time) {
				$file = array_search_partial($files, $time);
				
				//print($file);
				
				$satstr = substr($file, 0, 7);

				$timestr = substr($time, 0, 4)."-".substr($time, 4, 2)."-".substr($time, 6, 2)." ".substr($time, 9, 2).":".substr($time, 11, 2).":".substr($time, 13, 2);
				$timestamp = strtotime($timestr);
				
				$ago = time() - $timestamp;
				$agostr = secsToAgo($ago);
				
				echo "<a href=\"/FTPServer/satimg/apt/$file\"><img width=\"100%\" src=\"/FTPServer/satimg/apt/$file\"></a><br>
					<div class=\"text\"<span>$satstr: $timestr, $agostr ago</span></div><br>";
			}
		?>
		
		</div>
		
	</body>
</html>