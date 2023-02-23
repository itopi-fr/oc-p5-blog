<?php

namespace App\Model;

use App\Database\Connection;

class FileModel
{
    private Connection $db;

    public function __construct()
    {
        $this->db = new Connection();
    }

    public function getFileById($id)
    {
        $sql = "SELECT * FROM file WHERE id = " . $id;
        return $this->db->getSingleAsClass($sql, 'App\Entity\File');
    }
}