<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; 

$sortCoiso = [
'mitada' => 'mitadas DESC',
'sojada' => 'sojadas DESC',
'davecoin' => 'davecoins DESC'
];

$sortCoisitos = (isset($_GET['sort']) && array_key_exists($_GET['sort'], $sortCoiso)) ? $_GET['sort'] : 'mitada';

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
			<?php if ($sortCoisitos != 'mitada') { ?><a href="/ranking?sort=mitada">mais mitados</a><?php } else { ?><b>mais mitados</b><?php } ?>
			- 
			<?php if ($sortCoisitos != 'sojada') { ?><a href="/ranking?sort=sojada">mais sojados</a><?php } else { ?><b>mais sojados</b><?php } ?>
			- 
			<?php if ($sortCoisitos != 'davecoin') { ?><a href="/ranking?sort=davecoin">com mais davecoins</a><?php } else { ?><b>com mais davecoins</b><?php } ?>
			</div>
			
			<div class="separador" style="margin-bottom: 8px; border-color:#D2EDFF"></div>
			<?php
			$usuarios = [];

			$pages = coisos_tudo($usuarios, 'usuarios', 1, '', '', 1000, $sortCoiso[$sortCoisitos]);
			?>
			<div class="usuariosRanking">
				<?php
				$lugar = 1;
				foreach ($usuarios as $usuario) { ?>
				<div class="rankeado">
					<span class="lugar<?php if ($lugar < 4) { echo $lugar; } ?>"><?= $lugar ?>º</span>
					<img src="<?php
                if ($usuario->pfp != null) {
                  echo '/static/pfps/' . $usuario->pfp;
                } else {
                  echo '/static/pfp_padrao.png';
                } ?>">
					<span class="username"><?= $usuario->username ?></span>
					<span class="infoExtra">com <?php 
					if ($sortCoisitos == "mitada") { echo $usuario->mitadas; }
					if ($sortCoisitos == "sojada") { echo $usuario->sojadas; }
					if ($sortCoisitos == "davecoin") { echo $usuario->davecoins; }
					?> <?= $sortCoisitos ?>s</span>
				</div>
				<?php 
				$lugar += 1;
				} ?>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>