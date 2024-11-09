<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);

$erro;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET['deletar'])) {
    $oq_deletar = $_GET['deletar'];

    $oq_deletar = substr($oq_deletar, 0, 255);

    $rows = $db->prepare("SELECT * FROM convites WHERE codigo = ? AND criado_por = ?");
    $rows->bindParam(1, $oq_deletar);
    $rows->bindParam(2, $usuario['id']);
    $rows->execute();

    if ($rows->rowCount() > 0) {
      $rows = $db->prepare("DELETE FROM convites WHERE codigo = ?");
      $rows->bindParam(1, $oq_deletar);
      $rows->execute();
    } else {
      $erro = "Whoops! Esse convite não existe, ou não é seu.";
    }
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $codigo = $_POST['codigo'];

  $codigo = substr($codigo, 0, 255);

  if (isset($codigo)) {
    $rows = $db->prepare("SELECT * FROM convites WHERE codigo = ?");
    $rows->bindParam(1, $codigo);
    $rows->execute();

    if ($rows->rowCount() > 0) {
      $erro = "Whoops! Um convite com esse nome já existe.";
    } else {
      $rows = $db->prepare("INSERT INTO convites (codigo, criado_por) VALUES (?, ?)");
      $rows->bindParam(1, $codigo);
      $rows->bindParam(2, $usuario['id']);
      $rows->execute();
    }
  }
}
?>

<?php
$titulo = "[Seus convites <> PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';
?>

<div class="container">
  <?php
  $esconder_ad = true;
  include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php';
  ?>

  <div class="page_content" style="min-height: 254px">
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
        $rows = $db->prepare("SELECT * FROM convites WHERE criado_por = ?");
        $rows->bindParam(1, $usuario['id']);
        $rows->execute();

        if ($rows->rowCount() > 0) {
          while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
            $username = null;
            if ($row->usado_por) {
              $user_q = $db->prepare("SELECT username FROM usuarios WHERE id = ?");
              $user_q->bindParam(1, $row->usado_por);
              $user_q->execute();
              $user = $user_q->fetch(PDO::FETCH_OBJ);
              $username = $user->username;
            }
        ?>
            <li>
              <?= $row->codigo ?> -
              <?php if ($username != null): ?>
                Usado por <a href="/usuarios/<?= $username ?>"><?= $username ?></a>
              <?php else: ?>
                <button onclick="navigator.clipboard.writeText('<?= $config['URL'] ?>/registro.php?convite=<?= $row->codigo ?>')">Copiar link</button> - <a href="/convites.php?deletar=<?= $row->codigo ?>">Deletar</a>
              <?php endif ?>
            </li>
        <?php
          }
        }
        ?>
      </ul>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>