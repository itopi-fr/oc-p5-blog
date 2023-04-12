<?php

namespace App\Model;


use App\Controller\MainController;
use App\Database\Connection;
use App\Entity\File;
use Exception;

class FileModel extends Connection
{

    private MainController $mc;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mc = new MainController();
    }

    /**
     * Returns a File instance based on its id
     * @param int $fileId
     * @return bool
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
    public function getFileById(int $fileId): File|null
    {
        $sql = "SELECT * FROM file WHERE id =?";
        $result = $this->getSingleAsFile($sql, [$fileId]);
        return $result;
    }

    /**
     * Insert a file in the database
     * @param File $file
     * @return string|null
     */
    public function insertFile(File $file): string|null
    {
        try {
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
            return (is_null($result) === false) ? $result : null;
        } catch (Exception $e) {
            $this->mc->dump($e);
            return null;
        }
    }

    /**
     * Delete a file from the database based on its id
     */
    public function deleteFileById(int $fileId)
    {
        $sql = "DELETE FROM file WHERE id = ?";
        return $this->delete($sql, [$fileId]);
    }

}