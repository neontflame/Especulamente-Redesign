<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (!isset($_GET['username'])) {
  erro_404();
}
$username = $_GET['username'];

$erro = [];

$perfil = usuario_requestinator($username);

// carregar Pfp (checar comentario de baixo)
if (isset($_POST)) {
  // O comentario acima vai aqui nao ali então imagine q está aqui
  if (isset($_FILES['pfp_fnf'])) {
    $pfp_rtn = subir_arquivo($_FILES['pfp_fnf'], '/static/pfps/', 'usuarios', $usuario->id, 'pfp', ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'PNG', 'JPG', 'JPEG', 'GIF', 'BMP'], 1024 * 1024);
    if (str_starts_with($pfp_rtn, '§')) {
      array_push($erro, substr($pfp_rtn, 1));
    }
  }

  // carregar Bnr
  if (isset($_FILES['bnr_fnf'])) {
    $bnr_rtn = subir_arquivo($_FILES['bnr_fnf'], '/static/banners/', 'usuarios', $usuario->id, 'banner', ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'PNG', 'JPG', 'JPEG', 'GIF', 'BMP'], 1024 * 1024);
    if (str_starts_with($bnr_rtn, '§')) {
      array_push($erro, substr($bnr_rtn, 1));
    }
  }

  // carregar Biographia
  if (isset($_POST['bio_fnf'])) {
    $bio = $_POST['bio_fnf'];

    mudar_usuario($usuario->id, ['bio' => $bio]);
  }

  $perfil = usuario_requestinator($username);
}

// whoops you have to put the usuario in the url
if (!$perfil) {
  erro_404();
}

echo ' WHERE id_criador = ' . $usuario->id;
$projetos = [];
$proejos = coisos_tudo($projetos, 'projetos', 1, '', ' WHERE id_criador = ' . $usuario->id, 2);
// variaveis com alma ?

$perfil_e_meu = $usuario ? ($usuario->id == $perfil->id) : false;
?>

<?php
$meta["titulo"] = "[" . $perfil->username . " <> PORTAL ESPECULAMENTE]";
include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="margin-bottom: 6px;">
    <style>
      .coolBorderyNormal {
        border: solid;
        border-color: #9EBBFF;
        border-width: 1px;
      }

      .coolBorderyEditable {
        background-color: #5d85e2;
        border: none;
        padding: 1px 1px;
        cursor: pointer;
      }

      .coolBorderyEditable:hover {
        background-color: #9EBBFF;
      }

      .coolBorderyEditable:active {
        opacity: .7;
      }

      .bioEditavel {
        background-color: #FFFFFF00;
        border: none;
        position: relative;
        padding: 1px 1px;
        text-align: left;
        vertical-align: top;

        font: 12px "Verdana";
        color: #4f6bad;
      }

      .bioEditavel:hover {
        background-color: #FFFFDD;
      }

      .bioEditavel:active {
        background-color: #FFEEAA;
      }

      .bioButt {
        margin-top: 3px;
        width: 100%;
        background-color: #D6EBFF;
        border-style: solid;
        border-width: 1px;
        border-color: #5d85e2;

        font-family: Verdana;
      }

      .bioButt:hover {
        background-color: aliceblue;
      }

      .bioButt:active {
        background-color: #B5DCFF;
      }
    </style>
    <?php if ($erro) : ?>
      <div class="erro" style="color: red; background: black; text-align: center;">
        <img src="/static/skull-and-cross.gif" width="24" height="24" />
        <?= $erro[0] ?>
        <img src="/static/skull-and-cross.gif" width="24" height="24" />
      </div>
    <?php endif; ?>

    <!-- Foto de perfil -->
    <?php if ($perfil_e_meu) : ?>
      <button onclick="pfp_fnf.click()" class="coolBorderyEditable" style="margin-bottom: 6px;">
      <?php endif; ?>

      <img src="<?= pfp($perfil) ?>" alt="Foto de perfil de <?= $perfil->username ?>" width="48" height="48"
        <?php if (!$perfil_e_meu) : ?>
        class="coolBorderyNormal"
        style="margin-bottom: 6px;"
        <?php endif; ?>>

      <?php if ($perfil_e_meu) : ?>
      </button>
      <form action="" method="post" enctype="multipart/form-data" id="form_pfp" style="display: none;">
        <input type="file" name="pfp_fnf" id="pfp_fnf" accept="image/*" onchange="form_pfp.submit()">
      </form>
    <?php endif; ?>

    <!-- Banner -->
    <?php if ($perfil_e_meu) : ?>
      <button onclick="bnr_fnf.click()" class="coolBorderyEditable" style="float: right;">
      <?php endif; ?>

      <img src="<?= banner($perfil) ?>" alt="Foto de banner de <?= $perfil->username ?>" width="385" height="48"
        <?php if (!$perfil_e_meu) : ?>
        class="coolBorderyNormal"
        style="float: right;"
        <?php endif; ?>>

      <?php if ($perfil_e_meu) : ?>
      </button>
      <form action="" method="post" enctype="multipart/form-data" id="form_bnr" style="display: none;">
        <input type="file" name="bnr_fnf" id="bnr_fnf" accept="image/*" onchange="form_bnr.submit()">
      </form>
    <?php endif; ?>

    <div class="inside_page_content">
      <h1 style="margin: 0;"><?= $perfil->username ?></h1>
      <div class="separador"></div>
      <!-- Bio -->
      <?php if ($perfil_e_meu) : ?>
        <button class="bioEditavel" onclick="form_bio.style.display = 'block'; bio.style.display = 'none'">
        <?php endif; ?>

        <p id="bio" style="margin-top: 0px; white-space: pre-line;">
          <?php if ($perfil_e_meu && ($perfil->bio == null or $perfil->bio == '')) : ?>vazio - insira algo aqui!<?php endif; ?>
          <?= responde_clickers($perfil->bio) ?></p>

        <?php if ($perfil_e_meu) : ?>
        </button>
        <form action="" method="post" enctype="multipart/form-data" id="form_bio" style="display: none;">
          <textarea name="bio_fnf" id="bio_fnf" style="width: 425px; height: 150px;"><?= htmlspecialchars($perfil->bio) ?></textarea>
          <button type="submit" class="bioButt">
            Salvar bio
          </button>
        </form>
      <?php endif; ?>
      <div class="separador"></div>
	  			<style>
				.labelManeira {
					font-size: 15px;
					font-weight: bold;
					margin-top: 4px;
					margin-bottom: 4px;
				}
			</style>
	<?php if ($projetos != []) : ?>
	  <p class="labelManeira">>> PROJETOS RECENTES</p>
	  <div class="separador" style="border-color: #c7eaf9; margin-bottom: 8px;"></div>
	  <div class="projetos">
              <?php 
			  foreach ($projetos as $projeto) { 
				renderarProjeto($projeto);
			  }
			  ?>
	  </div>
	  <a class="autorDeProjeto" style="color: #9ebbff; font-weight:bold; text-align:right; display:block; margin-top:0px;"href="/projetos/?q=<?= $username ?>">ver mais projetos! >></a>
      <div class="separador"></div>
	<?php endif; ?>
      <p>Esse usuário tem <b><?= $perfil->davecoins ?></b> davecoins</p>
      <?php reajor_d_reagida('perfil', $perfil, $usuario) ?>

    </div>
  </div>

  <div class="page_content" style="min-height: 310px;">
    <div class="inside_page_content">
      <?php vedor_d_comentario('perfil', $perfil->id, true, $usuario); ?>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>