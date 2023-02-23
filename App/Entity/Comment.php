<?php

namespace App\Entity;

use DateTime;

class Comment
{
    private int $id;
    private int $post_id;
    private int $author_id;
    private string $content;
    private DateTime $created_date;
    private DateTime $last_update;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->post_id;
    }

    /**
     * @param int $post_id
     */
    public function setPostId(int $post_id): void
    {
        $this->post_id = $post_id;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    /**
     * @param int $author_id
     */
    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
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
        return $this->created_date;
    }

    /**
     * @param DateTime $created_date
     */
    public function setCreatedDate(DateTime $created_date): void
    {
        $this->created_date = $created_date;
    }

    /**
     * @return DateTime
     */
    public function getLastUpdate(): DateTime
    {
        return $this->last_update;
    }

    /**
     * @param DateTime $last_update
     */
    public function setLastUpdate(DateTime $last_update): void
    {
        $this->last_update = $last_update;
    }

    /**
     * ----------------------------------------------------------------------------------------------- Getters & Setters
     */

}