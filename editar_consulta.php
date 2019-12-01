<?php
  include_once('check_session.php');
  include_once('connection.php');

  if (!empty($_POST)) {
    if (empty($_POST['idConsulta']) || empty($_POST['titulo']) || empty($_POST['observacao'])
      || empty($_POST['data']) || empty($_POST['hora'])) {
      ?>
        <script>
          alert('Todos os campos são obrigatorios!');
        </script>
      <?php
      header('Refresh: 0; lista_consulta.php');
      return;
    } else {
      $id = $_POST['idConsulta'];
      $data = [
        'titulo' => $_POST['titulo'],
        'observacao' => $_POST['observacao'],
        'status' => $_POST['status'],
        'data_hora' => date('Y-m-d H:i:s', strtotime($_POST['data'] . ' ' . $_POST['hora'])),
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
                  consulta
                SET
                  {$update_fields}
                WHERE
                  idConsulta = {$id}";

      $result = mysqli_query($conn, $query);

      if ($result) {
        ?>
          <script>
            alert('Cadastro alterado com sucesso!');
          </script>
        <?php
      } else {
        ?>
          <script>
            alert('Erro ao alterar o cadastro!');
          </script>
        <?php
      }
      header('Refresh: 0; lista_consulta.php');
      return;
    }
  }

  if (empty($_GET['id'])) {
    header('Location: lista_consulta.php');
  }
  $id = intval($_GET['id']);
  if (empty($id)) {
    header('Refresh: 0; lista_consulta.php');
    return;
  }

  $sql = mysqli_query($conn, "SELECT * FROM consulta WHERE idConsulta = {$id} ");

  $rows = mysqli_num_rows($sql);

  if ($rows == 0) {
    header('Location: lista_consulta.php');
    return;
  } else {
    while ($data = mysqli_fetch_array($sql)) {
      $idConsulta = $data['idConsulta'];
      $titulo = $data['titulo'];
      $observacao = $data['observacao'];
      $status = $data['status'];
      $data_hora = date('Y-m-d H:i:s', strtotime($data['data_hora']));

      $exploded_date = explode(' ', $data_hora);

      $data_valor = $exploded_date && $exploded_date[0] ? $exploded_date[0] : NULL;
      $hora_valor = $exploded_date && $exploded_date[1] ? $exploded_date[1] : NULL;
    }
  }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Editar Consulta">
  <meta name="keyword" content="Web System, Odontologic System, Dentist">
  <title>Editar Consulta</title>
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
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                EDITAR CONSULTA
              </header>
              <div class="panel-body">
                <div class="form">
                  <form class="form-validate form-horizontal" id="register_form" method="POST" action="">
                    <input type="hidden" name="idConsulta" value="<?= $id; ?>">

                    <div class="row">
                      <div class="col-lg-6 form-group">
                        <label for="titulo" class="control-label col-lg-2">Titulo<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" name="titulo" placeholder="Digite o Titulo" required="required" value="<?= $titulo; ?>"/>
                        </div>
                      </div>

                      <div class="col-lg-6 form-group">
                        <label for="observacao" class="control-label col-lg-2">Observações <span class="required">*</span></label>
                        <div class="col-lg-10">
                          <textarea class="form-control" name="observacao" style="width:100%; height:100px; resize: vertical;" required="required" placeholder="Se não tiver observações escreva que não possui."><?= $observacao; ?></textarea>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 form-group">
                        <label for="status" class="control-label col-lg-2">Status<span class="required">*</span></label>
                        <div class="col-lg-10" style="height: 34px !important; margin-bottom: 10px;">
                          <select id="status" name="status" class="form-control" required="required">
                            <?php
                              $opt_status = ['agendada', 'finalizada', 'cancelada'];

                              foreach ($opt_status as $option) {
                                if ($status === $option) {
                                  ?>
                                    <option selected value="<?= $option; ?>">
                                      <?= ucwords($option); ?>
                                    </option>
                                  <?php
                                } else {
                                  ?>
                                    <option value="<?= $option; ?>">
                                      <?= ucwords($option); ?>
                                    </option>
                                  <?php
                                }
                              }
                            ?>
                          </select>
                          <br>
                        </div>
                      </div>
                      <div class="col-lg-6 form-group"></div>
                    </div>

                    <br>

                    <div class="row">
                      <div class="col-lg-6 form-group">
                        <label for="data" class="control-label col-lg-2">Data<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" name="data" type="date" placeholder="Digite a data da consulta" value="<?= $data_valor; ?>"/>
                        </div>
                      </div>

                      <div class="col-lg-6 form-group">
                        <label for="hora" class="control-label col-lg-2">Hora<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" name="hora" type="time" placeholder="Digite a hora da consulta" value="<?= $hora_valor; ?>"/>
                        </div>
                      </div>
                    </div>

                    <div class="row" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                      <div>
                        <small class="form-text text">
                          OBS: Antes de encerrar verificar se todos os dados estão corretos.
                        </small>
                      </div>
                      <button class="btn btn-primary" type="submit">Salvar</button>
                    </div>
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
