<?php

namespace App\Model;


class FileModel extends \App\Database\Connection
{


    public function __construct()
    {
        parent::__construct();
    }

    public function getFileById($fileId)
    {
        $sql = "SELECT * FROM file WHERE id =?";
        return $this->getSingleAsClass($sql, [$fileId], 'App\Entity\File');
    }
}