<?php
  include_once('check_session.php');
  include_once('connection.php');

  $paciente = $_POST['paciente'];
  $bochecho = $_POST['bochecho'];
  $creme_dental = $_POST['creme_dental'];
  $palito = $_POST['palito'];
  $higiene_lingua = $_POST['higiene_lingua'];
  $fio_dental = $_POST['fio_dental'];
  $observacao = trim(htmlspecialchars(filter_var($_POST['observacao'], FILTER_SANITIZE_STRING)));

  $query = "INSERT INTO prontuario_higiene_oral (bochecho, creme_dental, palito, higiene_lingua, fio_dental, observacao)
  values
  ('{$bochecho}', '{$creme_dental}', '{$palito}', '{$higiene_lingua}', '{$fio_dental}', '{$observacao}')";

  $salvar_prontuario = mysqli_query($conn, $query);

  $inserted_id = mysqli_insert_id($conn);

  if (empty($inserted_id)) {
    ?>
      <script>
        alert('Houve um erro ao cadastrar!');
      </script>
    <?php
    header('Refresh: 0; prontuario_higiene_oral.php');
    return;
  }

  $query = "INSERT INTO paciente_prontuario_higiene_oral (fk_idUsuario, fk_idPaciente, fk_idProntuarioHigieneOral)
  values
  ({$usuario_id}, {$paciente}, {$inserted_id});";

  $salvar_relacao = mysqli_query($conn, $query);

  $inserted_id = mysqli_insert_id($conn);

  if (empty($inserted_id)) {
    ?>
      <script>
        alert('Houve um erro ao cadastrar!');
      </script>
    <?php
    header('Refresh: 0; prontuario_higiene_oral.php');
    return;
  }

  mysqli_close($conn);
?>

<script>
  alert('Prontuario de Higiene Oral Cadastrado!');
</script>

<?PHP
  header('Refresh: 0; prontuario_higiene_oral.php');
?>
