<?php

namespace app\service;
use app\model\Post;
use app\model\User;
use Nette\Database\Context;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;

class WallService
{
    /** @var \Nette\Database\Context */
    private $database;
    /**
     * WallService constructor.
     * @param $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }
    /**
     * @param ArrayList[Post] $user
     */
    public function getAllPosts(User $user) {
    }

    public function createPost(Post $post) {
        if ($post->postId != null) {
            throw new InvalidStateException("Novy prispevok nesmie mat ID.");
        }

        $row = $this->database->table(Post::TABLE)->insert($post);
        $post->postId = $row->postId;

    }

    public function updatePost(Post $post) {
        if ($post->getPostId() == null){
            throw new InvalidStateException("Novy prispevok musi mat ID.");
        };

        $this->database->table(Post::TABLE)->where('postId', $post->postId)->update($post);
    }
}