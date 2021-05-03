<?php
class Home extends Controller
{
  public function index()
  {
    $data = $this->model('Log')->exportLog();
    $this->view('templates/header');
    $this->view('home/index', $data);
    $this->view('templates/footer');
  }
}
