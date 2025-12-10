<?php
// copiado do 1604chan hihi
$banners = array();
$dir = $_SERVER['DOCUMENT_ROOT'] . "/elementos/header/headers/";
if ($handle = scandir($dir)) {
	foreach ($handle as $target) {
		if (!in_array($target, [".", ".."])) {
			$banners[] = $target;
		}
	}
}

$banner = $banners[array_rand($banners)];

global $usuario;
global $config;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta content="pt-br" http-equiv="Content-Language" />
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link href="/cssManeiro.css?v35" rel="stylesheet" type="text/css" />
	<link id="favicon" rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="preload" as="image" href="/elementos/davecoin/dvc_1.png">
	<link rel="preload" as="image" href="/elementos/davecoin/dvc_5.png">
	<link rel="preload" as="image" href="/elementos/davecoin/dvc_10.png">
	<link rel="preload" as="image" href="/elementos/davecoin/dvc_25.png">
	<link rel="preload" as="image" href="/elementos/davecoin/dvc_50.png">
	<link rel="preload" as="image" href="/elementos/davecoin/dvc_100.png">
	<link rel="preload" href="/bounties/elementos/pop.wav" as="audio" type="audio/wav">
	<link rel="preload" href="/bounties/elementos/plim.wav" as="audio" type="audio/wav">

	<!-- Metas Tags -->
	<title><?= $meta["titulo"] ?? "[PORTAL ESPECULAMENTE]" ?></title>
	<meta name="description" content="<?= isset($meta["descricao"]) ? $meta["descricao"] . " <> " : "" ?>Sejam todos bem vindos ao ESPECULAMENTE!! O portal mais FODA da internet!!! Jogos, arte, animação e muito mais, criados por nós, os ESPECULATIVOS, artistas internéticos assim como você!">

	<meta property="og:url" content="<?= $config["URL"] . ($meta["pagina"] ?? $_SERVER['REQUEST_URI']) ?>">
	<meta property="og:type" content="<?= $meta["type"] ?? "website" ?>">
	<meta property="og:title" content="<?= $meta["titulo"] ?? "[PORTAL ESPECULAMENTE]" ?>">
	<meta property="og:description" content="<?= isset($meta["descricao"]) ? $meta["descricao"] . " <> " : "" ?>Sejam todos bem vindos ao ESPECULAMENTE!! O portal mais FODA da internet!!! Jogos, arte, animação e muito mais, criados por nós, os ESPECULATIVOS, artistas internéticos assim como você!">
	<meta property="og:image" content="<?= $config["URL"] . ($meta["imagem"] ?? "/static/share_image.png") ?>">

	<meta name="twitter:card" content="summary_large_image">
	<meta property="twitter:domain" content="<?= $config["URL"] ?>">
	<meta property="twitter:url" content="<?= $config["URL"] . ($meta["pagina"] ?? $_SERVER['REQUEST_URI']) ?>">
	<meta name="twitter:title" content="<?= $meta["titulo"] ?? "[PORTAL ESPECULAMENTE]" ?>">
	<meta name="twitter:description" content="<?= isset($meta["descricao"]) ? $meta["descricao"] . " <> " : "" ?>Sejam todos bem vindos ao ESPECULAMENTE!! O portal mais FODA da internet!!! Jogos, arte, animação e muito mais, criados por nós, os ESPECULATIVOS, artistas internéticos assim como você!">
	<meta name="twitter:image" content="<?= $config["URL"] . ($meta["imagem"] ?? "/static/share_image.png") ?>">

	<!-- CSS ESPECIAL -->
	<style>
		/* primaveresque */
		body {
			background-color: #BBE2FE;
			background-image: url("/elementos/murais/primavera.png");
		}
	</style>
</head>

