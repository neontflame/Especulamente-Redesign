<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
// TODO: trocar isso pelos ids de usuarios
$creditados = [
	['Fupicat', 'codigo, desenhos, banners, puxar cordas'],
	['neontflame', 'html css e codigo, desenhos, banners, puxar cordas'],
	['Sushi', 'banners e chars'],
	['Sketcher', 'banner e char'],
	['Hawnt', 'banner e char'],
	['henry.guy', 'banners e anuncios'],
	['DexDousky', 'banners'],
	['brenoborb', 'banner'],
	['dafver', 'o cara do dinheiro'],
];
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content">
		<img src="/elementos/pagetitles/creditos.png" class="inside_page_content" style="padding: 0px; margin-bottom: 7px;">

		<div class="inside_page_content" style="background-image: url(/elementos/chillmaia.png); background-position: right bottom; background-repeat: no-repeat;">
			<div class="creditos">
				<?php foreach ($creditados as $credado) : ?>
					<div class="credito">
						<a href="/usuarios/<?= usuario_requestinator($credado[0])->username ?? "NADA" ?>"><img src="<?= pfp(usuario_requestinator($credado[0]) ?? "NADA") ?>" width="64" height="64"></a>
						<a href="/usuarios/<?= usuario_requestinator($credado[0])->username ?? "NADA" ?>">
							<h1><?= usuario_requestinator($credado[0])->username ?? "NADA" ?></h1>
						</a>
						<p><?= $credado[1] ?></p>
					</div>
				<?php endforeach ?>
			</div>
			<br>
			<p style="text-align: center;">uma salva de palmas aos individuos envolvidos!!!</p>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>