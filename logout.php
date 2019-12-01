<?php
unset($_COOKIE['edent-session']);
setcookie('edent-session', null, time() - 3600);

unset($usuario_nome);
unset($usuario_senha);
unset($usuario_hash);
unset($timestamp_cookie);
unset($usuario_id);
unset($usuario_tipo);

header('HTTP/1.1 302 Found');
header('Location: login.php');
?>
