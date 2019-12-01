<?php
include_once('check_session.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Prontuário Higiene Oral">
  <meta name="keyword" content="Web System, Odontologic System, Dentist">
  <title>Prontuário Higiene Oral</title>
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
          if (empty($_POST['bochecho']) || empty($_POST['creme_dental']) || empty($_POST['palito'])
            || empty($_POST['higiene_lingua']) || empty($_POST['fio_dental']) || empty($_POST['observacao'])) {
            ?>
              <script>
                alert('Todos os campos são obrigatorios!');
              </script>
            <?php
            header('Refresh: 0; prontuario_higiene_oral.php');
            return;
          } else {
            $id = $_POST['idProntuarioHigieneOral'];
            $data = [
              'bochecho' => $_POST['bochecho'],
              'creme_dental' => $_POST['creme_dental'],
              'palito' => $_POST['palito'],
              'higiene_lingua' => $_POST['higiene_lingua'],
              'fio_dental' => $_POST['fio_dental'],
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
                        prontuario_higiene_oral
                      SET
                        {$update_fields}
                      WHERE
                        idProntuarioHigieneOral = {$id}";

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
            header('Refresh: 0; prontuario_higiene_oral.php');
            return;
          }
        }

        if (empty($_GET['id'])) {
          header('Location: prontuario_higiene_oral.php');
          return;
        }

        $id = intval($_GET['id']);
        if (empty($id)) {
          header('Refresh: 0; prontuario_higiene_oral.php');
          return;
        }

        $query = "SELECT * FROM prontuario_higiene_oral WHERE idProntuarioHigieneOral = {$id}";

        $result = mysqli_query($conn, $query);

        if ($result) {
          while ($data = mysqli_fetch_array($result)) {
            $idProntuarioHigieneOral = $data['idProntuarioHigieneOral'];
            $bochecho = $data['bochecho'];
            $creme_dental = $data['creme_dental'];
            $palito = $data['palito'];
            $higiene_lingua = $data['higiene_lingua'];
            $fio_dental = $data['fio_dental'];
            $observacao = $data['observacao'];
          }
        }
      ?>
      <section class="wrapper">

        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                PRONTUÁRIO DE HIGIENE ORAL
              </header>
              <div class="panel-body">
                <div class="form">
                  <form class="form-validate form-horizontal" id="register_form" method="POST" action="editar_prontuario_higiene_oral.php">
                    <input type="hidden" name="idProntuarioHigieneOral" value="<?= $idProntuarioHigieneOral; ?>">
                    <div class="form-group">
                      <label for="bochecho" class="control-label col-lg-2">Bochecho<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          if ($bochecho === 'sim') {
                            ?>
                            <input type="radio" name="bochecho" value="sim" checked> Sim <br>
                            <input type="radio" name="bochecho" value="nao"> Não <br>
                            <?php
                          } else {
                            ?>
                            <input type="radio" name="bochecho" value="sim"> Sim <br>
                            <input type="radio" name="bochecho" value="nao" checked> Não <br>
                            <?php
                          }
                        ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="creme_dental" class="control-label col-lg-2">Creme Dental<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          $options = ['nao costuma usar', 'uma vez por semana', 'uma vez por dia', 'mais de uma vez por dia', 'duas ou mais vezes por dia'];
                          foreach ($options as $opt) {
                            if ($opt === $creme_dental) {
                              ?>
                                <input type="radio" name="creme_dental" value="<?= $opt; ?>" checked> <?= ucfirst(str_ireplace('nao', 'não', $opt)); ?> <br>
                              <?php
                            } else {
                              ?>
                                <input type="radio" name="creme_dental" value="<?= $opt; ?>"> <?= ucfirst(str_ireplace('nao', 'não', $opt)); ?> <br>
                              <?php
                            }
                          }
                        ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="palito" class="control-label col-lg-2">Palito de Dente<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          if ($palito === 'sim') {
                            ?>
                            <input type="radio" name="palito" value="sim" checked> Sim <br>
                            <input type="radio" name="palito" value="nao"> Não <br>
                            <?php
                          } else {
                            ?>
                            <input type="radio" name="palito" value="sim"> Sim <br>
                            <input type="radio" name="palito" value="nao" checked> Não <br>
                            <?php
                          }
                        ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="higiene_lingua" class="control-label col-lg-2">Higene na Língua<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          $options = ['uma vez por dia', 'mais de uma vez por dia', 'duas uma mais vezes por dia'];
                          foreach ($options as $opt) {
                            if ($opt === $higiene_lingua) {
                              ?>
                                <input type="radio" name="higiene_lingua" value="<?= $opt; ?>" checked> <?= ucfirst(str_ireplace('nao', 'não', $opt)); ?> <br>
                              <?php
                            } else {
                              ?>
                                <input type="radio" name="higiene_lingua" value="<?= $opt; ?>"> <?= ucfirst(str_ireplace('nao', 'não', $opt)); ?> <br>
                              <?php
                            }
                          }
                        ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="fio_dental" class="control-label col-lg-2">Fio Dental<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <?php
                          $options = ['nao costumo usar', 'uma vez por semana', 'uma vez por dia', 'mais de uma vez por dia', 'duas ou mais vezes por dia'];
                          foreach ($options as $opt) {
                            if ($opt === $fio_dental) {
                              ?>
                                <input type="radio" name="fio_dental" value="<?= $opt; ?>" checked> <?= ucfirst(str_ireplace('nao', 'não', $opt)); ?> <br>
                              <?php
                            } else {
                              ?>
                                <input type="radio" name="fio_dental" value="<?= $opt; ?>"> <?= ucfirst(str_ireplace('nao', 'não', $opt)); ?> <br>
                              <?php
                            }
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
