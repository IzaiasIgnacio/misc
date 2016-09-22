<?php
function trata_string($string) {
	return trim(str_replace("'", "", str_replace("-", "", str_replace("the ","", strtolower($string)))));
}
function add_game($dados) {
	$xml = simplexml_load_string(file_get_contents("http://thegamesdb.net/api/GetGame.php?id=".$dados->id));
	$xml_image = simplexml_load_string(file_get_contents("http://thegamesdb.net/api/GetArt.php?id=".$dados->id));
	if (!file_exists("capas/".$dados->arquivo."-image.jpg")) {
		copy(str_replace("/original/", "/thumb/original/", $xml_image->baseImgUrl.$xml_image->Images[0]->boxart), "capas/".$dados->arquivo."-image.jpg");
	}
	foreach ($xml as $xm) {
		$x = $xm;
	}
	$genre = array();
	if (count($x->Genres->genre) > 0) {
		foreach ($x->Genres->genre as $g) {
			$genre[] = $g;
		}
	}
	$f = fopen('gamelist.xml', 'a');
	fwrite($f, "	<game>\r\n");
	fwrite($f, "		<path>./".$dados->arquivo.".zip</path>\r\n");
	fwrite($f, "		<name>".$dados->titulo."</name>\r\n");
	fwrite($f, "		<desc>".$x->Overview."</desc>\r\n");
	fwrite($f, "		<image>~/.emulationstation/downloaded_images/arcade/".$dados->arquivo."-image.jpg</image>\r\n");
	fwrite($f, "		<rating>".$x->rating."</rating>\r\n");
	fwrite($f, "		<releasedate>".$x->ReleaseDate."</releasedate>\r\n");
	fwrite($f, "		<developer>".$x->Developer."</developer>\r\n");
	fwrite($f, "		<publisher>".$x->Publisher."</publisher>\r\n");
	fwrite($f, "		<genre>".implode(", ", $genre)."</genre>\r\n");
	fwrite($f, "		<players>".$x->Players."</players>\r\n");
	fwrite($f, "	</game>\r\n");
}
switch ($_POST['acao']) {
	case 'listar_roms':
		/*$pasta = opendir("roms") or die('erro');
		$jogos = array();
		while ($arquivo = readdir($pasta)) {
			if ($arquivo != '.' && $arquivo != '..') {
				if (!file_exists('capas/'.trim(substr($arquivo,0,-4)).'-image.jpg')) {
					$jogos[] = trim(substr($arquivo,0,-4));
				}
			}
		}*/

		//if (count($jogos) > 0) {
			$lista = fopen("list.txt","r");
			$roms = array();
			while ($linha = fgets($lista)) {
				$game = explode("\"",$linha);
				//if (in_array(trim($game[0]), $jogos)) {
					if (!file_exists('capas/'.trim(substr($game[0],0,-4)).'-image.jpg')) {
						$i = count($roms);
						$roms[$i]['arquivo'] = trim($game[0]);
						$roms[$i]['titulo'] = $game[1];
					}
				//}
			}
			echo json_encode($roms);
		/*}
		else {
			echo '0';
		}*/
	break;
	case 'buscar':
		$game = $_POST['game']['titulo'];
		$g = explode("(",$game);
		$g = explode("[", $g[0]);
		$g = explode(":", $g[0]);
		$g = explode("/", $g[0]);
		$g = explode(" - ", $g[0]);
		$xml = simplexml_load_string(file_get_contents("http://thegamesdb.net/api/GetGamesList.php?platform=arcade&name='".urlencode(trim($g[0]))."'"));
		$id = array();
		$opcao = array();
		$vazio = array();

		foreach ($xml as $x) {
			$x->titulo = $_POST['game']['titulo'];
			$x->arquivo = $_POST['game']['arquivo'];
			if (strpos(trata_string($x->GameTitle), trata_string($g[0])) !== FALSE) {
				$opcao[] = $x;
			}
			else {
				$vazio[] = $x;
			}
		}

		if (count($opcao) == 1) {
			echo "1 resultado";
			add_game($opcao[0]);
		}

		if (count($opcao) > 1) {
			echo json_encode($opcao);
		}

		if (count($opcao) == 0) {
			echo json_encode($vazio);
		}
	break;
	case 'add_game':
		$game = explode(",", $_POST['game']);
		$x->titulo = $game[2];
		$x->arquivo = $game[1];
		$x->id = $game[0];
		add_game($x);
	break;
	case 'fechar':
		$f = fopen('gamelist.xml', 'a');
		fwrite($f, "</gameList>");
	break;
}