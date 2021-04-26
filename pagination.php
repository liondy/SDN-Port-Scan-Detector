<?php
require 'functions.php';

$batas = 5;
$halaman = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$previous = $halaman - 1;
$next = $halaman + 1;

$get_all_timestamp = "select DISTINCT(`Log`.`Timestamp`) from `Log`";
$all_timestamp = query($get_all_timestamp);

$jumlah_data = count($all_timestamp);

$total_halaman = ceil($jumlah_data / $batas);
