<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php 
$forum = true;
$meta["titulo"] = "[Postar nos Fóruns <> PORTAL ESPECULAMENTE]";
$meta["descricao"] = "Como se já não bastassem os blogs dos ESPECULATIVOS para pensar sozinho, agora você também pode pensar em GRUPO! Pense mais e fale mais com os FÓRUNS do PORTAL ESPECULAMENTE!";

include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; 
?>
<style>
	label {
		font-weight: bold;
		display: block;
		font-size: 15px;

		margin-top: 5px;
		margin-bottom: 5px;
	}
</style>

<div class="container">
	<div class="projTitulo">
		<p><a href="/foruns">Fóruns</a> >> <i style="color: #4f6bad">Postar</i></p>
	</div>
	<div>	
		<div class="inside_page_content">
			Proponha algo a ser discutido!
			<form action="/foruns/postarPost.php" method="post" enctype="multipart/form-data">
				<label for="categoria" class="labelManeira">>> CATEGORIA</label>
				<select id="categoria" name="categoria" style="width: 100%;">
					<?php
					$cats = [];

					$pages = coisos_tudo($cats, 'forum_categorias', 1, '', '', 100);
					foreach ($cats as $cat) : 
					?>
					<option value="<?= $cat->id ?>"><?= $cat->nome ?></option>
					<?php endforeach ?>
				</select>

				<label for="sujeito" class="labelManeira">>> SUJEITO</label>
				<input type="text" style="width: 99%" id="sujeito" name="sujeito" required>
				<br>

				<label for="comentario" class="labelManeira">>> POSTAGEM</label>
				<textarea style="width: 99%; max-width: 614px;" name="comentario" id="comentario"></textarea>
				<br>
				<button type="submit" class="coolButt verde grandissimo">Postar</button>
			</form>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>