<?php
$conn = mysqli_connect("localhost", "****", "****", "sdn_port_scanning");

function export_log($data, $halaman_awal, $batas, $filters)
{
  $filters =  (array)$filters;
  $data = array_reverse($data);
  $data = array_slice($data, $halaman_awal, $batas);
  $logs = [];
  $i = 0;
  foreach ($data as $curTimestamp) {
    $timestamp = $curTimestamp["Timestamp"]; #extract each timestamp
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
      $curPort = getPort($log["IdL"]);
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
    $logs[$i++]["Ports"] = $ports;
  }
  return $logs;
}

function getLogs($timestamp, $filters = null)
{
  $condition = "where `Log`.`Timestamp` = '" . $timestamp . "'";
  // echo $timestamp . "<br>";
  if ($filters) {
    if ($filters["source"] != "") {
      $condition .= " and `Log`.`Source` = '" . $filters["source"] . "'";
    }
    if ($filters["destination"] != "") {
      $condition .= " and `Log`.`Destination` = '" . $filters["destination"] . "'";
    }
  }
  $get_logs_by_timestamp = "select * from `Log` inner join `Teknik` on `Log`.`IdT` = `Teknik`.`IdT` " . $condition . " order by `Log`.`idL`";
  $curLogs = query($get_logs_by_timestamp);
  // var_dump($curLogs);
  // echo "<br>";
  return $curLogs;
}

function getPort($idLog)
{
  $query = "select `Port` from `LogPort` inner join `Log` on `LogPort`.`IdL` = `Log`.`IdL` where `Log`.`IdL` = " . $idLog;
  $result = query($query, "Port");
  $portID = array_map('intval', $result);
  return $portID;
}

function getIP($type)
{
  if (isset($type)) {
    $query = "select distinct(" . $type . ") from `Log`";
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
