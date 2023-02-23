<?php

namespace App\Entity;

class UserOwner extends User
{
    private int $owner_id;
    private int $photo_file_id;
    private File $photo_file;
    private int $cv_file_id;
    private File $cv_file;
    private string $first_name;
    private string $last_name;
    private string $catch_phrase;


    /**
     * ----------------------------------------------------------------------------------------------- Getters & Setters
     */

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->owner_id;
    }

    /**
     * @param int $owner_id
     */
    public function setOwnerId(int $owner_id): void
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return int
     */
    public function getPhotoFileId(): int
    {
        return $this->photo_file_id;
    }

    /**
     * @param int $photo_file_id
     */
    public function setPhotoFileId(int $photo_file_id): void
    {
        $this->photo_file_id = $photo_file_id;
    }

    /**
     * @return File
     */
    public function getPhotoFile(): File
    {
        return $this->photo_file;
    }

    /**
     * @param File $photo_file
     */
    public function setPhotoFile(File $photo_file): void
    {
        $this->photo_file = $photo_file;
    }

    /**
     * @return int
     */
    public function getCvFileId(): int
    {
        return $this->cv_file_id;
    }

    /**
     * @param int $cv_file_id
     */
    public function setCvFileId(int $cv_file_id): void
    {
        $this->cv_file_id = $cv_file_id;
    }

    /**
     * @return File
     */
    public function getCvFile(): File
    {
        return $this->cv_file;
    }

    /**
     * @param File $cv_file
     */
    public function setCvFile(File $cv_file): void
    {
        $this->cv_file = $cv_file;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getCatchPhrase(): string
    {
        return $this->catch_phrase;
    }

    /**
     * @param string $catch_phrase
     */
    public function setCatchPhrase(string $catch_phrase): void
    {
        $this->catch_phrase = $catch_phrase;
    }


}