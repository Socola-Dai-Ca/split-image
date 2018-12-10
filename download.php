<?php
error_reporting(0);
$dir = "rsc/";

if(!empty($_GET["id"]) && !empty($_GET["_e"]) && $_GET["_c"] == 2 || $_GET["_c"] == 4 && $_GET["_e"] == "jpg" || $_GET["_e"] == "png") {
	if(file_exists($dir . $_GET["id"] . "-1." . $_GET["_e"]) || file_exists($dir . "cropto4Image/" . $_GET["id"] . "-1." . $_GET["_e"])){	
		if($_GET["_c"] == 4){
			$dir .= "cropto4Image/";
			$zipdir = $dir . "release/";
			
			if(!file_exists($dir . $_GET["id"] . "-1." . $_GET["_e"])){
				die("file id not exist");
			}
		}
		else
			$zipdir = $dir . "release/";

		$zip = new ZipArchive;
		$tmp_file = $zipdir . $_GET["id"] . ".zip";

		if ($zip->open($tmp_file,  ZipArchive::CREATE)) {
			$zip->addFile($dir . $_GET["id"] . "-1." . $_GET["_e"], "SplitImage1." . $_GET["_e"]);
			$zip->addFile($dir . $_GET["id"] . "-2." . $_GET["_e"], "SplitImage2." . $_GET["_e"]);
			if($_GET["_c"] == 4 && file_exists($dir . $_GET["id"] . "-3." . $_GET["_e"])){
				$zip->addFile($dir . $_GET["id"] . "-3." . $_GET["_e"], "SplitImage3." . $_GET["_e"]);
				$zip->addFile($dir . $_GET["id"] . "-4." . $_GET["_e"], "SplitImage4." . $_GET["_e"]);
			}
			$zip->close();
			echo 'Archive created!';
			header('Content-disposition: attachment; filename=' . $_GET["id"] .'.zip');
			header('Content-type: application/zip');
			readfile($tmp_file);
		} else {
		   echo 'Failed!';
		}
	} else {
		echo "file id not exist";
	}
	
} else {
	echo "invail method";
}