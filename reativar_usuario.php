<?php
  include_once('check_session.php');
  include_once('connection.php');

  $id = intval($_GET['id']);
  if (empty($id)) {
    header('Refresh: 0; lista_usuario.php');
    return;
  }

  $query = "UPDATE usuario SET deleted_at = NULL WHERE idUsuario = {$id}";

  $result = mysqli_query($conn, $query);

  if (empty($result)) {
    ?>
      <script>
        alert('Houve um erro!');
      </script>
    <?php
    header('Refresh: 0; lista_usuario.php');
    return;
  }

  ?>
    <script>
      alert('Reativado com sucesso!');
    </script>
  <?php
  header('Refresh: 0; lista_usuario.php');

  mysqli_close($conn);
?>
