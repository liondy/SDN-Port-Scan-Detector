<?php
class Home extends Controller
{

  private $filters;

  public function index($page = 1, $src = "", $dst = "")
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

    //set batas pagination
    $pagination = 5;

    //initiate model log port
    $this->model('Log_Port');
    $log_model = $this->model('Log');

    //export all log
    $data["logs"] = $log_model->exportLog();

    //check filters
    $data["filtered"] = $data["logs"];
    if (!empty($this->filters)) {
      $data["filtered"] = $this->filtered($data["logs"], $this->filters);
    }

    //will be used in header for filter
    $data["sources"] = $log_model->extractAllSource();
    $data["destinations"] = $log_model->extractAllDestination();

    //for pagination, will be used in footer
    $jumlah_data = count($data["filtered"]);
    $data["total_halaman"] = ceil($jumlah_data / $pagination);
    if ($jumlah_data == 0) {
      $page = 0;
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
    $filtered = array_intersect_key($log, $filter);
    return $filter == $filtered;
  }

  private function paginate($data, $halaman_awal, $batas)
  {
    return array_slice($data, $halaman_awal, $batas);
  }
}
