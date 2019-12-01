<?php
include_once('connection.php');
include_once('utils.php');

if ($_POST && $_POST['email'] && $_POST['password']) {
  $email = trim(htmlspecialchars(filter_var($_POST['email'], FILTER_SANITIZE_STRING)));
  $password = trim(htmlspecialchars(filter_var($_POST['password'], FILTER_SANITIZE_STRING)));
  $remember = isset($_POST['remember']) && $_POST['remember'] === '1' ? 1 : 0;

  if (preg_match('/[^(a-z0-9_\-@\.)]+/', $email)) {
    ?>
      <script>
        alert('E-mail inválido');
      </script>
    <?php
    header('Refresh: 0; login.php');
    return;
  } else {
    $email = $email;
    $password = md5($password);

    $query = "SELECT
                idUsuario, nome, email, senha, tipo_usuario, hash
              FROM
                usuario
              WHERE
                email = '{$email}' AND
                deleted_at IS NULL
              LIMIT 1";

    $result = mysqli_query($conn, $query);

    if ($result->num_rows <> 1) {
      ?>
        <script>
          alert('Usuário ou senha inválida');
        </script>
      <?php
      header('Refresh: 0; login.php');
      return;
    }

    if ($result) {
      while ($data = mysqli_fetch_array($result)) {
        if ($data['senha'] !== $password) {
          ?>
            <script>
              alert('Senha inválida');
            </script>
          <?php
          header('Refresh: 0; login.php');
          return;
        }

        $date = date('Y-m-d H:i:s');
        $hash = generate_string(30);
        $token = $data['nome'] . '-' . $password . '-' . $hash . '-' . date_to_timestamp($date) . '-' . $data['idUsuario'] . '-' . base64_encode($data['tipo_usuario']);

        $query = "UPDATE
                    usuario
                  SET
                    hash = '{$token}'
                  WHERE
                    idUsuario = '{$data['idUsuario']}'";

        $result = mysqli_query($conn, $query);

        if (!$result) {
          ?>
            <script>
              alert('Ocorreu um erro');
            </script>
          <?php
          header('Refresh: 0; login.php');
          return;
        }

        if ($remember === 1) {
          setcookie('edent-session', urlencode($token), time() + (24 * 3600)); // 24 hours of validity (24 * 3600)
        } else {
          setcookie('edent-session', urlencode($token)); // until the end of session
        }

        header('Refresh: 0; index.php');
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
  <meta name="description" content="Login">
  <meta name="keyword" content="Web System, Odontologic System, Dentist">
  <title>Login</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet"/>
  <link rel="icon" type="image/png" href="images/icons/iconEdent.png"/>
</head>

<body>
  <div class="container-login-wrap">
    <form action="login.php" method="POST">
      <div class="login-wrap">
        <div class="form-group text-center">
          <div>
            <img src="images/icons/iconEdent2.png">
          </div>
        </div>
        <div class="form-group">
          <input type="email" class="form-control" name="email" value="" placeholder="E-mail" autofocus>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="password" value="" placeholder="Senha">
        </div>
        <button type="submit">
          Login
        </button>
        <div class="form-group text-center">
          <label style="font-size: 14px; font-weight: bold; cursor: pointer;">
            <input type="checkbox" name="remember" value="1" checked> Lembrar
          </label>
        </div>
      </div>
    </form>
  </div>
</body>

</html>