<body>
	<div class="bodyPrincipal">
		<div class="top_nav">
			<!-- HEADER -->
			<div class="headerAwesome">
				<!-- LINKS DO TOPO DO HEADER-->
				<div class="coolOrganizationy">
					<div class="coolLinkery">
						<a href="/projetos/">PROJETOS</a>
						<a href="/jogos/">JOGOS</a>
						<a href="/midia/">MÍDIA</a>
						<a href="/blogs/">BLOGS</a>
						<a href="/resto/">"O resto..."</a>
					</div>
					<form action="/<?= pagetitlePorTipo($_GET['tipo'] ?? ($tipo ?? '')) ?>" style="float:right;">
						<input type="text" id="search" name="q" placeholder="Pesquise algo lol" class="coolSearchBar" style="height: 18px; width: 198px; float:left; margin-right: 3px;" value="<?= $_GET["q"] ?? "" ?>" />
						<button style="cursor:pointer; display: inline-block; padding: 0; border: 0;">
							<img src="/elementos/header/pesquisa.png">
						</button>
					</form>
				</div>
				<!-- FIM DOS LINKS DO TOPO DO HEADER-->

				<div class="coolBannery">
					<a href="/">
						<?php if (str_ends_with($banner, '.swf')) : ?>
							<object width="633" height="110" style="display: inline-block">
								<param name="movie" value="/elementos/header/headers/<?= $banner ?>">
								<embed src="/elementos/header/headers/<?= $banner ?>" width="633" height="110">
								</embed>
							</object>
						<?php else : ?>
							<img alt="especula header" src="/elementos/header/headers/<?= $banner ?>" width="633" height="110" />
						<?php endif; ?>
					</a>
				</div>

				<!-- LINKS ABAIXO DO BANNER -->
				<div class="coolSubHeadery">
					<div class="coolLinkery">
						<?php if (isset($usuario)) : ?>
							<?php if (isset($forum)) { ?>
								<a href="/foruns/postar<?php if (isset($getDeCategoria)) {
																					if (isset($_GET[$getDeCategoria])) { ?>?cat=<?php echo $_GET[$getDeCategoria];
																																										}
																																									} ?>" style="color: darkblue;">+ POSTAR</a>
							<?php } else { ?>
								<a href="/criar" style="color: forestgreen;">+ CRIAR</a>
							<?php } ?>
						<?php endif ?>
						<a href="/usuarios">AMIGOS</a>
						<a href="/foruns">FÓRUNS</a>
					</div>

					<div class="coolUsery">
						<?php if (isset($usuario)) : ?>
							<script>
								// mensagem negocios
								var tituloOriginal = document.title;
								var faviconOriginal = document.getElementById("favicon").href;

								function carregarMensagens() {
									var xhttp = new XMLHttpRequest();
									xhttp.onload = function() {
										document.getElementById("msgContador").innerHTML = this.responseText;
										if (this.responseText != 0) {
											document.title = "(" + this.responseText + ") " + tituloOriginal;
											document.getElementById("favicon").href = "/faviconmail.ico";
											document.getElementById("msgIcone").src = '/elementos/header/msgAtiva.png';
										} else {
											document.title = tituloOriginal;
											document.getElementById("favicon").href = faviconOriginal;
											document.getElementById("msgIcone").src = '/elementos/header/msgInativa.png';
										}
									};
									xhttp.open(
										"GET",
										"/mensagens/contagem.php",
										true
									);
									xhttp.send();
								}

								carregarMensagens();
								setInterval(carregarMensagens, 10000);
							</script>
							<!-- MENSAGENS -->
							<div class="links">
								<span id="msgContador" class="msgContador">0</span>
								<a href="/mensagens"><img id="msgIcone" src="/elementos/header/msgInativa.png"></a>
							</div>
							<!-- OUTROS LINKS -->
							<div class="links">
								Olá novamente, <a href="/usuarios/<?= $usuario->username ?>"><?= $usuario->username ?></a>
								<button id="headerSeta"></button>
								<div id="headerMenu">
									<a href="/usuarios/<?= $usuario->username ?>">Perfil</a>
									<a href="/convites.php">Seus convites</a>
									<a href="/bounties">Bounties</a>
									<?php if (isset($forum)) : ?>
									<a href="/foruns/assinatura.php">Assinatura</a>
									<?php endif; ?>
									<hr>
									<a href="/sair.php">Sair</a>
								</div>
							</div>
						<?php else : ?>
							<div class="links" style="margin-top: 1px; margin-right: 10px; border-left: 0;"><a href="/registro.php">Criar conta</a> | <a href="/entrar.php">Entrar</a></div>
						<?php endif ?>
					</div>

				</div>
				<!-- FIM DOS LINKS ABAIXO DO BANNER -->
			</div>
			<!-- FIM DO HEADER -->
		</div>

		<div id="moeda"><audio src="/bounties/elementos/pop.wav"></audio><audio src="/bounties/elementos/plim.wav"></audio></div>
		<script>
			var moedaInterval;
			var mousePos = [0, 0];
		
			document.addEventListener('mousemove', function(e) {
				mousePos = [e.clientX, e.clientY];
				// console.log(mousePos);
			});
			
			function moeda(valor) {
				if (moedaInterval) {
					clearInterval(moedaInterval);
				}
				var arquivo = "/elementos/davecoin/dvc_" + valor + ".png";
				var moedaDiv = document.getElementById("moeda");
				moedaDiv.style = "background-image: url('" + arquivo + "'); display: block;";
				var frame = 0;
				var pop = moedaDiv.getElementsByTagName("audio")[0];
				var plim = moedaDiv.getElementsByTagName("audio")[1];

				moedaDiv.style.backgroundPositionX = "0px";
				moedaDiv.style.top = mousePos[1] - 48 + "px";
				moedaDiv.style.left = mousePos[0] - 8 + "px";
				pop.volume = 0.2;
				pop.pause();
				pop.currentTime = 0;
				pop.play();
				moedaInterval = setInterval(nextFrame, 70);

				function nextFrame() {
					frame++;
					if (frame > 24) {
						moedaDiv.style.display = "none";
						if (moedaInterval) {
								clearInterval(moedaInterval);
						}
						return;
					}
					if (frame == 8) {
						plim.volume = 0.2;
						plim.pause();
						plim.currentTime = 0;
						plim.play();
					}
					moedaDiv.style.backgroundPositionX = "-" + (frame * 16) + "px";
				}
			}
		</script>