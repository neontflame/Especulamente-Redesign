<?php http_response_code(403); ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<script>
	// shoutout pro Pointy no stackoverflow
	function pad(n, width, z) {
		z = z || '0';
		n = n + '';
		return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
	}

	var dia = 1;
	var hora = 0;
	var minuto = 0;
	var segundo = 30;

	var x = setInterval(function() {
		segundo -= 1;

		if (segundo == -1) {
			minuto -= 1;
			segundo = 59;
		}

		if (minuto == -1) {
			hora -= 1;
			minuto = 59;
		}

		if (hora == -1) {
			dia -= 1;
			hora = 23;
		}

		document.getElementById("HORA").innerHTML = "VOCÊ TEM " + dia + ":" + pad(hora, 2, 0) + ":" + pad(minuto, 2, 0) + ":" + pad(segundo, 2, 0) + " PARA"

		if (dia == 0 && segundo == 0 && minuto == 0 && hora == 0) {
			clearInterval(x);
			window.location.href = "https://youtu.be/TieBU5Dpo6I?t=14";
		}
	}, 1000);
</script>
<div class="container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

	<div class="page_content" style="height: 556px">
		<div class="inside_page_content">
			<div class="erro">
				<h1>403</h1>
				<img src="/elementos/erros/proibido.png">
				<br>
				<img src="/elementos/erros/jackblack.png" style="margin-top: 0px;">
				<p>Este local deve ser acessado apenas por</p>
				<p>VERDADEIROS especula-heads, portanto...</p>
				<h2 style="font-style: italic; margin-top: 0px; margin-bottom: 8px; color: #680000;">Vaze.</h2>

				<p style="color:red; font-size:16px;" id="HORA">VOCÊ TEM 1:00:00:30 PARA</p>
				<p style="color:red; font-size:16px;">SAIR DESSA PÁGINA</p>
			</div>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>