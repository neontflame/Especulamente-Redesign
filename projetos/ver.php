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

<?php
$meta["titulo"] = "[" . $projeto->nome . " <> " . ['dl' => 'Downloadável', 'md' => 'Mídia', 'jg' => 'Jogo', 'bg' => 'Blog', 'rt' => 'Website'][$projeto->tipo] . " no PORTAL ESPECULAMENTE]";
$meta["descricao"] = str_replace("\n", " ", markdown_apenas_texto($projeto->descricao));
$meta["type"] = ['dl' => 'website', 'md' => 'image', 'jg' => 'website', 'bg' => 'article', 'rt' => 'website'][$projeto->tipo];
$meta["pagina"] = '/projetos/' . $projeto->id;
$meta["imagem"] = $projeto->thumbnail ? '/static/projetos/' . $projeto->id . '/thumb/' . $projeto->thumbnail : null;
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<style>
	.jogo {
		border: 1px solid #9ebbff;
		margin-bottom: 12px;
	}
</style>
<div class="container">
	<?php /* include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; */ ?>

	<div class="page_content" style="min-height: 120px; margin-left: 0">
		<div class="projTitulo">
			<?php if ($arquivos[0] != '' && $projeto->tipo != 'bg') : ?>
				<a href="/projetos/<?= $projeto->id ?>/zipar" style="float:right; margin:8px;"><img src="/elementos/botaoTransferir.png"></a>
			<?php endif ?>
			<?php if ($projeto->tipo == 'rt') : ?>
				<a href="<?= '/~' . $arquivos_de_vdd[0] ?>" style="float:right; margin:8px;"><img src="/elementos/botaoVerResto.png"></a>
			<?php endif ?>
			<?php if ($projeto_e_meu) : ?>
				<a href="/projetos/<?= $projeto->id ?>/editar" style="float:right; margin:8px;"><img src="/elementos/botaoEditar.png"></a>
			<?php endif ?>

			<h1><i><?= $projeto->nome ?></i></h1>
			<p>por <a href="/usuarios/<?= usuario_requestIDator($projeto->id_criador)->username ?>"><?= usuario_requestIDator($projeto->id_criador)->username ?></a></p>
		</div>
		<?php if ($projeto->tipo != 'bg') : ?>
			<div class="inside_page_content">
				<?php if ($projeto->tipo == 'jg') : ?>
					<!-- Embed -->
					<?php if (str_ends_with($arquivo_vivel[0], '.swf')) : ?>
						<!-- JOGOS FLASH -->
						<div style="margin: 0 auto; width: -moz-fit-content; width: intrinsic; width: fit-content;">
							<div class="jogo">
								<object width="auto" height="auto" data="<?= $config['URL'] . '/static/projetos/' . $projeto->id . '/' . $arquivo_vivel[1] ?>" allowfullscreen="true">
								</object>
							</div>
						</div>
						
						<script>
						var flashio = document.getElementsByClassName('jogo')[0].children[0];
						
						window.addEventListener('load', function () {
							flashio.width = 620;
							flashio.height = parseInt(flashio.TGetProperty('/', 9) * (620 / flashio.TGetProperty('/', 8)))
							console.log('browser véio fix !');
						});
						</script>
					<?php endif; ?>
					<?php if (str_ends_with($arquivo_vivel[0], '.zip')) : ?>
						<!-- JOGOS HTML -->
						<div class="jogo">
							<iframe width="100%" height="360" src="<?= $config['URL'] . '/static/projetos/' . $projeto->id . '/jogo/index.html' ?>" frameborder="0" allowfullscreen="true"></iframe>
						</div>
					<?php endif; ?>
					<?php if (str_ends_with($arquivo_vivel[0], '.sb') || str_ends_with($arquivo_vivel[0], '.sb2')) : ?>
						<!-- JOGOS SCRATCH 1.x/2.0 -->
						<div style="margin: 0 auto; width: -moz-fit-content; width: intrinsic; width: fit-content;">
							<object data="/projetos/Scratch.swf" height="387" width="482">
								<param name="allowScriptAccess" value="sameDomain">
								<param name="allowFullScreen" value="true">
								<param name="flashvars" value="project=<?= $config['URL'] . '/static/projetos/' . $projeto->id . '/' . $arquivo_vivel[1] ?>&autostart=false">
							</object>
						</div>
					<?php endif; ?>

					<?php if (str_ends_with($arquivo_vivel[0], '.sb3')) : ?>
						<!-- JOGOS SCRATCH 3.0 (CRONOLOGICAMENTE INNACURATE MAS WHATEVER) -->
						<div class="jogo" style="margin: 0 auto; width: -moz-fit-content; width: intrinsic; width: fit-content;">
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
								<div class="projetoSide">
									<a href="/projetos/<?= $projeto->id ?>/<?= $arquivos_de_vdd[$i] ?>" download><img src="/elementos/botaoTransferirSingular.png"></a>
								</div>
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
						<p id="paginacio">Mídia 1 de <?= count($arquivos) ?></p>

						<button id="pagprimeiro" onclick="comecoCoisa()" style="float: left; margin: 14px 5px 0 0;" disabled>
							<img src="/elementos/vedor_d_imagem/botaoPrimeiro.png" alt="Início">
						</button>
						<button id="paganterior" onclick="anteriorCoisa()" style="float: left; margin: 14px 6px 0 0;" disabled>
							<img src="/elementos/vedor_d_imagem/botaoAnterior.png" alt="Anterior">
						</button>
						<button id="pagultimo" onclick="fimCoisa()" style="float: right; margin: 14px 0 0 5px;" <?= count($arquivos) == 1 ? "disabled" : "" ?>>
							<img src="/elementos/vedor_d_imagem/botaoUltimo.png" alt="Último">
						</button>
						<button id="pagproximo" onclick="proximoCoisa()" style="float: right; margin: 14px 0 0 5px;" <?= count($arquivos) == 1 ? "disabled" : "" ?>>
							<img src="/elementos/vedor_d_imagem/botaoProximo.png" alt="Próximo">
						</button>
						<div id="outrasImagens">
							<?php $tiposDeVideo = ['mp4', 'ogg', 'avi', 'mkv'];
							$tiposDeFlash = ['swf']; // provavelmente nao existe mais tipos de flash do que swf mas eu fiquei com preguiça e vai que minha hipotese e desprovada eventualmente					
							?>
							<?php foreach ($arquivos as $i => $arquivo) : ?>
								<?php $eh_um_video = in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $tiposDeVideo);
								$eh_um_flash = in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $tiposDeFlash);
								?>
								<button
									data-url="/projetos/<?= $projeto->id ?>/<?= urlencode($arquivos_de_vdd[$i]) ?>"
									data-static="/static/projetos/<?= $projeto->id ?>/<?= $arquivo ?>"
									<?= $eh_um_video ? "data-video='true'" : "" ?>
									<?= $eh_um_flash ? "data-flash='true'" : "" ?>
									onclick="clicCoiso(<?= $i ?>)"
									style="<?= $i > 8 ? "display: none;" : "" ?>"
									class="<?= $i == 0 ? "essa-imagem" : "" ?>">
									<img
										src="<?= $eh_um_video ? '/elementos/vedor_d_imagem/video_coiso.png' : ($eh_um_flash ? '/elementos/vedor_d_imagem/flash_coiso.png' : "/static/projetos/" . $projeto->id . "/" . $arquivo) ?>"
										alt="<?= $arquivo ?>"
										width="48px"
										height="48px">
								</button>
							<?php endforeach ?>
						</div>

						<br>

						<video id="videoAtual" width="620" autoplay="false" controls="true" style="display: none;">
							Seu navegador não tem suporte pra tag de vídeo!!
						</video>
						<embed id="flashAtual" type="application/x-shockwave-flash" src="" width="620" height="465">
						</embed>

						<a href="/elementos/chillmaia.png" target="_blank" id="imagemAtual" style="display: none;">
							<img src="/elementos/chillmaia.png">
						</a>

						<style>
							/* holy fucking imagens */
							#imagemAtual {
								display: block;
							}

							#paginacio {
								text-align: center;
								margin: 4px auto 8px;
							}

							#imagemAtual img {
								max-width: 620px;
								margin: auto;
								display: block;
							}

							.vedorDImagem button {
								padding: 0;
								background: none;
								border: none;
								cursor: pointer;
							}

							.vedorDImagem button:disabled {
								cursor: unset;
								opacity: 0.5;
							}

							#outrasImagens {
								margin: auto;
								width: -moz-fit-content;
								width: intrinsic;
								width: -webkit-fit-content;
								width: fit-content;
							}

							#outrasImagens button {
								background-color: rgba(0, 0, 0, 0.4);
							}

							#outrasImagens button img {
								opacity: 0.15;
							}

							#outrasImagens button.essa-imagem img {
								opacity: 1;
							}
						</style>

						<script>
							var totalImagens = <?= count($arquivos) ?>;
							var curSelected = 0;

							function clicCoiso(id) {
								// codigo com alma ?
								// sim;.
								console.log('clic');

								var imgs = document.getElementById("outrasImagens").children;

								var imgAnterior = imgs[curSelected];
								imgAnterior.className = "";
								document.getElementById("paginacio").innerText = "Mídia " + (id + 1) + " de " + totalImagens;

								var img = imgs[id];
								img.className = "essa-imagem";

								if (img.getAttribute('data-video') == 'true') {
									document.getElementById("videoAtual").style.display = "block";
									document.getElementById("videoAtual").src = img.getAttribute('data-static');
									if (typeof document.getElementById("flashAtual").pause === 'function') {
										document.getElementById("flashAtual").pause();
									} else {
										if (document.getElementById("flashAtual").src != "/elementos/placery.swf") document.getElementById("flashAtual").src = "/elementos/placery.swf";
									}
									document.getElementById("flashAtual").style.display = "none";
									document.getElementById("imagemAtual").style.display = "none";
								} else if (img.getAttribute('data-flash') == 'true') {
									document.getElementById("videoAtual").pause();
									document.getElementById("videoAtual").style.display = "none";
									document.getElementById("flashAtual").src = img.getAttribute('data-static');
									document.getElementById("flashAtual").style.display = "block";
									document.getElementById("imagemAtual").style.display = "none";
								} else {
									document.getElementById("videoAtual").pause();
									document.getElementById("videoAtual").style.display = "none";
									if (typeof document.getElementById("flashAtual").pause === 'function') {
										document.getElementById("flashAtual").pause();
									} else {
										if (document.getElementById("flashAtual").src != "/elementos/placery.swf") document.getElementById("flashAtual").src = "/elementos/placery.swf";
									}
									document.getElementById("flashAtual").style.display = "none";
									document.getElementById("imagemAtual").href = img.getAttribute('data-url');
									document.getElementById("imagemAtual").getElementsByTagName('img')[0].src = img.getAttribute('data-static');
									document.getElementById("imagemAtual").style.display = "block";
								}

								// paginacio
								if (id == 0) {
									document.getElementById("pagprimeiro").disabled = true;
									document.getElementById("paganterior").disabled = true;
								} else {
									document.getElementById("pagprimeiro").disabled = false;
									document.getElementById("paganterior").disabled = false;
								}
								if (id == totalImagens - 1) {
									document.getElementById("pagultimo").disabled = true;
									document.getElementById("pagproximo").disabled = true;
								} else {
									document.getElementById("pagultimo").disabled = false;
									document.getElementById("pagproximo").disabled = false;
								}

								// esconde e mostra os botoes
								for (var i = 0; i < imgs.length; i++) {
									if (i == id) {
										imgs[i].style.display = "inline-block";
									} else {
										imgs[i].style.display = "none";
									}
								}
								var testando = 1;
								for (var i = 1; i < 9;) {
									var achou_um = false;
									if (id - testando >= 0) {
										imgs[id - testando].style.display = "inline-block";
										i++;
										achou_um = true;
									}
									if (id + testando < totalImagens) {
										imgs[id + testando].style.display = "inline-block";
										i++;
										achou_um = true;
									}
									if (!achou_um) {
										break;
									}
									testando++;
								}

								curSelected = id;
							}

							// paginacios
							function comecoCoisa() {
								clicCoiso(0);
							}

							function anteriorCoisa() {
								if (curSelected > 0) {
									clicCoiso(curSelected - 1);
								}
							}

							function proximoCoisa() {
								if (curSelected < totalImagens - 1) {
									clicCoiso(curSelected + 1);
								}
							}

							function fimCoisa() {
								clicCoiso(totalImagens - 1);
							}

							clicCoiso(0);
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
					<div class="jogo" style="margin: 0 auto;">
						<iframe width="100%" height="360" src="<?= $config['URL'] . '/~' . $arquivos_de_vdd[0] ?>" frameborder="0" allowfullscreen="true"></iframe>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<style>
			.descricao img {
				max-width: 620px;
			}
		</style>
		<div class="inside_page_content" style="margin-top: 8px; margin-bottom: 8px;">
			<?php
			function trocadorDeImagemCoiso($texto)
			{
				global $arquivos;
				global $arquivos_de_vdd;
				global $id;

				$trocador = [];

				foreach ($arquivos as $i => $arquivo) {
					$trocador += [
						'^!\[(.*?)\]\(' . $arquivos_de_vdd[$i] . '\)^' => '![$1](/static/projetos/' . $id . '/' . $arquivo . ')'
					];
				}
				$texto = preg_replace(array_keys($trocador), array_values($trocador), $texto);

				return $texto;
			}
			?>
			<div class="descricao">
				<?= responde_clickers(trocadorDeImagemCoiso($projeto->descricao)) ?>
			</div>
			<?php $postadoString = '';
			
			if (isset($projeto->dataBump)) {
				$postadoString .= 'Editado dia <b>' . velhificar_data($projeto->dataBump) . '</b>, ';
			}
			$postadoString .= 'Postado dia <b>' . velhificar_data($projeto->data) . '</b>';
			
			reajor_d_reagida('projeto', $projeto, $usuario, $postadoString); ?>
		</div>

	</div>

	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content">
		<div class="inside_page_content">
			<?php vedor_d_comentario('projeto', $projeto->id, true, $usuario); ?>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>