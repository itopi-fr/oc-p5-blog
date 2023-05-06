<?php

namespace App\Entity;

/**
 * Class User - Represents a user
 */
class User
{
    /**
     * @var int
     */
    protected int $user_id;

    /**
     * @var int|null
     */
    protected ?int $avatar_id;

    /**
     * @var File
     */
    protected File $avatar_file;

    /**
     * @var string
     */
    protected string $pseudo;

    /**
     * @var string
     */
    protected string $email;

    /**
     * @var string
     */
    protected string $pass;

    /**
     * @var string
     */
    protected string $role;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->avatar_file = new File();
    }


    /**
     * ----------------------------------------------------------------------------------------------- Getters & Setters
     */
    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }


    /**
     * @param int $user_id
     * @return void
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }


    /**
     * @return int|null
     */
    public function getAvatarId(): ?int
    {
        return $this->avatar_id;
    }


    /**
     * @param int|null $avatar_id
     * @return void
     */
    public function setAvatarId(?int $avatar_id): void
    {
        $this->avatar_id = $avatar_id;
    }


    /**
     * @return File
     */
    public function getAvatarFile(): File
    {
        return $this->avatar_file;
    }


    /**
     * @param File $avatar_file
     * @return void
     */
    public function setAvatarFile(File $avatar_file): void
    {
        $this->avatar_file = $avatar_file;
    }


    /**
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }


    /**
     * @param string $pseudo
     * @return void
     */
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }


    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }


    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }


    /**
     * @param string $pass
     * @return void
     */
    public function setPass(string $pass): void
    {
        $this->pass = $pass;
    }


    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }


    /**
     * @param string $role
     * @return void
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }


}
