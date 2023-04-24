<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Res;
use App\Model\FileModel;
use Exception;

class FileController extends MainController
{
    private Res $res;
    private array $filePosted;
    private File $file;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

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


    public function getFileById(int $fileId): File|bool
    {
        return (new FileModel())->getFileById($fileId);
    }

    /**
     * Upload file to the file system
     * @param array $user_file
     * @return File
     * @throws Exception
     */
    public function uploadFile(array $user_file): Res
    {
        try {
            $this->filePosted = $user_file;

            if (empty($this->filePosted) === true) {
                return $this->res->ko('upload-file', 'upload-file-ko-no-file');
            }

            // Upload.
            $uploaded = move_uploaded_file($this->filePosted['tmp_name'], $this->filePosted['dest-path']);

            if ($uploaded) {
                // Build File object.
                $this->file = $this->buildFileObjectFromPostedFile($this->filePosted);
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
     * @param File $file
     * @return string|null
     */
    public function insertFile(File $file): string|null
    {
        return (new FileModel())->insertFile($file);
    }

    /**
     * Delete file from the database
     * @param int $fileId
     * @return Res
     */
    public function deleteFileById(int $fileId): Res
    {
        try {
            $return = [];

            // Check that this file exists in the database.
            if (!(new FileModel())->fileExistsById($fileId)) {
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

}