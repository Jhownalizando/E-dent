<?php
include_once('check_session.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="História Médica">
  <meta name="keyword" content="Web System, Odontologic System, Dentist">
  <title>História Médica</title>
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
          if (empty($_POST['queixa_principal']) || empty($_POST['historia_doenca_atual']) || empty($_POST['historia_progressa'])
            || empty($_POST['historia_familiar']) || empty($_POST['historia_pessoal_social']) || empty($_POST['observacao'])) {
            ?>
              <script>
                alert('Todos os campos são obrigatorios!');
              </script>
            <?php
            header('Refresh: 0; prontuario_historia_medica.php');
            return;
          } else {
            $id = $_POST['idProntuarioHistoriaMedica'];
            $data = [
              'queixa_principal' => $_POST['queixa_principal'],
              'historia_doenca_atual' => $_POST['historia_doenca_atual'],
              'historia_progressa' => $_POST['historia_progressa'],
              'historia_familiar' => $_POST['historia_familiar'],
              'historia_pessoal_social' => $_POST['historia_pessoal_social'],
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
                        prontuario_historia_medica
                      SET
                        {$update_fields}
                      WHERE
                        idProntuarioHistoriaMedica = {$id}";

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
            header('Refresh: 0; prontuario_historia_medica.php');
            return;
          }
        }

        if (empty($_GET['id'])) {
          header('Location: prontuario_historia_medica.php');
          return;
        }

        $id = intval($_GET['id']);
        if (empty($id)) {
          header('Refresh: 0; prontuario_historia_medica.php');
          return;
        }

        $query = "SELECT * FROM prontuario_historia_medica WHERE idProntuarioHistoriaMedica = {$id}";

        $result = mysqli_query($conn, $query);

        if ($result) {
          while ($data = mysqli_fetch_array($result)) {
            $idProntuarioHistoriaMedica = $data['idProntuarioHistoriaMedica'];
            $queixa_principal = $data['queixa_principal'];
            $historia_doenca_atual = $data['historia_doenca_atual'];
            $historia_progressa = $data['historia_progressa'];
            $historia_familiar = $data['historia_familiar'];
            $historia_pessoal_social = $data['historia_pessoal_social'];
            $observacao = $data['observacao'];
          }
        }
      ?>

      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                HISTORIA MÉDICA
              </header>
              <div class="panel-body">
                <div class="form">
                  <form class="form-validate form-horizontal" id="register_form" method="POST" action="editar_prontuario_historia_medica.php">
                    <input type="hidden" name="idProntuarioHistoriaMedica" value="<?= $idProntuarioHistoriaMedica; ?>">
                    <div class="form-group">
                      <label for="queixa_principal" class="control-label col-lg-2">Queixa Principal <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <textarea class="form-control" name="queixa_principal" style="width:100%; height:80px; resize: vertical;" required="required"><?= $queixa_principal; ?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="historia_doenca_atual" class="control-label col-lg-2">Historia da doença atual <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <textarea class="form-control" name="historia_doenca_atual" style="width:100%; height:80px; resize: vertical;" required="required"><?= $historia_doenca_atual; ?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="historia_progressa" class="control-label col-lg-2">Historia Progresssa <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <textarea class="form-control" name="historia_progressa" style="width:100%; height:80px; resize: vertical;" required="required"><?= $historia_progressa; ?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="historia_familiar" class="control-label col-lg-2">Historia Familiar <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <textarea class="form-control" name="historia_familiar" style="width:100%; height:100px; resize: vertical;" required="required"><?= $historia_familiar; ?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="historia_pessoal_social" class="control-label col-lg-2">Historia Pessoal e Social <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <textarea class="form-control" name="historia_pessoal_social" style="width:100%; height:100px; resize: vertical;" required="required"><?= $historia_pessoal_social; ?></textarea>
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
