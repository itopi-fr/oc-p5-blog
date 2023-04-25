<?php

namespace App\Controller\Form;

use App\Controller\FileController;
use App\Controller\MainController;
use App\Entity\File;
use App\Entity\Res;
use App\Model\PostModel;
use App\Model\UserModel;
use Exception;

class FormController extends MainController
{
    private Res $res;
    private array $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    private array $docExtensions = ['pdf', 'doc', 'docx'];
    private array $imageMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    private array $docMimeTypes = [ 'application/pdf',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    private int $photoMaxSize = 2097152; // 2 Mo.
    private int $postImgMaxSize = 2097152; // 2 Mo.
    private int $cvMaxSize = 5242880; // 5 Mo.
    private int $avatarMaxSize = 2097152; // 2 Mo.
    private string $photoPath = 'public/upload/owner/';
    private string $postImgPath = 'public/upload/blog/post/';
    private string $cvPath = 'public/upload/owner/';
    private string $avatarPath = 'public/upload/user/';


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
    }


    /**
     * Check if file size is ok based on its type
     * @param array $posted_file
     * @param int $file_size_max
     * @return bool
     */
    protected function checkFileSize(array $posted_file, int $file_size_max): bool
    {
        return $posted_file['size'] <= $file_size_max;
    }

    /**
     * Check if file is an image based on its extension and mime type
     * @param array $posted_file
     * @return bool
     */
    protected function checkFileIsImage(array $posted_file): bool
    {
        $ext = pathinfo($posted_file['name'], PATHINFO_EXTENSION);
        return in_array($ext, $this->imageExtensions) && in_array($posted_file['type'], $this->imageMimeTypes);
    }

    /**
     * Check if file is a document based on its extension and mime type
     * @param array $posted_file
     * @return bool
     */
    protected function checkFileIsDoc(array $posted_file): bool
    {
        $ext = pathinfo($posted_file['name'], PATHINFO_EXTENSION);
        return in_array($ext, $this->docExtensions) && in_array($posted_file['type'], $this->docMimeTypes);
    }

    /**
     * Build destination path of the file based on its type
     * @param array $posted_file
     * @param string $file_type
     * @return array
     * @throws Exception
     */
    protected function buildFileDestPath(array $posted_file, string $file_type): array
    {
        $posted_file['unique-name'] = pathinfo($posted_file['name'], PATHINFO_FILENAME) . '_' . $this->generateKey(3);
        $posted_file['ext'] = pathinfo($posted_file['name'], PATHINFO_EXTENSION);
        $base_path = $this->sGlob->getServer('DOCUMENT_ROOT') . '/';

        switch ($file_type) {
            case 'photo':
                $posted_file['dest-path'] = $base_path . $this->photoPath .
                                            $posted_file['unique-name'] . '.' . $posted_file['ext'];
                $posted_file['url'] = "/" . $this->photoPath . $posted_file['unique-name'] . '.' . $posted_file['ext'];
                break;

            case 'cv':
                $posted_file['dest-path'] = $base_path . $this->cvPath .
                                            $posted_file['unique-name'] . '.' . $posted_file['ext'];
                $posted_file['url'] = "/" . $this->cvPath . $posted_file['unique-name'] . '.' . $posted_file['ext'];
                break;

            case 'avatar':
                $posted_file['dest-path'] = $base_path . $this->avatarPath .
                                            $posted_file['unique-name'] . '.' . $posted_file['ext'];
                $posted_file['url'] = "/" . $this->avatarPath . $posted_file['unique-name'] . '.' . $posted_file['ext'];
                break;

            case 'post-image':
                $posted_file['dest-path'] = $base_path . $this->postImgPath .
                                            $posted_file['unique-name'] . '.' . $posted_file['ext'];
                $posted_file['url'] = "/" . $this->postImgPath .
                                            $posted_file['unique-name'] . '.' . $posted_file['ext'];
                break;
        }
        return $posted_file;
    }

