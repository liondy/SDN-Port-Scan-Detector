<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SDN Port Scanning Detection</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="script.js" defer></script>
</head>

<body>
  <div class="container table-responsive">
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
    <?php include 'pagination.php'; ?>
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item <?= $halaman > 1 ? "" : "disabled"; ?>">
          <a class="page-link" <?php if ($halaman > 1) {
                                  echo "href = '?page=" . $previous . "'";
                                } ?> aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <?php for ($x = 1; $x <= $total_halaman; $x++) : ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $x ?>"><?= $x; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?= $halaman == $total_halaman ? "disabled" : ""; ?>">
          <a class="page-link" <?php if ($halaman < $total_halaman) {
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