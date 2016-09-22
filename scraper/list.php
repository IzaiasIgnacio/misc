<?php
function trata_string($string) {
	return trim(str_replace("-", "", str_replace("the ","", strtolower($string))));
}
$pasta = opendir ("P:/Games/emulators/mameplus/roms") or die('erro');
while ($arquivo = readdir($pasta)) {
	if ($arquivo != '.' && $arquivo != '..') {
		$roms[] = trim(substr($arquivo,0,-4));
	}
}
$ok = 0;
$varios = 0;
$nenhum = 0;
$lista = fopen("list.txt","r");
while ($linha = fgets($lista)) {
	$game = explode("\"",$linha);

	if (in_array(trim($game[0]), $roms)) {
		$g = explode("(",$game[1]);
		$g = explode("[", $g[0]);
		$g = explode(":", $g[0]);
		$g = explode("/", $g[0]);
		$g = explode(" - ", $g[0]);
		$xml = simplexml_load_string(file_get_contents("http://thegamesdb.net/api/GetGamesList.php?platform=arcade&name='".urlencode(trim($g[0]))."'"));
		$id = array();

		foreach ($xml as $x) {
			if (strpos(trata_string($x->GameTitle), trata_string($g[0])) !== FALSE) {
				$id[] = $x->id;
			}
		}

		if (count($id) == 1) {
			echo trim($g[0])." 1 resultado";
			echo "<br>";
			echo "\r\n";
			$ok++;
		}

		if (count($id) > 1) {
			echo trim($g[0]);
			echo "<br>";
			echo "\r\n";
			print_r($id);
			$varios++;
		}

		if (count($id) == 0) {
			echo trim($g[0])." nenhum resultado";
			echo "<br>";
			echo "\r\n";
			$nenhum++;
		}
	}
}
echo "ok ".$ok;
echo "<br>";
echo "\r\n";
echo "varios ".$varios;
echo "<br>";
echo "\r\n";
echo "nenhum ".$nenhum;