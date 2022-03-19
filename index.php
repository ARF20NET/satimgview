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
	$_GET["type"] = "raw";
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
		<a class="text" href="?type=raw">raw</a>
		<a class="text" href="?type=HVC">HVC</a>
		<a class="text" href="?type=HVC-precip">HVC-precip</a>
		<a class="text" href="?type=HVCT-precip">HVCT-precip</a>
		<a class="text" href="?type=HVCT">HVCT</a>
		<a class="text" href="?type=MCIR-precip">MCIR-precip</a>
		<a class="text" href="?type=MCIR">MCIR</a>
		<a class="text" href="?type=MSA-precip">MSA-precip</a>
		<a class="text" href="?type=MSA">MSA</a>
		<a class="text" href="?type=therm">therm</a>
		<hr>
		<div class="content">

		<?php
			
			
			$imagdir = "/d/FTPServer/satimg/apt/".$type."/";
			$files = scandir($imagdir);
			
			
			$times = array();
			foreach ($files as $file)
				if ($file != "." && $file != "..")
					array_push($times, substr($file, 8, 16));			
			rsort($times);
			
			foreach ($times as $time) {
				$file = array_search_partial($files, $time);
				
				$satstr = substr($file, 0, strpos($file, '_'));

				$timestr = substr($time, 0, 4)."-".substr($time, 4, 2)."-".substr($time, 6, 2)." ".substr($time, 9, 2).":".substr($time, 11, 2).":".substr($time, 13, 2);
				$timestamp = strtotime($timestr);
				
				$ago = time() - $timestamp;
				$agostr = secsToAgo($ago);
				
				echo "<a href=\"/FTPServer/satimg/apt/$type/$file\"><img width=\"100%\" src=\"/FTPServer/satimg/apt/$type/$file\"></a><br>
					<div class=\"text\"<span>$satstr: $timestr, $agostr ago</span></div><br>";
			}
		?>
		
		</div>
		
	</body>
</html>