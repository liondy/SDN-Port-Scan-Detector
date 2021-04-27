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
  <?php $date = explode(" ", $row["Timestamp"]) ?>
  <tr class=<?= $date == date("Y-m-d") ? "table-danger" : "table-warning"; ?>>
    <td><?= $i++; ?></td>
    <td><?= $row["Timestamp"]; ?></td>
    <td><?= $row["Source"]; ?></td>
    <td><?= $row["Destination"]; ?></td>
    <td><?= $row["Ports"]; ?></td>
    <td><?= $row["Protokol"]; ?></td>
    <td><?= $row["NamaTeknik"]; ?></td>
    <td><?= $row["Flags"]; ?></td>
  </tr>
<?php endforeach; ?>