<?php
class Log_Port
{
  private $table;
  private $db;
  private $ports;

  public function __construct()
  {
    $this->table = 'log_port';
    $this->db = new Database;

    $this->idLog = 'idL';
    $this->ports = 'port';
  }

  public function getPort($idLog)
  {
    $query = "select `$this->ports` from `$this->table` where `$this->table`.`$this->idLog` = " . $idLog;
    $result = $this->db->query($query, $this->ports);
    $portID = array_map('intval', $result);
    return $portID;
  }
}
