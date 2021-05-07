<?php
class Teknik
{
  private $table;
  private $db;

  public function __construct()
  {
    $this->table = 'teknik';
    $this->db = new Database;
  }

  public function getAllTeknik()
  {
    $query = "select `nama_teknik` from `$this->table`";
    $result = $this->db->query($query);
    return $result;
  }
}
