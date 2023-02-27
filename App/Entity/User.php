<?php

namespace App\Entity;

class User
{
    protected int $id;
    protected ?int $avatar_id;
    protected File $avatar_file;
    protected string $pseudo;
    protected string $email;
    protected string $pass;
    protected string $role;

    /**
     * ----------------------------------------------------------------------------------------------- Getters & Setters
     */

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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

    /**
     * --------------------------------------------------------------------------------------------------------- Methods
     */


}