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
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                PRONTUÁRIO ODONTOLÓGICO
              </header>
              <div class="panel-body">
                <div class="form">
                  <form class="form-validate form-horizontal" id="register_form" method="POST" action="cadastrar_prontuario_odontologico.php">

                    <div class="form-group">
                      <label for="paciente" class="control-label col-lg-2">Paciente<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <select id="paciente" name="paciente" class="form-control" required="required">
                          <option value="" selected>Selecione</option>
                          <?php
                            include_once('connection.php');

                            $query = "SELECT idPaciente, nome FROM paciente WHERE deleted_at IS NULL";

                            $result = mysqli_query($conn, $query);

                            if ($result) {
                              while ($data = mysqli_fetch_array($result)) {
                                ?>
                                  <option value="<?= $data['idPaciente']; ?>">
                                    <?= $data['nome']; ?>
                                  </option>
                                <?php
                              }
                            }
                          ?>
                        </select>
                        <br>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="dificuldade_engolir_alimentos" class="control-label col-lg-2">Dificuldade em engolir alimentos?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input type="radio" required="required" name="dificuldade_engolir_alimentos" value="sim"> Sim <br>
                        <input type="radio" required="required" name="dificuldade_engolir_alimentos" value="nao"> Não
                        <br>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="protese_dentadura" class="control-label col-lg-2">Prótese de dentadura?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input type="radio" required="required" name="protese_dentadura" value="sim"> Sim <br>
                        <input type="radio" required="required" name="protese_dentadura" value="nao"> Não <br>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="quanto_tempo_perdeu_dentes" class="control-label col-lg-2">Com quanto tempo trocou todos os dentes de leite?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input class="form-control" name="quanto_tempo_perdeu_dentes" placeholder="Quantos anos você tinha quando seu último dente de leite caiu?" type="number" required="required"/>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="adaptado_protese" class="control-label col-lg-2">Prótese Dentária?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input type="radio" required="required" name="adaptado_protese" value="sim"> Sim <br>
                        <input type="radio" required="required" name="adaptado_protese" value="nao"> Não <br>
                    </div>
                    </div>
                    <div class="form-group">
                      <label for="dentes_sensiveis" class="control-label col-lg-2">Dentes são sensíveis?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input type="radio" required="required" name="dentes_sensiveis" value="sim"> Sim <br>
                        <input type="radio" required="required" name="dentes_sensiveis" value="nao"> Não <br>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="gengiva_sangra" class="control-label col-lg-2">Genviva costuma sangrar?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input type="radio" required="required" name="gengiva_sangra" value="sim"> Sim <br>
                        <input type="radio" required="required" name="gengiva_sangra" value="nao"> Não <br>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="mau_halito" class="control-label col-lg-2">Mau hálito?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input type="radio" required="required" name="mau_halito" value="sim"> Sim <br>
                        <input type="radio" required="required" name="mau_halito" value="nao"> Não <br>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="toma_cafe_refrigerante" class="control-label col-lg-2">Costuma tomar café ou refigerante?<span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input type="radio" required="required" name="toma_cafe_refrigerante" value="sim"> Sim <br>
                        <input type="radio" required="required" name="toma_cafe_refrigerante" value="nao"> Não <br>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="observacao" class="control-label col-lg-2">Observações <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <textarea class="form-control" name="observacao" style="width:100%; height:100px; resize: vertical;" required="required" placeholder="Se não tiver observações escreva que não possui."></textarea>
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
