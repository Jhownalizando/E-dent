<?php
function generate_string($size){
  $string_codes = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+@&%(){}[]';
  $string_return = '';
  for ($count = 0; $size > $count; $count ++) {
    $string_return .= $string_codes[rand(0, strlen($string_codes) - 1)];
  }
  return $string_return;
}

function date_to_timestamp($data) {
  $dia = date('Y-m-d', strtotime($data));
  $dia = explode('-', ($dia));
  $hora = date('H:i:s', strtotime($data));
  $hora = explode(':', ($hora));
  return mktime($hora[0], $hora[1], $hora[2], $dia[1], $dia[2], $dia[0]);
}
?>
