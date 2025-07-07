<?php
$chars = array();
$dir = $_SERVER['DOCUMENT_ROOT'] . "/elementos/footer/chars/";
if ($handle = scandir($dir)) {
  foreach ($handle as $target) {
    if (!in_array($target, [".", ".."])) {
      $chars[] = $target;
    }
  }
}

$char = $chars[array_rand($chars)];
?>
</div>

<div class="bodyFooter">
  <div class="insideBodyFooter">
    Esse site é melhor visualizado em<br />800x600 px, com High Color (16
    bits). <br /><br />
    Webmasters: Neon T. Flame e Fupicat
    <br /><br />
    <img src="/elementos/footer/LaughedTesticle.png" />
    <a href="https://archive.org/details/flashplayer_old"><img src="/elementos/footer/get_flash_player.png" /></a>
    <img src="/elementos/footer/expression.png" />
    <a href="https://vinnic1998.github.io/AssociaDouga/index.html"><img src="/elementos/footer/associates.png"></a>
    <br />
    <br />
    <a href="/termos.php">TERMOS DE USO</a> -
    <a href="/regras.php">REGRAS PARA PESTINHAS COMO VOCÊ</a> -
    <a href="/creditos.php">CRÉDITOS</a>
  </div>
  <img id="character" src="/elementos/footer/chars/<?= $char ?>" />
</div>
<script src="/js.js"></script>
</body>

</html>