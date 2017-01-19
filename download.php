<?php

$token = $_GET['Token'];

function mb_basename($path) { return end(explode('/',$path)); }
function utf2euc($str) { return iconv("UTF-8","cp949//IGNORE", $str); }
function is_ie() {
if(!isset($_SERVER['HTTP_USER_AGENT']))return false;
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) return true; // IE8
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Windows NT 6.1') !== false) return true; // IE11
return false;
}

// configuration file
// download.ini
// filemap.txt 
$ini_array = parse_ini_file("download.ini");

// [mapfile]
// path=xxxx
$mapfilepath = $ini_array['mapfile']['path'];

$myfile = fopen($mapfilepath, "r") or die("Unable to open file");

$doc_data = array();

while(!feof($myfile))
{
	$doc_line=fgets($myfile);
	array_push($doc_data, $doc_line);
}

fclose($myfile);

// find file path by input token
$filepath = 'empty';

for($i = 0;$i < count($doc_data); ++$i)
{
	if (strlen($doc_data[$i]) == 0)
		continue;
	$digest = strtok($doc_data[$i], " ");
	$path = trim(strstr($doc_data[$i], "\""), " \t\n\"");

		if ($digest == $token)
	{
		$filepath = $path;
		break;
	}
	//echo $digest." = ".$path."\n";
}

// if key can not be found, 
if ($filepath == 'empty')
	die("no file");

$filesize = filesize($filepath);
$filename = mb_basename($filepath);
if( is_ie() ) $filename = utf2euc($filename);

header("Pragma: public");
header("Expires: 0");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: $filesize");

readfile($filepath);
?>




