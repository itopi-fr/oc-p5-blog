<?php

namespace App\Controller;

use App\Entity\File;

class FileController extends MainController
{
    private array $filePosted;
    private File $file;
    private array $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    private array $imageMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    private array $docExtensions = ['pdf', 'doc', 'docx'];
    private array $docMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    private int $avatarMaxSize = 524288; // 512 Ko
    private string $avatarPath = 'public/upload/user/';
    private int $photoMaxSize = 2097152; // 2 Mo
    private string $photoPath = 'public/upload/owner/';
    private int $cvMaxSize = 5242880; // 5 Mo
    private string $cvPath = 'public/upload/owner/';



    public function __construct()
    {
        parent::__construct();
    }

    private function buildFileObject()
    {
        $this->file = new File();
        $this->file->setTitle($this->filePosted['name']);
        $this->file->setUrl($this->filePosted['path']);
        $this->file->setExt($this->filePosted['ext']);
        $this->file->setMime($this->filePosted['type']);
        $this->file->setSizeBytes($this->filePosted['size']);
        return $this->file;
    }

    /**
     * Check if file is an image based on its extension and mime type
     * @param array $file_ext
     * @param array $mime_type
     * @return string
     */
    public function checkFileIsImage($file_ext, $mime_type)
    {
        return in_array($file_ext, $this->imageExtensions) && in_array($mime_type, $this->imageMimeTypes) ? 'file-is-image' : 'file-not-image';
    }

    /**
     * Check if file is a document based on its extension and mime type
     * @param array $file_ext
     * @param array $mime_type
     * @return string
     */
    public function checkFileIsDoc($file_ext, $mime_type)
    {
        return in_array($file_ext, $this->docExtensions) && in_array($mime_type, $this->docMimeTypes) ? 'file-is-doc' : 'file-not-doc';
    }

    /**
     * Check if file size is ok based on its type
     * @param string $file_type
     * @param int $file_size
     * @return string
     */
    public function checkFileSize($file_type, $file_size)
    {
        if (!empty($file_type)) {
            return match ($file_type) {
                'avatar' => $file_size <= $this->avatarMaxSize ? 'file-size-ok' : 'file-size-too-big',
                'photo' => $file_size <= $this->photoMaxSize ? 'file-size-ok' : 'file-size-too-big',
                'cv' => $file_size <= $this->cvMaxSize ? 'file-size-ok' : 'file-size-too-big',
                default => 'file-size-error',
            };
        } else {
            return 'file-size-error';
        }
    }

    /**
     * Upload file to the file system
     * @param array $user_file
     * @param string $file_type
     */
    public function uploadFile(array $user_file, string $file_type)
    {
        /* -------------------------------------------------------
        * Beaucoup de trucs à bouger dans FormUserProfile
        * uploadFile doit juste uploader un objet File
        * Checks à faire dans FormUserProfile
        * -------------------------------------------------------
        *
        */
        $this->filePosted = $user_file;
        $this->filePosted['unique-name'] = pathinfo($this->filePosted['name'], PATHINFO_FILENAME) . '_' . $this->generateKey(8);
        $this->filePosted['ext'] = pathinfo($this->filePosted['name'], PATHINFO_EXTENSION);
        $this->filePosted['dest-path'] = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->avatarPath . $this->filePosted['name'];
        $msg = [];
        if (empty($this->filePosted)) $msg['error'] = 'no-image';

        if (!empty($this->filePosted)) {

            if ($file_type === 'avatar') {
                // Checks
                if ($this->checkFileIsImage($this->filePosted['ext'], $this->filePosted['type']) !== 'file-is-image') $msg['error'] ='file-not-image';
                if ($this->checkFileSize($file_type, $this->filePosted['size']) !== 'file-size-ok') $msg['error'] = 'file-size-too-big';
                if (!empty($msg['error'])) return $msg;

                // Upload
                $uploaded = move_uploaded_file($this->filePosted['tmp_name'], $this->filePosted['dest-path']);
                if ($uploaded) {
                    $this->filePosted['path'] = $this->avatarPath . $this->filePosted['unique-name'] . '.' . $this->filePosted['ext'];

                    // Build File object
                    $this->buildFileObject();
                    $msg['file'] = $this->file;

                    // Save File object in DB

                } else {
                    $msg['error'] = 'file-upload-fail';
                }
            }

            else {
                $msg['error'] = 'file-type-error';
            }
        }
        else {
            $msg['error'] = 'no-image';
        }
        return $msg;
    }

}