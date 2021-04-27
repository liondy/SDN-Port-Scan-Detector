<?php
require 'functions.php';

$batas = 5;
$halaman = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$src = isset($_POST["src"]) ? $_POST["src"] : "";
$dst = isset($_POST["dst"]) ? $_POST["dst"] : "";

echo $src . "<br>";

$cond = "";
if ($src != "" and $dst != "") {
  $cond .= " where `Log`.`Source` = '" . $src . "' and `Log`.`Destination` = '" . $dst . "'";
} else if ($src != "") {
  $cond .= " where `Log`.`Source` = '" . $src . "'";
} else if ($dst != "") {
  $cond .= " where `Log`.`Destination` = '" . $dst . "'";
}

$previous = $halaman - 1;
$next = $halaman + 1;

$get_all_timestamp = "select DISTINCT(`Log`.`Timestamp`) from `Log`" . $cond;
$all_timestamp = query($get_all_timestamp);

$jumlah_data = count($all_timestamp);

$total_halaman = ceil($jumlah_data / $batas);
