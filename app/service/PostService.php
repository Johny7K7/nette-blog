<?php

namespace App\Service;

use App\Model\Post;
use App\Model\Subject;
use App\Model\User;
use Nette\Database\Context;
use Nette\InvalidStateException;
use Nette\Object;

class PostService extends Object
{
    /** @var \Nette\Database\Context */
    private $database;

    /**
     * PostService constructor.
     * @param $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }
    /**
     * @param ArrayList[Post] $user
     */
    public function getAllPosts()
    {
        $sql = "SELECT p.*, s.title, u.username FROM Post p, Subject s, User u WHERE (p.subjectId = s.subjectId) 
                AND (p.userId = u.userId) ORDER BY created_at DESC";
        $posts = $this->database->query($sql)->fetchAll();
        
        return $posts;
    }

    public function createPost(Post $post) {
        if ($post->getPostId() != null) {
            throw new InvalidStateException("Novy prispevok nesmie mat ID.");
        }

        $row = $this->database->table(Post::TABLE)->insert($post);
        $post->postId = $row->postId;

    }

    public function updatePost(Post $post) {
        if ($post->getPostId() == null){
            throw new InvalidStateException("Obnoveny prispevok musi mat ID.");
        };

        $this->database->table(Post::TABLE)->where('postId', $post->postId)->update($post);
    }

    public function deletePost(Post $post) {
        if ($post->getPostId() == null){
            throw new InvalidStateException("Prispevok, ktory chete zmazat musi mat ID.");
        };

        $this->database->table(Post::TABLE)->where('postId', $post->postId)->delete($post);
    }
    
    public function addTeacherSubject($userId, $subjectId)
    {
        $ts = array("userId" => $userId, "subjectId" => $subjectId);

        $row = $this->database->table('Teacher_Subject')->insert($ts);
    }

    public function getOnePost($postId)
    {
        $sql = "SELECT p.*, s.title, u.username FROM Post p, Subject s, User u WHERE (p.subjectId = s.subjectId) 
                AND (p.userId = u.userId) AND postId = $postId";
        $post = $this->database->query($sql)->fetch();
        
        return $post;
    }
    
}