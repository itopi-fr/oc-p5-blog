<?php

namespace App\Model;

use App\Controller\MainController;
use App\Database\Connection;
use App\Entity\File;
use Exception;

/**
 * Class FileModel - Manages the files in the database
 */
class FileModel extends Connection
{
    /**
     * @var MainController
     */
    private MainController $mainCtr;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mainCtr = new MainController();
    }


    /**
     * Returns a File instance based on its id
     * @param int $fileId - The id of the file
     * @return bool
     */
    public function fileExistsById(int $fileId): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM file WHERE file_id = ?)";
        return $this->exists($sql, [$fileId]);
    }


    /**
     * Returns a File instance based on its id
     * @param int $fileId - The id of the file
     * @return File|null
     */
    public function getFileById(int $fileId): File|null
    {
        $sql = "SELECT * FROM file WHERE file_id =?";
        return $this->getSingleAsFile($sql, [$fileId]);
    }


    /**
     * Insert a file in the database
     * @param File $file - The file to insert
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
            return ($result !== null) ? $result : null;
        } catch (Exception $e) {
            $this->mainCtr->dump($e);
            return null;
        }
    }


    /**
     * Delete a file from the database based on its id
     * @param int $fileId - The id of the file
     * @return bool|null
     */
    public function deleteFileById(int $fileId): bool|null
    {
        $sql = "DELETE FROM file WHERE file_id = ?";
        return $this->delete($sql, [$fileId]);
    }
}
