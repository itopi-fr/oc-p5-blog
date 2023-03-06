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
     * @return null|object
     */
    public function fileExistsById(int $fileId): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM file WHERE id = ?)";
        $res = $this->exists($sql, [$fileId]);
        return $res;
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

    /**
     * Insert a file in the database
     * @param File $file
     * @return string|null
     */
    public function insertFile(File $file): string|null
    {
        $sql = "INSERT INTO file (title, url, ext, mime, size)
                VALUES (:title, :url, :ext, :mime, :size)";
        $params = [
            'title' => $file->getTitle(),
            'url' => $file->getUrl(),
            'ext' => $file->getExt(),
            'mime' => $file->getMime(),
            'size' => $file->getSize()
        ];
        $result = $this->insert($sql, $params);
        return (!is_null($result)) ? $result : null;
    }

    /**
     * Delete a file from the database based on its id
     */
    public function deleteFileById(int $fileId) {
        $sql = "DELETE FROM file WHERE id = ?";
        return $this->delete($sql, [$fileId]);
    }

}