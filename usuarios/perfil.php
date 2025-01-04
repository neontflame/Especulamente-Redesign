<?php include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
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
    $pfp_rtn = subir_arquivo($_FILES['pfp_fnf'], '/static/pfps/', 'usuarios', $usuario->id, 'pfp', ['png', 'jpg', 'jpeg', 'gif', 'bmp'], 1024 * 1024);
    if (str_starts_with($pfp_rtn, '§')) {
      array_push($erro, substr($pfp_rtn, 1));
    }
  }

  // carregar Bnr
  if (isset($_FILES['bnr_fnf'])) {
    $bnr_rtn = subir_arquivo($_FILES['bnr_fnf'], '/static/banners/', 'usuarios', $usuario->id, 'banner', ['png', 'jpg', 'jpeg', 'gif', 'bmp'], 1024 * 1024);
    if (str_starts_with($bnr_rtn, '§')) {
      array_push($erro, substr($bnr_rtn, 1));
    }
  }

  // carregar Biographia
  if (isset($_POST['bio_fnf'])) {
    $bio = $_POST['bio_fnf'];

    mudar_usuario($usuario->id, ['bio' => $bio]);
  }

  // carregar Mito
  if (isset($_POST['mitar'])) {
    $mitar = $_POST['mitar'];

    if ($mitar) {
      $count = reagir($usuario->id, $perfil->id, 'perfil', 'mitada');
      if ($count == -1) {
        $count = desreagir($usuario->id, $perfil->id, 'perfil', 'mitada');
        echo $count;
      } else {
        echo $count;
      }
      die();
    }
  }

  // carregar Soja
  if (isset($_POST['sojar'])) {
    $sojar = $_POST['sojar'];

    if ($sojar) {
      $count = reagir($usuario->id, $perfil->id, 'perfil', 'sojada');
      if ($count == -1) {
        $count = desreagir($usuario->id, $perfil->id, 'perfil', 'sojada');
        echo $count;
      } else {
        echo $count;
      }
      die();
    }
  }

  $perfil = usuario_requestinator($username);
}

// whoops you have to put the usuario in the url
if (!$perfil) {
  erro_404();
}

$perfil_e_meu = $usuario ? ($usuario->id == $perfil->id) : false;
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="min-height: 486px">
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
        background-color: #FFFFFF;
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
      <?php if ($perfil_e_meu) : ?>
        <button class="bioEditavel" onclick="form_bio.style.display = 'block'; bio.style.display = 'none'">
        <?php endif; ?>

        <p id="bio" style="margin-top: 0px; white-space: pre-line;"><?= htmlspecialchars($perfil->bio) ?></p>

        <?php if ($perfil_e_meu) : ?>
        </button>
        <form action="" method="post" enctype="multipart/form-data" id="form_bio" style="display: none;">
          <textarea name="bio_fnf" id="bio_fnf" style="width: 425px; height: 150px;"><?= htmlspecialchars($perfil->bio) ?></textarea>
          <button type="submit" class="bioButt">
		  Salvar bio
		  </button>
        </form>
      <?php endif; ?>

      <p>
        Esse usuário tem
        <span id="mitadas_cnt"><?= $perfil->mitadas ?></span> mitadas e
        <span id="sojadas_cnt"><?= $perfil->sojadas ?></span> sojadas.
      </p>

      <?php if (!$perfil_e_meu && isset($usuario)) : ?>
        <?php
        $ja_mitou = ja_reagiu($usuario->id, $perfil->id, 'perfil', 'mitada');
        $ja_sojou = ja_reagiu($usuario->id, $perfil->id, 'perfil', 'sojada');
        ?>
        <button onclick="mitar(this)"><?= $ja_mitou ? 'Desmitar' : 'Mitar' ?></button>
        <button onclick="sojar(this)"><?= $ja_sojou ? 'Dessojar' : 'Sojar' ?></button>

        <script>
          function mitar(element) {
            const req = new XMLHttpRequest();
            req.addEventListener("load", function() {
              if (this.responseText == "null") {
                alert("Erro ao mitar");
              } else {
                mitadas_cnt.innerText = this.responseText;
                element.innerText = element.innerText == "Mitar" ? "Desmitar" : "Mitar";
              }
            });
            const formData = new FormData();
            formData.append("mitar", "true");

            req.open("POST", "/usuarios/<?php echo $perfil->username ?>");
            req.send(formData);
          }

          function sojar(element) {
            const req = new XMLHttpRequest();
            req.addEventListener("load", function() {
              if (this.responseText == "-1") {
                alert("Erro ao sojar");
              } else {
                sojadas_cnt.innerText = this.responseText;
                element.innerText = element.innerText == "Sojar" ? "Dessojar" : "Sojar";
              }
            });
            const formData = new FormData();
            formData.append("sojar", "true");

            req.open("POST", "/usuarios/<?php echo $perfil->username ?>");
            req.send(formData);
          }
        </script>
      <?php endif; ?>


      <h1><?= $perfil->username ?></h1>

    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>