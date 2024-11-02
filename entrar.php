<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/login_coisos.php';

if (isset($_SESSION['id'])) {
  header("Location: index.php");
  die();
}

$erro;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $senha = $_POST['senha'];

  if (isset($username) && isset($senha)) {
    $con = new PDO("mysql:host=localhost;dbname=especulamente", "especulamente", "");

    $stmt = $con->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bindParam(1, $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(PDO::FETCH_OBJ);
      if (password_verify($senha, $row->password_hash)) {
        $_SESSION['id'] = $row->id;
        $_SESSION['username'] = $row->username;
        header("Location: index.php");
        die();
      } else {
        $erro = "Usuário ou senha incorretos!";
      }
    } else {
      $erro = "Usuário ou senha incorretos!";
    }
  }
}
?>

<?php
$titulo = "[Entrar <> PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';
?>

<div class="container">
  <?php
  $esconder_ad = true;
  include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php';
  ?>

  <div class="page_content" style="height: 270px">
    <div class="inside_page_content">
      <?php if (isset($erro)) : ?>
        <p><?= $erro ?></p>
      <?php endif ?>
      <form action="/entrar.php" method="post">
        <label for="username">nome de usuário</label>
        <input name="username" id="username" type="text" required>
        <br>
        <label for="senha">senha</label>
        <input name="senha" id="senha" type="text" required>
        <br>
        <button>Entrar</button>
      </form>
      <p><a href="/esqueci.php">esqueceu a senha?</a></p>
      <p>não tem uma conta ainda? <a href="/registrar.php" title="ou morra tentando">crie uma aqui</a></p>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>