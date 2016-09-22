<?php
//exec('ffmpeg -ss 00:00:02 -i rr.mp4 -frames 1 -vf "select=not(mod(n\,80)),scale=640:480,tile=2x3" out.png');
function segundos($tempo) {
	$t = explode(":", $tempo);
	$horas = $t[0];
	$minutos = $t[1];
	$segundos = $t[2];
	return $t[2] + ($t[1]*60) + ($t[0]*60*60);
}
function tempo($segundos) {
	return gmdate("H:i:s", $segundos);
}
$segundos = 0;
exec("ffmpeg.exe -i rr.mp4 2>&1", $info);
foreach ($info as $i) {
	$a = explode(":",$i);
	if (trim($a[0]) == 'Duration') {
		$i = explode(",",$i);
		//echo $i[0];
		$t = explode(": ",$i[0]);
		//echo substr($t[1],0,8);
		$segundos = segundos(substr($t[1],0,8));

	}
}
unlink("out.png");
$s = $segundos/3;

for ($sc=$s;$sc<=$segundos;$sc+=$s) {
	echo $tempo = tempo($sc);
	exec("ffmpeg.exe -i rr.mp4 -ss ".$tempo." -vframes 1 out_".$sc.".png");
}