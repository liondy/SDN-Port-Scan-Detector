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
      //page
      $part = $_GET["page"];
      $page = (int)$this->filterURL($part);
      if ($page == 0) {
        $page = 1;
      }
      $url[] = $page;

      //source
      $part = $_GET["src"];
      $url[] = $this->filterURL($part);

      //destination
      $part = $_GET["dst"];
      $url[] = $this->filterURL($part);

      //tcp
      $part = $_GET["tcp"];
      $string = $this->filterURL($part);
      $url[] = $string === 'true' ? true : false;

      //udp
      $part = $_GET["udp"];
      $string = $this->filterURL($part);
      $url[] = $string === 'true' ? true : false;

      //stealth
      $part = $_GET["syn"];
      $string = $this->filterURL($part);
      $url[] = $string === 'true' ? true : false;

      //connect
      $part = $_GET["con"];
      $string = $this->filterURL($part);
      $url[] = $string === 'true' ? true : false;

      //null
      $part = $_GET["null"];
      $string = $this->filterURL($part);
      $url[] = $string === 'true' ? true : false;

      //fin
      $part = $_GET["fin"];
      $string = $this->filterURL($part);
      $url[] = $string === 'true' ? true : false;

      //xmas
      $part = $_GET["xmas"];
      $string = $this->filterURL($part);
      $url[] = $string === 'true' ? true : false;

      //ack/window
      $part = $_GET["ack"];
      $string = $this->filterURL($part);
      $url[] = $string === 'true' ? true : false;

      //maimon
      $part = $_GET["maimon"];
      $string = $this->filterURL($part);
      $url[] = $string === 'true' ? true : false;

      //udp scan
      $part = $_GET["udpS"];
      $string = $this->filterURL($part);
      $url[] = $string === 'true' ? true : false;

      //date start
      $part = $_GET["ds"];
      $string = $this->filterURL($part);
      $url[] = $string;
      //minute start
      $part = $_GET["ms"];
      $string = $this->filterURL($part);
      $url[] = $string;
      //date finish
      $part = $_GET["df"];
      $string = $this->filterURL($part);
      $url[] = $string;
      //minute finish
      $part = $_GET["mf"];
      $string = $this->filterURL($part);
      $url[] = $string;
    }
    return $url;
  }

  private function filterURL($url)
  {
    return filter_var($url, FILTER_SANITIZE_URL);
  }
}
