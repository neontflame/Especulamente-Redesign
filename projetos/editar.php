<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>
<?php
$erro = [];
$id = $_GET['id'] ?? null;
$projeto = projeto_requestIDator($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
  if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];

    if ($tipo == 'dl') {
      $nome = $_POST['nome'];
      $descricao = $_POST['descricao'];
      $arquivos = $_FILES['arquivos'];

      if (strlen($nome) < 3) {
        array_push($erro, "O nome do projeto é muito curto.");
      }

      $projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $arquivos);
      if (is_string($projeto)) {
        array_push($erro, $projeto);
      }

      if (count($erro) == 0) {
        header('Location: /projetos/ver.php?id=' . $projeto->id);
      }
    }
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

  <div class="page_content" style="min-height: 486px">
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
        <a href="/projetos/criar.php"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
        <h1 style="text-align: center; font-style: italic;">Editando projeto</h1>

        <form action="/projetos/editar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="tipo" value="<?= $projeto->tipo ?>">
          <input type="hidden" name="id" value="<?= $projeto->id ?>">

          <label for="nome">nome</label>
          <input type="text" style="width: 97%" id="nome" name="nome" value="<?= $projeto->nome ?>" required>
          <br>

          <label for="descricao">descrição</label>
          <textarea style="width: 97%" name="descricao" id="descricao"><?= $projeto->descricao ?></textarea>
          <br>

          <label>arquivos</label>
          <div id="multiFileUploader" style="margin-bottom: 10px;">
            <ul class="files">
              <?php foreach (explode('\n', $projeto->arquivos) as $i => $arquivo) : ?>
                <li>
                  <p style="width: 253px; margin: 0"><?= $arquivo ?></p>
                  <button type="button" class="coolButt vermelho" onclick="
                    if (this.parentElement.parentElement.children.length > 1) {
                      marcarParaRemoção(this.parentElement);
                    }
                  ">Remover</button>
                  <button type="button" class="coolButt" onclick="
                    var prev = this.parentElement.previousElementSibling;
                    if (prev) {
                      prev.before(this.parentElement);
                    }
                  ">^</button>
                  <button type="button" class="coolButt" onclick="
                    var next = this.parentElement.nextElementSibling;
                    if (next) {
                      next.after(this.parentElement);
                    }
                  ">v</button>
                </li>
              <?php endforeach ?>
            </ul>
            <button class="coolButt grandissimo" type="button" onclick="addMais1()">+ Adicionar mais um</button>
          </div>

          <button type="submit" class="coolButt verde grandissimo">Criar</button>
        </form>
        <form action="/projetos/deletar.php" method="post">
          <input type="hidden" name="id" value="<?= $projeto->id ?>">
          <button type="submit" class="coolButt vermelho grandissimo">DELETAR PROJETO ???!?</button>
        </form>

        <div id="fileTemplate" style="display: none;">
          <li>
            <input type="file" name="arquivos[]" id="arquivos" required>
            <button type="button" class="coolButt vermelho" onclick="
              if (this.parentElement.parentElement.children.length > 1) {
                if (!confirm('Tem certeza que deseja remover este arquivo?')) return;
                this.parentElement.remove()
              }
            ">Remover</button>
            <button type="button" class="coolButt" onclick="
              var prev = this.parentElement.previousElementSibling;
              if (prev) {
                prev.before(this.parentElement);
              }
            ">^</button>
            <button type="button" class="coolButt" onclick="
              var next = this.parentElement.nextElementSibling;
              if (next) {
                next.after(this.parentElement);
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
    remover.innerText = 'Adicionar';
    remover.onclick = function() {
      marcarParaDesremoção(elemento);
    }
  }

  function marcarParaDesremoção(elemento) {
    var input = document.querySelector('input[value="' + elemento.children[0].innerText + '"]');
    input.remove();

    elemento.className = '';

    var remover = elemento.getElementsByTagName('button')[0];
    remover.innerText = 'Remover';
    remover.onclick = function() {
      marcarParaRemoção(elemento);
    }
  }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>