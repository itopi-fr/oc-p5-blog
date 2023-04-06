<?php

namespace App\Controller;

use App\Entity\File;
use App\Model\FileModel;
use Exception;

class FileController extends MainController
{
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
    public function uploadFile(array $user_file): File
    {
        try {
            $this->filePosted = $user_file;

            if (empty($this->filePosted) === true) {
                throw new Exception('Erreur du fichier posté');
            }

            // Upload
            $uploaded = move_uploaded_file($this->filePosted['tmp_name'], $this->filePosted['dest-path']);


            if ($uploaded) {
                // Build File object
                $this->file = $this->buildFileObjectFromPostedFile($this->filePosted);
                return $this->file;
            } else {
                throw new Exception('Erreur lors de l\'upload du fichier');
            }
        } catch (Exception $e) {
            $this->dump($e);
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
     * @return array $return
     */
    public function deleteFileById(int $fileId): array
    {
        try {
            $return = [];

            // Check that this file exists in the database
            if (!(new FileModel())->fileExistsById($fileId)) {
                $return['err'] = true;
                $return['msg'] = 'Fichier non trouvé dans la base de données';
                return $return;
            }

            // Get file from the database
            $file = $this->getFileById($fileId);

            // Delete file from the database
            $result_db = (new FileModel())->deleteFileById($fileId);
            if (is_null($result_db) === false) {
                $return['err'] = true;
                $return['msg'] = 'Fichier non supprimé de la base de données';
                return $return;
            }

            // Delete file from the file system
            $result_fs = unlink($_SERVER['DOCUMENT_ROOT'] . $file->getUrl());
            if ($result_fs) {
                $return['msg'] = 'Fichier supprimé de la base de données et du système de fichiers';
            } else {
                $return['err'] = true;
                $return['msg'] = 'Fichier non supprimé du système de fichiers';
            }
            return $return;
        }
        catch (Exception $e) {
            throw $e;
        }
    }

}