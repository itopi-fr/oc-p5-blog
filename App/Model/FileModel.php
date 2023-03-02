<?php

namespace App\Model;


use App\Entity\File;

class FileModel extends \App\Database\Connection
{


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns a File instance based on its id
     * @param int $fileId
     * @return File
     */
    public function getFileById($fileId): File
    {
        $sql = "SELECT * FROM file WHERE id =?";
        return $this->getSingleAsClass($sql, [$fileId], 'App\Entity\File');
    }
}