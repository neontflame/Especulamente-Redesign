<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (isset($usuario)) {
  redirect('/');
}

$erro;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $senha = $_POST['senha'];

  if (isset($username) && isset($senha)) {
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bindParam(1, $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(PDO::FETCH_OBJ);
      if (password_verify($senha, $row->password_hash)) {
        $_SESSION['id'] = $row->id;
        $_SESSION['username'] = $row->username;
        redirect('/');
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

  <style>
    .inside_page_content {
      text-align-last: center;
      color: #898989;
    }

    .inside_page_content form {
      text-align-last: right;
      margin-right: 80px;
      color: #566C9E;
    }

    .inside_page_content form input {
      text-align-last: left;
      margin-top: 4px;
    }


    .inside_page_content form button {
      margin-top: 16px;
      margin-right: 118px;
      text-align-last: left;
    }

    .inside_page_content a {
      text-decoration: none;
    }
  </style>

  <div class="page_content" style="height: 254px">
    <div class="inside_page_content">
      <img src="elementos/ola.png" style="margin-top: -5px; margin-left: -5px;">
      <form action="" method="post">
        <label for="username">nome de usuário</label>
        <input name="username" id="username" type="text" required>
        <br>
        <label for="senha">senha</label>
        <input name="senha" id="senha" type="password" required>
        <br>
		<?php if (isset($erro)) : ?><p style="color: #FF0000;"><?= $erro ?></p><?php endif ?>
        <button class="coolButt">Entrar</button>
      </form>
      <p><a href="/esqueci.php">esqueceu a senha?</a></p>
      <p>não tem uma conta ainda? <a href="/registrar.php" title="ou morra tentando">crie uma aqui</a></p>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>