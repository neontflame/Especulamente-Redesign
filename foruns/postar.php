<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
$forum = true;
$meta["titulo"] = "[Postar nos Fóruns <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Como se já não bastassem os blogs dos ESPECULATIVOS para pensar sozinho, agora você também pode pensar em GRUPO! Pense mais e fale mais com os FÓRUNS do PORTAL ESPECULAMENTE!";

login_obrigatorio($usuario);

include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';

$erro = $_GET['erro'] ?? null;

$erroArray = ["Comeram seu post?", "Seu sujeito é muito curto!"]
?>
<style>
	label {
		font-weight: bold;
		display: block;
		font-size: 15px;

		margin-top: 5px;
		margin-bottom: 5px;
	}

	.imagemCoiso {
		width: 48px;
		height: 48px;
		margin: 4px;
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
		<?php if (isset($erro)) : ?>
			<div class="erro" style="color: red; background: black; text-align: center;">
				<img src="/static/skull-and-cross.gif" width="24" height="24" />
				<?= $erroArray[$erro - 1] ?>
				<img src="/static/skull-and-cross.gif" width="24" height="24" />
			</div>
		<?php endif; ?>

		<div class="inside_page_content">
			Proponha algo a ser discutido!
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
						($cat->nome == 'Avisos' && ($usuario->username == 'neontflame' || $usuario->username == 'fupicat'))
						) :
					?>
						<option value="<?= $cat->id ?>"><?= $cat->nome ?></option>
					<?php endif;
					} ?>
				</select>

				<label for="sujeito" class="labelManeira">>> SUJEITO</label>
				<input type="text" style="width: 99%" id="sujeito" name="sujeito" required>
				<br>

				<label for="comentario" class="labelManeira">>> POSTAGEM</label>

				<input type="file" id="inputImg" accept="image/*" style="width: 0px; height: 0px; opacity: 0" onchange="anexarImg(this.files)">
				<button type="button" onclick="document.getElementById('inputImg').click()" class="coolButt grandissimo" style="width: 100%;">Anexar imagem</button>
				<textarea style="width: 99%; max-width: 614px;" name="comentario" id="comentario"></textarea>
				<br>

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