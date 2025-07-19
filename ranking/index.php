<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; 

$sortCoiso = [
'mitada' => 'mitadas DESC',
'sojada' => 'sojadas DESC',
'davecoin' => 'davecoins DESC'
];

$sortCoisitos = (isset($_GET['sort']) && array_key_exists($_GET['sort'], $sortCoiso)) ? $_GET['sort'] : 'davecoin';

?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<style>
	.sortsCoiso {
	  color: #87A0DB;
	  text-align: center;
	  margin-top: 2px;
	}

	.sortsCoiso a {
	  color: #87A0DB;
	  text-decoration: none;
	}

	.sortsCoiso a:hover {
	  text-decoration: underline;
	}

	.sortsCoiso b {
	  color: black;
	}
	</style>
	<?php 
	include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content" style="min-height: 556px">
		<div class="inside_page_content">
			<img src="/elementos/pagetitles/rankings.png" style="margin-top: -5px; margin-left: -5px; margin-right: -5px;">
			<div class="sortsCoiso">
			<?php if ($sortCoisitos != 'davecoin') { ?><a href="/ranking?sort=davecoin">com maior nível</a><?php } else { ?><b>com maior nível</b><?php } ?>
			-
			<?php if ($sortCoisitos != 'mitada') { ?><a href="/ranking?sort=mitada">mais mitados</a><?php } else { ?><b>mais mitados</b><?php } ?>
			- 
			<?php if ($sortCoisitos != 'sojada') { ?><a href="/ranking?sort=sojada">mais sojados</a><?php } else { ?><b>mais sojados</b><?php } ?>
			</div>
			
			<div class="separador" style="margin-bottom: 8px; border-color:#D2EDFF"></div>
			<?php
			$usuarios = [];

			$pages = coisos_tudo($usuarios, 'usuarios', 1, '', '', 1000, $sortCoiso[$sortCoisitos]);
			?>
			<div class="usuariosRanking">
				<?php
				$lugar = 0;
				$ultimaQuant = 0;
				foreach ($usuarios as $usuario) { 
				if (
				($sortCoisitos == "mitada" && $usuario->mitadas != $ultimaQuant) ||
				($sortCoisitos == "sojada" && $usuario->sojadas != $ultimaQuant) ||
				($sortCoisitos == "davecoin" && $usuario->davecoins != $ultimaQuant)
				) {
				$lugar += 1;
				}
				?>
				<div class="rankeado">
					<span class="lugar<?php if ($lugar < 4) { echo $lugar; } ?>"><?= $lugar ?>º</span>
					<a href="/usuarios/<?= $usuario->username ?>"><img src="<?= pfp($usuario) ?>"></a>
					<a class="username" href="/usuarios/<?= $usuario->username ?>"><?= $usuario->username ?></a>
				<?php if ($sortCoisitos != "davecoin") { ?> 
				<span class="infoExtra">com <?php 
					if ($sortCoisitos == "mitada") { echo $usuario->mitadas; }
					if ($sortCoisitos == "sojada") { echo $usuario->sojadas; }
					?> <?= $sortCoisitos ?>s
				</span>
				<?php } else { ?>
				<span class="infoExtra" style="float:right;">
					<?= obter_rank($usuario->davecoins)["nome"] ?> 
					<img src="/elementos/ranks/<?= obter_rank($usuario->davecoins)["imagem"] ?>" width="48" height="48">
				</span>
				<?php } ?>
				</div>
				<?php 
				
				if ($sortCoisitos == "mitada") { $ultimaQuant = $usuario->mitadas; }
				if ($sortCoisitos == "sojada") { $ultimaQuant = $usuario->sojadas; }
				if ($sortCoisitos == "davecoin") { $ultimaQuant = $usuario->davecoins; }
				} ?>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>