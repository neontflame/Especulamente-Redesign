<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

reinvindicar_bounty(1);

$bounty = obter_bounty(1);
$rank = obter_rank($usuario->davecoins);
?>
<span class="missaoCompleta">+<?= $bounty->davecoins == 0 ? $rank["diada"] : $bounty->davecoins ?> <img style="vertical-align: bottom;" src="/elementos/davecoin/dvc.gif"></span>