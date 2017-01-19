<?php
// php generatelink.php file_path
echo $argv[1]."\n";

echo md5($argv[1])."\n";

$filepath = getcwd().$argv[1];

// file exist test
if (file_exists($argv[1]) == FALSE)
{
	echo $argv[1]." : file not exist.\n";
	die("...");
}

// generatelink.ini 
// filemap.txt 
$ini_array = parse_ini_file(getcwd()."generatelink.ini", true);
if ($ini_array == FALSE)
	die("There is no generatelink.ini");

// [mapfile]
// path=xxxx
$mapfilepath = $ini_array['mapfile']['path'];

// [server]
// url=xxxx
$url = $ini_array['server']['url'];

$myfile = fopen($mapfilepath, "a");
if ($myfile == FALSE)
	die("There is no mapfile : [".$mapfilepath."]\n");

$digest = md5($filepath);

$line = $digest." "."\"".$filepath."\"\n";

fwrite($myfile, $line);

fclose($myfile);

// show link
echo $url."/download.php?Token=".$digest."\n";

?>
