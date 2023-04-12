<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\Post;

class PostModel extends Connection
{
    protected Post $post;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $post = new Post();
    }


    public function getLastPubPosts(int $max = 3): array|null
    {
        $req = "SELECT * FROM post WHERE status = 'pub' ORDER BY last_update DESC LIMIT ?";
        $result = $this->getMultipleAsObjectsArray($req, [$max]);
        return $result ? $result : null;
    }

    public function postExistsBySlug(string $postSlug): bool
    {
        $req = "SELECT * FROM post WHERE slug = ?";
        $result = $this->getSingleAsObject($req, [$postSlug]);
        return $result ? true : false;
    }

    public function getPostBySlug(string $postSlug): object|null
    {
        $req = "SELECT * FROM post WHERE slug = ?";
        $result = $this->getSingleAsObject($req, [$postSlug]);
        return $result ? $result : null;
    }

}