<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';

$id = $_GET['id'] ?? null;
$projeto = projeto_requestIDator($id);

if ($projeto->arquivos_de_vdd != '') : ?>
	<?php foreach (explode('\n', $projeto->arquivos_de_vdd) as $i => $arquivo) : ?>
		<li data-filename="<?= $arquivo ?>">
			<p style="width: 253px; margin: 0; display: inline-block"><?= $arquivo ?></p>
			<button type="button" class="coolButt vermelho" onclick="
		<?php if ($projeto->tipo != 'jg' && $projeto->tipo != 'bg') : ?>
		if (this.parentElement.parentElement.children.length > 1) {
		<?php endif; ?>
			marcarParaRemoção(this.parentElement);
			recalcularOrdem()
		<?php if ($projeto->tipo != 'jg' && $projeto->tipo != 'bg') : ?>
		}
		<?php endif; ?>
		// eu me recuso
	">Remover</button>
			<button type="button" class="coolButt" onclick="
		var prev = this.parentElement.previousElementSibling;
		if (prev) {
			prev.before(this.parentElement);
			recalcularOrdem();
		}
	">^</button>
			<button type="button" class="coolButt" onclick="
		var next = this.parentElement.nextElementSibling;
		if (next) {
			next.after(this.parentElement);
			recalcularOrdem();
		}
	">v</button>
		</li>
	<?php endforeach ?>
<?php endif ?>