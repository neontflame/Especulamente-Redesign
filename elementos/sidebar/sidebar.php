<?php
$ads = [
  ['ad1.png', 'https://www.google.com.br/search?q=cabelo'],
  ['ad2.png', 'https://store.steampowered.com/app/2161700/Persona_3_Reload/'],
  ['ad3.png', 'https://especulamente.com.br'],
  ['ad4.gif', 'https://fupi.cat/arte/feiocore/'],
  ['ad5.png', 'https://www.sentaifilmworks.com/blogs/catalog/magical-play'],
];
$ad = $ads[array_rand($ads)];
?>
<div class="left_col">
  <a href="/projetos/"><img src="/elementos/sidebar/projetosInativo.png" onmouseover="this.src='/elementos/sidebar/projetosAtivo.png';" onmouseout="this.src='/elementos/sidebar/projetosInativo.png';" /></a><br />
  <img style="margin-top: 8px;" src="/elementos/sidebar/tiposcoisa.png">
  <a href="/midia/"><img src="/elementos/sidebar/midiaInativo.png" onmouseover="this.src='/elementos/sidebar/midiaAtivo.png';" onmouseout="this.src='/elementos/sidebar/midiaInativo.png';" /></a><br />
  <a href="/jogos/"><img src="/elementos/sidebar/jogosInativo.png" onmouseover="this.src='/elementos/sidebar/jogosAtivo.png';" onmouseout="this.src='/elementos/sidebar/jogosInativo.png';" /></a><br />
  <a href="/blogs/"><img src="/elementos/sidebar/blogsInativo.png" onmouseover="this.src='/elementos/sidebar/blogsAtivo.png';" onmouseout="this.src='/elementos/sidebar/blogsInativo.png';" /></a><br />
  <a href="/downloadaveis/"><img src="/elementos/sidebar/downloadaveisInativo.png" onmouseover="this.src='/elementos/sidebar/downloadaveisAtivo.png';" onmouseout="this.src='/elementos/sidebar/downloadaveisInativo.png';" /></a><br />
  <a href="/resto/"><img src="/elementos/sidebar/orestoInativo.png" style="margin-top: 8px;" onmouseover="this.src='/elementos/sidebar/orestoAtivo.png';" onmouseout="this.src='/elementos/sidebar/orestoInativo.png';" /></a>
  <?php if (!($esconder_ad ?? false)) : ?>
    <br />
    <img src="/elementos/sidebar/patrociono.png" style="margin-top: 6px;" />
    <!-- ANUNCIOS SAO 180x208-->
    <a href="<?= $ad[1] ?>" target="_blank"><img width="180" height="208" style="border: 1px solid #5D85E2;" src="/elementos/sidebar/patrocinios/<?= $ad[0] ?>" /></a>
  <?php endif ?>
</div>