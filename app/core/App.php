<?php
class App
{
  protected $controller = 'home';
  protected $method = 'index';
  protected $params = [];

  public function __construct()
  {
    $url = $this->parseURL();

    //controller
    if (file_exists('../app/controllers/' . $url[0] . '.php')) {
      $this->controller = $url[0];
      unset($url[0]);
    }

    require_once '../app/controllers/' . $this->controller . '.php';
    $this->controller = new $this->controller;

    //method
    if (isset($url[1])) {
      if (method_exists($this->controller, $url[1])) {
        $this->method = $url[1];
        unset($url[1]);
      }
    }

    //params
    if (!empty($url)) {
      $this->params = $url;
    }

    // var_dump($this->params);

    //execute controller & method with params if exist
    call_user_func_array([$this->controller, $this->method], $this->params);
  }

  private function parseURL()
  {
    $url = [];
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
      $part = $_GET["page"];
      if ($part == "" || is_string($part)) {
        $part = 1;
      }
      $url[] = $this->filterURL($part);
      $part = $_GET["src"];
      $url[] = $this->filterURL($part);
      $part = $_GET["dst"];
      $url[] = $this->filterURL($part);
    }
    return $url;
  }

  private function filterURL($url)
  {
    return filter_var($url, FILTER_SANITIZE_URL);
  }
}
