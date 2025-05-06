<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
?>
<?php
$query = $_GET['q'] ?? '';
$page = $_GET['page'] ?? 1;
$projetos = [];
$userQuery = '';
$usuariosios = [];
$userOnly = false;

if ($query != '') {
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

$pages = coisos_tudo($projetos, 'projetos', $page, $query, $userQuery);

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
$meta["titulo"] = "[Projetos <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Como todo bom ESPECULATIVO, nossa mesa está sempre transbordando de ideias, e às vezes essas ideias se tornam PROJETOS!! Veja aqui todos os melhores jogos, artes, vídeos e tudo mais já criados!";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <img src="/elementos/pagetitles/projetos.png" class="inside_page_content" style="padding: 0px; margin-left: 4px; margin-bottom: 7px;">

  <div class="page_content" style="min-height: 486px;">
    <div class="inside_page_content">

      <?php if ($query != '') { ?>
        <div class="pesquisaThing">Resultados da pesquisa por <b>"<?php echo htmlspecialchars($query) . '"</b></div>';
                                                                } ?>
            <div class="projetos">
              <?php foreach ($projetos as $projeto) {
				  renderarProjeto($projeto);
			  }
			  ?>

              <!-- here be pagination -->
              <div class="pagination">
                <?php if ($page > 1) : ?>
                  <a href="/projetos/<?= $coisodepagina ?>page=1">Início</a>
                  <p class="textinhoClaro">~</p>
                  <a href="/projetos/<?= $coisodepagina ?>page=<?= $page - 1 ?>">« Anterior</a>
                  <p class="textinhoClaro">~</p>
                <?php endif ?>
                <?php if ($page == 1) : ?>
                  <p class="textinhoClaro" style="margin-right: 4px;">Início ~ « Anterior ~ </a>
                  <?php endif ?>

                  <p>Página <?= $page ?> de <?= $pages ?></p>

                  <?php if ($page < $pages) : ?>
                    <p class="textinhoClaro">~</p>
                    <a href="/projetos/<?= $coisodepagina ?>page=<?= $page + 1 ?>">Próximo »</a>
                    <p class="textinhoClaro">~</p>
                    <a href="/projetos/<?= $coisodepagina ?>page=<?= $pages ?>">Fim</a>
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