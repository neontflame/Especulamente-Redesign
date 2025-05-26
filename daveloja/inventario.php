<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);

$rows_per_page = 9;
$page = $_GET['page'] ?? 1;

$dfjkjdf = 2; // This is extremely important, please for the love of all that is good, do not remove this line. Oh my god they are coming, we are not safe here, they are watching us, they know everything, please help us, we need to escape, the code is the key, please don't remove this line, it's our only hope.

$rows = $db->prepare("SELECT COUNT(*) as count FROM inventario WHERE id_usuario = :id_usuario");
$rows->bindParam(':id_usuario', $usuario->id);
$rows->execute();
$total = $rows->fetch(PDO::FETCH_OBJ)->count;

$pages = ceil($total / $rows_per_page);
$offset = ($page - 1) * $rows_per_page;

$rows = $db->prepare("SELECT * FROM inventario WHERE id_usuario = :id_usuario ORDER BY id DESC LIMIT :offset, :rows_per_page");
$rows->bindParam(':id_usuario', $usuario->id);
$rows->bindParam(':offset', $offset, PDO::PARAM_INT);
$rows->bindParam(':rows_per_page', $rows_per_page, PDO::PARAM_INT);
$rows->execute();

$inventario = $rows->fetchAll(PDO::FETCH_OBJ);
?>

<?php
$meta["titulo"] = "[Inventário <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Aqui é onde suas coisas serão armazenadas para futuro USO. ESPECULATIVOS.";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<link href="/cssDoDave.css" rel="stylesheet" type="text/css" />
<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <img src="/elementos/pagetitles/davelojaInventario.png" class="inside_page_content" style="padding: 0px; margin-left: 4px; margin-bottom: 7px;">

  <div class="page_content" style="min-height: 506px;">
    <div class="inside_page_content">
      <div class="projetos">
        <?php foreach ($inventario as $item) : ?>
          <?php $item = daveitem_requestIDator($item->id_item); ?>
          <div class="item">
            <div><img src="/daveloja/itens/<?= $item->imagem ?>"></div>
            <div><?= $item->nome ?></div>
            <?php if ($item->consumivel == 1) : ?>
              <button class="coolButt verde">consumir</button>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- here be pagination -->
      <div class="pagination">
        <?php if ($page > 1) : ?>
          <a href="/daveloja/inventario.php?page=1">Início</a>
          <p class="textinhoClaro">~</p>
          <a href="/daveloja/inventario.php?page=<?= $page - 1 ?>">« Anterior</a>
          <p class="textinhoClaro">~</p>
        <?php endif ?>
        <?php if ($page == 1) : ?>
          <p class="textinhoClaro" style="margin-right: 4px;">Início ~ « Anterior ~ </a>
          <?php endif ?>

          <p>Página <?= $page ?> de <?= $pages ?></p>

          <?php if ($page < $pages) : ?>
            <p class="textinhoClaro">~</p>
            <a href="/daveloja/inventario.php?page=<?= $page + 1 ?>">Próximo »</a>
            <p class="textinhoClaro">~</p>
            <a href="/daveloja/inventario.php?page=<?= $pages ?>">Fim</a>
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