<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\Comment;

class CommentModel extends Connection
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get the x last comments for a given post id.
     * @param int $postId
     * @param int $max
     * @return array|null
     */
//    public function getThisPostComments(int $postId, int $max): array|null
//    {
//        $req = "SELECT * FROM comment WHERE post_id = ? ORDER BY created_date LIMIT ?";
//        $result = $this->getMultipleAsObjectsArray($req, [$postId, $max]);
//        return $result ? $result : [];
//    }
    public function getThisPostComments(int $postId, int $max): array|null
    {
        $req = "SELECT comment.*, post.title AS post_title, post.slug AS post_slug
                FROM comment
                JOIN post ON comment.post_id = post.post_id
                WHERE post.post_id = ? ORDER BY comment.created_date LIMIT ?";
        $result = $this->getMultipleAsObjectsArray($req, [$postId, $max]);
        return $result ? $result : [];
    }


    /**
     * Get the x last comments
     * @param int $max
     * @return array|null
     */
    public function getAllPostComments(int $max): array|null
    {
        $req = "SELECT comment.*, post.title AS post_title, post.slug AS post_slug
                FROM comment
                JOIN post ON comment.post_id = post.post_id
                ORDER BY comment.status DESC LIMIT ?";

        $result = $this->getMultipleAsObjectsArray($req, [$max]);
        return $result ? $result : [];
    }


    /**
     * Create a comment
     * @param Comment $comment
     * @return int|null
     */
    public function createComment(Comment $comment): int|null
    {
        $sql = "INSERT INTO comment (post_id, author_id, content, created_date, status) VALUES (?, ?, ?, ?, ?)";

        return $this->insert(
            $sql,
            [
               $comment->getPostId(),
               $comment->getAuthorId(),
               $comment->getContent(),
               $comment->getCreatedDate()->format('Y-m-d H:i:s'),
               $comment->getStatus(),
            ]
        );
    }


    /**
     * Update the status of a comment to 'valid'
     * @param int $comId
     * @return int
     */
    public function validateComment(int $comId): int
    {
        $sql = "UPDATE comment SET status = 'valid' WHERE com_id = ?";
        return $this->update($sql, [$comId]);
    }


    /**
     * Delete a comment
     * @param int $comId
     * @return bool|null
     */
    public function deleteComment(int $comId): bool|null
    {
        $sql = "DELETE FROM comment WHERE com_id = ?";
        return $this->delete($sql, [$comId]);
    }


    /**
     * Delete all comments for a given post id
     * @param int $postId
     * @return bool|null
     */
    public function deletePostComments(int $postId): bool|null
    {
        $sql = "DELETE FROM comment WHERE post_id = ?";
        return $this->delete($sql, [$postId]);
    }


    /**
     * Checks if a comment exists based on its id
     * @param int $comId
     * @return bool
     */
    public function commentExistsById(int $comId): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM comment WHERE com_id = ?)";
        $result = $this->exists($sql, [$comId]);
        return $result;
    }


}
