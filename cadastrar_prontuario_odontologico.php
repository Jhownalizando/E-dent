<?php
  include_once('check_session.php');
  include_once('connection.php');

  $paciente = $_POST['paciente'];
  $dificuldade_engolir_alimentos = $_POST['dificuldade_engolir_alimentos'];
  $protese_dentadura = $_POST['protese_dentadura'];
  $quanto_tempo_perdeu_dentes = $_POST['quanto_tempo_perdeu_dentes'];
  $adaptado_protese = $_POST['adaptado_protese'];
  $dentes_sensiveis = $_POST['dentes_sensiveis'];
  $gengiva_sangra = $_POST['gengiva_sangra'];
  $mau_halito = $_POST['mau_halito'];
  $toma_cafe_refrigerante = $_POST['toma_cafe_refrigerante'];
  $observacao = trim(htmlspecialchars(filter_var($_POST['observacao'], FILTER_SANITIZE_STRING)));

  $query = "INSERT INTO prontuario_odontologico (dificuldade_engolir_alimentos, protese_dentadura, quanto_tempo_perdeu_dentes, adaptado_protese, dentes_sensiveis, gengiva_sangra, mau_halito,toma_cafe_refrigerante, observacao)
  values
  ('{$dificuldade_engolir_alimentos}', '{$protese_dentadura}', '{$quanto_tempo_perdeu_dentes}', '{$adaptado_protese}', '{$dentes_sensiveis}', '{$gengiva_sangra}', '{$mau_halito}', '{$toma_cafe_refrigerante}', '{$observacao}')";

  $salvar_prontuario = mysqli_query($conn, $query);

  $inserted_id = mysqli_insert_id($conn);

  if (empty($inserted_id)) {
    ?>
      <script>
        alert('Houve um erro ao cadastrar!');
      </script>
    <?php
    header('Refresh: 0; prontuario_odontologico.php');
    return;
  }

  $query = "INSERT INTO paciente_prontuario_odontologico (fk_idUsuario, fk_idPaciente, fk_idProntuarioOdontologico)
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
    header('Refresh: 0; prontuario_odontologico.php');
    return;
  }

  mysqli_close($conn);
?>

<script>
  alert('Prontuario odontológico cadastrado!');
</script>

<?PHP
  header('Refresh: 0; prontuario_odontologico.php');
?>