<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (isset($usuario)) {
  redirect('/');
}

$erro = null;
$sucesso = null;
$username = "";
$email = "";
$convite = null;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  function checagens()
  {
    global $db, $convite;

    // Convite foi especificado?
    $convite = $_GET["convite"] ?? null;
    if ($convite == null) {
      return null;
    }

    // Convite existe?
    $rows = $db->prepare("SELECT * FROM convites WHERE codigo = ? AND usado_por IS NULL");
    $rows->bindParam(1, $convite);
    $rows->execute();
    if ($rows->rowCount() == 0) {
      return "Código inválido! Você não é digno.";
    }

    return null;
  }

  $erro = checagens();
  if ($erro) {
    $convite = null;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  function checagens()
  {
    global $db;

    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;
    $senhaConfirm = $_POST['senhaConfirm'] ?? null;
    $convite = $_POST['convite'] ?? null;

    if ($username == null || $email == null || $senha == null || $senhaConfirm == null) {
      return "Preencha todos os campos!";
    }
    if ($convite == null) {
      return "Convite não especificado!";
    }

    // Convite existe?
    $rows = $db->prepare("SELECT * FROM convites WHERE codigo = ? AND usado_por IS NULL");
    $rows->bindParam(1, $_POST['convite']);
    $rows->execute();
    if ($rows->rowCount() == 0) {
      return "Código inválido! Você não é digno.";
    }

    // Nome de usuário existe?
    $rows = $db->prepare("SELECT * FROM usuarios WHERE username = ?");
    $rows->bindParam(1, $username);
    $rows->execute();
    if ($rows->rowCount() != 0) {
      return "Cadê a originalidade? Esse nome de usuário JÁ existe.";
    }

    // Nome de usuário inválido?
    if (strlen($username) < 3) {
      return "Seu nome de usuário é: muito curto.";
    }
    if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
      return "Apenas letras, números, pontos e underlines pfv!!";
    }

    // Email inválido?
    $rows = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
    $rows->bindParam(1, $email);
    $rows->execute();
    if ($rows->rowCount() != 0) {
      return "Cadê a originalidade? Esse email JÁ foi usado.";
    }

    // Senha inválida?
    if (strlen($senha) < 6) {
      return "Sua senha é: muito curta. senha.";
    }
    if ($senha != $senhaConfirm) {
      return "As senhas não coincidem!";
    }

    return null;
  }

  $erro = checagens();

  if ($erro == null) {
    criar_usuario(
      $_POST['username'],
      $_POST['email'],
      $_POST['senha'],
      $_POST['convite']
    );

    $sucesso = "Sua conta foi criada!! Você pode entrar agora!";
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

  <div class="page_content" style="min-height: 324px">
    <div class="inside_page_content">
      <?php if ($erro) mostrarErro($erro); ?>
      <?php if ($sucesso) mostrarSucesso($sucesso); ?>
      <img src="elementos/registrar.png" style="margin-top: -5px; margin-left: -5px;">
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
          <label for="senhaConfirm">confirme a senha</label>
          <input name="senhaConfirm" id="senhaConfirm" type="password" required>
          <br>
          <button class="coolButt" style="margin-right: 100px;">Registrar</button>
        </form>
      <?php else : ?>
        <p>O ESPECULAMENTE é um site apenas para convidados! Se você tiver um código de convite, insira-o abaixo:</p>
        <form action="" method="get" style="margin-right: 108px;">
          <label for="convite">código</label>
          <input name="convite" id="convite" type="text" required value="<?= $convite ?>">
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