<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SDN Port Scanning Detection</title>
  <link rel="stylesheet" href="./css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <script src="./js/script.js" defer></script>
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
          <form class="filter">
            <input type="hidden" name="page" id="page" value=<?= $data["halaman"]; ?>>
            <input type="hidden" name="total_halaman" id="total_halaman" value=<?= $data["total_halaman"]; ?>>
            <input type="hidden" name="srcLst" id="srcLst" value=<?= json_encode($data["sources"]); ?>>
            <input type="hidden" name="dstLst" id="dstLst" value=<?= json_encode($data["destinations"]); ?>>
            <div class="input-group">
              <span class="input-group-text">Source and Destination</span>
              <input type="text" aria-label="Source" class="form-control" list="listSourceIP" placeholder="IP Address Asal" name="src" id="src">
              <input type="text" aria-label="Destination" class="form-control" list="listDestIP" placeholder="IP Address Tujuan" name="dst" id="dst">
              <datalist id="listSourceIP">
                <?php foreach ($data["sources"] as $source) : ?>
                  <option value="<?= $source["source"]; ?>">
                  <?php endforeach; ?>
              </datalist>
              <datalist id="listDestIP">
                <?php foreach ($data["destinations"] as $dest) : ?>
                  <option value="<?= $dest["destination"]; ?>">
                  <?php endforeach; ?>
              </datalist>
            </div>
            <div class="mt-3">
              <div class="row">
                <div class="col-3">
                  <label for="protokol">Tampilkan protokol: </label>
                </div>
                <div class="col-4">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="tcpcheckbox" value="TCP">
                    <label class="form-check-label" for="tcpcheckbox">TCP</label>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="udpcheckbox" value="UDP">
                    <label class="form-check-label" for="udpcheckbox">UDP</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-3">
              <div class="row">
                <label for="protokol">Tampilkan protokol <span class="protokol"></span> untuk teknik: </label>
              </div>
              <div class="row mt-2">
                <?php foreach ($data["teknik"] as $teknik) : ?>
                  <div class="col-3 mb-2">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" id="<?= str_replace("_/_", "_", str_replace(" ", "_", strtolower($teknik["nama_teknik"]))); ?>_checkbox" value="<?= $teknik["nama_teknik"]; ?>">
                      <label class="form-check-label"><?= $teknik["nama_teknik"]; ?></label>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="mt-2 row">
              <div class="col">
                <label for="datetime" class="col-form-label">Pilih rentang waktu</label>
              </div>
              <div class="col-10">
                <input type="text" class="form-control" name="datetimes" id="datetimes" placeholder="Pilih rentang waktu" value="" />
              </div>
            </div>
            <div class="mt-3 float-end">
              <input type="submit" class="btn btn-success" value="Apply">
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="keterangan mt-3">
      <p>Keterangan: </p>
      <p>Baris bewarna merah: Sistem mendeteksi adanya aktivitas port scanning hari ini</p>
      <p>Baris bewarna kuning: Aktivitas port scanning yang sudah lama terdeteksi oleh sistem</p>
    </div>
    <?php if ($data["message"]) : ?>
      <div class="alert alert-success" role="alert">
        <div class="row">
          <div class="col-10">
            <?= $data["message"]; ?>
          </div>
          <div class="col">
            <div class="float-end">
              <button type="button" class="btn btn-danger remove">Hapus Filter</button>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
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