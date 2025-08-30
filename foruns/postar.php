<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
$forum = true;
$meta["titulo"] = "[Postar nos Fóruns <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Como se já não bastassem os blogs dos ESPECULATIVOS para pensar sozinho, agora você também pode pensar em GRUPO! Pense mais e fale mais com os FÓRUNS do PORTAL ESPECULAMENTE!";

login_obrigatorio($usuario);

include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';

$erro = $_SESSION['erroPost'] ?? null;
unset($_SESSION['erroPost']);
?>
<style>
		label {
			font-weight: bold;
			display: block;
			font-size: 15px;

			margin-top: 5px;
			margin-bottom: 5px;
		}
		.forumUserCoiso {
			max-width: 114px;
			min-width: 114px;
			height: 134px;
			min-height: 134px;
			margin: -6px;
			background-color: #e1f4ff;
			text-align: center;
			vertical-align: top;
			padding-top: 6px;
		}

		.forumUserCoiso a {
			display: block;
		}

		.postTitulo {
			width: 522px;
			margin: -4px;
			margin-top: -3px;
			margin-bottom: 0px;
			border-left: 0px;
			border-right: 0px;
			padding: 4px;
			font-size: 14px;
			font-style: italic;
		}

		.forumUser {
			color: #3f65cc;
			font-weight: bold;
			text-decoration: none;
			font-size: 12px;
			margin: 4px 0px;
		}

		.oPostEmSi {
			margin: 4px;
		}

		.imagemCoiso {
			width: 48px;
			height: 48px;
			margin: 4px;
		}

		.oPostEmSi img {
			max-width: 492px;
		}
		
		.fucInfo {
			font-size: 10px;
		}
		
		.fucInfo b {
			font-size: 12px;
		}
</style>
	
<script>
	function anexarImg(imgs) {
		var osNegocios = new FormData();
		var xhttp = new XMLHttpRequest();

		osNegocios.append('image', imgs[0]);

		xhttp.open(
			"POST",
			"/foruns/subirImg.php",
			true
		);

		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == XMLHttpRequest.DONE) {
				if (xhttp.responseText.substring(0, 1) == '§') {
					alert(xhttp.responseText.substring(1, xhttp.responseText.length));
				} else {
					document.getElementById('comentario').value += '![](' + xhttp.responseText + ')';
					document.getElementById('imagensAnexas').style.display = "";

					var img = document.createElement("img");
					img.src = xhttp.responseText;
					img.className = "imagemCoiso";
					img.onclick = function() {
						document.getElementById('comentario').value += '![](' + xhttp.responseText + ')';
					};
					document.getElementById("imagensAnexasAnexas").appendChild(img);
				}
			}
		}

		xhttp.send(osNegocios);
	}
</script>

<div class="container">
	<div class="projTitulo">
		<p><a href="/foruns">Fóruns</a> >> <i style="color: #4f6bad">Postar</i></p>
	</div>
	<div>
		<div class="projTitulo">
			<h1 style="line-height: 6px; margin-top: 14px;"><i>Postar</i></h1>
			<p style="text-align: right;">Proponha algo para ser discutido juntamente de outros ESPECULATIVOS!</p>
		</div>
		
		<div class="inside_page_content">
			<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/statusbar.php'; ?>
			<form action="/foruns/postarPost.php" method="post" enctype="multipart/form-data">
				<label for="categoria" class="labelManeira">>> CATEGORIA</label>
				<select id="categoria" name="categoria" style="width: 100%;">
					<?php
					$cats = [];

					$rows = $db->prepare("SELECT * FROM forum_categorias ORDER BY id ASC");
					$rows->execute();

					while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
						array_push($cats, $row);
					}
					foreach ($cats as $cat) {
						if ($cat->nome != 'Avisos' || 
						($cat->nome == 'Avisos' && in_array(strtolower($usuario->username), $osAdminsEpicos)) // note to self: deixar isso aqui bonito
						) :
					?>
						<option <?php if (isset($_GET['cat']) && $cat->id == $_GET['cat']) : ?>selected<?php endif; ?> value="<?= $cat->id ?>"><?= $cat->nome ?></option>
					<?php endif;
					} ?>
				</select>
				
				<table style="margin-left: -6px; margin-right: -6px; margin-top: 6px; margin-bottom: 6px;">
					<tr>
						<td class="forumUserCoiso">
							<a href="/usuarios/<?= $usuario->username ?>"><img src="<?= pfp($usuario) ?>" width="64" height="64"></a>
							<a class="forumUser" href="/usuarios/<?= $usuario->username ?>"><?= $usuario->username ?></a>
							<div class="fucInfo">
								<b><?= $rank = obter_rank($usuario->davecoins)["nome"]; ?></b>
								<br><?= quantReacoes($usuario->id, 'mitada') ?> mitadas
								<br><?= quantReacoes($usuario->id, 'sojada') ?> sojadas
								<br><?= quantPosts($usuario->id) ?> posts
							</div>
						</td>
						<td style="min-width: 500px; max-width: 500px; overflow-x: auto; background-color: white; vertical-align: top;">
							<div class="projTitulo postTitulo" style="width: 100%;">
							
								<input type="text" style="width: 99%" id="sujeito" name="sujeito" required>
								
								<div style="text-align: right;">
									<input type="file" id="inputImg" accept="image/*" style="width: 0px; height: 0px; opacity: 0" onchange="anexarImg(this.files)">
									<button onclick="document.getElementById('inputImg').click()" class="coolButt" style="height: 18px; margin-right: 6px;">Anexar imagem</button>
								</div>
							</div>
							<div class="oPostEmSi">
								<div class="sayYourPrayers">
									<textarea name="comentario" id="comentario" style="width: 486px; max-width: 486px; resize: vertical; height: 150px;"></textarea>
									<br>

									<div id="imagensAnexas" style="display:none;">
										Imagens anexas:
										<div id="imagensAnexasAnexas">
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>

				<div id="imagensAnexas" style="display:none;">
					Imagens anexas:
					<div id="imagensAnexasAnexas">
					</div>
				</div>

				<button type="submit" class="coolButt verde grandissimo" style="width: 100%;">Postar</button>
			</form>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>