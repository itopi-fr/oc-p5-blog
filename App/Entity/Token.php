<?php

namespace App\Entity;

use DateTime;

class Token
{
    private int $id;
    private int $user_id;

    private string $content;
    private DateTime $expiration_date;
    private string $type;

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
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return DateTime
     */
    public function getExpirationDate(): DateTime
    {
        return $this->expiration_date;
    }

    /**
     * @param DateTime $expiration_date
     * @return void
     */
    public function setExpirationDate(DateTime $expiration_date): void
    {
        $this->expiration_date = $expiration_date;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * --------------------------------------------------------------------------------------------------------- Methods
     */

    /**
     * Creates a token for password change. This token is valid for 15 minutes.
     * @param int $userId
     * @return Token
     */
    public function createPassChangeToken(int $userId) {
        $this->setUserId($userId);
        $this->setContent($this->generateKey());
        $this->setExpirationDate(new DateTime('now + 15 minutes'));
        $this->setType('password_change');
        return $this;
    }

    /**
     * Generates a random key
     * @return string
     */
    public function generateKey() : string
    {
        return ( bin2hex( random_bytes(32) ) );
    }
}