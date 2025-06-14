<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
$query = $_GET['q'] ?? '';
$page = $_GET['page'] ?? 1;
$tipo = $_GET['tipo'] ?? '';
$formato = $_GET['formato'] ?? '';
$tipoQuery = '';
$projetos = [];
$userQuery = '';
$usuariosios = [];
$userOnly = false;

if ($tipo != '') {
	$tipoQuery = " WHERE tipo = " . $db->quote($tipo);
}

if ($query != '') {
	if ($tipo != '') {
		$tipoQuery = " AND tipo = " . $db->quote($tipo);
	}
	// PARTE UM DO FUNNY COISO COM O NOME DE USUARIO
	if (substr($query, 0, 1) == '@') {
		$query = substr($query, 1);
		$userOnly = true;
	}

	$usuRows = $db->prepare("SELECT * FROM usuarios WHERE username LIKE ?");
	$usuRows->bindParam(1, $query, PDO::PARAM_STR);
	$usuRows->execute();
	while ($row = $usuRows->fetch(PDO::FETCH_OBJ)) {
		array_push($usuariosios, $row);
	}

	foreach ($usuariosios as $usario) {
		$userQuery = $userQuery . " OR id_criador = " . $usario->id;
	}

	if ($userOnly) {
		$query = '@' . $query;
	}
	$coisodepagina = '?q=' . $query . '&';
} else {
	$coisodepagina = '?';
}

/* 
if ($formato != '') {
	$coisodepagina = $coisodepagina . 'formato=' . $formato . '&';
}

if ($tipo != '') {
	$coisodepagina = $coisodepagina . 'tipo=' . $tipo . '&';
}
*/

$pages = coisos_tudo($projetos, 'projetos', $page, $query, $userQuery . $tipoQuery, ($formato == 'grade' ? 9 : 10), 'dataBump DESC, data DESC, id DESC');

if ($userOnly) {
	$projCount = 0;
	foreach ($projetos as $projo) {
		$projCount += 1;
		$conteiro = 0;

		foreach ($usuariosios as $usario) {
			if ($projo->id_criador == $usario->id) {
				$conteiro += 1;
			}
		}

		if ($conteiro == 0) {
			unset($projetos[$projCount - 1]);
		}
	}
	array_values($projetos);
}
?>

<?php
$meta["titulo"] = "[" . nomePorTipo($tipo) . " <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = descPorTipo($tipo);
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<img src="/elementos/pagetitles/<?= pagetitlePorTipo($tipo) ?>.png" class="inside_page_content" style="padding: 0px; margin-left: 4px; margin-bottom: 7px;">

	<div class="page_content">
		<div class="inside_page_content">

			<?php if ($query != '') { ?>
				<div class="pesquisaThing">Resultados da pesquisa por <b>"<?php echo htmlspecialchars($query) . '"</b></div>';
																																} ?>
						<div class="projetos">
							<?php foreach ($projetos as $projeto) {
								if ($formato == 'grade') {
									renderarProjGrade($projeto);
								} else {
									renderarProjeto($projeto, ($tipo != 'jg'), ($tipo == 'jg'));
								}
							}
							?>

							<!-- here be pagination -->
							<div class="pagination">
								<?php if ($page > 1) : ?>
									<a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepagina ?>page=1">Início</a>
									<p class="textinhoClaro">~</p>
									<a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepagina ?>page=<?= $page - 1 ?>">« Anterior</a>
									<p class="textinhoClaro">~</p>
								<?php endif ?>
								<?php if ($page == 1) : ?>
									<p class="textinhoClaro" style="margin-right: 4px;">Início ~ « Anterior ~ </a>
									<?php endif ?>

									<p>Página <?= $page ?> de <?= $pages ?></p>

									<?php if ($page < $pages) : ?>
										<p class="textinhoClaro">~</p>
										<a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepagina ?>page=<?= $page + 1 ?>">Próximo »</a>
										<p class="textinhoClaro">~</p>
										<a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepagina ?>page=<?= $pages ?>">Fim</a>
									<?php endif ?>
									<?php if ($page == $pages) : ?>
										<p class="textinhoClaro"> ~ Próximo » ~ Fim</a>
										<?php endif ?>
							</div>
						</div>
				</div>
		</div>
	</div>

	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>