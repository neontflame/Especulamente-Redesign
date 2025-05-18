<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);

$erro;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET['deletar'])) {
    $convite = obter_convite($_GET['deletar']);

    if (!$convite || $convite->criado_por != $usuario->id) {
      $erro = "Whoops! Esse convite não existe, ou não é seu.";
    } else {
      deletar_convite($convite->codigo);
    }
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $codigo = $_POST['codigo'];

  if (isset($codigo)) {
    $convite = obter_convite($codigo);

    if ($convite) {
      $erro = "Whoops! Um convite com esse nome já existe.";
    } else {
      criar_convite($codigo, $usuario->id);
    }
  }
}
?>

<?php
$meta["titulo"] = "[Seus convites <> PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';
?>

<div class="container">
  <?php
  $esconder_ad = true;
  include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php';
  ?>

  <div class="page_content" style="min-height: 322px">
    <div class="inside_page_content">
      <h2>Seus convites</h2>
      <h3>Criar novo convite</h3>
      <form action="" method="post">
        <label for="codigo">código</label>
        <input type="text" name="codigo" id="codigo">
        <button>Criar</button>
      </form>
      <?php if (isset($erro)) : ?>
        <p><?= $erro ?></p>
      <?php endif ?>
      <ul id="convites">
        <?php
        foreach (obter_convites_criados_por($usuario->id) as $convite) {
          $user = null;
          if ($convite->usado_por) {
            $user = usuario_requestIDator($convite->usado_por);
          }
        ?>
          <li>
            <?= $convite->codigo ?> -
            <?php if ($user != null): ?>
              Usado por <a href="/usuarios/<?= $user->username ?>"><?= $user->username ?></a>
            <?php else: ?>
              <button onclick="navigator.clipboard.writeText('<?= $config['URL'] ?>/registro.php?convite=<?= $convite->codigo ?>')">Copiar link</button> - <a href="/convites.php?deletar=<?= $convite->codigo ?>">Deletar</a>
            <?php endif ?>
          </li>
        <?php
        }
        ?>
      </ul>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>