<?php

namespace App\Entity;

class OwnerInfo
{
    protected int $owner_id;
    protected string $first_name = '';
    protected string $last_name = '';
    protected string $catch_phrase = '';
    protected int $photo_file_id = 0;
    protected File $photo_file;
    protected int $cv_file_id = 0;
    protected File $cv_file;


    /**
     * --------------------------------------------------------------------------------------------------------- Getters
     */

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->owner_id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getCatchPhrase(): string
    {
        return $this->catch_phrase;
    }

    /**
     * @return int
     */
    public function getPhotoFileId(): int
    {
        return $this->photo_file_id;
    }

    /**
     * @return File
     */
    public function getPhotoFile(): File
    {
        return $this->photo_file;
    }

    /**
     * @return int
     */
    public function getCvFileId(): int
    {
        return $this->cv_file_id;
    }

    /**
     * @return File
     */
    public function getCvFile(): File
    {
        return $this->cv_file;
    }

    /**
     * --------------------------------------------------------------------------------------------------------- Setters
     */

    /**
     * @param int $owner_id
     * @return OwnerInfo
     */
    public function setOwnerId(int $owner_id): OwnerInfo
    {
        $this->owner_id = $owner_id;
        return $this;
    }

    /**
     * @param string $first_name
     * @return OwnerInfo
     */
    public function setFirstName(string $first_name): OwnerInfo
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @param string $last_name
     * @return OwnerInfo
     */
    public function setLastName(string $last_name): OwnerInfo
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @param string $catch_phrase
     * @return OwnerInfo
     */
    public function setCatchPhrase(string $catch_phrase): OwnerInfo
    {
        $this->catch_phrase = $catch_phrase;
        return $this;
    }

    /**
     * @param int $photo_file_id
     * @return OwnerInfo
     */
    public function setPhotoFileId(int $photo_file_id): OwnerInfo
    {
        $this->photo_file_id = $photo_file_id;
        return $this;
    }

    /**
     * @param File $photo_file
     * @return OwnerInfo
     */
    public function setPhotoFile(File $photo_file): OwnerInfo
    {
        $this->photo_file = $photo_file;
        return $this;
    }

    /**
     * @param int $cv_file_id
     * @return OwnerInfo
     */
    public function setCvFileId(int $cv_file_id): OwnerInfo
    {
        $this->cv_file_id = $cv_file_id;
        return $this;
    }

    /**
     * @param File $cv_file
     * @return OwnerInfo
     */
    public function setCvFile(File $cv_file): OwnerInfo
    {
        $this->cv_file = $cv_file;
        return $this;
    }
}
