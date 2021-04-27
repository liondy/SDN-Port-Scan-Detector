<?php require 'pagination.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SDN Port Scanning Detection</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="script.js" defer></script>
</head>

<body>
  <div class="container fluid mt-5">
    <div>
      <p>
        <a class="btn btn-info text-light" data-bs-toggle="collapse" href="#toggleFilter" role="button" aria-expanded="false" aria-controls="toggleFilter">
          Filter
        </a>
      </p>
      <div class="collapse" id="toggleFilter">
        <div class="card card-body">
          <form class="filter" action="pagination.php" method="post">
            <div class="input-group">
              <span class="input-group-text">Source and Destination</span>
              <input type="text" aria-label="Source" class="form-control" list="listSourceIP" placeholder="IP Address Asal" name="src" id="src">
              <input type="text" aria-label="Destination" class="form-control" list="listDestIP" placeholder="IP Address Tujuan" name="dst" id="dst">
              <datalist id="listSourceIP">
                <?php
                $sourceIP = [];
                $destIP = [];
                foreach ($all_timestamp as $timestamp) {
                  $curTimestamp = $timestamp["Timestamp"]; #extract each timestamp
                  $curLogs = getLogs($curTimestamp); #getLogByTimestamp
                  if (!in_array($curLogs[0]["Source"], $sourceIP)) {
                    $sourceIP[] = $curLogs[0]["Source"];
                  }
                  if (!in_array($curLogs[0]["Destination"], $destIP)) {
                    $destIP[] = $curLogs[0]["Destination"];
                  }
                }
                foreach ($sourceIP as $source) : ?>
                  <option value="<?= $source; ?>">
                  <?php endforeach; ?>
              </datalist>
              <datalist id="listDestIP">
                <?php foreach ($destIP as $dest) : ?>
                  <option value="<?= $dest; ?>">
                  <?php endforeach; ?>
              </datalist>
            </div>
            <div class="mt-3 float-end">
              <input type="submit" class="btn btn-success" value="Apply">
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead>
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
        </thead>
        <tbody id="output">
        </tbody>
      </table>
    </div>
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item <?= $halaman > 1 ? "" : "disabled"; ?>">
          <a class="page-link changePage" <?php if ($halaman > 1) {
                                            echo "href = '?page=" . $previous . "'";
                                          } ?> aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <?php for ($x = 1; $x <= $total_halaman; $x++) : ?>
          <li class="page-item">
            <a class="page-link changePage" href="?page=<?= $x ?>"><?= $x; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?= $halaman == $total_halaman ? "disabled" : ""; ?>">
          <a class="page-link changePage" <?php if ($halaman < $total_halaman) {
                                            echo "href = '?page=" . $next . "'";
                                          } ?> aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</body>

</html>