<?php

namespace App\Entity;

class UserOwner extends User
{
    private int $owner_id;
    private int|null $photo_file_id;
    private File $photo_file;
    private int|null $cv_file_id;
    private File $cv_file;
    private string|null $first_name;
    private string|null $last_name;
    private string|null $catch_phrase;


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
     * @return void
     */
    public function setOwnerId(int $owner_id): void
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return int|null
     */
    public function getPhotoFileId(): int|null
    {
        return $this->photo_file_id;
    }

    /**
     * @param int $photo_file_id
     * @return void
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
     * @return void
     */
    public function setPhotoFile(File $photo_file): void
    {
        $this->photo_file = $photo_file;
    }

    /**
     * @return int|null
     */
    public function getCvFileId(): int|null
    {
        return $this->cv_file_id;
    }

    /**
     * @param int $cv_file_id
     * @return void
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
     * @return void
     */
    public function setCvFile(File $cv_file): void
    {
        $this->cv_file = $cv_file;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): string|null
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     * @return void
     */
    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string|null
     */
    public function getLastName(): string|null
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * @return void
     */
    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string|null
     */
    public function getCatchPhrase(): string|null
    {
        return $this->catch_phrase;
    }

    /**
     * @param string $catch_phrase
     * @return void
     */
    public function setCatchPhrase(string $catch_phrase): void
    {
        $this->catch_phrase = $catch_phrase;
    }
}
