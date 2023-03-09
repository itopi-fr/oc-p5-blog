<?php

namespace App\Controller\Form;

use App\Controller\FileController;
use App\Controller\MainController;
use App\Entity\File;

class FormController extends MainController
{
    private array $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    private array $docExtensions = ['pdf', 'doc', 'docx'];
    private array $imageMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    private array $docMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    private int $photoMaxSize = 2097152; // 2 Mo
    private int $cvMaxSize = 5242880; // 5 Mo
    private int $avatarMaxSize = 524288; // 512 Ko
    private string $photoPath = 'public/upload/owner/';

    private string $cvPath = 'public/upload/owner/';

    private string $avatarPath = 'public/upload/user/';



    public function __construct()
    {
        parent::__construct();
    }

    protected function checkFileIsUploaded($posted_file)
    {
        return !empty($posted_file)
            && !empty($posted_file['name'])
            && !empty($posted_file['type'])
            && !empty($posted_file['tmp_name'])
            && !empty($posted_file['size']);
    }


    /**
     * Check if file size is ok based on its type
     * @param array $posted_file
     * @param int $file_size_max
     * @return bool
     */
    protected function checkFileSize($posted_file, $file_size_max)
    {
        return $posted_file['size'] <= $file_size_max;
    }

    /**
     * Check if file is an image based on its extension and mime type
     * @param array $posted_file
     * @return bool
     */
    protected function checkFileIsImage($posted_file)
    {
        $ext = pathinfo($posted_file['name'], PATHINFO_EXTENSION);
        return in_array($ext, $this->imageExtensions) && in_array($posted_file['type'], $this->imageMimeTypes);
    }

    /**
     * Check if file is a document based on its extension and mime type
     * @param array $posted_file
     * @return bool
     */
    protected function checkFileIsDoc($posted_file)
    {
        $ext = pathinfo($posted_file['name'], PATHINFO_EXTENSION);
        return in_array($ext, $this->docExtensions) && in_array($posted_file['type'], $this->docMimeTypes);
    }

    /**
     * Build destination path of the file based on its type
     * @param array $posted_file
     * @param string $file_type
     */
    protected function buildFileDestPath($posted_file, $file_type)
    {
        $posted_file['unique-name'] = pathinfo($posted_file['name'], PATHINFO_FILENAME) . '_' . $this->generateKey(3);
        $posted_file['ext'] = pathinfo($posted_file['name'], PATHINFO_EXTENSION);
        $base_path = $_SERVER['DOCUMENT_ROOT'] . '/';

        switch ($file_type) {
            case 'photo':
                $posted_file['dest-path'] = $base_path . $this->photoPath . $posted_file['unique-name'] . '.' . $posted_file['ext'];
                $posted_file['url'] = "/" . $this->photoPath . $posted_file['unique-name'] . '.' . $posted_file['ext'];
                break;
            case 'cv':
                $posted_file['dest-path'] = $base_path . $this->cvPath . $posted_file['unique-name'] . '.' . $posted_file['ext'];
                $posted_file['url'] = "/" . $this->cvPath . $posted_file['unique-name'] . '.' . $posted_file['ext'];
                break;
            case 'avatar':
                $posted_file['dest-path'] = $base_path . $this->avatarPath . $posted_file['unique-name'] . '.' . $posted_file['ext'];
                $posted_file['url'] = "/" . $this->avatarPath . $posted_file['unique-name'] . '.' . $posted_file['ext'];
                break;
        }
        return $posted_file;
    }

    protected function treatFile($posted_file, $file_type)
    {
        $posted_file = $this->buildFileDestPath($posted_file, $file_type);
        $fileCtrl = new FileController();
        $file = $fileCtrl->uploadFile($posted_file);
        $insert = $fileCtrl->insertFile($file);
        return $fileCtrl->getFileById((int) $insert);
    }

    /**
     * ---------------------------------------------------- Getters ----------------------------------------------------
     */

    /**
     * @return int
     */
    protected function getPhotoMaxSize(): int
    {
        return $this->photoMaxSize;
    }

    /**
     * @return int
     */
    protected function getCvMaxSize(): int
    {
        return $this->cvMaxSize;
    }

    /**
     * @return int
     */
    protected function getAvatarMaxSize(): int
    {
        return $this->avatarMaxSize;
    }

    /**
     * @return string
     */
    protected function getPhotoPath(): string
    {
        return $this->photoPath;
    }

    /**
     * @return string
     */
    protected function getCvPath(): string
    {
        return $this->cvPath;
    }



    /**
     * @return string
     */
    protected function getAvatarPath(): string
    {
        return $this->avatarPath;
    }

}