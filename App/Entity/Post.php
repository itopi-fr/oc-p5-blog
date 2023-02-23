<?php

namespace App\Entity;

use DateTime;

class Post
{
    private int $id;
    private int $author_id;
    private User $author_user;
    private int $feat_img_id;
    private File $feat_img_file;
    private string $title;
    private string $slug;
    private string $excerpt;
    private string $content;
    private DateTime $creation_date;
    private DateTime $last_update;
    private string $status;

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
     * @return User
     */
    public function getAuthorUser(): User
    {
        return $this->author_user;
    }

    /**
     * @param User $author_user
     */
    public function setAuthorUser(User $author_user): void
    {
        $this->author_user = $author_user;
    }

    /**
     * @return int
     */
    public function getFeatImgId(): int
    {
        return $this->feat_img_id;
    }

    /**
     * @param int $feat_img_id
     */
    public function setFeatImgId(int $feat_img_id): void
    {
        $this->feat_img_id = $feat_img_id;
    }

    /**
     * @return File
     */
    public function getFeatImgFile(): File
    {
        return $this->feat_img_file;
    }

    /**
     * @param File $feat_img_file
     */
    public function setFeatImgFile(File $feat_img_file): void
    {
        $this->feat_img_file = $feat_img_file;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    /**
     * @param string $excerpt
     */
    public function setExcerpt(string $excerpt): void
    {
        $this->excerpt = $excerpt;
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
    public function getCreationDate(): DateTime
    {
        return $this->creation_date;
    }

    /**
     * @param DateTime $creation_date
     */
    public function setCreationDate(DateTime $creation_date): void
    {
        $this->creation_date = $creation_date;
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
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * ----------------------------------------------------------------------------------------------- Getters & Setters
     */

}