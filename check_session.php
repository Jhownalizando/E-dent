<?php
include_once('connection.php');
include_once('utils.php');

if (empty($_COOKIE['edent-session'])) {
  header('Refresh: 0; login.php');
  return;
}

$date = date('Y-m-d H:i:s');

$cookie = urldecode($_COOKIE['edent-session']);
$cookie_parts = explode('-', $cookie);

// cookie parts handling
$usuario_nome = $cookie_parts[0];
$usuario_senha = $cookie_parts[1];
$usuario_hash = $cookie_parts[2];
$timestamp_cookie = (int) $cookie_parts[3] + (24 * 3600); // 24 hours of validity (24 * 3600)
$usuario_id = $cookie_parts[4];
$usuario_tipo = base64_decode($cookie_parts[5]);

if (empty($usuario_nome) || empty($usuario_senha) || empty($usuario_hash)
  || empty($timestamp_cookie) || empty($usuario_id) || empty($usuario_tipo)) {
  header('Refresh: 0; login.php');
  return;
}

if ($usuario_tipo !== 'profissional' && $usuario_tipo !== 'coordenador') {
  header('Refresh: 0; login.php');
  return;
}

$query = "SELECT
            idUsuario, nome, email, hash, senha
          FROM
            usuario
          WHERE
            idUsuario = '{$usuario_id}' AND nome = '{$usuario_nome}' AND hash = '{$cookie}'
          LIMIT 1";

$result = mysqli_query($conn, $query);

if ($result->num_rows <> 1) {
  header('Refresh: 0; login.php');
  return;
}

if ($result) {
  while ($data = mysqli_fetch_array($result)) {
    if ($data['senha'] !== $usuario_senha) {
      header('Refresh: 0; login.php');
      return;
    }
    $timestamp_now = date_to_timestamp($date);

    // session expired
    if($timestamp_now > $timestamp_cookie){
      header('Refresh: 0; login.php');
      return;
    }
    // here the cookie and session are valid, continue
  }
}
?>
