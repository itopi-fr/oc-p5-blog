<?php

namespace App\Entity;

/**
 * Class UserOwner - Represents a user with the role "owner"
 */
class UserOwner extends User
{
    /**
     * @var int
     */
    private int $owner_id;

    /**
     * @var int|null
     */
    private int|null $photo_file_id;

    /**
     * @var File
     */
    private File $photo_file;

    /**
     * @var int|null
     */
    private int|null $cv_file_id;

    /**
     * @var File
     */
    private File $cv_file;

    /**
     * @var string|null
     */
    private string|null $first_name;

    /**
     * @var string|null
     */
    private string|null $last_name;

    /**
     * @var string|null
     */
    private string|null $catch_phrase;

    /**
     * @var string|null
     */
    private string|null $sn_github;

    /**
     * @var string|null
     */
    private string|null $sn_linkedin;


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


    /**
     * @return string|null
     */
    public function getSnGithub(): ?string
    {
        return $this->sn_github;
    }


    /**
     * @param string|null $sn_github
     */
    public function setSnGithub(?string $sn_github): void
    {
        $this->sn_github = $sn_github;
    }


    /**
     * @return string|null
     */
    public function getSnLinkedin(): ?string
    {
        return $this->sn_linkedin;
    }


    /**
     * @param string|null $sn_linkedin
     */
    public function setSnLinkedin(?string $sn_linkedin): void
    {
        $this->sn_linkedin = $sn_linkedin;
    }
}
