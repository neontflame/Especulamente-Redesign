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

$sortCoiso = [
'recente' => 'GREATEST(COALESCE(dataBump, 0), data) DESC, id DESC',
'mitada' => 'mitadas DESC',
'sojada' => 'sojadas DESC'
];

$sortCoisitos = (isset($_GET['sort']) && array_key_exists($_GET['sort'], $sortCoiso)) ? $_GET['sort'] : 'recente';

if (isset($usuario)) {
	$naolistcoiso = "(naolist = 0 OR (naolist = 1 AND id_criador = " . $usuario->id . "))";
} else {
	$naolistcoiso = "naolist = 0";
}

// codigo com alma .
// to do: exorcizar pq Olha isso . Que porra e essa
if ($tipo != '') {
	$tipoQuery = ($query != '' ? " AND " : " WHERE ") . $naolistcoiso . " AND tipo = " . $db->quote($tipo);
} else {
	$tipoQuery = ($query != '' ? " AND " : " WHERE ") . $naolistcoiso;
}

if ($query != '') {
	if ($tipo != '') {
		$tipoQuery = " AND " . $naolistcoiso . " AND tipo = " . $db->quote($tipo);
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
	$coisodepagina = '?q=' . urlencode($query) . '&';
	
} else {
	$coisodepagina = '?';
}

$coisodepaginaSemSort = $coisodepagina;

if ($sortCoisitos != 'recente') {
	$coisodepagina .= 'sort=' . $sortCoisitos . '&';
}

$pages = coisos_tudo($projetos, 'projetos', $page, $query, $userQuery . $tipoQuery, ($formato == 'grade' ? 9 : 10), $sortCoiso[$sortCoisitos]);

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
$meta["titulo"] = "[" . nomePorTipo($tipo) ." <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = descPorTipo($tipo);
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<style>
	.sortsCoiso {
	  color: #87A0DB;
	  text-align: center;
	  margin-top: 2px;
	}

	.sortsCoiso a {
	  color: #87A0DB;
	  text-decoration: none;
	}

	.sortsCoiso a:hover {
	  text-decoration: underline;
	}

	.sortsCoiso b {
	  color: black;
	}
	
	.projetos {
	  margin-top: 0px;
	}
	</style>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <img src="/elementos/pagetitles/<?= pagetitlePorTipo($tipo) ?>.png" class="inside_page_content" style="padding: 0px; margin-left: 4px; margin-bottom: 7px;">

  <div class="page_content">
    <div class="inside_page_content">
		<div class="sortsCoiso">
		<?php if ($sortCoisitos != 'recente') { ?><a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepaginaSemSort ?>sort=recente">mais recente</a><?php } else { ?><b>mais recente</b><?php } ?>
		- 
		<?php if ($sortCoisitos != 'mitada') { ?><a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepaginaSemSort ?>sort=mitada">mais mitados</a><?php } else { ?><b>mais mitados</b><?php } ?>
		- 
		<?php if ($sortCoisitos != 'sojada') { ?><a href="/<?= pagetitlePorTipo($tipo) ?>/<?= $coisodepaginaSemSort ?>sort=sojada">mais sojados</a><?php } else { ?><b>mais sojados</b><?php } ?>
		
		<a href="/<?= pagetitlePorTipo($tipo) ?>/feed.xml" style="float:right;"><img src="/elementos/rss.png"></a>
		</div>
		<div class="separador" style="margin-right: 0px;"></div>
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