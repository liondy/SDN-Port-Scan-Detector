<?php
class Home extends Controller
{

  private $filters;

  public function index($page = 1, $src = "", $dst = "")
  {
    echo "<br>controller home: <br>";
    if ($src != "") {
      $this->filters["src"] = $src;
      $data["src"] = $src;
    }
    if ($dst != "") {
      $this->filters["dst"] = $dst;
      $data["dst"] = $src;
    }
    var_dump($this->filters);
    //set batas pagination
    $pagination = 5;

    //initiate model log port
    $this->model('Log_Port');
    $log_model = $this->model('Log');

    //export all log
    $data["logs"] = $log_model->exportLog();

    //will be used in header for filter
    $data["sources"] = $log_model->extractAllSource();
    $data["destinations"] = $log_model->extractAllDestination();

    //for pagination, will be used in footer
    $jumlah_data = count($data["logs"]);
    $data["total_halaman"] = ceil($jumlah_data / $pagination);
    $data["halaman"] = $page;
    $data["previous"] = $data["halaman"] - 1;
    $data["next"] = $data["halaman"] + 1;

    //paginate the data, will be used in content table logs
    $data["logs"] = $this->paginate($data["logs"], $page, $pagination);
    $data["iteration"] = ($page > 1) ? ($page * $pagination) - $pagination : 0;

    //render view the paginated logs
    $this->view('templates/header', $data);
    $this->view('home/index', $data);
    $this->view('templates/footer', $data);
  }

  private function paginate($data, $halaman_awal, $batas)
  {
    return array_slice($data, $halaman_awal, $batas);
  }
}
