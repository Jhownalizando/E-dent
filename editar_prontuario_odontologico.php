<?php
include_once('check_session.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Prontuário Odontológico">
  <meta name="keyword" content="Web System, Odontologic System, Dentist">
  <title>Prontuário Odontológico</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet"/>
  <link rel="icon" type="image/png" href="images/icons/iconEdent.png"/>
</head>

<body>
  <section id="container">
    <header class="header" style="background-color: #009788; border-bottom: #fff 1px solid;">
      <div class="toggle-nav" style="margin-top: 15px;">
        <div class="icon-reorder tooltips" data-original-title="Menu lateral" data-placement="bottom">
        <i class="fas fa-bars" style="color: #fff;"></i>
      </div>
      </div>
      <a class="navbar-brand" href="login.php">
        <img src="images/icons/E-DENT-3.png" class="nav-item" alt="logo" style="width: 90px">
      </a>
    </header>

    <?php
      include('aside.php');
    ?>

    <section id="main-content">
      <?php
        include_once('connection.php');

        if (!empty($_POST)) {
          if (empty($_POST['dificuldade_engolir_alimentos']) || empty($_POST['protese_dentadura']) || empty($_POST['quanto_tempo_perdeu_dentes'])
            || empty($_POST['adaptado_protese']) || empty($_POST['dentes_sensiveis']) || empty($_POST['gengiva_sangra'])
            || empty($_POST['mau_halito']) || empty($_POST['toma_cafe_refrigerante']) || empty($_POST['observacao'])) {
            ?>
              <script>
                alert('Todos os campos são obrigatorios!');
              </script>
            <?php
            header('Refresh: 0; prontuario_odontologico.php');
            return;
          } else {
            $id = $_POST['idProntuarioOdontologico'];
            $data = [
              'dificuldade_engolir_alimentos' => $_POST['dificuldade_engolir_alimentos'],
              'protese_dentadura' => $_POST['protese_dentadura'],
              'quanto_tempo_perdeu_dentes' => $_POST['quanto_tempo_perdeu_dentes'],
              'adaptado_protese' => $_POST['adaptado_protese'],
              'dentes_sensiveis' => $_POST['dentes_sensiveis'],
              'gengiva_sangra' => $_POST['gengiva_sangra'],
              'mau_halito' => $_POST['mau_halito'],
              'toma_cafe_refrigerante' => $_POST['toma_cafe_refrigerante'],
              'observacao' => trim(htmlspecialchars(filter_var($_POST['observacao'], FILTER_SANITIZE_STRING))),
            ];

            $update_fields = [];

            $update_fields = array_map(function($key, $value) {
              $value = trim(htmlspecialchars(filter_var($value, FILTER_SANITIZE_STRING)));
              if (preg_match("/[^0-9]+/", $value)) {
                $value="'{$value}'";
              }
              return "{$key} = {$value}";
            }, array_keys($data), $data);

            $update_fields = implode(', ', $update_fields);

            $query = "UPDATE
                        prontuario_odontologico
                      SET
                        {$update_fields}
                      WHERE
                        idProntuarioOdontologico = {$id}";

            $result = mysqli_query($conn, $query);

            if ($result) {
              ?>
                <script>
                  alert('Cadastro alterado com sucesso no sistema!');
                </script>
              <?php
            } else {
              ?>
                <script>
                  alert('Erro ao alterar o cadastro do paciente!');
                </script>
              <?php
            }
            header('Refresh: 0; prontuario_odontologico.php');
            return;
          }
        }

        if (empty($_GET['id'])) {
          header('Location: prontuario_odontologico.php');
          return;
        }

        $id = intval($_GET['id']);
        if (empty($id)) {
          header('Refresh: 0; prontuario_odontologico.php');
          return;
        }

        $query = "SELECT * FROM prontuario_odontologico WHERE idProntuarioOdontologico = {$id}";

        $result = mysqli_query($conn, $query);

        if ($result) {
          while ($data = mysqli_fetch_array($result)) {
            $idProntuarioOdontologico = $data['idProntuarioOdontologico'];
            $dificuldade_engolir_alimentos = $data['dificuldade_engolir_alimentos'];
            $protese_dentadura = $data['protese_dentadura'];
            $quanto_tempo_perdeu_dentes = $data['quanto_tempo_perdeu_dentes'];
            $adaptado_protese = $data['adaptado_protese'];
            $dentes_sensiveis = $data['dentes_sensiveis'];
            $gengiva_sangra = $data['gengiva_sangra'];
            $mau_halito = $data['mau_halito'];
            $toma_cafe_refrigerante = $data['toma_cafe_refrigerante'];
            $observacao = $data['observacao'];
          }
        }
      ?>
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                PRONTUÁRIO ODONTOLÓGICO
              </header>
              <div class="panel-body">
                <div class="form">
                  <form class="form-validate form-horizontal" id="register_form" method="POST" action="editar_prontuario_odontologico.php">
                    <input type="hidden" name="idProntuarioOdontologico" value="<?= $idProntuarioOdontologico; ?>">
                    <div class="form-group">
                      <label for="dificuldade_engolir_alimentos" class="control-label col-lg-2">Dificuldade em engolir alimentos?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          if ($dificuldade_engolir_alimentos === 'sim') {
                            ?>
                            <input type="radio" name="dificuldade_engolir_alimentos" value="sim" checked> Sim <br>
                            <input type="radio" name="dificuldade_engolir_alimentos" value="nao"> Não <br>
                            <?php
                          } else {
                            ?>
                            <input type="radio" name="dificuldade_engolir_alimentos" value="sim"> Sim <br>
                            <input type="radio" name="dificuldade_engolir_alimentos" value="nao" checked> Não <br>
                            <?php
                          }
                        ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="protese_dentadura" class="control-label col-lg-2">Prótese de dentadura?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          if ($protese_dentadura === 'sim') {
                            ?>
                            <input type="radio" name="protese_dentadura" value="sim" checked> Sim <br>
                            <input type="radio" name="protese_dentadura" value="nao"> Não <br>
                            <?php
                          } else {
                            ?>
                            <input type="radio" name="protese_dentadura" value="sim"> Sim <br>
                            <input type="radio" name="protese_dentadura" value="nao" checked> Não <br>
                            <?php
                          }
                        ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="quanto_tempo_perdeu_dentes" class="control-label col-lg-2">Com quanto tempo trocou todos os dentes de leite?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input class="form-control" name="quanto_tempo_perdeu_dentes" value="<?= $quanto_tempo_perdeu_dentes ; ?>" placeholder="Quantos anos você tinha quando seu último dente de leite caiu?" type="text" required="required"/>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="adaptado_protese" class="control-label col-lg-2">Prótese Dentária?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          if ($adaptado_protese === 'sim') {
                            ?>
                            <input type="radio" name="adaptado_protese" value="sim" checked> Sim <br>
                            <input type="radio" name="adaptado_protese" value="nao"> Não <br>
                            <?php
                          } else {
                            ?>
                            <input type="radio" name="adaptado_protese" value="sim"> Sim <br>
                            <input type="radio" name="adaptado_protese" value="nao" checked> Não <br>
                            <?php
                          }
                        ?>
                    </div>
                    </div>
                    <div class="form-group">
                      <label for="dentes_sensiveis" class="control-label col-lg-2">Dentes são sensíveis?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          if ($dentes_sensiveis === 'sim') {
                            ?>
                            <input type="radio" name="dentes_sensiveis" value="sim" checked> Sim <br>
                            <input type="radio" name="dentes_sensiveis" value="nao"> Não <br>
                            <?php
                          } else {
                            ?>
                            <input type="radio" name="dentes_sensiveis" value="sim"> Sim <br>
                            <input type="radio" name="dentes_sensiveis" value="nao" checked> Não <br>
                            <?php
                          }
                        ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="gengiva_sangra" class="control-label col-lg-2">Genviva costuma sangrar?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          if ($gengiva_sangra === 'sim') {
                            ?>
                            <input type="radio" name="gengiva_sangra" value="sim" checked> Sim <br>
                            <input type="radio" name="gengiva_sangra" value="nao"> Não <br>
                            <?php
                          } else {
                            ?>
                            <input type="radio" name="gengiva_sangra" value="sim"> Sim <br>
                            <input type="radio" name="gengiva_sangra" value="nao" checked> Não <br>
                            <?php
                          }
                        ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="mau_halito" class="control-label col-lg-2">Mau hálito?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          if ($mau_halito === 'sim') {
                            ?>
                            <input type="radio" name="mau_halito" value="sim" checked> Sim <br>
                            <input type="radio" name="mau_halito" value="nao"> Não <br>
                            <?php
                          } else {
                            ?>
                            <input type="radio" name="mau_halito" value="sim"> Sim <br>
                            <input type="radio" name="mau_halito" value="nao" checked> Não <br>
                            <?php
                          }
                        ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="toma_cafe_refrigerante" class="control-label col-lg-2">Costuma tomar café ou refigerante?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          if ($toma_cafe_refrigerante === 'sim') {
                            ?>
                            <input type="radio" name="toma_cafe_refrigerante" value="sim" checked> Sim <br>
                            <input type="radio" name="toma_cafe_refrigerante" value="nao"> Não <br>
                            <?php
                          } else {
                            ?>
                            <input type="radio" name="toma_cafe_refrigerante" value="sim"> Sim <br>
                            <input type="radio" name="toma_cafe_refrigerante" value="nao" checked> Não <br>
                            <?php
                          }
                        ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="observacao" class="control-label col-lg-2">Observações <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <textarea class="form-control" name="observacao" style="width:100%; height:100px; resize: vertical;" required="required" placeholder="Se não tiver observações escreva que não possui."><?= $observacao; ?></textarea>
                      </div>
                    </div>
                    <center>
                      <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                          <button class="btn btn-primary" type="submit">Salvar</button>
                          <button class="btn btn-default" type="button">Cancelar</button>
                        </div>
                      </div>
                    </center>
                  </form>
                </div>
              </div>
            </section>
          </div>
        </div>
      </section>
    </section>
  </section>

  <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
  <script src="js/jquery-ui-1.10.4.min.js"></script>
  <script src="js/jquery-1.8.3.min.js"></script>
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="js/jquery.customSelect.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
  <script src="js/scripts.js"></script>
</body>

</html>
