<?php

namespace App\Model;


class FileModel extends \App\Database\Connection
{


    public function __construct()
    {
        parent::__construct();
    }

    public function getFileById($id)
    {
        $sql = "SELECT * FROM file WHERE id = " . $id;
        return $this->getSingleAsClass($sql, 'App\Entity\File');
    }
}