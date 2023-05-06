<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Res;
use App\Model\FileModel;
use Exception;

/**
 * Class FileController - File functions.
 */
class FileController extends MainController
{
    /**
     * @var Res
     */
    private Res $res;

    /**
     * @var File
     */
    private File $file;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
    }


    /**
     * Builds a file object from a posted file.
     *
     * @param $filePosted - The posted file.
     * @return File
     */
    public function buildFileObjectFromPostedFile($filePosted): File
    {
        $this->res = new Res();
        $this->file = new File();
        $this->file->setTitle($filePosted['name']);
        $this->file->setUrl($filePosted['url']);
        $this->file->setExt($filePosted['ext']);
        $this->file->setMime($filePosted['type']);
        $this->file->setSize($filePosted['size']);
        return $this->file;
    }


    /**
     * Returns a file object based on its id.
     *
     * @param int $fileId - The id of the file.
     * @return File|bool
     */
    public function getFileById(int $fileId): File|bool
    {
        return (new FileModel())->getFileById($fileId);
    }


    /**
     * Upload a file to the file system
     *
     * @param array $user_file - The file to upload.
     * @return Res
     */
    public function uploadFile(array $user_file): Res
    {
        try {
            if (empty($user_file) === true) {
                return $this->res->ko('upload-file', 'upload-file-ko-no-file');
            }

            // Upload.
            $uploaded = move_uploaded_file($user_file['tmp_name'], $user_file['dest-path']);

            if ($uploaded === true) {
                // Build File object.
                $this->file = $this->buildFileObjectFromPostedFile($user_file);
                return $this->res->ok('upload-file', 'upload-file-ok', $this->file);
            } else {
                return $this->res->ko('upload-file', 'upload-file-ko');
            }
        } catch (Exception $e) {
            return $this->res->ko('upload-file', 'upload-file-ko', $e);
        }
    }

    /**
     * Insert file in the database
     *
     * @param File $file - The file to insert.
     * @return string|null
     */
    public function insertFile(File $file): string|null
    {
        return (new FileModel())->insertFile($file);
    }


    /**
     * Delete file from the database
     *
     * @param int $fileId - The id of the file to delete.
     * @return Res
     */
    public function deleteFileById(int $fileId): Res
    {
        try {
            // Check that this file exists in the database.
            if ($this->fileExistsById($fileId) === false) {
                return $this->res->ko('delete-file', 'delete-file-ko-not-found');
            }

            // Get file from the database.
            $file = $this->getFileById($fileId);

            // Delete file from the database.
            $result_db = (new FileModel())->deleteFileById($fileId);
            if ($result_db === null) {
                return $this->res->ko('delete-file', 'delete-file-ko-not-deleted');
            }

            // Delete file from the file system.
            $result_fs = unlink($this->sGlob->getServer('DOCUMENT_ROOT') . $file->getUrl());
            if ($result_fs) {
                return $this->res->ok('delete-file', 'delete-file-ok');
            } else {
                return $this->res->ko('delete-file', 'delete-file-ko-not-deleted');
            }
        } catch (Exception $e) {
            return $this->res->ko('delete-file', 'delete-file-ko-not-deleted', $e);
        }
    }


    /**
     * Check that a file exists in the database provided its id.
     *
     * @param int $fileId - The id of the file to check.
     * @return bool
     */
    public function fileExistsById(int $fileId): bool
    {
        return (new FileModel())->fileExistsById($fileId);
    }


}
