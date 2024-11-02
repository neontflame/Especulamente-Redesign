<?php
$ads = [
  'ad1.png',
  'ad2.png',
];
$ad = $ads[array_rand($ads)];
?>
<div class="left_col">
  <a href=""><img src="/elementos/sidebar/projetosInativo.png" onmouseover="this.src='/elementos/sidebar/projetosAtivo.png';" onmouseout="this.src='/elementos/sidebar/projetosInativo.png';" /></a><br />
  <a href=""><img src="/elementos/sidebar/jogosInativo.png" onmouseover="this.src='/elementos/sidebar/jogosAtivo.png';" onmouseout="this.src='/elementos/sidebar/jogosInativo.png';" /></a><br />
  <a href=""><img src="/elementos/sidebar/midiaInativo.png" onmouseover="this.src='/elementos/sidebar/midiaAtivo.png';" onmouseout="this.src='/elementos/sidebar/midiaInativo.png';" /></a><br />
  <a href=""><img src="/elementos/sidebar/blogsInativo.png" onmouseover="this.src='/elementos/sidebar/blogsAtivo.png';" onmouseout="this.src='/elementos/sidebar/blogsInativo.png';" /></a><br />
  <a href=""><img src="/elementos/sidebar/orestoInativo.png" style="margin-top: 8px;" onmouseover="this.src='/elementos/sidebar/orestoAtivo.png';" onmouseout="this.src='/elementos/sidebar/orestoInativo.png';" /></a>
  <br />
  <img src="/elementos/sidebar/patrociono.png" style="margin-top: 6px;" />
  <!-- ANUNCIOS SAO 180x208-->
  <a href=""><img style="border: 1px solid #5D85E2;" src="/elementos/sidebar/patrocinios/<?= $ad?>" /></a>
</div>