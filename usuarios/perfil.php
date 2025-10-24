<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/cssCoiso.php'; ?>
<?php
if (!isset($_GET['username'])) {
	erro_404();
}
$username = $_GET['username'];

$erro = [];

$perfil = usuario_requestinator($username);

// carregar Pfp (checar comentario de baixo)
if (isset($_POST)) {
	// O comentario acima vai aqui nao ali então imagine q está aqui
	if (isset($_FILES['pfp_fnf'])) {
		$pfp_rtn = subir_arquivo($_FILES['pfp_fnf'], '/static/pfps/', 'usuarios', $usuario->id, 'pfp', ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'PNG', 'JPG', 'JPEG', 'GIF', 'BMP'], 1024 * 1024);
		if (str_starts_with($pfp_rtn, '§')) {
			array_push($erro, substr($pfp_rtn, 1));
		}
	}

	// carregar Bnr
	if (isset($_FILES['bnr_fnf'])) {
		$bnr_rtn = subir_arquivo($_FILES['bnr_fnf'], '/static/banners/', 'usuarios', $usuario->id, 'banner', ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'PNG', 'JPG', 'JPEG', 'GIF', 'BMP'], 1024 * 1024);
		if (str_starts_with($bnr_rtn, '§')) {
			array_push($erro, substr($bnr_rtn, 1));
		}
	}

	// carregar Biographia
	if (isset($_POST['bio_fnf'])) {
		$bio = $_POST['bio_fnf'];

		mudar_usuario($usuario->id, ['bio' => $bio]);
	}

	if (isset($_POST['css_fnf'])) {
		$css = $_POST['css_fnf'];

		mudar_usuario($usuario->id, ['css' => $css]);
	}

	$perfil = usuario_requestinator($username);
}

// whoops you have to put the usuario in the url
if (!$perfil) {
	erro_404();
}


if (isset($usuario)) {
	$naolistcoiso = "(naolist = 0 OR (naolist = 1 AND id_criador = " . $usuario->id . "))";
} else {
	$naolistcoiso = "naolist = 0";
}

$projetos = [];
$proejos = coisos_tudo($projetos, 'projetos', 1, '', ' WHERE ' . $naolistcoiso . ' AND id_criador = ' . $perfil->id, 2, 'GREATEST(COALESCE(dataBump, 0), data) DESC, id DESC');
// variaveis com alma ?

$perfil_e_meu = $usuario ? ($usuario->id == $perfil->id) : false;
?>

