<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
$forum = true;
$getDeCategoria = 'categoria';

$id = $_GET['id'] ?? 1;
$ofix = forumpost_requestIDator($id);
$categoria = $_GET['categoria'] ?? $ofix->id_categoria;

if ($ofix->id_resposta != -1) {
	redirect('/foruns/' . $ofix->id_categoria . '/' . $ofix->id_resposta . '#post_' . $id);
}

if ($categoria != $ofix->id_categoria) {
	redirect('/foruns/' . $ofix->id_categoria . '/' . $id);
}

$meta["titulo"] = "[" . forumpost_requestIDator($id)->sujeito . " <> " . categoria_requestIDator(forumpost_requestIDator($id)->id_categoria)->nome . " <> Fóruns do PORTAL ESPECULAMENTE]";
$meta["descricao"] = str_replace("\n", " ", markdown_apenas_texto(forumpost_requestIDator($id)->conteudo));

include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php';
?>
<script>
	// comentario negocios
	function postarPost(ocomentario, that, respondido, categoria, sujeito) {
		var osNegocios = new FormData();

		var bototes = that.parentElement.getElementsByTagName("button");

		for (var i = 0; i < bototes.length; i++) {
			bototes[i].disabled = true;
		}

		osNegocios.append("comentario", ocomentario);
		osNegocios.append("categoria", categoria);
		osNegocios.append("sujeito", sujeito);
		osNegocios.append("respondido", respondido);

		var xhttp = new XMLHttpRequest();
		xhttp.open(
			"POST",
			"/foruns/postarPost.php",
			true
		);

		xhttp.onload = function() {
			for (var i = 0; i < bototes.length; i++) {
				bototes[i].disabled = false;
			}

			window.location.reload();
		};

		xhttp.send(osNegocios);
	}

	function citarPost(user, data, cont) {
		// var coiso = document.getElementById(cont).children[0].children[0].children[0].children[1].children[1].children[1];
		// CODIGO ASSOMBRADO BEGONE
		var coiso = document.getElementById(cont);
		document.getElementById('post_fnf').value += "> De @" + user + ", dia " + data;

		for (var i = 0; i < coiso.childElementCount; i++) {
			console.log(coiso.children[i].tagName);
			switch (coiso.children[i].tagName) {
				case 'BLOCKQUOTE':
					console.log(coiso.children[i].tagName + ': ' + coiso.children[i].textContent);
					break;
				case 'P':
					var coiso2 = coiso.children[i];
					for (var f = 0; f < coiso2.childElementCount; f++) {
						switch (coiso2.children[f].tagName) {
							case 'IMG':
								document.getElementById('post_fnf').value += "\n> > " + '![](' + coiso2.children[f].src + ')';
						}
					}

				default:
					if (coiso.children[i].innerText != '') {
						document.getElementById('post_fnf').value += "\n> > " + coiso.children[i].innerText;
					}
			}
		}
	}

	function deletarPost(id, that) {
		if (confirm("Tem certeza que quer deletar esse post??")) {
			var bototes = that.parentElement.getElementsByTagName("button");
			for (var i = 0; i < bototes.length; i++) {
				bototes[i].disabled = true;
			}

			var osNegocios = new FormData();
			osNegocios.append("id", id);

			var xhttp = new XMLHttpRequest();
			xhttp.open(
				"POST",
				"/foruns/deletarPost.php",
				true
			);

			xhttp.onload = function() {
				window.location.reload();
			};

			xhttp.send(osNegocios);
		}
	}

	function editarPost(id, that) {
		var osNegocios = new FormData();

		var bototes = that.parentElement.getElementsByTagName("button");

		for (var i = 0; i < bototes.length; i++) {
			bototes[i].disabled = true;
		}

		osNegocios.append("id", id);
		osNegocios.append("comentario", document.getElementById('edit_fnf_' + id).value);
		osNegocios.append("sujeito", document.getElementById('sujeito_fnf_' + id).value);

		var xhttp = new XMLHttpRequest();
		xhttp.open(
			"POST",
			"/foruns/editarPost.php",
			true
		);

		xhttp.onload = function() {
			for (var i = 0; i < bototes.length; i++) {
				bototes[i].disabled = false;
			}

			window.location.reload();
		};

		xhttp.send(osNegocios);
	}

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
					document.getElementById('post_fnf').value += '![](' + xhttp.responseText + ')';
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
	<style>
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
			max-width: 100%;
		}

		.oReajorEmSi {
			min-width: 102px;
		}

		.fucInfo {
			font-size: 10px;
		}

		.fucInfo b {
			font-size: 12px;
		}
	</style>
	<div>
		<div class="projTitulo">
			<p><a href="/foruns">Fóruns</a> >> <a href="/foruns/<?= $categoria ?>"><?= categoria_requestIDator($categoria)->nome ?></a> >> <i style="color: #4f6bad"><?= forumpost_requestIDator($id)->sujeito ?></i></p>
		</div>

		<?php
		$posts = [];

		$rows = $db->prepare("SELECT * FROM forum_posts WHERE id = ? OR id_resposta = ? ORDER BY data ASC");
		$rows->bindParam(1, $id, PDO::PARAM_INT);
		$rows->bindParam(2, $id, PDO::PARAM_INT);
		$rows->execute();

		while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
			array_push($posts, $row);
		}

		foreach ($posts as $post) : ?>
			<div class="inside_page_content" style="margin-bottom: 8px;" id="post_<?= $post->id ?>">
				<table style="margin: -6px;">
					<tr>
						<td class="forumUserCoiso">
							<a href="/usuarios/<?= usuario_requestIDator($post->id_postador)->username ?>"><img src="<?= pfp(usuario_requestIDator($post->id_postador)) ?>" width="64" height="64"></a>
							<a class="forumUser" href="/usuarios/<?= usuario_requestIDator($post->id_postador)->username ?>"><?= usuario_requestIDator($post->id_postador)->username ?></a>
							<div class="fucInfo">
								<b><?= $rank = obter_rank(usuario_requestIDator($post->id_postador)->davecoins)["nome"]; ?></b>
								<br><?= quantReacoes($post->id_postador, 'mitada') ?> mitadas
								<br><?= quantReacoes($post->id_postador, 'sojada') ?> sojadas
								<br><?= quantPosts($post->id_postador) ?> posts
							</div>
						</td>
						<td style="min-width: 500px; max-width: 500px; overflow-x: auto; background-color: white; vertical-align: top;">
							<div class="projTitulo postTitulo" style="width: 100%;">
								<span id="sujeito_<?= $post->id ?>"><?= $post->sujeito ?></span>
								<?php if (isset($usuario) && $usuario->id == $post->id_postador) : ?>
									<input type="text" style="width: 99%; display: none;" id="sujeito_fnf_<?= $post->id ?>" name="sujeito_fnf_<?= $post->id ?>" value="<?= htmlspecialchars($post->sujeito) ?>">
								<?php endif; ?>
								<br>
								<?php if (isset($usuario)) : ?>
									<div style="text-align: right;">
										<button onclick='citarPost("<?= usuario_requestIDator($post->id_postador)->username ?>", "<?= velhificar_data($post->data) ?>", "postissimo_<?= $post->id ?>")' class="coolButt" style="height: 18px;">Citar</button>

										<?php if ($usuario->id == $post->id_postador) : ?>
											<button onclick='deletarPost(<?= $post->id ?>, this)' class="coolButt vermelho" style="height: 18px;">Deletar</button>
											<button onclick='
								document.getElementById("edit_<?= $post->id ?>").style.display = "";
								document.getElementById("sujeito_<?= $post->id ?>").style.display = "none";
								document.getElementById("sujeito_fnf_<?= $post->id ?>").style.display = "";
								document.getElementById("postissimo_<?= $post->id ?>").style.display = "none";
								' class="coolButt verde" style="height: 18px; margin-right: 6px;">Editar</button>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="oPostEmSi">
								<?php if (isset($usuario) && $usuario->id == $post->id_postador) { ?>
									<div class="sayYourPrayers" id="edit_<?= $post->id ?>" style="display: none;">
										<textarea name="edit_fnf_<?= $post->id ?>" id="edit_fnf_<?= $post->id ?>" style="width: 486px; max-width: 486px; resize: vertical; height: 150px;"><?= $post->conteudo ?></textarea>
										<script>
											var contInicial<?= $post->id ?> = document.getElementById('edit_fnf_<?= $post->id ?>').value;
											var sujInicial<?= $post->id ?> = document.getElementById('sujeito_fnf_<?= $post->id ?>').value;
										</script>
										<br>
										<button type="submit" onclick="editarPost(<?= $post->id ?>, this);" class="coolButt">
											Editar comentário
										</button>

										<button class="coolButt vermelho" onclick='
								document.getElementById("edit_fnf_<?= $post->id ?>").value = contInicial<?= $post->id ?>;
								document.getElementById("edit_<?= $post->id ?>").style.display = "none";
								
								document.getElementById("sujeito_fnf_<?= $post->id ?>").value = sujInicial<?= $post->id ?>;
								document.getElementById("sujeito_fnf_<?= $post->id ?>").style.display = "none";
								
								document.getElementById("sujeito_<?= $post->id ?>").style.display = "";
								document.getElementById("postissimo_<?= $post->id ?>").style.display = "";
								'>
											Cancelar
										</button>
									</div>
								<?php } ?>

								<div id="postissimo_<?= $post->id ?>">
									<?= responde_clickers($post->conteudo) ?>
								</div>
								<?php if (usuario_requestIDator($post->id_postador)->assinatura != null && usuario_requestIDator($post->id_postador)->assinatura != '') : ?>
									<div class="separador"></div>
									<?= responde_clickers(usuario_requestIDator($post->id_postador)->assinatura) ?>
								<?php endif ?>
							</div>
						</td>
					</tr>
				</table>

				<?php reajor_d_reagida("forum", $post, $usuario, 'Postado dia ' . velhificar_data($post->data)) ?>
			</div>
		<?php endforeach ?>

		<?php if (isset($usuario)) : ?>
			<div class="inside_page_content">
				<table style="margin: -6px;">
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
								<input type="text" style="width: 73%; float: left;" id="sujeito_fnf" name="sujeito_fnf" value="Resposta a &#039;<?= htmlspecialchars(forumpost_requestIDator($id)->sujeito) ?>&#039;">
								<div style="text-align: right;">
									<input type="file" id="inputImg" accept="image/*" style="width: 0px; height: 0px; opacity: 0" onchange="anexarImg(this.files)">
									<button onclick="document.getElementById('inputImg').click()" class="coolButt" style="height: 18px; margin-right: 6px;">Anexar imagem</button>
								</div>
							</div>
							<div class="oPostEmSi">
								<div class="sayYourPrayers">
									<textarea name="post_fnf" id="post_fnf" style="width: 486px; max-width: 486px; resize: vertical; height: 150px;"></textarea>
									<br>

									<div id="imagensAnexas" style="display:none;">
										Imagens anexas:
										<div id="imagensAnexasAnexas">
										</div>
									</div>

									<button type="submit" onclick="postarPost(document.getElementById('post_fnf').value, this, <?= $id ?>, <?= $ofix->id_categoria ?>, sujeito_fnf.value);" class="coolButt">
										Enviar comentário
									</button>

									<button class="coolButt vermelho" onclick="document.getElementById('post_fnf').value = '';">
										Cancelar
									</button>
								</div>
							</div>
						</td>
					</tr>
				</table>

			</div>
		<?php endif ?>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>