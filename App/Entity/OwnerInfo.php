<?php

namespace App\Entity;

/**
 * OwnerInfo entity
 */
class OwnerInfo
{
    /**
     * @var int
     */
    protected int $owner_id;

    /**
     * @var string
     */
    protected string $first_name = '';

    /**
     * @var string
     */
    protected string $last_name = '';

    /**
     * @var string
     */
    protected string $catch_phrase = '';

    /**
     * @var int
     */
    protected int $photo_file_id = 0;

    /**
     * @var File
     */
    protected File $photo_file;

    /**
     * @var int
     */
    protected int $cv_file_id = 0;

    /**
     * @var File
     */
    protected File $cv_file;

    /**
     * @var string
     */
    protected string $sn_github = '';

    /**
     * @var string
     */
    protected string $sn_linkedin = '';


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
     * @return string
     */
    public function getSnGithub(): string
    {
        return $this->sn_github;
    }


    /**
     * @return string
     */
    public function getSnLinkedin(): string
    {
        return $this->sn_linkedin;
    }


    /**
     * --------------------------------------------------------------------------------------------------------- Setters
     */
    /**
     * @param int $owner_id
     * @return void
     */
    public function setOwnerId(int $owner_id): void
    {
        $this->owner_id = $owner_id;
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
     * @param string $last_name
     * @return void
     */
    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
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
     * @param int $photo_file_id
     * @return void
     */
    public function setPhotoFileId(int $photo_file_id): void
    {
        $this->photo_file_id = $photo_file_id;
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
     * @param int $cv_file_id
     * @return void
     */
    public function setCvFileId(int $cv_file_id): void
    {
        $this->cv_file_id = $cv_file_id;
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
     * @param string $sn_github
     */
    public function setSnGithub(string $sn_github): void
    {
        $this->sn_github = $sn_github;
    }


    /**
     * @param string $sn_linkedin
     */
    public function setSnLinkedin(string $sn_linkedin): void
    {
        $this->sn_linkedin = $sn_linkedin;
    }

}
