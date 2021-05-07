<?php if ($data["nodata"]) : ?>
  <div class="alert alert-danger" role="alert">
    <?= $data["nodata"]; ?>
  </div>
<?php else : ?>
  <?php
  $i = $data["iteration"] + 1;
  foreach ($data["filtered"] as $row) :
    $date = explode(" ", $row["timestamp"]); ?>
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
<?php endif; ?>