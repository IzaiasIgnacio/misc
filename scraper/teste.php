<?php
function trata_string($string) {
	return trim(str_replace("-", "", str_replace("the ","", strtolower($string))));
}
$game[1] = "The last blade 2";
$g = explode("(",$game[1]);
$g = explode("[", $g[0]);
$g = explode(":", $g[0]);
$g = explode("/", $g[0]);
$g = explode(" - ", $g[0]);

$xml = simplexml_load_string(file_get_contents("http://thegamesdb.net/api/GetGamesList.php?platform=arcade&name='".urlencode(trim($g[0]))."'"));
$id = array();

foreach ($xml as $x) {echo trata_string($x->GameTitle).">".trata_string($g[0]);
echo "<br>";
	if (strpos(trata_string($x->GameTitle), trata_string($g[0])) !== FALSE) {
		echo $x->GameTitle;
		echo "\r\n";
		$id[] = $x->id;
	}
}

print_r($id);