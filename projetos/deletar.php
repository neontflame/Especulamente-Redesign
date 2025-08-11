<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>
<?php
$erro = [];
$id = $_GET['id'] ?? null;
$projeto = projeto_requestIDator($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
  $id = $_POST['id'];

  $projeto_rtn = deletar_projeto($usuario->id, $id);
  if (is_string($projeto)) {
    array_push($erro, $projeto);
  }

  if (count($erro) == 0) {
    header('Location: /projetos');
  }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

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
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content">
    <?php if ($erro) : ?>
      <div class="erro" style="color: red; background: black; text-align: center;">
        <img src="/static/skull-and-cross.gif" width="24" height="24" />
        <?= $erro ?>
        <img src="/static/skull-and-cross.gif" width="24" height="24" />
      </div>
    <?php endif; ?>

    <div class="inside_page_content" style="padding-right: 0px;">
      <?php if ($id == null) : ?>
        <p>wtf</p>
      <?php else : ?>
        <!-- Downloadável -->
        <a href="/projetos/<?= $id ?>"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
        <h1 style="text-align: center; font-style: italic;">Deletando projeto...???!</h1>

        <form action="/projetos/deletar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $projeto->id ?>">
          <p>Tem certeza que quer deletar o projeto <?= $projeto->nome ?>??</p>
          <p>você NUNCA mais vai conseguir acessá-lo.</p>
          <p>Isso é irreversível.</p>
          <p>Você também vai perder <b><?= ($projeto->tipo == 'jg' || $projeto->tipo == 'rt') ? 25 : 10 ?> davecoins</b> com isso.</p>
          <p>Pense bem.</p>
          <br><br><br><br>

          <img src="ilusaodeoticaoquevoceve.png" style="width: 100%" alt="">

          <br><br><br><br>
          <button class="coolButt vermelho grandissimo" style="font-size: 80px;">SIM. DELETAR.</button>
        </form>

        <div id="fileTemplate" style="display: none;">
          <li data-filename="">
            <input type="file" name="arquivos[]" id="arquivos" required onchange="this.parentElement.setAttribute('data-filename', this.files[0].name); recalcularOrdem()">
            <button type="button" class="coolButt vermelho" onclick="
              if (this.parentElement.parentElement.children.length > 1) {
                if (!confirm('Tem certeza que deseja remover este arquivo?')) return;
                this.parentElement.remove()
                recalcularOrdem()
              }
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
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
  .removido p {
    text-decoration: line-through;
    font-style: italic;
    opacity: 0.5;
  }
</style>

<script>
  function addMais1() {
    var template = document.getElementById('fileTemplate').getElementsByTagName('li')[0];
    var clone = template.cloneNode(true);
    clone.style.display = 'list-item';
    document.getElementById('multiFileUploader').getElementsByClassName('files')[0].appendChild(clone);
  }

  function marcarParaRemoção(elemento) {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'remover[]';
    input.value = elemento.children[0].innerText;
    document.getElementById('multiFileUploader').appendChild(input);

    elemento.className = 'removido';

    var remover = elemento.getElementsByTagName('button')[0];
    remover.className = 'coolButt verde';
    remover.innerText = 'Adicionar';
    remover.onclick = function() {
      marcarParaDesremoção(elemento);
      recalcularOrdem()
    }
  }

  function marcarParaDesremoção(elemento) {
    var input = document.querySelector('input[value="' + elemento.children[0].innerText + '"]');
    input.remove();

    elemento.className = '';

    var remover = elemento.getElementsByTagName('button')[0];
    remover.className = 'coolButt vermelho';
    remover.innerText = 'Remover';
    remover.onclick = function() {
      marcarParaRemoção(elemento);
      recalcularOrdem()
    }
  }

  function recalcularOrdem() {
    var ordem = []
    var files = document.getElementById('multiFileUploader').getElementsByClassName('files')[0].children;
    for (var i = 0; i < files.length; i++) {
      if (files[i].className == 'removido') continue;
      ordem.push(files[i].getAttribute('data-filename'));
    }

    document.querySelector('input[name="ordem"]').value = ordem.join('\\n');
  }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>