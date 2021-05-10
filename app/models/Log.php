<?php
class Log
{
  private $table;
  private $table_log_port;
  private $db;

  public function __construct()
  {
    $this->table = 'log';
    $this->db = new Database;
    $this->table_log_port = new Log_Port;
  }

  public function exportLog($filters = [])
  {
    $all_timestamps = $this->getAllTimestamps($filters);
    $logs = [];
    $i = 0;
    foreach ($all_timestamps as $curTimestamp) {
      $timestamp = $curTimestamp; #extract each timestamp

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
    $query = "select distinct(`$this->table`.`source`) from $this->table";
    return $this->db->query($query);
    // return array_unique(array_column($this->logs, "source"));
  }

  public function extractAllDestination()
  {
    $query = "select distinct(`$this->table`.`destination`) from $this->table";
    return $this->db->query($query);
    // return array_unique(array_column($this->logs, "destination"));
  }

  private function getAllTimestamps($filters = [])
  {
    // var_dump($filters);
    // echo "<br>" . count($filters) . "<br>";
    $query = "select `$this->table`.`idL`, `$this->table`.`timestamp` from `$this->table` inner join `teknik` on `$this->table`.`idT` = `teknik`.`idT` inner join `log_port` on `log_port`.`idL` = `log`.`idL`";
    if (!empty($filters)) {
      $where = " where ";
      $firstFilter = true;
      foreach (array_keys($filters) as $keyFilter) {
        if (!$firstFilter) {
          $where .= " and ";
        }
        if ($keyFilter === "nama_teknik") {
          $firstTeknik = true;
          $queryTeknik = "";
          if (count($filters[$keyFilter]) > 1) {
            $queryTeknik .= "(";
          }
          foreach ($filters[$keyFilter] as $teknik) {
            if (!$firstTeknik) {
              $queryTeknik .= " or ";
            }
            $queryTeknik .= $keyFilter . " = " . "'$teknik'";
            $firstTeknik = false;
          }
          if (count($filters[$keyFilter]) > 1) {
            $queryTeknik .= ")";
          }
          $where .= $queryTeknik;
        } else if ($keyFilter == "timestamp") {
          $where .= $keyFilter . " BETWEEN '" . $filters[$keyFilter][0] . "' AND '" . $filters[$keyFilter][1] . "'";
        } else if ($keyFilter !== "port") {
          $where .= $keyFilter . " = " . "'$filters[$keyFilter]'";
        }
        // echo $keyFilter . "<br>";
        $firstFilter = false;
      }
      $query .= $where;
      // for ($i = 0; $i < count($filters); $i++) {
      //   echo array_keys($filters) . "<br>";
      // }
    }
    $query .= " order by `$this->table`.`idL`";
    // echo "<br><br>" . $query . "<br>";
    $timestamps = $this->db->query($query);
    $distinctTimestamps = $this->distinct($timestamps);
    // var_dump($timestamps);
    return $distinctTimestamps;
  }

  private function distinct($data)
  {
    $distinctTimestamps = [];
    foreach ($data as $log) {
      $curTimestamp = $log["timestamp"];
      if (!in_array($curTimestamp, $distinctTimestamps["timestamp"])) {
        $distinctTimestamps["timestamp"][] = $curTimestamp;
      }
    }
    return array_reverse($distinctTimestamps["timestamp"]);
  }

  private function getLogs($timestamp)
  {
    if (isset($timestamp)) {
      $timestamp = $this->db->escapeString($timestamp);
      $condition = "where `$this->table`.`timestamp` = '$timestamp'";

      $get_logs_by_timestamp = "select * from `$this->table` inner join `teknik` on `$this->table`.`idT` = `teknik`.`idT` $condition order by `$this->table`.`idL`";
      $curLogs = $this->db->query($get_logs_by_timestamp);
      return $curLogs;
    }
  }

  public function recount()
  {
    $query = "select distinct(`log`.`timestamp`) from `log`";
    $logs = $this->db->query($query);
    $result = count($logs);
    return $result;
  }
}
