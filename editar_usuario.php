<?php
  include_once('check_session.php');
  include_once('connection.php');

  // only coordinator is allowed to access this page
  if (!isset($usuario_tipo) || $usuario_tipo !== 'coordenador') {
    header('HTTP/1.1 302 Found');
    header('Location: index.php');
    return;
  }

  if (!empty($_POST)) {
    if (empty($_POST['idUsuario']) || empty($_POST['nome']) || empty($_POST['email'])
      || empty($_POST['rg']) || empty($_POST['cpf']) || empty($_POST['tipo_usuario'])
      || empty($_POST['data_nasc']) || empty($_POST['telefone']) || empty($_POST['sexo'])
      || empty($_POST['estado_civil']) || empty($_POST['bairro']) || empty($_POST['cep'])
      || empty($_POST['cidade']) || empty($_POST['endereco_residencial'])) {
      ?>
        <script>
          alert('Todos os campos são obrigatorios!');
        </script>
      <?php
      header('Refresh: 0; lista_usuario.php');
      return;
    } else {
      $id = $_POST['idUsuario'];
      $data = [
        'nome' => trim(htmlspecialchars(filter_var($_POST['nome'], FILTER_SANITIZE_STRING))),
        'email' => trim(htmlspecialchars(filter_var($_POST['email'], FILTER_SANITIZE_STRING))),
        'rg' => trim(htmlspecialchars(filter_var($_POST['rg'], FILTER_SANITIZE_STRING))),
        'cpf' => trim(htmlspecialchars(filter_var($_POST['cpf'], FILTER_SANITIZE_STRING))),
        'tipo_usuario' => $_POST['tipo_usuario'],
        'data_nasc' => date('Y-m-d', strtotime($_POST['data_nasc'])),
        'telefone' => $_POST['telefone'],
        'sexo' => $_POST['sexo'],
        'estado_civil' => $_POST['estado_civil'],
        'cep' => $_POST['cep'],
        'endereco_residencial' => $_POST['endereco_residencial'],
        'bairro' => $_POST['bairro'],
        'cidade' => $_POST['cidade'],
      ];

      // validate email
      if ((!empty($email) && !preg_match("/^[\w]{1,15}[\.]?[\w]{1,15}[\.]?[\w]{1,10}[@][^\W][\w]{1,15}[\.][\w]{1,15}[\.]?[\w]{0,5}[^\W$]/", $email))
        || (!empty($data['email']) && !preg_match("/^[\w]{1,15}[\.]?[\w]{1,15}[\.]?[\w]{1,10}[@][^\W][\w]{1,15}[\.][\w]{1,15}[\.]?[\w]{0,5}[^\W$]/", $data['email']))) {
        ?>
          <script>
            alert('E-mail inválido!');
          </script>
        <?php

        header('Refresh: 0; lista_usuario.php');
        return;
      }

      // validate rg
      if ((!empty($rg) && !preg_match("/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}-[0-9]$/", $rg))
        || (!empty($data['rg']) && !preg_match("/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}-[0-9]$/", $data['rg']))) {
        ?>
          <script>
            alert('RG inválido!');
          </script>
        <?php

        header('Refresh: 0; lista_usuario.php');
        return;
      }

      // validate cpf
      if ((!empty($cpf) && !preg_match("/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$/", $cpf))
        || (!empty($data['cpf']) && !preg_match("/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$/", $data['cpf']))) {
        ?>
          <script>
            alert('CPF inválido!');
          </script>
        <?php

        header('Refresh: 0; lista_usuario.php');
        return;
      }

      // validate data_nasc
      if ((!empty($data_nasc) && $data_nasc > date('Y-m-d'))
        || (!empty($data['data_nasc']) && $data['data_nasc'] > date('Y-m-d'))) {
        ?>
          <script>
            alert('Data de nascimento inválida!');
          </script>
        <?php

        header('Refresh: 0; lista_usuario.php');
        return;
      }

      if (!empty($_POST['senha']) || !empty($_POST['confirmar_senha'])) {
        if ($_POST['senha'] !== $_POST['confirmar_senha']) {
          ?>
            <script>
              alert('Senhas não coincidem!');
            </script>
          <?php
          header('Refresh: 0; lista_usuario.php');
          return;
        }
        $data['senha'] = trim(htmlspecialchars(filter_var($_POST['senha'], FILTER_SANITIZE_STRING)));
        $data['senha'] = md5($data['senha']);
      }

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
                  usuario
                SET
                  {$update_fields}
                WHERE
                  idUsuario = {$id}";

      $result = mysqli_query($conn, $query);

      if ($result) {
        ?>
          <script>
            alert('Cadastro do usuario alterado com sucesso no sistema!');
          </script>
        <?php
      } else {
        ?>
          <script>
            alert('Erro ao alterar o cadastro do usuario!');
          </script>
        <?php
      }
      header('Refresh: 0; lista_usuario.php');
      return;
    }
  }

  //Retornar dados do usuário:
  if (empty($_GET['id'])) {
    header('Location: lista_usuario.php');
    return;
  }

  $idU = intval($_GET['id']);
  if (empty($idU)) {
    header('Refresh: 0; lista_usuario.php');
    return;
  }

  $sql = mysqli_query($conn, "SELECT idUsuario, nome, email, senha, rg, cpf, tipo_usuario, data_nasc, telefone, sexo, estado_civil, bairro, cep, cidade, endereco_residencial FROM usuario WHERE idUsuario = {$idU} AND deleted_at IS NULL");

  $rows = mysqli_num_rows($sql);

  if ($rows == 0) {
    header('Location: lista_usuario.php');
    return;
  } else {
    while ($data = mysqli_fetch_array($sql)) {
      $idUsuario = $data['idUsuario'];
      $nome = $data['nome'];
      $email = $data['email'];
      $senha = $data['senha'];
      $rg = $data['rg'];
      $cpf = $data['cpf'];
      $tipo_usuario = $data['tipo_usuario'];
      $data_nasc = $data['data_nasc'];
      $telefone = $data['telefone'];
      $sexo = $data['sexo'];
      $estado_civil = $data['estado_civil'];
      $bairro = $data['bairro'];
      $cep = $data['cep'];
      $cidade = $data['cidade'];
      $endereco_residencial = $data['endereco_residencial'];
    }
  }

  $genre_options = [
    [ 'name' => 'Feminino', 'value' => 'f' ],
    [ 'name' => 'Masculino', 'value' => 'm' ],
  ];

  $marital_state_options = [
    [ 'name' => 'Casado', 'value' => 'c' ],
    [ 'name' => 'Solteiro', 'value' => 's' ],
    [ 'name' => 'Divorciado', 'value' => 'd' ],
  ];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Editar Usuários">
  <meta name="keyword" content="Web System, Odontologic System, Dentist">
  <title>Editar Usuários</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet"/>
  <link rel="icon" type="image/png" href="images/icons/iconEdent.png"/>
  <style>
    input {
      border: 1px solid #c7c7cc;
    }
    input[type="text"]:not(:placeholder-shown),
    input[type="email"]:not(:placeholder-shown),
    input[type="password"]:not(:placeholder-shown) {
      border: 1px solid #ff1e1e;
    }
    input[type="text"]:valid,
    input[type="email"]:valid,
    input[type="password"]:valid {
      border: 1px solid #0ee10e;
    }
  </style>
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
                CADASTRO USUARIO
              </header>
              <div class="panel-body">
                <div class="form">
                  <form class="form-validate form-horizontal" id="register_form" method="POST" action="">
                    <input type="hidden" name="idUsuario" value="<?= $idU; ?>">

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="nome" class="control-label col-lg-2">Nome Completo<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" name="nome" required="required" placeholder="Digite o Nome" value="<?= $nome; ?>"/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="email" class="control-label col-lg-2">Email<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" name="email" type="email" required="required" placeholder="email@dominio.com" value="<?= $email; ?>"/>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="rg" class="control-label col-lg-2">RG<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" onkeypress="$(this).mask('99.999.999-9')" type="text" id="rg" name="rg" required="required" placeholder="99.999.999-9" value="<?= $rg; ?>" pattern="[0-9]{2}\.[0-9]{3}\.[0-9]{3}-[0-9]"/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="cpf" class="control-label col-lg-2">CPF<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" onkeypress="$(this).mask('000.000.000-00');" type="text" id="cpf" name="cpf" required="required" placeholder="000.000.000-00" value="<?= $cpf; ?>" pattern="[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}"/>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="tipo_usuario" class="control-label col-lg-2">Tipo Usuário<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <select name="tipo_usuario" class="form-control" required="required">
                            <?php
                              if ($tipo_usuario === 'profissional') {
                                ?>
                                  <option value="profissional" selected>Profissional</option>
                                  <option value="coordenador">Coordenador</option>
                                <?php
                              } else {
                                ?>
                                  <option value="profissional">Profissional</option>
                                  <option value="coordenador" selected>Coordenador</option>
                                <?php
                              }
                            ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="data_nasc" class="control-label col-lg-2">Data de Nascimento<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="date" name="data_nasc" required="required" value="<?= $data_nasc; ?>"/>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="telefone" class="control-label col-lg-2">Telefone<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" onkeypress="$(this).mask('(00)00009-0000')" type="text" id="telefone" name="telefone" required="required" placeholder="(00)00000-0000" value="<?= $telefone; ?>" pattern="\([0-9]{2}\)[0-9]{4,5}-[0-9]{4}"/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="sexo" class="control-label col-lg-2">Sexo<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <select name="sexo" class="form-control" required="required">
                            <?php
                              foreach ($genre_options as $option) {
                                if ($sexo === $option['value']) {
                                  ?>
                                    <option selected value="<?= $option['value']; ?>">
                                      <?= $option['name']; ?>
                                    </option>
                                  <?php
                                } else {
                                  ?>
                                    <option value="<?= $option['value']; ?>">
                                      <?= $option['name']; ?>
                                    </option>
                                  <?php
                                }
                              }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="cep" class="control-label col-lg-2">CEP<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" id="cep" name="cep" placeholder="00.000-000" required="required" maxlength="10" value="<?= $cep; ?>" pattern="[0-9]{2}\.[0-9]{3}-[0-9]{3}"/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="estado_civil" class="control-label col-lg-2">Estado Civil<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <select name="estado_civil" class="form-control" required="required">
                            <?php
                              foreach ($marital_state_options as $option) {
                                if ($estado_civil === $option['value']) {
                                  ?>
                                    <option selected value="<?= $option['value']; ?>">
                                      <?= $option['name']; ?>
                                    </option>
                                  <?php
                                } else {
                                  ?>
                                    <option value="<?= $option['value']; ?>">
                                      <?= $option['name']; ?>
                                    </option>
                                  <?php
                                }
                              }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="endereco_residencial" class="control-label col-lg-2">Endereço Residencial<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" id="endereco_residencial" name="endereco_residencial" class="form-control" placeholder="Digite o Endereço" required="required" value="<?= $endereco_residencial; ?>"/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group"></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="bairro" class="control-label col-lg-2">Bairro<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" id="bairro" name="bairro" class="form-control" placeholder="Digite o Bairro" required="required" value="<?= $bairro; ?>"/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="senha" class="control-label col-lg-2">Senha</label>
                        <div class="col-lg-10">
                          <input class="form-control" type="password" name="senha" placeholder="Digite a Senha"/>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="cidade" class="control-label col-lg-2">Cidade<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" id="cidade" name="cidade" class="form-control" placeholder="Digite a Cidade" required="required" value="<?= $cidade; ?>"/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="confirmar_senha" class="control-label col-lg-2">Confirme a Senha</label>
                        <div class="col-lg-10">
                          <input class="form-control" type="password" name="confirmar_senha" placeholder="Confirme a senha"/>
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
            </section>
          </div>
        </div>
      </section>
    </section>
  </section>

  <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.10.4.min.js"></script>
  <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
  <script type="text/javascript" src="js/jquery.scrollTo.min.js"></script>
  <script type="text/javascript" src="js/jquery.nicescroll.js"></script>
  <script type="text/javascript" src="js/jquery.customSelect.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
  <script type="text/javascript" src="js/jquery.mask.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>
  <script>
    const cepField = $('#cep');
    cepField.keyup(function(e) {
      let value = e.target.value;
      if (!value) {
        return;
      }
      value = value.toString().replace(/[^\d]+/g, '');
      if (value.length > 2) {
        value = `${value.substring(0, 2)}.${value.substring(2,)}`;
      }
      if (value.length > 6) {
        value = `${value.substring(0, 6)}-${value.substring(6,)}`;
      }
      if (value.length === 10 && value.match(/[\d]{2}\.[\d]{3}-[\d]{3}/g)) {
        $.get(`http://viacep.com.br/ws/${value.toString().replace(/[^\d]+/g, '')}/json`, function(data) {
          let body = typeof data === 'string' ? JSON.parse(data) : data;
          if (body.logradouro) {
            $('#endereco_residencial').val(body.logradouro);
          }
          if (body.bairro) {
            $('#bairro').val(body.bairro);
          }
          if (body.localidade) {
            $('#cidade').val(body.localidade);
          }
        });
      }
      e.target.value = value;
    });
  </script>
</body>

</html>
