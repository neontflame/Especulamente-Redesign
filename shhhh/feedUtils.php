<?php 

function renderarProjetosMasDessaVezNoRss(&$projetos, &$canal) {
	global $config;
	
	foreach ($projetos as $proj) {
		// Cria outro elemento <item> dentro de <channel>
		$date = date_create($proj->data);
		
		$item = $canal->addChild('item');

		$item->addChild('title', $proj->nome);
		$item->addChild('link', $config['URL'] . '/projetos/' . $proj->id);
		
		$oGrandeEmbed = '';
		
		if ($proj->tipo == 'md') {
			$tiposDeVideo = ['mp4', 'avi', 'mkv'];
			$tiposDeFlash = ['swf'];
			$tiposDeAudio = ['mp3', 'wav', 'ogg'];
								
			$arquivos_de_vdd = explode('\n', $proj->arquivos);
			
			foreach ($arquivos_de_vdd as $arquivito) {
				$eh_um_video = in_array(pathinfo($arquivito, PATHINFO_EXTENSION), $tiposDeVideo);
				$eh_um_flash = in_array(pathinfo($arquivito, PATHINFO_EXTENSION), $tiposDeFlash);
				$eh_um_audio = in_array(pathinfo($arquivito, PATHINFO_EXTENSION), $tiposDeAudio);
				
				if ($eh_um_video) {
					$oGrandeEmbed = $oGrandeEmbed . '<video src="/static/projetos/' . $proj->id . '/' . $arquivito . '" controls="true">Seu navegador não tem suporte pra tag de vídeo!!</video>';
				}
				else if ($eh_um_flash) {
					$oGrandeEmbed = $oGrandeEmbed . '<embed type="application/x-shockwave-flash" src="/static/projetos/' . $proj->id . '/' . $arquivito . '">Seu leitor RSS não tem suporte pra Flash!!</embed>';
				}
				else if ($eh_um_audio) {
					$oGrandeEmbed = $oGrandeEmbed . '<audio src="/static/projetos/' . $proj->id . '/' . $arquivito . '">Seu leitor RSS não tem suporte pra tag de áudio!!</audio>';
				}
				else {
					$oGrandeEmbed = $oGrandeEmbed . '<img src="/static/projetos/' . $proj->id . '/' . $arquivito . '">';
				}
				$oGrandeEmbed = $oGrandeEmbed . '<br>';
			}
			
			$item->addChild('description', $oGrandeEmbed . responde_clickers(trocadorDeImagemCoiso($proj->descricao, $proj->id))); 
		} else {
			if ($proj->tipo != 'bg' && $proj->tipo != 'rt') {
				$arquivos_de_vdd = explode('\n', $proj->arquivos_de_vdd);
				$arquivos = explode('\n', $proj->arquivos);
				
				$oContador = 0;
				if (count($arquivos_de_vdd) > ($proj->tipo == 'jg' ? 1 : 0)) {
					$oGrandeEmbed = $oGrandeEmbed . '<h2>Lista de arquivos</h2><ul>';
					
					foreach ($arquivos_de_vdd as $arquivito) {
						$oGrandeEmbed = $oGrandeEmbed . '<li><a href="/static/projetos/' . $proj->id . '/' . $arquivos[$oContador] . '" download="' . $arquivito . '">' . $arquivito . '</a></li>';
						$oContador += 1;
					}
					$oGrandeEmbed = $oGrandeEmbed . '</ul>';
				}
			}
			
			if ($proj->tipo == 'rt') {
				$oGrandeEmbed = '<a href="/~' . $proj->arquivos_de_vdd . '">Acesse esse <i>o resto<i> aqui!</a>';
			}
				
			if ($proj->thumbnail != null) { 
				$item->addChild('description', '<img width="128" src="/static/projetos/' . $proj->id . '/thumb/' . $proj->thumbnail . '"><br>' . $oGrandeEmbed . responde_clickers(trocadorDeImagemCoiso($proj->descricao, $proj->id)));
			} else {
				$item->addChild('description', $oGrandeEmbed . responde_clickers(trocadorDeImagemCoiso($proj->descricao, $proj->id)));
			}
		}
		
		if (isset($proj->dataBump)) {	$item->addChild('pubDate', $proj->dataBump);	}
		else {	$item->addChild('pubDate', $proj->data);	}
	}
}

function trocadorDeImagemCoiso($texto, $projid)
{
	$projeto = projeto_requestIDator($projid);
	
	$arquivos = explode('\n', $projeto->arquivos);
	$arquivos_de_vdd = explode('\n', $projeto->arquivos_de_vdd);
	$id = $projeto->id;

	$trocador = [];

	foreach ($arquivos as $i => $arquivo) {
		$trocador += [
			'^!\[(.*?)\]\(' . $arquivos_de_vdd[$i] . '\)^' => '![$1](/static/projetos/' . $id . '/' . $arquivo . ')'
		];
	}
	$texto = preg_replace(array_keys($trocador), array_values($trocador), $texto);

	return $texto;
}