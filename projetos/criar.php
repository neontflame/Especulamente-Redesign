<?php
include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php';
login_obrigatorio($usuario);
?>
<?php
$erro = [];
$tipo = $_GET['tipo'] ?? null;

if (isset($_POST)) {
  if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];

    if ($tipo == 'dl') {
      $nome = $_POST['nome'];
      $descricao = $_POST['descricao'];
      $arquivos = $_FILES['arquivos'];

      if (strlen($nome) < 3) {
        array_push($erro, "O nome do projeto é muito curto.");
      }

      if (count($arquivos['name']) == 0) {
        array_push($erro, "Você precisa enviar pelo menos um arquivo.");
      }

      if (count($erro) == 0) {
        $projeto = criar_projeto($usuario->id, $nome, $descricao, $tipo, $arquivos['name']);

        for ($i = 0; $i < count($arquivos['name']); $i++) {
          $arquivo = $arquivos['name'][$i];
          $arquivo_tmp = $arquivos['tmp_name'][$i];
          $arquivo_size = $arquivos['size'][$i];

          if ($arquivo_size > 1024 * 1024 * 1024) {
            array_push($erro, "O arquivo $arquivo é muito grande.");
          } else {
            $arquivo_path = $_SERVER['DOCUMENT_ROOT'] . '/static/projetos/' . $projeto->id . '/' . $arquivo;
            move_uploaded_file($arquivo_tmp, $arquivo_path);
          }
        }

        if (count($erro) == 0) {
          header('Location: /projetos/ver.php?id=' . $projeto->id);
        }
      }
    }
  }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

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

    <div class="inside_page_content">
      <?php if ($tipo == null) : ?>
        <h1>O que você quer criar hoje?</h1>

        <ul>
          <li><a href="/projetos/criar.php?tipo=dl">Downloadável</a></li>
        </ul>
      <?php endif; ?>

      <?php if ($tipo == 'dl') : ?>
        <!-- Downloadável -->
        <a href="/projetos/criar.php">&lt; Voltar</a>
        <h1>Downloadável!</h1>
        <p><i>Esse tipo de projeto oferece arquivos para descarga. Os usuários podem transferir as suas coisas para seus discos rígidos.</i></p>

        <form action="/projetos/criar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="tipo" value="dl">

          <label for="nome">Nome</label>
          <input type="text" id="nome" name="nome" required>
          <br>

          <label for="descricao">Descrição</label>
          <textarea name="descricao" id="descricao"></textarea>
          <br>

          <label for="arquivos">Arquivos</label>
          <div id="multiFileUploader">
            <ul class="files">

            </ul>
            <button class="maisum" type="button" onclick="addMais1()">+ Adicionar mais um</button>
          </div>

          <button type="submit">Criar</button>
        </form>

        <div id="fileTemplate" style="display: none;">
          <li>
            <input type="file" name="arquivos[]" id="arquivos" required>
            <button type="button" onclick="
              if (this.parentElement.parentElement.children.length > 1) {
                if (!confirm('Tem certeza que deseja remover este arquivo?')) return;
                this.parentElement.remove()
              }
            ">Remover</button>
            <button type="button" onclick="
              var prev = this.parentElement.previousElementSibling;
              if (prev) {
                prev.before(this.parentElement);
              }
            ">^</button>
            <button type="button" onclick="
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

<script>
  function addMais1() {
    var template = document.getElementById('fileTemplate').getElementsByTagName('li')[0];
    var clone = template.cloneNode(true);
    clone.style.display = 'list-item';
    document.getElementById('multiFileUploader').getElementsByClassName('files')[0].appendChild(clone);
  }

  addMais1();
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>