<?php
$ads = [
	['ad1.png', 'https://www.google.com.br/search?q=cabelo'],
	['ad2.png', 'https://store.steampowered.com/app/2161700/Persona_3_Reload/'],
	['ad3.png', 'https://especulamente.com.br'],
	['ad4.gif', 'https://fupi.cat/arte/feiocore/'],
	['ad5.png', 'https://www.sentaifilmworks.com/blogs/catalog/magical-play'],
	['ad6.png', 'https://www.newgrounds.com/portal/view/770371'],
	['ad7.png', 'https://scratch.mit.edu'],
];
$ad = $ads[array_rand($ads)];
?>
<div class="left_col">
	<a href="/projetos/"><img src="/elementos/sidebar/projetosInativo.png" onmouseover="this.src='/elementos/sidebar/projetosAtivo.png';" onmouseout="this.src='/elementos/sidebar/projetosInativo.png';" /></a><br />
	<a href="/colecoes/"><img src="/elementos/sidebar/colecoesInativo.png" style="margin-top: 8px;" onmouseover="this.src='/elementos/sidebar/colecoesAtivo.png';" onmouseout="this.src='/elementos/sidebar/colecoesInativo.png';" /></a><br />
	<img style="margin-top: 8px;" src="/elementos/sidebar/tiposcoisa.png">
	<a href="/midia/"><img src="/elementos/sidebar/midiaInativo.png" onmouseover="this.src='/elementos/sidebar/midiaAtivo.png';" onmouseout="this.src='/elementos/sidebar/midiaInativo.png';" /></a><br />
	<a href="/jogos/"><img src="/elementos/sidebar/jogosInativo.png" onmouseover="this.src='/elementos/sidebar/jogosAtivo.png';" onmouseout="this.src='/elementos/sidebar/jogosInativo.png';" /></a><br />
	<a href="/blogs/"><img src="/elementos/sidebar/blogsInativo.png" onmouseover="this.src='/elementos/sidebar/blogsAtivo.png';" onmouseout="this.src='/elementos/sidebar/blogsInativo.png';" /></a><br />
	<a href="/downloadaveis/"><img src="/elementos/sidebar/downloadaveisInativo.png" onmouseover="this.src='/elementos/sidebar/downloadaveisAtivo.png';" onmouseout="this.src='/elementos/sidebar/downloadaveisInativo.png';" /></a><br />
	<a href="/resto/"><img src="/elementos/sidebar/orestoInativo.png" style="margin-top: 8px;" onmouseover="this.src='/elementos/sidebar/orestoAtivo.png';" onmouseout="this.src='/elementos/sidebar/orestoInativo.png';" /></a>
	<?php if (($forum_no_lado ?? false)) : ?>
		<br />	<style>
	.listinhaDeForinhos {
		background-image: url('/elementos/sidebar/forumBg.png');
		border: 1px solid #5D85E2;
	}
		
	.minipost {
		margin: 0px 4px 0px 4px;
		border-bottom: 1px solid #9EBBFF;
		font-size: 11px;
	}

	.minipost .categoria {
		color: #AAC9FF;
		font-weight: bold;
		margin-top: 4px;
		margin-bottom: 2px;
	}

	.minipost .titulo {
		color: #000000;
		font-weight: bold;
		text-decoration: none;
		margin-top: 4px;
		margin-bottom: 0px;
	}

	.minipost .titulo:hover {
		text-decoration: underline;
	}

	.minipost .criadorTitulo {
		color: #808080;
		text-decoration: none;
		font-size: 9px;
		margin-left: 4px;
		margin-top: 4px;
		margin-bottom: 0px;
	}

	.minipost .texto {
		color: #5A5A7F;
		font-size: 9px;
		margin-top: 4px;
		margin-bottom: 0px;
	}

	.minipost .bump {
		color: #9EBBFF;
		font-size:10px;
		margin-top: 4px;
		margin-bottom: 4px;
	}

	.minipost:last-child {
		border-bottom: none;
	}
	</style>
		<img src="/elementos/sidebar/conversely.png" style="margin-top: 6px;" />
		<!-- ANUNCIOS SAO 180x208-->
	<div class="listinhaDeForinhos">
		<?php
		$posts = [];
		$rows = $db->prepare("SELECT * FROM forum_posts WHERE id_resposta = -1 ORDER BY dataBump DESC LIMIT 6");
		$rows->execute();
		
		while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
			array_push($posts, $row);
		}
		foreach ($posts as $post) { ?>
		<div class="minipost">
			<p class="categoria"><?= categoria_requestIDator($post->id_categoria)->nome ?></p>
			<a class="titulo" href="/foruns/<?= $post->id_categoria ?>/<?= $post->id ?>"><?= htmlspecialchars($post->sujeito) ?></a><a class="criadorTitulo" href="/usuarios/<?=usuario_requestIDator($post->id_postador)->username ?>">por <?=usuario_requestIDator($post->id_postador)->username ?></a>
			<p class="texto"><?= explode("\n", markdown_apenas_texto($post->conteudo))[0] ?></p>
			<p class="bump">bump: <?= velhificar_data($post->dataBump) ?></p>
		</div>
		<?php } ?>
	</div>
		<a href="/foruns/"><img src="/elementos/sidebar/verForunsInativo.png" onmouseover="this.src='/elementos/sidebar/verForunsAtivo.png';" onmouseout="this.src='/elementos/sidebar/verForunsInativo.png';" /></a>
	<?php endif ?>
	
	<?php if (!($esconder_ad ?? false)) : ?>
		<br />
		<img src="/elementos/sidebar/patrociono.png" style="margin-top: 6px;" />
		<!-- ANUNCIOS SAO 180x208-->
		<a href="<?= $ad[1] ?>" target="_blank"><img width="180" height="208" style="border: 1px solid #5D85E2;" src="/elementos/sidebar/patrocinios/<?= $ad[0] ?>" /></a>
	<?php endif ?>
</div>