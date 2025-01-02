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

    <div class="inside_page_content" style="padding-right: 0px;">
      <?php if ($tipo == null) : ?>
		<img src="/elementos/pagetitles/projeto-criator.png" style="margin-top: -5px; margin-left: -5px;">
        <h1 style="text-align: center; font-style: italic; font-weight: normal;">O que você quer criar hoje...?</h1>

        <!-- Coiso Downloadável -->
        <a href="/projetos/criar.php?tipo=dl" style="margin-left: -5px; margin-right:-4px;">
		<img src="/elementos/projetos/dl.png" onmouseover="this.src='/elementos/projetos/dl-hover.png';" onmouseout="this.src='/elementos/projetos/dl.png';">
		</a>
		
		<a href="/projetos/criar.php?tipo=jg" style="margin-right:-4px;">
		<img src="/elementos/projetos/jogo.png" onmouseover="this.src='/elementos/projetos/jogo-hover.png';" onmouseout="this.src='/elementos/projetos/jogo.png';">
		</a>
		<a href="/projetos/criar.php?tipo=md" style="margin-right:-4px;">
		<img src="/elementos/projetos/midia.png" onmouseover="this.src='/elementos/projetos/midia-hover.png';" onmouseout="this.src='/elementos/projetos/midia.png';">
		</a>
		<a href="/projetos/criar.php?tipo=bg" style="margin-right:-4px;">
		<img src="/elementos/projetos/blog.png" onmouseover="this.src='/elementos/projetos/blog-hover.png';" onmouseout="this.src='/elementos/projetos/blog.png';">
		</a>
		<a href="/projetos/criar.php?tipo=rt">
		<img src="/elementos/projetos/resto.png" onmouseover="this.src='/elementos/projetos/resto-hover.png';" onmouseout="this.src='/elementos/projetos/resto.png';">
		</a>
      <?php endif; ?>

      <?php if ($tipo == 'jg' || $tipo == 'md' || $tipo == 'bg' || $tipo == 'rt') : ?>
	  
      <h1 style="text-align: center; font-style: italic; font-weight: normal;">isso ainda nao existe :(</h1>
      <?php endif; ?>
	  
      <?php if ($tipo == 'dl') : ?>
        <!-- Downloadável -->
        <a href="/projetos/criar.php"><img style="margin-left: -5px; margin-top: -5px;" src="/elementos/voltar.png"></a>
        <h1 style="text-align: center; font-style: italic;">Downloadável!</h1>
        <p><i>Esse tipo de projeto oferece arquivos para descarga. Os usuários podem transferir as suas coisas para seus discos rígidos.</i></p>

        <form action="/projetos/criar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="tipo" value="dl">

          <label for="nome">nome do downloadável</label>
          <input type="text" style="width: 97%" id="nome" name="nome" required>
          <br>

          <label for="descricao">descrição do seu downloadável</label>
          <textarea style="width: 97%" name="descricao" id="descricao"></textarea>
          <br>

          <label for="arquivos">arquivos de seu downloadável</label>
          <div id="multiFileUploader">
            <ul class="files">

            </ul>
            <button class="coolButt grandissimo" type="button" onclick="addMais1()">+ Adicionar mais um</button>
          </div>

          <button type="submit" class="coolButt verde grandissimo" >Criar</button>
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