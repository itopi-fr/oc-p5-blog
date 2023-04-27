<?php

namespace App\Entity;

use DateTime;

class Comment
{
    private int $comId;

    private int $postId;

    private int $authorId;

    private User $authorUser;

    private string $content;

    private DateTime $createdDate;

    private DateTime $lastUpdate;

    private string $status;


    /**
     * ----------------------------------------------------------------------------------------------- Getters & Setters
     */

    /**
     * @return int
     */
    public function getComId(): int
    {
        return $this->comId;
    }

    /**
     * @param int $comId
     * @return void
     */
    public function setComId(int $comId): void
    {
        $this->comId = $comId;
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @param int $postId
     * @return void
     */
    public function setPostId(int $postId): void
    {
        $this->postId = $postId;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @param int $authorId
     * @return void
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
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
    public function getCreatedDate(): DateTime
    {
        return $this->createdDate;
    }

    /**
     * @param DateTime $createdDate
     * @return void
     */
    public function setCreatedDate(DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return DateTime
     */
    public function getLastUpdate(): DateTime
    {
        return $this->lastUpdate;
    }

    /**
     * @param DateTime $lastUpdate
     * @return void
     */
    public function setLastUpdate(DateTime $lastUpdate): void
    {
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @return User
     */
    public function getAuthorUser(): User
    {
        return $this->authorUser;
    }

    /**
     * @param User $authorUser
     * @return Comment
     */
    public function setAuthorUser(User $authorUser): void
    {
        $this->authorUser = $authorUser;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