    /**
     * Check if a value is unique in database
     * @param string $formName
     * @param string $field
     * @param int $userId
     * @return Res $res
     */
    protected function isUnique(string $formName, string $field, int $userId): Res
    {
        $data = $this->sGlob->getPost($field);
        $userModel = new UserModel();
        if (!$userModel->isUnique($data, $field, $userId)) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-not-unique');
        }
        return $this->res;
    }

    /**
     * Check if a POST field is sent, is alphanumeric (allowing _ and -) and is between 2 lengths
     * @param string $formName
     * @param string $field
     * @param int $min
     * @param int $max
     * @return Res
     */
    protected function checkPostedText(string $formName, string $field, int $min, int $max): Res
    {
        $data = $this->sGlob->getPost($field);
        if ($this->isSet($data) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-empty');
        } elseif ($this->isAlphaNumSpacesPunct($data) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-not-alpha-num-punct');
        } elseif ($this->isBetween($data, $min, $max) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-not-between- ' . $min . '-and-' . $max);
        }
        return $this->res;
    }


    /**
     * Check if a POST radio is sent, is alphanumeric and "-" and is valid
     * @param string $formName
     * @param string $field
     * @param array $allowed_values
     * @return Res
     */
    protected function checkPostedRadio(string $formName, string $field, array $allowed_values): Res
    {
        $data = $this->sGlob->getPost($field);
        if ($this->isSet($data) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-empty');
        } elseif (in_array($data, $allowed_values) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-value-not-allowed');
        }
        return $this->res;
    }


    /**
     * Check if a POST field is sent, is a valid email and is between 2 lengths
     * @param string $formName
     * @param string $field
     * @param int $min
     * @param int $max
     * @return Res
     */
    protected function checkPostedEmail(string $formName, string $field, int $min, int $max): Res
    {
        $data = $this->sGlob->getPost($field);
        if ($this->isSet($data) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-empty');
        } elseif ($this->isEmail($data) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-not-email-format');
        } elseif ($this->isBetween($data, $min, $max) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-not-between-' . $min . '-and-' . $max);
        }
        return $this->res;
    }


    /**
     * Check if a POST field is sent, is a valid slug (alphanum and "-") and is between 4 and 128 chars
     * @param string $formName
     * @param string $field
     * @param int $postId
     * @return Res
     */
    public function checkPostedSlug(string $formName, string $field, int $postId): Res
    {
        $postModel = new PostModel();
        $data = $this->sGlob->getPost($field);
        if ($this->isSet($data) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-empty');
        } elseif ($this->isAlphaNumDash($data) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-not-alpha-num-dash');
        } elseif ($this->isBetween($data, 4, 128) === false) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-not-between-3-and-64');
        } elseif ($postModel->postSlugAlreadyExists($data, $postId) === true) {
            $this->res->ko($formName, $formName . '-ko-' . $field . '-not-unique');
        }
        return $this->res;
    }


    /**
     * This method performs the following checks:
     * - if a POST file is sent or already set
     * - if the file is a valide image
     * - if the file size is not too big
     * @param string $formName
     * @param string $type
     * @param array $postedFile
     * @param File $userFileObject
     * @param int $maxSize
     * @return Res
     */
    protected function checkPostedFileImg(
        string $formName,
        string $type,
        array $postedFile,
        File $userFileObject,
        int $maxSize
    ): Res {
        if (($postedFile['error'] == 4) && ($userFileObject->getFileId() == 0)) {
            $this->res->ko($formName, $formName . '-' . $type . '-ko-missing');
        } elseif ($postedFile['error'] == 0) {
            if ($this->checkFileIsImage($postedFile) === false) {
                $this->res->ko($formName, $formName . '-' . $type . '-ko-not-image');
            }
            if ($this->checkFileSize($postedFile, $maxSize) === false) {
                $this->res->ko($formName, $formName . '-' . $type . '-ko-too-big');
            }
        }
        return $this->res;
    }

    /**
     * This method performs the following checks:
     * - if a POST file is sent or already set
     * - if the file is a valide pdf
     * - if the file size is not too big
     * @param string $formName
     * @param string $type
     * @param array $postedFile
     * @param File $userFileObject
     * @param int $maxSize
     * @return Res
     */
    protected function checkPostedFileDoc(
        string $formName,
        string $type,
        array $postedFile,
        File $userFileObject,
        int $maxSize
    ): Res {
        if (($postedFile['error'] == 4) && ($userFileObject->getFileId() == 0)) {
            $this->res->ko($formName, $formName . '-' . $type . '-ko-missing');
        } elseif ($postedFile['error'] == 0) {
            if ($this->checkFileIsDoc($postedFile) === false) {
                $this->res->ko($formName, $formName . '-' . $type . '-ko-not-image');
            }
            if ($this->checkFileSize($postedFile, $maxSize) === false) {
                $this->res->ko($formName, $formName . '-' . $type . '-ko-too-big');
            }
        }
        return $this->res;
    }


    /**
     * Treats a file and returns a Res containing a File object
     * @param $posted_file
     * @param $file_type
     * @return Res
     */
    protected function treatFile($posted_file, $file_type): Res
    {
        try {
            $posted_file = $this->buildFileDestPath($posted_file, $file_type);
            $fileCtrl = new FileController();
            $uploadedFile = $fileCtrl->uploadFile($posted_file)->getResult()['upload-file'];
            $insertedFileId = $fileCtrl->insertFile($uploadedFile);
            $this->res->ok('treat-file', 'treat-file-ok-upl-and-ins', $fileCtrl->getFileById($insertedFileId));
        } catch (Exception $e) {
            $this->res->ko('treat-file', 'treat-file-ko', $e);
        }
        return $this->res;
    }


    /**
     * @param string $password
     * @return string
     */
    protected function hashPassword(string $password): string
    {
        return hash('sha256', $password);
    }

    /**
     * Check the password format matches the requirements : 8 characters, 1 uppercase, 1 lowercase, 1 number
     * @param string $password
     * @return bool
     */
    protected function checkPasswordFormat(string $password): bool
    {
        return preg_match('/^(?=.*[0-9])(?=.*[A-Z]).{8,20}$/', $password);
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
    protected function getPostImgMaxSize(): int
    {
        return $this->postImgMaxSize;
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
    protected function getPostImgPath(): string
    {
        return $this->postImgPath;
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
