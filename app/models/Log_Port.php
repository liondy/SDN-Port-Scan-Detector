<?php
class Log_Port
{
  private $table;
  private $db;

  public function __construct()
  {
    $this->table = 'log_port';
    $this->db = new Database;
  }

  public function getPort($idLog)
  {
    if (isset($idLog)) {
      $idLog = $this->db->escapeString($idLog);
      $query = "select `port` from `$this->table` where `$this->table`.`idL` = " . $idLog;
      $result = $this->db->query($query, "port");
      $portID = array_map('intval', $result);
      return $portID;
    }
  }
}
