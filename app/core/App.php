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
      $partTCP = $_GET["tcp"];
      $stringTCP = $this->filterURL($partTCP);
      $url[] = $stringTCP === 'true' ? true : false;

      //udp
      $partUDP = $_GET["udp"];
      $stringUDP = $this->filterURL($partUDP);
      $url[] = $stringUDP === 'true' ? true : false;

      //stealth
      $partSYN = $_GET["syn"];
      $stringSYN = $this->filterURL($partSYN);
      $url[] = $stringSYN === 'true' ? true : false;

      //connect
      $partCon = $_GET["con"];
      $stringCon = $this->filterURL($partCon);
      $url[] = $stringCon === 'true' ? true : false;

      //null
      $partNull = $_GET["null"];
      $stringNull = $this->filterURL($partNull);
      $url[] = $stringNull === 'true' ? true : false;

      //fin
      $partFin = $_GET["fin"];
      $stringFin = $this->filterURL($partFin);
      $url[] = $stringFin === 'true' ? true : false;

      //xmas
      $partXmas = $_GET["xmas"];
      $stringXmas = $this->filterURL($partXmas);
      $url[] = $stringXmas === 'true' ? true : false;

      //ack/window
      $partAck = $_GET["ack"];
      $stringAck = $this->filterURL($partAck);
      $url[] = $stringAck === 'true' ? true : false;

      //maimon
      $partMaimon = $_GET["maimon"];
      $stringMaimon = $this->filterURL($partMaimon);
      $url[] = $stringMaimon === 'true' ? true : false;

      //udp scan
      $partUDPS = $_GET["udpS"];
      $stringUDPS = $this->filterURL($partUDPS);
      $url[] = $stringUDPS === 'true' ? true : false;
    }
    return $url;
  }

  private function filterURL($url)
  {
    return filter_var($url, FILTER_SANITIZE_URL);
  }
}
