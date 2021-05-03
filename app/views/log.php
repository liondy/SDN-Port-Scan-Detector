<?php
require 'pagination.php';

$batas = 5;

$halaman = isset($_POST['halaman']) ? (int)$_POST['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$filters = isset($_POST['filter']) ? $_POST['filter'] : "";

$result = export_log($all_timestamp, $halaman_awal, $batas, $filters);

$i = $halaman_awal + 1;
foreach ($result as $row) :
?>
  <?php $date = explode(" ", $row["timestamp"]); ?>
  <tr class=<?= $date[0] == date("Y-m-d") ? "table-danger" : "table-warning"; ?>>
    <td><?= $i++; ?></td>
    <td><?= $row["timestamp"]; ?></td>
    <td><?= $row["source"]; ?></td>
    <td><?= $row["destination"]; ?></td>
    <td><?= $row["ports"]; ?></td>
    <td><?= $row["protokol"]; ?></td>
    <td><?= $row["nama_teknik"]; ?></td>
    <td><?= $row["flags"]; ?></td>
  </tr>
<?php endforeach; ?>