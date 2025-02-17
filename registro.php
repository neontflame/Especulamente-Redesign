<?php include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (isset($usuario)) {
  redirect('/');
}

$erro;
$username = "";
$email = "";
$convite = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["convite"])) {
    $convite = $_GET["convite"];
    $rows = $db->prepare("SELECT * FROM convites WHERE codigo = ? AND usado_por IS NULL");
    $rows->bindParam(1, $convite);
    $rows->execute();

    if ($rows->rowCount() == 0) {
      $erro = "Seu código é: null CÓDIGO";
      $convite = null;
    }
  } else {
    $erro = "Whoops! Você precisa de um convite para criar uma conta! Você não é digno!";
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];
  $convite = $_POST['convite'];

  $permitido_criar = true;

  $rows = $db->prepare("SELECT * FROM convites WHERE codigo = ? AND usado_por IS NULL");
  $rows->bindParam(1, $convite);
  $rows->execute();

  if ($rows->rowCount() == 0) {
    $erro = "Seu código é: null CÓDIGO";
    $permitido_criar = false;
  } else {
    $rows = $db->prepare("SELECT * FROM usuarios WHERE username = ?");
    $rows->bindParam(1, $username);
    $rows->execute();

    if ($rows->rowCount() != 0) {
      $erro = "Cadê a originalidade? Esse nome de usuário JÁ existe.";
      $permitido_criar = false;
    } else {
      $rows = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
      $rows->bindParam(1, $email);
      $rows->execute();

      if ($rows->rowCount() != 0) {
        $erro = "Cadê a originalidade? Esse email JÁ foi usado.";
        $permitido_criar = false;
      } else {
        if (strlen($senha) < 6) {
          $erro = "Sua senha é: muito curta. senha.";
          $permitido_criar = false;
        }
      }
    }
  }

  if ($permitido_criar) {
    $rows = $db->prepare("INSERT INTO usuarios (username, password_hash, email) VALUES (?, ?, ?)");
    $rows->bindParam(1, $username);
    $hashword = password_hash($senha, PASSWORD_DEFAULT);
    $rows->bindParam(2, $hashword);
    $rows->bindParam(3, $email);
    $rows->execute();

    $rows = $db->prepare("SELECT id FROM usuarios WHERE username = ?");
    $rows->bindParam(1, $username);
    $rows->execute();
    $row = $rows->fetch(PDO::FETCH_OBJ);

    $last_id = $row->id;

    $row = $rows->fetch(PDO::FETCH_OBJ);
    $rows = $db->prepare("UPDATE convites SET usado_por = ? WHERE codigo = ?");
    $rows->bindParam(1, $last_id);
    $rows->bindParam(2, $convite);
    $rows->execute();

    /*
    // The message
    $message = "Sua conta foi CRIADA com SUCESSO";

    // In case any of our lines are larger than 70 characters, we should use wordwrap()
    $message = wordwrap($message, 70, "\r\n");

    // Send
    mail($email, 'Atenção', $message);
    */
  }
}
?>

<?php
$titulo = "[Criar conta <> PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';
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

    .inside_page_content h1 {
      color: #0055DA;
    }

    .inside_page_content a {
      text-decoration: none;
    }
</style>
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
      <?php if ($convite) : ?>
        <h1>VOCÊ É DIGNO!!!</h1>
        <p>Seu código é: <?= $convite ?></p>
        <form action="" method="post">
          <input type="hidden" name="convite" value="<?= $convite ?>">
          <label for="username">nome de usuário</label>
          <input name="username" id="username" type="text" required value="<?= $username ?>">
          <br>
          <label for="email">email</label>
          <input name="email" id="email" type="email" required value="<?= $email ?>">
          <br>
          <label for="senha">senha</label>
          <input name="senha" id="senha" type="password" required>
          <br>
          <button class="coolButt">Registrar</button>
        </form>
        <p>já tem uma conta? então <a href="/entrar.php">entre</a></p>
      <?php endif ?>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>