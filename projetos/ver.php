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

// Suporte a Godot 4.0
if (str_ends_with($arquivo_vivel[0], '.zip')) {
	header('Cross-Origin-Embedder-Policy: require-corp');
	header('Cross-Origin-Opener-Policy: same-origin');
}
?>

<?php
$meta["titulo"] = "[" . htmlspecialchars($projeto->nome) . " <> " . ['dl' => 'Downloadável', 'md' => 'Mídia', 'jg' => 'Jogo', 'bg' => 'Blog', 'rt' => 'Website'][$projeto->tipo] . " no PORTAL ESPECULAMENTE]";
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
							
							window.addEventListener('load', function() {
								resFlash();
							});
							
							function resFlash() {
								flashio.width = 620;
								flashio.height = parseInt(flashio.TGetProperty('/', 9) * (620 / flashio.TGetProperty('/', 8)))
								console.log('browser véio fix !');
							}
							
							setTimeout(resFlash, 5000);
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
							<iframe src="https://turbowarp.org/embed?project_url=<?= $config['URL'] ?>/static/projetos/<?= $projeto->id ?>/<?= $arquivo_vivel[1] ?>" width="482" height="412" allowtransparency="true" frameborder="0" scrolling="no" allowfullscreen></iframe>
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
						<div id="listadorDImagem">
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
								<?php $tiposDeVideo = ['mp4', 'avi', 'mkv'];
								$tiposDeFlash = ['swf']; // provavelmente nao existe mais tipos de flash do que swf mas eu fiquei com preguiça e vai que minha hipotese e desprovada eventualmente					
								$tiposDeAudio = ['mp3', 'wav', 'ogg'];
								?>
								<?php foreach ($arquivos as $i => $arquivo) : ?>
									<?php $eh_um_video = in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $tiposDeVideo);
									$eh_um_flash = in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $tiposDeFlash);
									$eh_um_audio = in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $tiposDeAudio);
									?>
									<button
										data-url="/projetos/<?= $projeto->id ?>/<?= urlencode($arquivos_de_vdd[$i]) ?>"
										data-static="/static/projetos/<?= $projeto->id ?>/<?= $arquivo ?>"
										data-filename="<?= $arquivos_de_vdd[$i] ?>"
										<?= $eh_um_video ? "data-video='true'" : "" ?>
										<?= $eh_um_flash ? "data-flash='true'" : "" ?>
										<?= $eh_um_audio ? "data-audio='true'" : "" ?>
										onclick="clicCoiso(<?= $i ?>)"
										style="<?= $i > 8 ? "display: none;" : "" ?>"
										class="<?= $i == 0 ? "essa-imagem" : "" ?>">
										<img
											src="<?= $eh_um_video ? '/elementos/vedor_d_imagem/video_coiso.png' : ($eh_um_flash ? '/elementos/vedor_d_imagem/flash_coiso.png' : ($eh_um_audio ? '/elementos/vedor_d_imagem/audio_coiso.png' : "/static/projetos/" . $projeto->id . "/" . $arquivo)) ?>"
											alt="<?= $arquivo ?>"
											width="48px"
											height="48px">
									</button>
								<?php endforeach ?>
							</div>

							<br>
						</div>
						<video id="videoAtual" width="620" autoplay="false" controls="true" style="display: none;">
							Seu navegador não tem suporte pra tag de vídeo!!
						</video>
						<embed id="flashAtual" type="application/x-shockwave-flash" src="" width="620" height="465">
						</embed>

						<div class="vedorDMusica" id="audioAtual">
							<audio id="musicaDoVedor" src="WEATHER.mp3">
								Seu navegador não tem suporte pra tag de áudio!!
							</audio>

							<div class="botao">
								<button type="button" id="musPlayButton" onclick="musicaPlay()"><img src="/elementos/vedor_d_audio/playButt.png"></button>
								<button type="button" id="musPauseButton" style="display:none" onclick="musicaPause()"><img src="/elementos/vedor_d_audio/pauseButt.png"></button>
							</div>
							<div class="tocador">
								<div class="barrinha" id="musBarra" onmousedown="mudarMouseSeguro('tempo');">
									<div id="musSuco" class="juice"></div>
									<div id="musSucoSeek" class="juiceseek"></div>
								</div>
								<div class="info">
									<span id="musInfoTempo" class="tempo">0:00/sei:la</span>
									<div class="barrinha volume" id="musVolBarra" onmousedown="mudarMouseSeguro('volume');">
										<div id="musVolSuco" class="juice"></div>
									</div>
									<a href="" download="" id="musInfoFile" class="filename" style="font-weight: bold">Baixar áudio!</a>
									<span id="musInfoFilename" class="filename" style="overflow:hidden; width: 253px;">"Um negocio.ogg"</span>
								</div>
							</div>
						</div>

						<script>
							var mouseSeguro = '';
							var pauseDeMexerComOTempo = false;

							function musicaPlay() {
								musicaDoVedor.play();
								musPlayButton.style.display = 'none';
								musPauseButton.style.display = '';
							}

							function musicaPause() {
								musicaDoVedor.pause();
								musPlayButton.style.display = '';
								musPauseButton.style.display = 'none';
							}

							musicaDoVedor.ontimeupdate = function() {
								musMudarInfo()
							};
							musicaDoVedor.oncanplay = function() {
								musMudarInfo()
							};

							function musMudarInfo() {
								if (musicaDoVedor.paused) {
									musPlayButton.style.display = '';
									musPauseButton.style.display = 'none';
								} else {
									musPlayButton.style.display = 'none';
									musPauseButton.style.display = '';
								}
								musSuco.style.width = (568 * (musicaDoVedor.currentTime / musicaDoVedor.duration)) + 'px';
								musSucoSeek.style.width = ((568 * (getLoadedAudioSeconds(musicaDoVedor) / musicaDoVedor.duration)) - (568 * (musicaDoVedor.currentTime / musicaDoVedor.duration))) + 'px';
								musInfoTempo.innerText = formatadorDTempo(musicaDoVedor.currentTime) + ' / ' + formatadorDTempo(musicaDoVedor.duration);
							}

							function formatadorDTempo(segundos) {
								if (segundos == null) {
									segundos = 0;
								}
								var seg = Math.round(segundos % 60);
								var min = Math.floor(segundos / 60);

								var stringSeg = '';
								if (seg < 10) {
									stringSeg = '0' + seg;
								} else {
									stringSeg = seg;
								}

								return min + ':' + stringSeg;
							}

							musMudarInfo();
							musVolSuco.style.width = (100 * musicaDoVedor.volume) + 'px';
							musInfoFilename.innerText = '"audio.wav"';

							// robo faça meu trabalho por mim
							function getMousePosRelativeToElement(event, element) {
								event = event || window.event;

								var rect = element.getBoundingClientRect();
								var mouseX = (event.pageX !== undefined ? event.pageX : event.clientX +
									(document.documentElement.scrollLeft || document.body.scrollLeft));
								var mouseY = (event.pageY !== undefined ? event.pageY : event.clientY +
									(document.documentElement.scrollTop || document.body.scrollTop));
								var elemLeft = rect.left + (document.documentElement.scrollLeft || document.body.scrollLeft);
								var elemTop = rect.top + (document.documentElement.scrollTop || document.body.scrollTop);
								return {
									x: mouseX - elemLeft,
									y: mouseY - elemTop
								};
							}

							function getLoadedAudioSeconds(audio) {
								if (audio.buffered && audio.buffered.length) {
									return audio.buffered.end(audio.buffered.length - 1);
								}
								return 0;
							}

							// obrigado robo

							document.addEventListener('mousemove', function(e) {
								mouseCoisos();
							});

							document.addEventListener('mousedown', function(e) {
								mouseCoisos();
							});

							window.addEventListener("mouseup", function(e) {
								if (mouseSeguro === 'tempo' && pauseDeMexerComOTempo == true) {
									musicaPlay();
									pauseDeMexerComOTempo = false;
								}

								mouseSeguro = '';
							});

							function musChangeTime() {
								if (!musicaDoVedor.paused) {
									pauseDeMexerComOTempo = true;
								}
								musicaPause();

								var oTempcio = (getMousePosRelativeToElement(null, musBarra).x / 568) * musicaDoVedor.duration;
								if (oTempcio > musicaDoVedor.duration) {
									musicaDoVedor.currentTime = musicaDoVedor.duration;
								} else if (oTempcio < 0) {
									musicaDoVedor.currentTime = 0;
								} else {
									musicaDoVedor.currentTime = oTempcio;
								}
							}

							function musChangeVolume() {
								var oVolucio = getMousePosRelativeToElement(null, musVolBarra).x / 100;
								if (oVolucio > 1) {
									musicaDoVedor.volume = 1;
								} else if (oVolucio < 0) {
									musicaDoVedor.volume = 0;
								} else {
									musicaDoVedor.volume = oVolucio;
								}
								musVolSuco.style.width = (100 * musicaDoVedor.volume) + 'px';
							}

							function mudarMouseSeguro(coiso) {
								console.log(coiso);
								mouseSeguro = coiso;
							}

							function mouseCoisos() {
								if (mouseSeguro === 'tempo') {
									musChangeTime();
								}
								if (mouseSeguro === 'volume') {
									musChangeVolume();
								}
							}
						</script>
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

							/* VEDOR D MUSICA !!! YEAHYE */
							.vedorDMusica {
								display: table;
								width: 621px;
							}

							.vedorDMusica .botao {
								float: left;
							}

							.vedorDMusica .botao button {
								cursor: pointer;
								border: none;
								width: 44px;
								padding: 0px;
							}

							.vedorDMusica .tocador {
								float: right;
							}

							.vedorDMusica .tocador .barrinha {
								height: 7px;
								border: 1px solid #8D8D8D;
								background-image: url('/elementos/vedor_d_audio/vidro.png');
							}

							.vedorDMusica .tocador .volume {
								width: 100px;
								float: left;
								margin-top: 10px;
							}

							.vedorDMusica .tocador .barrinha .juice {
								height: 7px;
								background-image: url('/elementos/vedor_d_audio/suco.png');
								float: left;
								animation: davejuice_flow 25s infinite linear;
								-webkit-animation: davejuice_webkitflow 25s infinite linear;
								-moz-animation: davejuice_mozflow 25s infinite linear;
							}

							.vedorDMusica .tocador .barrinha .juiceseek {
								height: 7px;
								background-image: url('/elementos/vedor_d_audio/sucoSeek.png');
								float: left;
								animation: davejuice_flow 25s infinite linear;
								-webkit-animation: davejuice_webkitflow 25s infinite linear;
								-moz-animation: davejuice_mozflow 25s infinite linear;
							}

							.vedorDMusica .tocador .info {
								width: 568px;
								height: 32px;
								background-image: url('/elementos/vedor_d_audio/fundoInfo.png');
								border: 1px solid #8D8D8D;
								border-top: 0px;
								font-family: Verdana;
								display: table;
							}

							.vedorDMusica .tocador .info .tempo {
								margin: 6px;
								float: left;
								font-size: 14px;
								color: #353535;
							}

							.vedorDMusica .tocador .info .filename {
								margin: 8px;
								float: right;
								font-size: 11px;
								font-style: italic;
								color: #969696;
								max-width: 301px;
								overflow: hidden;
								white-space: nowrap;
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
									// VIDEOS
									document.getElementById("videoAtual").style.display = "block";
									document.getElementById("videoAtual").src = img.getAttribute('data-static');
									if (typeof document.getElementById("flashAtual").pause === 'function') {
										document.getElementById("flashAtual").pause();
									} else {
										if (document.getElementById("flashAtual").src != "/elementos/placery.swf") document.getElementById("flashAtual").src = "/elementos/placery.swf";
									}
									document.getElementById("flashAtual").style.display = "none";
									document.getElementById("imagemAtual").style.display = "none";
									document.getElementById("audioAtual").style.display = "none";
									musicaPause();
								} else if (img.getAttribute('data-flash') == 'true') {
									// FLASH
									document.getElementById("videoAtual").pause();
									document.getElementById("videoAtual").style.display = "none";
									document.getElementById("flashAtual").src = img.getAttribute('data-static');
									document.getElementById("flashAtual").style.display = "block";
									document.getElementById("imagemAtual").style.display = "none";
									document.getElementById("audioAtual").style.display = "none";
									musicaPause();
								} else if (img.getAttribute('data-audio') == 'true') {
									// AUDIOS
									document.getElementById("videoAtual").pause();
									document.getElementById("videoAtual").style.display = "none";
									if (typeof document.getElementById("flashAtual").pause === 'function') {
										document.getElementById("flashAtual").pause();
									} else {
										if (document.getElementById("flashAtual").src != "/elementos/placery.swf") document.getElementById("flashAtual").src = "/elementos/placery.swf";
									}
									document.getElementById("flashAtual").style.display = "none";
									document.getElementById("imagemAtual").style.display = "none";

									document.getElementById("audioAtual").style.display = "table";
									musicaDoVedor.src = img.getAttribute('data-static');
									musInfoFilename.innerText = '"' + img.getAttribute('data-filename') + '"';
									musInfoFile.href = img.getAttribute('data-static');
									musInfoFile.download = img.getAttribute('data-filename');
								} else {
									// IMAGENS
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
									document.getElementById("audioAtual").style.display = "none";
									musicaPause();
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

							if (totalImagens < 2) {
								// bom de fazer isso e q dai eu nao preciso reescrever o php inteiro !!!! muahuahau
								listadorDImagem.style.display = 'none';
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
		
		<?php if (count(projeto_colecoes($projeto->id)) > 0) :?>
		<div class="inside_page_content" style="margin-top: 8px; margin-bottom: 8px;">
			<style>
				.reajorNoTopo {
					height: 28px;
					margin-top: -6px;
					margin-bottom: 4px;
					border-bottom: solid 1px #9ebbff;
				}
				
				.colecoes {
					display:table; /* TODO PODEROSO display: table; */
					margin-left: -5px;
					margin-top: -4px;
					margin-bottom: -8px;
				}
				.colecao {
					display: table-cell;
					float: left;
					height: 36px;
				}

				.colecao a {
					text-decoration: none;
				}

				.colecao .colecaoInfo {
					font-size: 12px;
					font-weight: bold;
					width: 315px;
					height: 33px;
					position:absolute;
					margin-top: -36px;
					color: white;
					background-color: #00000099;
					opacity: 0;
				}

				.colecao .colecaoInfo:hover{
					opacity: 100;
				}
				
				.thumbless {
					margin-top: 0 !important;
					color: #6D81B0 !important;
					background-color: white !important;
					background-image: url(/elementos/chillmaia.png); 
					background-position: right -80px; background-repeat: no-repeat;
					opacity: 100 !important;
				}
				
				.thumbless p, .colecao .colecaoInfo p {
					margin-left: 8px;
				}

				.thumbless:hover{
					background-color: aliceblue !important;
					background-image: url(/elementos/chillermaia.png);
					color: black !important;
				}
			</style>
			<div class="reajorDReagida reajorNoTopo">
			<b style="display: block; margin-top: 7px; margin-left: 4px;">Inserido nas coleções</b>
			</div>
			<div class="colecoes">
				<?php foreach (projeto_colecoes($projeto->id) as $colecio) {
						$colecao = colecao_requestIDator($colecio); ?>
					<div class="colecao">
						<a href="/colecoes/<?= $colecao->id ?>">
							<?php if ($colecao->thumbnail != null) :?>
							<img src="/static/colecoes/<?= $colecao->thumbnail ?>" width="315" height="33">
							<div class="colecaoInfo">
								<p><?= $colecao->nome ?></p>
							</div>
							<?php else :?>
							<div class="colecaoInfo thumbless">
								<p><?= $colecao->nome ?></p>
							</div>
							<?php endif; ?>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php endif; ?>
	</div>

	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content">
		<div class="inside_page_content">
			<?php vedor_d_comentario('projeto', $projeto->id, true, $usuario); ?>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>