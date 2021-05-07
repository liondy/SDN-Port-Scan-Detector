<?php
class Home extends Controller
{

  private $filters;

  public function index($page = 1, $src = "", $dst = "", $tcp = false, $udp = false, $syn = false, $con = false, $null = false, $fin = false, $xmas = false, $ack = false, $maimon = false, $udpS = false)
  {
    //insert filters
    if ($src != "") {
      $this->filters["source"] = $src;
      $data["source"] = $src;
    }
    if ($dst != "") {
      $this->filters["destination"] = $dst;
      $data["destination"] = $src;
    }

    if (!$tcp || !$udp) {
      if ($tcp) {
        $this->filters["protokol"] = "TCP";
        // $data["protokol"] = "TCP";
      } else if ($udp) {
        $this->filters["protokol"] = "UDP";
        // $data["protokol"] = "UDP";
      }
    }

    if ((!$syn || !$con || !$null || !$fin || !$xmas || !$ack || !$maimon || !$udpS) && ($syn || $con || $null || $fin || $xmas || $ack || $maimon || $udpS)) {
      // if ($syn == false && $con == false && $null == false && $fin == false && $xmas == false && $ack == false && $maimon == false && $udpS == false) {
      // } else {
      $this->filters["nama_teknik"] = [];
      if ($syn) {
        $this->filters["nama_teknik"][] = "STEALTH SCAN";
      }
      if ($con) {
        $this->filters["nama_teknik"][] = "CONNECT SCAN";
      }
      if ($null) {
        $this->filters["nama_teknik"][] = "NULL SCAN";
      }
      if ($fin) {
        $this->filters["nama_teknik"][] = "FIN SCAN";
      }
      if ($xmas) {
        $this->filters["nama_teknik"][] = "XMAS SCAN";
      }
      if ($ack) {
        $this->filters["nama_teknik"][] = "ACK / WINDOW SCAN";
      }
      if ($maimon) {
        $this->filters["nama_teknik"][] = "MAIMON SCAN";
      }
      if ($udpS) {
        $this->filters["nama_teknik"][] = "UDP SCAN";
        // }
      }
    }

    // var_dump($this->filters);

    //set batas pagination
    $pagination = 5;

    //initiate model log port
    $this->model('Log_Port');
    $log_model = $this->model('Log');

    //get all teknik
    $data["teknik"] = $this->model('Teknik')->getAllTeknik();

    //export all log
    if (empty($this->filters)) {
      $data["logs"] = $log_model->exportLog();
    } else {
      //export log with filters
      $data["logs"] = $log_model->exportLog($this->filters);

      $data["message"] = "Filter untuk ";
      $i = 0;
      $firstMessage = true;

      foreach (array_keys($this->filters) as $keyFilter) {
        $jumlah_filter = count($this->filters);
        if ($jumlah_filter > 1 && $i == $jumlah_filter - 1) {
          if ($jumlah_filter > 2) {
            $data["message"] .= ",";
          }
          $data["message"] .= " dan ";
        } else {
          if (!$firstMessage) {
            $data["message"] .= ", ";
          }
        }

        if ($keyFilter === "nama_teknik") {
          $firstTeknik = true;
          $queryTeknik = "Teknik = ";
          $j = 0;

          foreach ($this->filters[$keyFilter] as $teknik) {
            $jumlah_teknik = count($this->filters[$keyFilter]);
            if ($jumlah_teknik > 1 && $j == $jumlah_teknik - 1) {
              if ($jumlah_teknik > 2) {
                $queryTeknik .= ",";
              }
              $queryTeknik .= " dan ";
            } else {
              if (!$firstTeknik) {
                $queryTeknik .= ", ";
              }
            }
            $queryTeknik .= "$teknik";
            $j++;
            $firstTeknik = false;
          }
          $data["message"] .= $queryTeknik;
        } else {
          $data["message"] .= $keyFilter . " = " . $this->filters[$keyFilter];
        }
        $i++;
        $firstMessage = false;
      }
    }
    // var_dump($data["logs"]);

    //check filters
    $data["filtered"] = $data["logs"];
    // if (!empty($this->filters)) {
    //   $data["filtered"] = $this->filtered($data["logs"], $this->filters);
    // }

    //will be used in header for filter
    $data["sources"] = $log_model->extractAllSource();
    $data["destinations"] = $log_model->extractAllDestination();

    //for pagination, will be used in footer
    $jumlah_data = count($data["filtered"]);
    // echo "<br>Jumlah Data: " . $jumlah_data;
    $data["total_halaman"] = ceil($jumlah_data / $pagination);
    if ($jumlah_data == 0) {
      $page = 0;
      $data["nodata"] = "Tidak ada log berdasarkan filter yang dicari";
    } else if ($page > $data["total_halaman"]) {
      $page = 1;
    }
    $data["halaman"] = $page;
    $data["previous"] = $data["halaman"] - 1;
    $data["next"] = $data["halaman"] + 1;

    //paginate the data, will be used in content table logs
    $data["iteration"] = ($page > 1) ? ($page * $pagination) - $pagination : 0;
    $data["filtered"] = $this->paginate($data["filtered"], $data["iteration"], $pagination);

    //render view the paginated logs
    $this->view('templates/header', $data);
    $this->view('home/index', $data);
    $this->view('templates/footer', $data);
  }

  private function filtered($data, $filter)
  {
    $filteredLog = [];
    foreach ($data as $log) {
      if ($this->isValid($log, $filter)) {
        $filteredLog[] = $log;
      }
    }
    return $filteredLog;
  }

  private function isValid($log, $filter)
  {
    // var_dump($log);
    // echo "=====================================================================================================================";
    // var_dump($filter);
    $filtered = array_intersect_key($log, $filter);
    // echo "=====================================================================================================================";
    // var_dump($filter);
    return $filter == $filtered;
  }

  private function paginate($data, $halaman_awal, $batas)
  {
    return array_slice($data, $halaman_awal, $batas);
  }
}
