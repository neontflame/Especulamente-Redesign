<?php include $_SERVER['DOCUMENT_ROOT'] . '/shhhh/autoload.php'; ?>
<?php
if (!isset($_GET['username'])) {
  erro_404();
}
$username = $_GET['username'];

$user = usuario_requestinator($username);

if (!$user) {
  erro_404();
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/header/header.php'; ?>

<div class="container">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/sidebar/sidebar.php'; ?>

  <div class="page_content" style="height: 486px">
    <div class="inside_page_content">
      <h1><?= $user->username ?></h1>

    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/elementos/footer/footer.php'; ?>