<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
if (!isset($_GET['id'])) {
	erro_404();
}
$id = $_GET['id'];

$projeto = projeto_requestIDator($id);
if ($projeto == null) {
	erro_404();
}
// EXPLODIR HOSTINGER
$arquivos = explode('\n', $projeto->arquivos);
$arquivos_de_vdd = explode('\n', $projeto->arquivos_de_vdd);
$arquivo_vivel = $projeto->arquivo_vivel == '' ? ['', ''] : explode('\n', $projeto->arquivo_vivel);

// bug q a gente surpreendentemente nao tinha pego
$projeto_e_meu = false;

if (isset($usuario)) {
	$projeto_e_meu = $projeto->id_criador == $usuario->id;
}

?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<style>
	.jogo {
		border: 1px solid #9ebbff;
	}
</style>
<div class="container">
	<?php /* include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; */ ?>

	<div class="page_content" style="min-height: 120px; margin-left: 0">
		<div class="projTitulo">
			<?php if ($projeto_e_meu) : ?>
				<a href="<?= $config['URL'] ?>/projetos/editar.php?id=<?= $projeto->id ?>" style="float:right; margin:8px;"><img src="/elementos/botaoEditar.png"></a>
			<?php endif ?>
			<?php if ($arquivos[0] != '') : ?>
				<a href="<?= $config['URL'] ?>/projetos/zipar.php?id=<?= $projeto->id ?>" style="float:right; margin:8px;"><img src="/elementos/botaoTransferir.png"></a>
			<?php endif ?>

			<h1><i><?= $projeto->nome ?></i></h1>
			<p>por <a href="/usuarios/<?= usuario_requestIDator($projeto->id_criador)->username ?>"><?= usuario_requestIDator($projeto->id_criador)->username ?></a></p>
		</div>
		<div class="inside_page_content">
			<?php if ($projeto->tipo == 'jg') : ?>
				<!-- Embed -->
				<?php if (str_ends_with($arquivo_vivel[0], '.swf')) : ?>
					<!-- JOGOS FLASH -->
					<div style="margin: 0 auto 16px; width: -moz-fit-content; width: intrinsic; width: fit-content;">
						<div class="jogo">
							<object width="auto" height="auto" data="<?= $config['URL'] . '/static/projetos/' . $projeto->id . '/' . $arquivo_vivel[1] ?>" allowfullscreen="true">
							</object>
						</div>
					</div>
				<?php endif; ?>
				<?php if (str_ends_with($arquivo_vivel[0], '.zip')) : ?>
					<!-- JOGOS HTML -->
					<div class="jogo" style="margin: 0 auto 16px;">
						<iframe width="100%" height="360" src="<?= $config['URL'] . '/static/projetos/' . $projeto->id . '/jogo/index.html' ?>" frameborder="0" allowfullscreen="true"></iframe>
					</div>
				<?php endif; ?>
				<?php if (str_ends_with($arquivo_vivel[0], '.sb') || str_ends_with($arquivo_vivel[0], '.sb2')) : ?>
					<!-- JOGOS SCRATCH 1.x/2.0 -->
					<div style="margin: 0 auto 16px; width: -moz-fit-content; width: intrinsic; width: fit-content;">
						<object data="/projetos/Scratch.swf" height="387" width="482">
							<param name="allowScriptAccess" value="sameDomain">
							<param name="allowFullScreen" value="true">
							<param name="flashvars" value="project=<?= $config['URL'] . '/static/projetos/' . $projeto->id . '/' . $arquivo_vivel[1] ?>&autostart=false">
						</object>
					</div>
				<?php endif; ?>

				<?php if (str_ends_with($arquivo_vivel[0], '.sb3')) : ?>
					<!-- JOGOS SCRATCH 3.0 (CRONOLOGICAMENTE INNACURATE MAS WHATEVER) -->
					<div class="jogo" style="margin: 0 auto 16px; width: -moz-fit-content; width: intrinsic; width: fit-content;">
						<iframe src="http://turbowarp.org/embed?project_url=<?= $config['URL'] ?>/static/projetos/<?= $projeto->id ?>/<?= $arquivo_vivel[1] ?>" width="482" height="412" allowtransparency="true" frameborder="0" scrolling="no" allowfullscreen></iframe>
					</div>
				<?php endif; ?>

			<?php endif; ?>

			<?php if (($projeto->tipo == 'dl' || $projeto->tipo == 'jg') && $arquivos[0] != '') : ?>
				<!-- downloadavel -->
				<!-- isso tecnicamente nao sao projetos mas nada me impede de reusar o css deles lol -->
				<div class=" projetos">
					<?php foreach ($arquivos as $i => $arquivo) : ?>
						<div class="projeto">
							<a href="<?= $config['URL'] ?>/static/projetos/<?= $projeto->id ?>/<?= $arquivo ?>" download="<?= $arquivos_de_vdd[$i] ?>"><img src="/elementos/botaoTransferirSingular.png"></a>
							<img src="/elementos/filetypes/<?= the_filetype_image($arquivo, '/static/projetos/' . $projeto->id) ?>.png" style="float:left; margin-right: 8px;">
							<h2><?= $arquivos_de_vdd[$i] ?></h2>
							<p><?= human_filesize($arquivo, '/static/projetos/' . $projeto->id) ?></p>
						</div>
					<?php endforeach ?>
				</div>
			<?php endif ?>

			<?php if ($projeto->tipo == 'md') : ?>
				<!-- midia -->
				<div class="vedorDImagem">
					<div class="pagination" style="width: 620px; margin: 0 0 0 0;">
						<style>
							a {
								cursor: pointer;
							}
						</style>
						<a onclick="comecoCoisa()">Início</a>
						<p class="textinhoClaro">~</p>
						<a onclick="anteriorCoisa()">« Anterior</a>
						<p class="textinhoClaro">~</p>
						<p id="paginacio">Página 1 de 2</p>
						<p class="textinhoClaro">~</p>
						<a onclick="proximoCoisa()">Próximo »</a>
						<p class="textinhoClaro">~</p>
						<a onclick="fimCoisa()">Fim</a>
					</div>
												  
					<video id="videoAtual" width="620" autoplay="false" controls="true">
					Seu navegador não tem suporte pra tag de vídeo!!
					</video> 
					<img src="/elementos/chillmaia.png" id="imagemAtual" onclick="window.open(this.src, '_blank').focus();">

					<br>

					<div class="outrasImagens">
					</div>

					<script>
						var tiposDeVideo = ['mp4', 'ogg', 'avi', 'mkv'];
						var re = /(?:\.([^.]+))?$/; // obrigado tomalak from stack overflow
						var projid = 0;
						var imagens = ["img1.png", "img2.png", "img3.png"];
						
						var curSelected = 0;

						function fazONegocioDasImagens() {
							for (var imgcoiso = 0; imgcoiso < imagens.length; imgcoiso++) {
								
								var img = document.createElement("img");
								if (tiposDeVideo.includes(re.exec(imagens[imgcoiso])[1])) {
									img.src = "/static/video_coiso.png";
								} else {
									img.src = "/static/projetos/" + projid + "/" + imagens[imgcoiso];
								}
								img.className = "imagemCoiso";
								img.id = imgcoiso;
								img.onclick = function() {
									var quiamsas = document.getElementsByClassName('outrasImagens')[0].children;

									for (var kid = 0; kid < quiamsas.length; kid++) {
										quiamsas[kid].className = "imagemCoiso";
									}
									clicCoiso(this.id)
									this.className = "imagemCoiso desopaco";
								};
								document.getElementsByClassName("outrasImagens")[0].appendChild(img);
							}

							clicCoiso(0);
						}

						function clicCoiso(id) {
							// codigo com alma ?
							console.log('clic');
							
							if (tiposDeVideo.includes(re.exec(imagens[id])[1])) {
								document.getElementById("videoAtual").style.display = "block";
								document.getElementById("videoAtual").src = "/static/projetos/" + projid + "/" + imagens[id];
								document.getElementById("imagemAtual").style.display = "none";
							} else {
								document.getElementById("videoAtual").pause();
								document.getElementById("videoAtual").style.display = "none";
								document.getElementById("imagemAtual").src = "/static/projetos/" + projid + "/" + imagens[id];
								document.getElementById("imagemAtual").style.display = "block";
							}
							
							var quiamsas = document.getElementsByClassName('outrasImagens')[0].children;

							for (var kid = 0; kid < quiamsas.length; kid++) {
								quiamsas[kid].className = "imagemCoiso";
							}
							quiamsas[id].className = "imagemCoiso desopaco";
							
							document.getElementById("paginacio").innerText = "Página " + (id+1) + " de " + imagens.length;
						}


						function carregarImagens(id) {
							var xhttp = new XMLHttpRequest();
							xhttp.onload = function() {
								projid = id;
								imagens = this.responseText.split("\n");
								imagens.shift();
								imagens.pop();
								fazONegocioDasImagens();
							};
							xhttp.open(
								"GET",
								"/projetos/vedorDImagem.php?id=" +
								id + "&modo=internal",
								true
							);
							xhttp.send();
						}
						
						// paginacios
						function comecoCoisa() {
							curSelected = 0;
							clicCoiso(curSelected);
						}
						
						function anteriorCoisa() {
							if (curSelected > 0) {
								curSelected -= 1;
								clicCoiso(curSelected);
							}
						}
						
						function proximoCoisa() {
							if (curSelected < imagens.length - 1) {
								curSelected += 1;
								clicCoiso(curSelected);
							}
						}
						
						function fimCoisa() {
							curSelected = imagens.length - 1;
							clicCoiso(curSelected);
						}

						carregarImagens(<?= $projeto->id ?>);
					</script>
				</div>
				<!--
				<object width="600" height="360" data="/elementos/vedorDImagem.swf" allowfullscreen="true">
					<param name="flashvars" value="server=<?= $config['URL'] ?>/&projectid=<?= $projeto->id ?>" />
				</object>
				-->
			<?php endif ?>

			<?php if ($projeto->tipo == 'rt') : ?>
				<!-- Embed -->
				<a href="<?= $config['URL'] . '/~' . $arquivos_de_vdd[0] ?>">Visualizar site!</a>
				<div class="jogo" style="margin: 0 auto 16px;">
					<iframe width="100%" height="360" src="<?= $config['URL'] . '/~' . $arquivos_de_vdd[0] ?>" frameborder="0" allowfullscreen="true"></iframe>
				</div>
			<?php endif; ?>
		</div>

		<div class="inside_page_content" style="margin-top: 8px; margin-bottom: 8px;">
			<div class="descricao" style="white-space:pre-line"><?= responde_clickers($projeto->descricao) ?></div>
			<?php reajor_d_reagida('projeto', $projeto, $usuario) ?>
		</div>

	</div>

	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content" style="min-height: 486px;">
		<div class="inside_page_content">
			<?php vedor_d_comentario('projeto', $projeto->id, true, $usuario); ?>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>