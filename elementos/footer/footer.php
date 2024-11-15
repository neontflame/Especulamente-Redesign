<?php
$chars = [
  'padudu.png',
  'queixao.png',
  'sushi.png',
];
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
    <img src="/elementos/footer/get_flash_player.png" />
    <img src="/elementos/footer/expression.png" />
    <img src="/elementos/footer/associates.png" />
    <br />
    <br />
    <a href="">TERMOS DE USO</a> -
    <a href="">REGRAS PARA PESTINHAS COMO VOCÊ</a> - <a href="">CRÉDITOS</a>
  </div>
  <img id="character" src="/elementos/footer/chars/<?= $char ?>" />
</div>
<script src="/js.js"></script>
</body>

</html>