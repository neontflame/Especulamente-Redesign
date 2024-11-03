<?php include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (isset($usuario)) {
  redirect('/');
}

$erro;
$convite;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["convite"])) {
    $convite = $_GET["convite"];
  } else {
    $erro = "Whoops! Você precisa de um convite para criar uma conta! Você não é digno!";
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $senha = $_POST['senha'];

  // The message
  $message = "Line 1\r\nLine 2\r\nLine 3";

  // In case any of our lines are larger than 70 characters, we should use wordwrap()
  $message = wordwrap($message, 70, "\r\n");

  // Send
  mail($_POST['email'], 'My Subject', $message);
}
?>

<?php
$titulo = "[Criar conta <> PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';
?>

<div class="container">
  <?php
  $esconder_ad = true;
  include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php';
  ?>

  <div class="page_content" style="min-height: 254px">
    <div class="inside_page_content">
      <?php if (isset($erro)) : ?>
        <p><?= $erro ?></p>
      <?php endif ?>
      <?php if (isset($convite)) : ?>
        <form action="" method="post">
          <label for="username">nome de usuário</label>
          <input name="username" id="username" type="text" required>
          <br>
          <label for="email">email</label>
          <input name="email" id="email" type="email" required>
          <br>
          <label for="senha">senha</label>
          <input name="senha" id="senha" type="password" required>
          <br>
          <button>Registrar</button>
        </form>
        <p>já tem uma conta? então <a href="/entrar.php">entre</a></p>
      <?php endif ?>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>