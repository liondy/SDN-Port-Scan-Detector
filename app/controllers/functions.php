<?php

function export_log($data, $halaman_awal, $batas, $filters)
{
  $filters =  (array)$filters;
  $data = array_reverse($data);
  $data = array_slice($data, $halaman_awal, $batas);
  $logs = [];
  $i = 0;
  foreach ($data as $curTimestamp) {
    $timestamp = $curTimestamp["timestamp"]; #extract each timestamp
    if (count($filters) > 1) {
      $curLogs = getLogs($timestamp, $filters); #getLogByTimestamp
    } else {
      $curLogs = getLogs($timestamp);
    }
    $logs[] = $curLogs[0]; #insert the first log to our presentation logs
    $j = 0;
    $curPorts = [];
    $ports = '';
    foreach ($curLogs as $log) {
      $curPort = getPort($log["idL"]);
      foreach ($curPort as $port) {
        $curPorts[] = $port;
      }
    }
    sort($curPorts);
    foreach ($curPorts as $port) {
      if ($j++ != 0) {
        $ports .= ", ";
      }
      $ports .= $port;
    }
    $logs[$i++]["ports"] = $ports;
  }
  return $logs;
}

function getLogs($timestamp, $filters = null)
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
  $curLogs = query($get_logs_by_timestamp);
  return $curLogs;
}

function getPort($idLog)
{
  $query = "select `port` from `log_port` inner join `log` on `log_port`.`idL` = `log`.`idL` where `log`.`idL` = " . $idLog;
  $result = query($query, "port");
  $portID = array_map('intval', $result);
  return $portID;
}

function getIP($type)
{
  if (isset($type)) {
    $query = "select distinct(" . $type . ") from `log`";
    $result = query($query);
    var_dump($result);
    return $result;
  }
}

function query($query, $key = null)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    if ($key) {
      $rows[] = $row[$key];
    } else {
      $rows[] = $row;
    }
  }
  return $rows;
}
