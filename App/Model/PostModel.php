<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\Post;

class PostModel extends Connection
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get the x last posts.
     *
     * @param int $max - The maximum number of posts to get.
     * @return array|null
     */
    public function getLastPosts(int $max = 10): array|null
    {
        $req = "SELECT * FROM post ORDER BY last_update DESC LIMIT ?";
        $result = $this->getMultipleAsObjectsArray($req, [$max]);
        return $result ? $result : null;
    }


    /**
     * Get the x last published posts.
     *
     * @param int $max - The maximum number of posts to get.
     * @return array|null
     */
    public function getLastPubPosts(int $max = 3): array|null
    {
        $req = "SELECT * FROM post WHERE status = 'pub' ORDER BY last_update DESC LIMIT ?";
        $result = $this->getMultipleAsObjectsArray($req, [$max]);
        return $result ? $result : null;
    }


    /**
     * Get a post providing its id.
     *
     * @param string $postId - The ID of the post to get.
     * @return object|null
     */
    public function getPostById(string $postId): object|null
    {
        $req = "SELECT * FROM post WHERE post_id = ?";
        $result = $this->getSingleAsObject($req, [$postId]);
        return $result ? $result : null;
    }


    /**
     * Check that a post exists providing its id.
     *
     * @param int $postId - The ID of the post to check the existence.
     * @return bool
     */
    public function postExistsById(int $postId): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM post WHERE post_id = ?)";
        return $this->exists($sql, [$postId]);
    }


    /**
     * Check that a post exists providing its slug.
     *
     * @param string $postSlug - The slug of the post to check the existence.
     * @return bool
     */
    public function postExistsBySlug(string $postSlug): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM post WHERE slug = ?)";
        return $this->exists($sql, [$postSlug]);
    }


    /**
     * Check if a post with an other ID has the same slug.
     *
     * @param string $postSlug - The slug of the post to check the existence.
     * @param int $postId - The ID of the post to exclude from the check.
     * @return bool
     */
    public function postSlugAlreadyExists(string $postSlug, int $postId): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM post WHERE slug = ? AND post_id != ?)";
        return $this->exists($sql, [$postSlug, $postId]);
    }


    /**
     * Get a post providing its slug.
     *
     * @param string $postSlug - The slug of the post to get.
     * @return object|null
     */
    public function getPostBySlug(string $postSlug): object|null
    {
        $req = "SELECT * FROM post WHERE slug = ?";
        $result = $this->getSingleAsObject($req, [$postSlug]);
        return $result ? $result : null;
    }


    /**
     * Create a post in the database providing a Post object.
     *
     * @param Post $post - The Post object to create.
     * @return int|null
     */
    public function createPost(Post $post): int|null
    {
        $sql = 'INSERT INTO post (
                  author_id, feat_img_id, title, slug, excerpt, content, creation_date, last_update, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

        return $this->insert(
            $sql,
            [
                $post->getAuthorId(),
                $post->getFeatImgId(),
                $post->getTitle(),
                $post->getSlug(),
                $post->getExcerpt(),
                $post->getContent(),
                $post->getCreationDate()->format('Y-m-d H:i:s'),
                $post->getLastUpdate()->format('Y-m-d H:i:s'),
                $post->getStatus()
            ]
        );
    }


    /**
     * Update a post in the database providing a Post object.
     *
     * @param Post $post - The Post object to update.
     * @return int|null
     */
    public function updatePost(Post $post): int|null
    {
        if ($this->postExistsById($post->getPostId()) === false) {
            return null;
        }

        $sql = 'UPDATE post
                SET feat_img_id = ?, title = ?, slug = ?, content = ?, last_update = ?, status = ?
                WHERE post_id = ?';
        return $this->update(
            $sql,
            [
                $post->getFeatImgId(),
                $post->getTitle(),
                $post->getSlug(),
                $post->getContent(),
                $post->getLastUpdate()->format('Y-m-d H:i:s'),
                $post->getStatus(),
                $post->getPostId()
            ]
        );
    }


    /**
     * Delete a post from the database providing its id.
     *
     * @param int $postId - The ID of the post to delete.
     * @return bool|null
     */
    public function deletePost(int $postId): bool|null
    {
        if ($this->postExistsById($postId) === false) {
            return null;
        }

        $sql = 'DELETE FROM post WHERE post_id = ?';
        return $this->delete($sql, [$postId]);
    }


    /**
     * Simply change the status of a post to "arch" (archived).
     *
     * @param int $postId - The ID of the post to archive.
     * @return int|null
     */
    public function archivePost(int $postId): int|null
    {
        if ($this->postExistsById($postId) === false) {
            return null;
        }

        $sql = 'UPDATE post SET status = "arch" WHERE post_id = ?';
        return $this->update($sql, [$postId]);
    }


}
