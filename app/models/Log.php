<?php
class Log
{
  private $table;
  private $table_log_port;
  private $table_teknik;
  private $db;

  private $idLog;
  private $timestamp;
  private $source;
  private $destination;
  private $idTeknik;
  // private $ports;
  // private $teknik;
  // private $protocol;
  // private $flags;

  public function __construct($table_log_port)
  {
    $this->table = 'log';
    $this->db = new Database;
    $this->table_log_port = $table_log_port;

    $this->idLog = 'idL';
    $this->timestamp = 'timestamp';
    $this->source = 'source';
    $this->destination = 'destination';
    $this->idTeknik = 'idT';
  }

  public function exportLog()
  {
    $all_timestamps = $this->getAllTimestamps();
    $logs = [];
    $i = 0;
    foreach ($all_timestamps as $curTimestamp) {
      $timestamp = $curTimestamp[$this->timestamp]; #extract each timestamp

      $curLogs = $this->getLogs($timestamp);

      $logs[] = $curLogs[0]; #insert the first log to our presentation logs

      $j = 0;
      $curPorts = [];
      $ports = '';

      //for each log, find all port
      foreach ($curLogs as $log) {
        $curPort = $this->table_log_port->getPort($log[$this->idLog]);

        //for each port in each log, add them to curPorts
        foreach ($curPort as $port) {
          $curPorts[] = $port;
        }
      }

      //sort all port from current timestamp ascending
      sort($curPorts);

      //make presentation log pretty by separating it with comma
      foreach ($curPorts as $port) {
        if ($j++ != 0) {
          $ports .= ", ";
        }
        $ports .= $port;
      }

      //add presentation port from current timestamp to logs
      $logs[$i++]["ports"] = $ports;
    }
    return $logs;
  }

  private function getAllTimestamps()
  {
    $timestamps = $this->db->query("select DISTINCT(`$this->table`.`$this->timestamp`) from `$this->table`");
    return array_reverse($timestamps);
  }

  private function getLogs($timestamp, $filters = null)
  {
    $condition = "where `$this->table`.`$this->timestamp` = '" . $timestamp . "'";
    // echo $timestamp . "<br>";
    if ($filters) {
      if ($filters[$this->source] != "") {
        $condition .= " and `$this->table`.`$this->source` = '" . $filters[$this->source] . "'";
      }
      if ($filters[$this->destination] != "") {
        $condition .= " and `$this->table`.`$this->destination` = '" . $filters[$this->destination] . "'";
      }
    }
    $get_logs_by_timestamp = "select * from `$this->table` inner join `teknik` on `$this->table`.`idT` = `teknik`.`idT` " . $condition . " order by `$this->table`.`$this->idLog`";
    $curLogs = $this->db->query($get_logs_by_timestamp);
    return $curLogs;
  }

  private function getIP($type)
  {
    if (isset($type)) {
      $query = "select distinct(" . $type . ") from `$this->table`";
      $result = $this->db->query($query);
      var_dump($result);
      return $result;
    }
  }
}
