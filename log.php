<?php
require 'functions.php';

$result = export_log();

?>
<table border="1" cellpadding="10" cellspacing="10">
  <tr>
    <th>No.</th>
    <th>Timestamp</th>
    <th>Source Address</th>
    <th>Destination Address</th>
    <th>Port Scanned</th>
    <th>Protocol</th>
    <th>Teknik</th>
    <th>Flags</th>
  </tr>
  <?php $i = 1 ?>
  <?php foreach ($result as $row) : ?>
    <tr>
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
</table>