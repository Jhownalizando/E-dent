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
    if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])
      || empty($_POST['confirmar_senha']) || empty($_POST['rg']) || empty($_POST['cpf'])
      || empty($_POST['tipo_usuario']) || empty($_POST['data_nasc']) || empty($_POST['telefone'])
      || empty($_POST['sexo']) || empty($_POST['estado_civil']) || empty($_POST['cep'])
      || empty($_POST['endereco_residencial']) || empty($_POST['bairro']) || empty($_POST['cidade'])) {
    ?>
      <script>
        alert('Todos os campos são obrigatorios');
      </script>
    <?php
      header('Refresh: 0; cadastro_paciente.php');
      return;
    } else {
      $nome = trim(htmlspecialchars(filter_var($_POST['nome'], FILTER_SANITIZE_STRING)));
      $email = trim(htmlspecialchars(filter_var($_POST['email'], FILTER_SANITIZE_STRING)));
      $rg = trim(htmlspecialchars(filter_var($_POST['rg'], FILTER_SANITIZE_STRING)));
      $cpf = trim(htmlspecialchars(filter_var($_POST['cpf'], FILTER_SANITIZE_STRING)));
      $tipo_usuario = $_POST['tipo_usuario'];
      $data_nasc = date('Y-m-d', strtotime($_POST['data_nasc']));
      $telefone = $_POST['telefone'];
      $sexo = $_POST['sexo'];
      $estado_civil = $_POST['estado_civil'];
      $cep = $_POST['cep'];
      $endereco_residencial = $_POST['endereco_residencial'];
      $bairro = $_POST['bairro'];
      $cidade = $_POST['cidade'];

      // validate email
      if ((!empty($email) && !preg_match("/^[\w]{1,15}[\.]?[\w]{1,15}[\.]?[\w]{1,10}[@][^\W][\w]{1,15}[\.][\w]{1,15}[\.]?[\w]{0,5}[^\W$]/", $email))
        || (!empty($data['email']) && !preg_match("/^[\w]{1,15}[\.]?[\w]{1,15}[\.]?[\w]{1,10}[@][^\W][\w]{1,15}[\.][\w]{1,15}[\.]?[\w]{0,5}[^\W$]/", $data['email']))) {
        ?>
          <script>
            alert('E-mail inválido!');
          </script>
        <?php

        header('Refresh: 0; cadastro_usuario.php');
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

        header('Refresh: 0; cadastro_usuario.php');
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

        header('Refresh: 0; cadastro_usuario.php');
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

        header('Refresh: 0; cadastro_usuario.php');
        return;
      }

      if ($_POST['senha'] !== $_POST['confirmar_senha']) {
        ?>
          <script>
            alert('Senhas não coincidem!');
          </script>
        <?php
        header('Refresh: 0; cadastro_usuario.php');
        return;
      }
      $senha = trim(htmlspecialchars(filter_var($_POST['senha'], FILTER_SANITIZE_STRING)));
      $senha = md5($senha);

      $query = mysqli_query($conn, "SELECT * FROM usuario WHERE email = '{$email}' OR rg = '{$rg}' OR cpf = '{$cpf}'");

      $result = mysqli_fetch_array($query);

      if ($result) {
        ?>
          <script>
            alert('Usuário já cadastrado no sistema, reveja os dados!');
          </script>
        <?php
        header('Refresh: 0; cadastro_usuario.php');
        return;
      } else {
        $query = "INSERT INTO usuario
        (nome, email, senha, rg, cpf, tipo_usuario, data_nasc, telefone, sexo, estado_civil, bairro, cep, cidade, endereco_residencial)
        VALUES
        ('{$nome}', '{$email}', '{$senha}', '{$rg}', '{$cpf}', '{$tipo_usuario}', '{$data_nasc}', '{$telefone}', '{$sexo}', '{$estado_civil}', '{$bairro}', '{$cep}', '{$cidade}', '{$endereco_residencial}')";

        $result = mysqli_query($conn, $query);

        if ($result) {
          ?>
            <script>
              alert('Usuário cadastrado com sucesso no sistema!');
            </script>
          <?php
          header('Refresh: 0; lista_usuario.php');
          return;
        } else {
          ?>
            <script>
              alert('Erro ao cadastrar usuário!');
            </script>
          <?php
          header('Refresh: 0; cadastro_usuario.php');
          return;
        }
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Cadastro de Usuários">
  <meta name="keyword" content="Web System, Odontologic System, Dentist">
  <title>Cadastro de Usuários</title>
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

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="nome" class="control-label col-lg-2">Nome Completo<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" name="nome" required="required" placeholder="Digite o Nome" value=""/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="email" class="control-label col-lg-2">Email<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" name="email" type="email" required="required" placeholder="email@dominio.com" value=""/>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="rg" class="control-label col-lg-2">RG<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" onkeypress="$(this).mask('99.999.999-9')" type="text" id="rg" name="rg" required="required" placeholder="99.999.999-9" value="" pattern="[0-9]{2}\.[0-9]{3}\.[0-9]{3}-[0-9]"/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="cpf" class="control-label col-lg-2">CPF<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" onkeypress="$(this).mask('000.000.000-00');" type="text" id="cpf" name="cpf" required="required" placeholder="000.000.000-00" value="" pattern="[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}"/>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="tipo_usuario" class="control-label col-lg-2">Tipo Usuário<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <select name="tipo_usuario" class="form-control" required="required">
                            <option value="" selected>Selecionar</option>
                            <option value="Profissional">Profissional</option>
                            <option value="Coordenador">Coordenador</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="data_nasc" class="control-label col-lg-2">Data de Nascimento<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="date" name="data_nasc" required="required"/>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="telefone" class="control-label col-lg-2">Telefone<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" onkeypress="$(this).mask('(00)00009-0000')" type="text" id="telefone" name="telefone" required="required" placeholder="(00)00000-0000" value="" pattern="\([0-9]{2}\)[0-9]{4,5}-[0-9]{4}"/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="sexo" class="control-label col-lg-2">Sexo<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <select name="sexo" class="form-control" required="required">
                            <option value="" selected>Selecionar</option>
                            <option value="f">Feminino</option>
                            <option value="m">Masculino</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="cep" class="control-label col-lg-2">CEP<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" id="cep" name="cep" placeholder="00.000-000" required="required" maxlength="10" value="" pattern="[0-9]{2}\.[0-9]{3}-[0-9]{3}"/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="estado_civil" class="control-label col-lg-2">Estado Civil<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <select name="estado_civil" class="form-control" required="required">
                            <option value="" selected>Selecionar</option>
                            <option value="s">Solteiro</option>
                            <option value="c">Casado</option>
                            <option value="d">Divorciado</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="endereco_residencial" class="control-label col-lg-2">Endereço Residencial<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" id="endereco_residencial" name="endereco_residencial" class="form-control" placeholder="Digite o Endereço" required="required" value=""/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group"></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="bairro" class="control-label col-lg-2">Bairro<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" id="bairro" name="bairro" class="form-control" placeholder="Digite o Bairro" required="required" value=""/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="senha" class="control-label col-lg-2">Senha<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="password" name="senha" required="required" placeholder="Digite a Senha"/>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="cidade" class="control-label col-lg-2">Cidade<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="text" id="cidade" name="cidade" class="form-control" placeholder="Digite a Cidade" required="required" value=""/>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                        <label for="confirmar_senha" class="control-label col-lg-2">Confirme a Senha<span class="required">*</span></label>
                        <div class="col-lg-10">
                          <input class="form-control" type="password" name="confirmar_senha" required="required" placeholder="Confirme a senha"/>
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
