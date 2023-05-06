<?php

namespace App\Entity;

use DateTime;

/**
 * Comment entity
 */
class Comment
{
    /**
     * @var int
     */
    private int $comId;

    /**
     * @var int
     */
    private int $postId;

    /**
     * @var int
     */
    private int $authorId;

    /**
     * @var User
     */
    private User $authorUser;

    /**
     * @var string
     */
    private string $content;

    /**
     * @var DateTime
     */
    private DateTime $createdDate;

    /**
     * @var DateTime
     */
    private DateTime $lastUpdate;

    /**
     * @var string
     */
    private string $status;

    /**
     * Used to display the post title in the comment list.
     * @var string
     */
    protected string $postTitle;

    /**
     * Used to open the post in the comment list.
     * @var string
     */
    protected string $postSlug;


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


    /**
     * @return string
     */
    public function getPostTitle(): string
    {
        return $this->postTitle;
    }


    /**
     * @param string $postTitle
     */
    public function setPostTitle(string $postTitle): void
    {
        $this->postTitle = $postTitle;
    }


    /**
     * @return string
     */
    public function getPostSlug(): string
    {
        return $this->postSlug;
    }


    /**
     * @param string $postSlug
     */
    public function setPostSlug(string $postSlug): void
    {
        $this->postSlug = $postSlug;
    }


}
