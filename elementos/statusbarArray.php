<?php if ($sucesso) : ?>
	<?php foreach ($sucesso as $suc) : ?>
	<div class="statusbar success" onclick="this.style.display='none'" title="Clique para fechar">
		<img src="/elementos/emoticons/_yay_.png" alt="" style="float: left;">
		<img src="/elementos/emoticons/_yay_.png" alt="" style="float: right;">
		<p><?= $suc ?></p>
	</div>
	<?php endforeach; ?>
<?php elseif ($erro) : ?>
	<?php foreach ($erro as $err) : ?>
	<div class="statusbar error" onclick="this.style.display='none'" title="Clique para fechar">
		<img src="/elementos/erro.gif" alt="" style="float: left;">
		<img src="/elementos/erro.gif" alt="" style="float: right;">
		<p><?= $err ?></p>
	</div>
	<?php endforeach; ?>
<?php elseif ($info) : ?>
	<?php foreach ($info as $inf) : ?>
	<div class="statusbar info" onclick="this.style.display='none'" title="Clique para fechar">
		<img src="/elementos/info.png" alt="" style="float: left;">
		<img src="/elementos/info.png" alt="" style="float: right;">
		<p><?= $inf ?></p>
	</div>
	<?php endforeach; ?>
<?php elseif ($aviso) : ?>
	<?php foreach ($aviso as $av) : ?>
	<div class="statusbar warning" onclick="this.style.display='none'" title="Clique para fechar">
		<img src="/elementos/warning.png" alt="" style="float: left;">
		<img src="/elementos/warning.png" alt="" style="float: right;">
		<p><?= $av ?></p>
	</div>
	<?php endforeach; ?>
<?php endif; ?>