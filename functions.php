<?php

setlocale(LC_TIME, 'tr_TR.UTF-8');
date_default_timezone_set('Europe/Istanbul');

function Renklendir($value, $value2)
{
  $parca = explode(" ", $value2);

  for ($i = 0; $i < sizeof($parca); $i++) {
    $value = str_ireplace($parca[$i], "<b style='background-color:#2c3e50; color:white'>$parca[$i]</b>", $value);
  }
  return $value;
}

function Security($value)
{
  $trim = trim($value);
  $strip_tags = strip_tags($trim);
  $htmlspecialchars = htmlspecialchars($strip_tags, ENT_QUOTES);
  $result = $htmlspecialchars;
  return $result;
}

function DeleteNumbers($Deger){
  $Islem = preg_replace("/[^0-9]/","",$Deger);
  $Sonuc = $Islem;
  return $Sonuc;
}
