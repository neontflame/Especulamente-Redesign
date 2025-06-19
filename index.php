<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php 
	$forum_no_lado = true;
	$esconder_ad = true;
	include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content" style="min-height: 556px">
		<div class="inside_page_content">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
			<style>
				.labelManeira {
					font-size: 15px;
					font-weight: bold;
					margin-top: 4px;
					margin-bottom: 4px;
				}
				.aDoLado {
				  color: #3F88F4;
				  display: block;
				  text-align: right;
				  float:right;
				  margin-top: -19px;
				  margin-right: 4px;
				  text-decoration: none;
				  font-weight: bold;
				}
				.aDoLado:hover {
				  text-decoration: underline;
				}
			</style>
			<p class="labelManeira">>> DESTAQUE</p>
			<div class="separador" style="margin-bottom: 8px; border-color:#D2EDFF"></div>
			<a href="/foruns/3/17"><img src="/elementos/destaques/collabMural1.png"></a>
			<div class="separador"></div>
			<p class="labelManeira">>> PROJETOS RECENTES</p>
			<div class="separador" style="margin-bottom: 8px; border-color:#D2EDFF"></div>
			<?php
			$projetos = [];

			$pages = coisos_tudo($projetos, 'projetos', 1, '', '', 4, 'GREATEST(COALESCE(dataBump, 0), data) DESC, id DESC');
			?>
			<div class="projetos">
				<?php foreach ($projetos as $projeto) {
					renderarProjeto($projeto);
				}
				?>
			</div>
			<div class="separador"></div>
			<div>
				<p class="labelManeira">>> RANKINGS</p>
				<a href="/ranking" class="aDoLado">ver lista completa >></a>
			</div>
			<div class="separador" style="margin-bottom: 8px; border-color:#D2EDFF"></div>
			<?php
			$usuarios = [];

			$pages = coisos_tudo($usuarios, 'usuarios', 1, '', '', 10, 'mitadas DESC');
			?>
			<p style="color: green; text-align: center; font-weight: bold; margin: 2px;">>> MITADALIST <<</p>
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
					<span class="infoExtra">com <?= $usuario->mitadas ?> mitadas</span>
				</div>
				<?php 
				$lugar += 1;
				} ?>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>