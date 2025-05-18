<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php 
$forum = true;

$id = $_GET['id'] ?? 1;
$ofix = forumpost_requestIDator($id);
$categoria = $_GET['categoria'] ?? $ofix->id_categoria;

if ($ofix->id_resposta != -1) {
	redirect('/foruns/' . $ofix->id_categoria . '/' . $ofix->id_resposta . '#post_' . $id);
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

	xhttp.onload = function () {
		for (var i = 0; i < bototes.length; i++) {
			bototes[i].disabled = false;
		}
	
		window.location.reload();
	};

	xhttp.send(osNegocios);
}

function citarPost(user, data, cont) {
	var coiso = document.getElementById(cont).children[0].children[0].children[0].children[1].children[1];
	
	document.getElementById('post_fnf').value += "> De @" + user + ", às " + data;
	
	for (var i = 0; i < coiso.childElementCount; i++) {
		document.getElementById('post_fnf').value += "\n>> " + coiso.children[i].innerText;
	}
}

</script>
<div class="container">
	<style>
	.forumUserCoiso {
		width: 116px;
		height: 134px;
		min-height: 134px;
		margin: -6px;
		background-color: #E1F4FF;
		text-align: center;
	}
	
	.postTitulo {
		width: 522px; 
		margin: -4px;
		margin-top: -3px;
		margin-bottom: 0px;
		border-left: 0px;
		border-right: 0px;
		padding: 4px;
		font-size: 16px;
		font-style: italic;
	}
	
	.oPostEmSi {
		margin: 4px;
	}
	
	.forumUser {
		color: #3f65cc;
		font-weight: bold;
		text-decoration: none;
		font-size: 12px;
	}
	</style>
	<div>
		<div class="projTitulo">
			<p><a href="/foruns">Fóruns</a> >> <a href="/foruns/<?= $categoria ?>"><?= categoria_requestIDator($categoria)->nome ?></a> >> <i style="color: #4f6bad"><?= forumpost_requestIDator($id)->sujeito ?></i></p>
		</div>
		
		<div class="inside_page_content" style="margin-bottom: 8px;" id="post_<?= $ofix->id ?>">
			<table style="margin: -6px;">
				<tr>
					<td class="forumUserCoiso">
						<a href="/usuarios/<?= usuario_requestIDator($ofix->id_postador)->username ?>"><img src="<?= pfp(usuario_requestIDator($ofix->id_postador))?>" width="64" height="64"></a>
						<a class="forumUser" href="/usuarios/<?= usuario_requestIDator($ofix->id_postador)->username ?>"><?= usuario_requestIDator($ofix->id_postador)->username ?></a>
						<br><?= quantReacoes($ofix->id_postador, 'mitada') ?> mitadas
						<br><?= quantReacoes($ofix->id_postador, 'sojada') ?> sojadas
					</td>
					<td style="width: 514px; background-color: white; vertical-align: top;">
						<div class="projTitulo postTitulo">
							<?= $ofix->sujeito ?>
							<p onclick='citarPost("<?= usuario_requestIDator($ofix->id_postador)->username ?>", "<?= velhificar_data($ofix->data) ?>", "post_<?= $ofix->id ?>")' style="float:right; font-size:14px; font-weight:bold; font-style:normal;">Citar</p>
						</div>
						<div class="oPostEmSi">
							<?= responde_clickers($ofix->conteudo) ?>
						</div>
					</td>
				</tr>
			</table>
			
			<?php reajor_d_reagida("forum", $ofix, $usuario, 'Postado dia ' . velhificar_data(forumpost_requestIDator($id)->data)) ?>
		</div>
		
		<?php 
		$posts = [];

		$rows = $db->prepare("SELECT * FROM forum_posts WHERE id_resposta = ? ORDER BY data ASC");
		$rows->bindParam(1, $id, PDO::PARAM_INT);
		$rows->execute();
		
		while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
			array_push($posts, $row);
		}
		
		foreach ($posts as $post) : ?>
		<div class="inside_page_content" style="margin-bottom: 8px;" id="post_<?= $post->id ?>">
			<table style="margin: -6px;">
				<tr>
					<td class="forumUserCoiso">
						<a href="/usuarios/<?= usuario_requestIDator($post->id_postador)->username ?>"><img src="<?= pfp(usuario_requestIDator($post->id_postador))?>" width="64" height="64"></a>
						<a class="forumUser" href="/usuarios/<?= usuario_requestIDator($post->id_postador)->username ?>"><?= usuario_requestIDator($post->id_postador)->username ?></a>
						<br><?= quantReacoes($post->id_postador, 'mitada') ?> mitadas
						<br><?= quantReacoes($post->id_postador, 'sojada') ?> sojadas
					</td>
					<td style="width: 514px; background-color: white; vertical-align: top;">
						<div class="projTitulo postTitulo">
							<?= $post->sujeito ?>
							<p onclick='citarPost("<?= usuario_requestIDator($post->id_postador)->username ?>", "<?= velhificar_data($post->data) ?>", "post_<?= $post->id ?>")' style="float:right; font-size:14px; font-weight:bold; font-style:normal;">Citar</p>
						</div>
						<div class="oPostEmSi">
							<?= responde_clickers($post->conteudo) ?>
						</div>
					</td>
				</tr>
			</table>
			
			<?php reajor_d_reagida("forum", $post, $usuario, 'Postado dia ' . velhificar_data(forumpost_requestIDator($id)->data)) ?>
		</div>
		<?php endforeach ?>
		
		<?php if (isset($usuario)) : ?>
		<div class="inside_page_content">
			<table style="margin: -6px;">
				<tr>
					<td class="forumUserCoiso">
						<a href="/usuarios/<?= $usuario->username ?>"><img src="<?= pfp($usuario)?>" width="64" height="64"></a>
						<a class="forumUser" href="/usuarios/<?= $usuario->username ?>"><?= $usuario->username ?></a>
						<br><?= quantReacoes($usuario->id, 'mitada') ?> mitadas
						<br><?= quantReacoes($usuario->id, 'sojada') ?> sojadas
					</td>
					<td style="width: 514px; background-color: white; vertical-align: top;">
						<div class="projTitulo postTitulo">
							Responder '<?= forumpost_requestIDator($id)->sujeito ?>'
						</div>
						<div class="oPostEmSi">
							<div class="sayYourPrayers">
							<textarea name="post_fnf" id="post_fnf" style="width: 508px; max-width: 508px; height: 150px;"></textarea>
							<br>
							<button type="submit" onclick="postarPost(document.getElementById('post_fnf').value, this, <?= $id ?>, <?= $ofix->id_categoria ?>, 'Resposta à \'<?= $ofix->sujeito ?>\'');" class="coolButt">
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