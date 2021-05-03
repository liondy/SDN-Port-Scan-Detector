<?php
class Log
{
  private $table = 'log';
  private $db;
  private $timestamp;
  private $source;
  private $destination;
  private $ports;
  private $teknik;
  private $protocol;
  private $flags;

  public function __construct()
  {
    $this->db = new Database;
  }

  public function exportLog()
  {
    $all_timestamps = $this->getAllTimestamps();
    $logs = [];
    $i = 0;
    foreach ($all_timestamps as $curTimestamp) {
      $timestamp = $curTimestamp["timestamp"]; #extract each timestamp

      $curLogs = $this->getLogs($timestamp);

      $logs[] = $curLogs[0]; #insert the first log to our presentation logs

      $j = 0;
      $curPorts = [];
      $ports = '';

      //for each log, find all port
      foreach ($curLogs as $log) {
        $curPort = $this->getPort($log["idL"]);

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
    $timestamps = $this->db->query("select DISTINCT(`log`.`timestamp`) from `$this->table`");
    $timestamps = array_reverse($timestamps);
    return $timestamps;
  }

  private function getLogs($timestamp, $filters = null)
  {
    $condition = "where `log`.`timestamp` = '" . $timestamp . "'";
    // echo $timestamp . "<br>";
    if ($filters) {
      if ($filters["source"] != "") {
        $condition .= " and `log`.`source` = '" . $filters["source"] . "'";
      }
      if ($filters["destination"] != "") {
        $condition .= " and `log`.`destination` = '" . $filters["destination"] . "'";
      }
    }
    $get_logs_by_timestamp = "select * from `log` inner join `teknik` on `log`.`idT` = `teknik`.`idT` " . $condition . " order by `log`.`idL`";
    $curLogs = $this->db->query($get_logs_by_timestamp);
    return $curLogs;
  }

  private function getPort($idLog)
  {
    $query = "select `port` from `log_port` inner join `log` on `log_port`.`idL` = `log`.`idL` where `log`.`idL` = " . $idLog;
    $result = $this->db->query($query, "port");
    $portID = array_map('intval', $result);
    return $portID;
  }

  private function getIP($type)
  {
    if (isset($type)) {
      $query = "select distinct(" . $type . ") from `log`";
      $result = $this->db->query($query);
      var_dump($result);
      return $result;
    }
  }
}
