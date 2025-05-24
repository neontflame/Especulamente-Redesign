<?php if ($sucesso) : ?>
	<div class="statusbar success" onclick="this.style.display='none'" title="Clique para fechar">
		<img src="/elementos/emoticons/_yay_.png" alt="" style="float: left;">
		<img src="/elementos/emoticons/_yay_.png" alt="" style="float: right;">
		<p><?= $sucesso ?></p>
	</div>
<?php elseif ($erro) : ?>
	<div class="statusbar error" onclick="this.style.display='none'" title="Clique para fechar">
		<img src="/elementos/erro.gif" alt="" style="float: left;">
		<img src="/elementos/erro.gif" alt="" style="float: right;">
		<p><?= $erro ?></p>
	</div>
<?php elseif ($info) : ?>
	<div class="statusbar info" onclick="this.style.display='none'" title="Clique para fechar">
		<img src="/elementos/info.png" alt="" style="float: left;">
		<img src="/elementos/info.png" alt="" style="float: right;">
		<p><?= $info ?></p>
	</div>
<?php elseif ($aviso) : ?>
	<div class="statusbar warning" onclick="this.style.display='none'" title="Clique para fechar">
		<img src="/elementos/warning.png" alt="" style="float: left;">
		<img src="/elementos/warning.png" alt="" style="float: right;">
		<p><?= $aviso ?></p>
	</div>
<?php endif; ?>