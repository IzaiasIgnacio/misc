<?php
if (!file_exists('gamelist.xml')) {
	$f = fopen('gamelist.xml', 'w');
	fwrite($f, "<?xml version='1.0'?>\r\n");
	fwrite($f, "<gameList>\r\n");
}
?>
<script src="jquery.js"></script>
<script type="text/javascript">
	var roms;
	var i = 0;
	$(function() {
		$.post('scraper.jquery.php',{acao:'listar_roms'},
		function(resposta) {
			if (resposta != '0') {
				roms = jQuery.parseJSON(resposta);
				buscar(roms[i]);
			}
			else {
				console.log('0');
			}
		});
	});

	function buscar(game) {
		$("#escolha").html('');
		if (game) {
			$.post('scraper.jquery.php',{acao:'buscar',game:game},
			function(resposta) {
				switch (resposta) {
					case '1 resultado':
						i++;
						buscar(roms[i]);
					break;
					default:
						i++;
						var dados = jQuery.parseJSON(resposta);
						if (dados.length > 0) {
							$("#escolha").html(dados[0].titulo+"<br>");
							$.each(dados, function(i,dados) {
								var game = [dados.id,dados.arquivo,dados.titulo];
								$("#escolha").append(dados.GameTitle+" <input type='button' value='Escolher' onclick='javascript:add(\""+game+"\")'><br>");
							});
							$("#escolha").append("Nenhum <input type='button' value='Escolher' onclick='javascript:buscar(roms["+i+"])'><br>");
						}
						else {
							buscar(roms[i]);
						}
					break;
				}
			});
		}
		else {
			$.post('scraper.jquery.php',{acao:'fechar'});
		}
	}

	function add(game) {
		$.post('scraper.jquery.php',{acao:'add_game',game:game},
		function(resposta) {
			i++;
			buscar(roms[i]);
		});
	}
</script>
<div id='escolha'></div>