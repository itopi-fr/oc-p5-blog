<?php

namespace App\Entity;

use DateTime;

class Post
{
    protected int $post_id;

    protected int $author_id;

    protected User $author_user;

    protected int $feat_img_id;

    protected File $feat_img_file;

    protected string $title;

    protected string $slug;

    protected string $excerpt;

    protected string $content;

    protected DateTime $creation_date;

    protected DateTime $last_update;

    protected string $status;

    protected array $comments;



    /**
     * ----------------------------------------------------------------------------------------------- Getters & Setters
     */

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->post_id;
    }

    /**
     * @param int $post_id
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }


    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }


    /**
     * @param array $comments
     * @return void
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }
}
