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

  public function getTeknik($idLog)
  {
  }
}
