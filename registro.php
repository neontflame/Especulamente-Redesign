<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (isset($usuario)) {
  redirect('/');
}

$entradas = [];
$ehDigno = false;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  function checagens($db, $convite)
  {
    // Convite foi especificado?
    if (!isset($convite)) {
      return null;
    }

    // Convite existe?
    $rows = $db->prepare("SELECT * FROM convites WHERE codigo = ? AND usado_por IS NULL");
    $rows->bindParam(1, $convite);
    $rows->execute();
    if ($rows->rowCount() == 0) {
      erro("Código inválido! Você não é digno.");
      return null;
    }

    return [
      "convite" => $convite,
    ];
  }

  $entradas = checagens($db, $_GET["convite"] ?? null);
  if ($entradas) {
    $ehDigno = true;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  function checagens($db, $username, $email, $senha, $senhaConfirm, $convite)
  {
    global $ehDigno;

    if (!isset($username) || !isset($email) || !isset($senha) || !isset($senhaConfirm)) {
      erro("Preencha todos os campos!");
      return null;
    }
    if (!isset($convite)) {
      erro("Você não tem um convite!");
      return null;
    }

    // Convite existe?
    $rows = $db->prepare("SELECT * FROM convites WHERE codigo = ? AND usado_por IS NULL");
    $rows->bindParam(1, $convite);
    $rows->execute();
    if ($rows->rowCount() == 0) {
      erro("Código inválido! Você não é digno.");
      return null;
    }

    $ehDigno = true;

    // Nome de usuário existe?
    $rows = $db->prepare("SELECT * FROM usuarios WHERE username = ?");
    $rows->bindParam(1, $username);
    $rows->execute();
    if ($rows->rowCount() != 0) {
      erro("Cadê a originalidade? Esse nome de usuário JÁ existe.");
      return null;
    }

    // Nome de usuário inválido?
    if (strlen($username) < 3) {
      erro("Seu nome de usuário é: muito curto.");
      return null;
    }
    if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
      erro("Apenas letras, números, pontos e underlines pfv!!");
      return null;
    }

    // Email inválido?
    $rows = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
    $rows->bindParam(1, $email);
    $rows->execute();
    if ($rows->rowCount() != 0) {
      erro("Cadê a originalidade? Esse email JÁ foi usado.");
      return null;
    }

    // Senha inválida?
    if (strlen($senha) < 6) {
      erro("Sua senha é: muito curta. senha.");
      return null;
    }
    if ($senha != $senhaConfirm) {
      erro("As senhas não coincidem!");
      return null;
    }

    return [
      "username" => $username,
      "email" => $email,
      "senha" => $senha,
      "convite" => $convite,
    ];
  }

  $entradas = checagens(
    $db,
    $_POST['username'] ?? null,
    $_POST['email'] ?? null,
    $_POST['senha'] ?? null,
    $_POST['senhaConfirm'] ?? null,
    $_POST['convite'] ?? null
  );

  if ($entradas) {
    criar_usuario(
      $entradas["username"],
      $entradas["email"],
      $entradas["senha"],
      $entradas["convite"],
    );

    sucesso("Sua conta foi criada!! Você pode entrar agora!");
    redirect('/entrar.php');
  }
}
?>

<?php
$meta["titulo"] = "[Criar conta <> PORTAL ESPECULAMENTE]";
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

  <div class="page_content">
    <div class="inside_page_content">
      <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
      <img src="elementos/registrar.png" style="margin-top: -5px; margin-left: -5px; margin-right: -5px;">
      <?php if ($ehDigno) : ?>
        <h1>VOCÊ É DIGNO!!!</h1>
        <p>Seu código é: <?= $_GET['convite'] ?? $_POST['convite'] ?? '' ?></p>
        <form action="" method="post">
          <input type="hidden" name="convite" value="<?= $_GET['convite'] ?? $_POST['convite'] ?? '' ?>">
          <label for="username">nome de usuário</label>
          <input name="username" id="username" type="text" required value="<?= $_POST['username'] ?? '' ?>">
          <br>
          <label for="email">email</label>
          <input name="email" id="email" type="email" required value="<?= $_POST['email'] ?? '' ?>">
          <br>
          <label for="senha">senha</label>
          <input name="senha" id="senha" type="password" required>
          <br>
          <label for="senhaConfirm">confirme a senha</label>
          <input name="senhaConfirm" id="senhaConfirm" type="password" required>
          <br>
          <button class="coolButt" style="margin-right: 100px;">Registrar</button>
        </form>
      <?php else : ?>
        <p>O ESPECULAMENTE é um site apenas para convidados! Se você tiver um código de convite, insira-o abaixo:</p>
        <form action="" method="get" style="margin-right: 108px;">
          <label for="convite">código</label>
          <input name="convite" id="convite" type="text" required value="<?= $_GET['convite'] ?? '' ?>">
          <br>
          <button class="coolButt" style="margin-right: 52px;">Checar convite</button>
        </form>
        <p>Se não tiver......... <i style="color: red;"><b>vaze.</b></i></p>
      <?php endif ?>
      <br>
      <p>já tem uma conta? então <a href="/entrar.php">entre</a></p>
    </div>
    <style>
      .inside_page_content p {
        text-align: center;
      }
    </style>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>