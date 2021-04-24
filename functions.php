<?php
$conn = mysqli_connect("localhost", "****", "****", "sdn_port_scanning");

function export_log()
{
  $logs = query("select * from `Log` inner join `Teknik` on `Log`.`IdT` = `Teknik`.`IdT` order by `Log`.`idL` desc");
  $i = 0;
  foreach ($logs as $log) {
    $ports = getPort($log["IdL"]);
    $logs[$i++]["Ports"] = $ports;
  }
  return $logs;
}

function getPort($idLog)
{
  $query = "select `Port` from `LogPort` inner join `Log` on `LogPort`.`IdL` = `Log`.`IdL` where `Log`.`IdL` = " . $idLog;
  $result = query($query, "Port");
  $portID = array_map('intval', $result);
  sort($portID);
  $ports = '';
  $firstPort = true;
  foreach ($portID as $port) {
    if (!$firstPort) {
      $ports .= ", ";
    }
    $ports .= $port;
    $firstPort = false;
  }
  return $ports;
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
