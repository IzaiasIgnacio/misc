<?php
$pasta = opendir(getcwd());

while ($arquivo = readdir($pasta)) {
	if (substr($arquivo,-4) == '.mp4') {
		$nfo = fopen(substr($arquivo,0,-4).".nfo","w");
		fwrite($nfo, "<musicvideo>\r\n");
        fwrite($nfo, "	<title>".substr($arquivo,0,-4)."</title>\r\n");
        fwrite($nfo, "	<artist>Bestartistintheworld</artist>\r\n");
		fwrite($nfo, "	<album>album</album>\r\n");
        fwrite($nfo, "	<genre>Pop</genre>\r\n");
        fwrite($nfo, "	<runtime>3:20</runtime>\r\n");
        fwrite($nfo, "	<year>2000</year>\r\n");
		fwrite($nfo, "</musicvideo>\r\n");
		fclose($nfo);
	}
}