<?php
$meta["titulo"] = "[" . $perfil->username . " <> Usuário do PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content" style="margin-bottom: 6px;">
		<style>
			.coolBorderyNormal {
				border: solid;
				border-color: #9EBBFF;
				border-width: 1px;
			}

			.coolBorderyEditable {
				background-color: #5d85e2;
				border: none;
				padding: 1px 1px;
				cursor: pointer;
			}

			.coolBorderyEditable:hover {
				background-color: #9EBBFF;
			}

			.coolBorderyEditable:active {
				opacity: .7;
			}

			.bioEditavel {
				background: none;
				border: none;
				position: relative;
				padding: 1px 1px;
				text-align: left;
				vertical-align: top;

				font: 12px "Verdana";
				color: #4f6bad;
			}

			.bioEditavel:hover {
				background-color: #FFFFDD;
			}

			.bioEditavel:active {
				background-color: #FFEEAA;
			}

			.bioButt {
				margin-top: 3px;
				width: 100%;
				background-color: #D6EBFF;
				border-style: solid;
				border-width: 1px;
				border-color: #5d85e2;

				font-family: Verdana;
			}

			.bioButt:hover {
				background-color: aliceblue;
			}

			.bioButt:active {
				background-color: #B5DCFF;
			}
			
			.projsRecentesCssavel {
				margin-left: -5px;
				margin-top: 5px; 
				margin-bottom: -3px;
			}
		</style>

		<style id="cssCustom">
			<?= css_sanitario($perfil->css ?? '') ?>
		</style>
		<?php if ($erro) : ?>
			<div class="erro" style="color: red; background: black; text-align: center;">
				<img src="/static/skull-and-cross.gif" width="24" height="24" />
				<?= $erro[0] ?>
				<img src="/static/skull-and-cross.gif" width="24" height="24" />
			</div>
		<?php endif; ?>

		<!-- Foto de perfil -->
		<?php if ($perfil_e_meu) : ?>
			<button onclick="pfp_fnf.click()" class="coolBorderyEditable" style="margin-bottom: 6px;">
			<?php endif; ?>

			<img src="<?= pfp($perfil) ?>" alt="Foto de perfil de <?= $perfil->username ?>" width="48" height="48"
				<?php if (!$perfil_e_meu) : ?>
				class="coolBorderyNormal"
				style="margin-bottom: 6px;"
				<?php endif; ?>>

			<?php if ($perfil_e_meu) : ?>
			</button>
			<form action="" method="post" enctype="multipart/form-data" id="form_pfp" style="display: none;">
				<input type="file" name="pfp_fnf" id="pfp_fnf" accept="image/*" onchange="form_pfp.submit()">
			</form>
		<?php endif; ?>

		<!-- Banner -->
		<?php if ($perfil_e_meu) : ?>
			<button onclick="bnr_fnf.click()" id="bannerPica" class="coolBorderyEditable" style="float: right;">
			<?php endif; ?>

			<img src="<?= banner($perfil) ?>" alt="Foto de banner de <?= $perfil->username ?>" width="385" height="48"
				<?php if (!$perfil_e_meu) : ?>
				class="coolBorderyNormal"
				style="float: right;"
				<?php endif; ?>>

			<?php if ($perfil_e_meu) : ?>
			</button>
			<form action="" method="post" enctype="multipart/form-data" id="form_bnr" style="display: none;">
				<input type="file" name="bnr_fnf" id="bnr_fnf" accept="image/*" onchange="form_bnr.submit()">
			</form>

			<script>
				var chromeVeio = ["8", "9", "10", "11", "12", "13", "14", "15", "16"];

				function chromeCheckUser(ver, index, element) {
					if (window.navigator.userAgent.indexOf('Chrome/' + ver + '.') != -1) {
						document.getElementById("bannerPica").style.margin = '-56px 0px 0px 0px'
					}
				}

				chromeVeio.forEach(chromeCheckUser);
			</script>
		<?php endif; ?>

		<div class="inside_page_content" style="max-width: 432px; word-wrap: break-word;">
			<div style="display: table; width: 100%;">
				<h1 style="margin: 0; float:left;"><?= $perfil->username ?></h1>
				<?php if ($perfil_e_meu) : ?>
					<button id="editarCss" class="coolButt" style="float:right;"
						onclick="
				form_css.style.display = 'block'
				cancelarCss.style.display = ''; 
				editarCss.style.display = 'none'; 
				">Editar CSS</button>
					<button id="cancelarCss" class="coolButt vermelho" style="float:right; display:none;"
						onclick="
				form_css.style.display = 'none'
				cancelarCss.style.display = 'none'; 
				editarCss.style.display = ''; 
				">
						Cancelar edição</button>
				<?php endif; ?>
				
				<?php if (!$perfil_e_meu) : ?>
					<a href="/usuarios/<?= $perfil->username ?>/feed.xml" style="float:right;"><img src="/elementos/rss.png"></a>
				<?php endif;?>
			</div>
			<form action="" method="post" enctype="multipart/form-data" id="form_css" style="display: none;">
				<textarea name="css_fnf" id="css_fnf" style="width: 425px; height: 150px;"><?= htmlspecialchars($perfil->css) ?></textarea>
				<button id="cssPrevGo" type="button" class="coolButt verde" style="width: 49.5%;"
					onclick="
				cssPrevGo.style.display = 'none'; 
				cssPrevStop.style.display = ''; 
				cssCustom.textContent = css_fnf.value;
				">Pré-visualização</button>
				<button id="cssPrevStop" type="button" class="coolButt vermelho" style="width: 49.5%; display:none;"
					onclick='
				cssPrevGo.style.display = ""; 
				cssPrevStop.style.display = "none"; 
				cssCustom.textContent = <?= json_encode($perfil->css) ?>;
				'>Parar pré-visualização</button>
				<button type="submit" class="coolButt" style="width: 49.5%;">Salvar CSS</button>
			</form>
			<div class="separador"></div>
			<!-- Bio -->
			<?php if ($perfil_e_meu) : ?>
				<button class="bioEditavel" onclick="form_bio.style.display = 'block'; bio.style.display = 'none'">
				<?php endif; ?>

				<p id="bio" style="margin-top: 0px; white-space: pre-line;">
					<?php if ($perfil_e_meu && ($perfil->bio == null or $perfil->bio == '')) : ?>vazio - insira algo aqui!<?php endif; ?>
					<?= responde_clickers($perfil->bio) ?></p>

				<?php if ($perfil_e_meu) : ?>
				</button>
				<form action="" method="post" enctype="multipart/form-data" id="form_bio" style="display: none;">
					<textarea name="bio_fnf" id="bio_fnf" style="width: 425px; height: 150px;"><?= htmlspecialchars($perfil->bio) ?></textarea>
					<button type="submit" class="bioButt">
						Salvar bio
					</button>
				</form>
			<?php endif; ?>
			<div class="separador" style="border-color:#D2EDFF"></div>
			<?php if ($projetos != []) : ?>
				<img src="/elementos/principaltitles/projsRecentes.png" class="projsRecentesCssavel">
				<div class="separador" style="border-color: #c7eaf9; margin-bottom: 8px;"></div>
				<div class="projetos">
					<?php
					foreach ($projetos as $projeto) {
						renderarProjeto($projeto);
					}
					?>
				</div>
				<a class="autorDeProjeto" style="color: #9ebbff; font-weight:bold; text-align:right; display:block; margin-top:0px;" href="/projetos/?q=@<?= $username ?>">ver mais projetos! >></a>
				<div class="separador"></div>
			<?php endif; ?>
			<?php dave_rank($perfil->davecoins); ?>
			<?php reajor_d_reagida('perfil', $perfil, $usuario) ?>

		</div>
	</div>

	<div class="page_content">
		<div class="inside_page_content">
			<?php vedor_d_comentario('perfil', $perfil->id, true, $usuario); ?>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>