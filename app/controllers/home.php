<?php
class Home extends Controller
{
  public function index()
  {
    $table_port = $this->model('Log_Port');
    $data = $this->model('Log', $table_port)->exportLog();
    $this->view('templates/header');
    $this->view('home/index', $data);
    $this->view('templates/footer');
  }
}
