<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>

<?php
$meta["titulo"] = "[𝓑𝓸𝓾𝓷𝓽𝓲𝓯𝓾𝓵 𝓑𝓸𝓾𝓷𝓽𝓲𝓮𝓼 do dave <> PORTAL ESPECULAMENTE]";
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<link href="/cssDoDave.css" rel="stylesheet" type="text/css" />
<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="min-height: 556px">
    <img src="/elementos/pagetitles/bountiful-bounties.png" class="inside_page_content" style="padding: 0px; margin-bottom: 7px;">
    <div class="inside_page_content">
      <?php dave_rank($usuario->davecoins); ?>
      <p>
        <center>Você precisa de <b><?= $rank["davecoins_proximo"] - $usuario->davecoins ?></b> davecoins para o próximo nível!</center>
      </p>
      <p>
        <center>Volte todos os dias para conseguir mais davecoins!!</center>
      </p>
      <div class="bounties">
        <?php $bounties = obter_bounties($usuario->id); ?>
        <?php foreach ($bounties as $bounty) : ?>
          <div class="bounty">
            <img src="/bounties/itens/<?= $bounty->imagem ?>" alt="">
            <div class="direito">
              <b><?= $bounty->nome ?></b><span><button onclick="moeda(event, 1)" class="coolButt verde" style="margin-top: 0;">Reivindicar <?= $bounty->davecoins == 0 ? $rank["diada"] : $bounty->davecoins ?> <img style="vertical-align: bottom;" src="/elementos/davecoin/dvc.gif"></button></span>
              <div class="barrinha">
                <p><?= $bounty->descricao ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <style>
    .daverank {
      padding-top: 0;
    }

    .bounty {
      border: 1px solid #9EBBFF;
      padding: 4px;
      display: table;
      margin-bottom: 6px;
    }

    .bounty>img {
      float: left;
    }

    .bounty .direito {
      float: right;
      width: 362px;
      margin-left: 12px;
    }

    .bounty .direito>b {
      float: left;
      font-size: 14px;
    }

    .bounty .direito>span {
      float: right;
    }

    .bounty .direito p {
      margin-bottom: 0;
      margin-top: 26px;
    }
  </style>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>