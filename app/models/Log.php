<?php
class Log
{
  private $table;
  private $table_log_port;
  private $db;
  private $logs;

  public function __construct()
  {
    $this->table = 'log';
    $this->db = new Database;
    $this->table_log_port = new Log_Port;
    $this->logs = [];
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
        $curPort = $this->table_log_port->getPort($log["idL"]);

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
    $this->logs = $logs;
    return $logs;
  }

  public function extractAllSource()
  {
    return array_unique(array_column($this->logs, "source"));
  }

  public function extractAllDestination()
  {
    return array_unique(array_column($this->logs, "destination"));
  }

  private function getAllTimestamps()
  {
    $timestamps = $this->db->query("select DISTINCT(`$this->table`.`timestamp`) from `$this->table`");
    return array_reverse($timestamps);
  }

  private function getLogs($timestamp, $filters = null)
  {
    if (isset($timestamp)) {
      $timestamp = $this->db->escapeString($timestamp);
      $condition = "where `$this->table`.`timestamp` = '$timestamp'";

      if ($filters) {
        if ($filters["source"] != "") {
          $src = $this->db->escapeString($filters["source"]);
          $condition .= " and `$this->table`.`source` = '$src'";
        }
        if ($filters["destination"] != "") {
          $dst = $this->db->escapeString($filters["destination"]);
          $condition .= " and `$this->table`.`destination` = '$dst'";
        }
      }

      $get_logs_by_timestamp = "select * from `$this->table` inner join `teknik` on `$this->table`.`idT` = `teknik`.`idT` $condition order by `$this->table`.`idL`";
      $curLogs = $this->db->query($get_logs_by_timestamp);
      return $curLogs;
    }
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
