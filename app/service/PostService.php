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

    public function getAllPosts($userId)
    {
        $sql = "SELECT p.*, s.title, u.username,  u.picture,
                (SELECT COUNT(*) FROM File f WHERE (f.postId = p.postId)) as 'countFile', 
                (SELECT COUNT(*) FROM Link l WHERE (l.postId = p.postId)) as 'countLink', 
                (SELECT COUNT(*) FROM User_to_Post_Like l WHERE (l.postId = p.postId AND l.userId = $userId)) as 'isLike', 
                (SELECT COUNT(*) FROM Comment c WHERE (c.postId = p.postId)) as 'countComment', 
                (SELECT COUNT(*) FROM User_to_Post_Like l WHERE (l.postId = p.postId)) as 'countLike' 
                FROM Post p, Subject s, User u WHERE (p.subjectId = s.subjectId) AND (p.userId = u.userId) 
                ORDER BY p.created_at DESC";
        $posts = $this->database->query($sql)->fetchAll();

        return $posts;
    }
    
    public function getWall1Posts($userId)
    {
        $sql = "SELECT p.*, s.title, u.username,  u.picture,
                (SELECT COUNT(*) FROM File f WHERE (f.postId = p.postId)) as 'countFile',
                (SELECT COUNT(*) FROM User_to_Post_Like l WHERE (l.postId = p.postId AND l.userId = $userId)) as 'isLike', 
                (SELECT COUNT(*) FROM Comment c WHERE (c.postId = p.postId)) as 'countComment', 
                (SELECT COUNT(*) FROM User_to_Post_Like l WHERE (l.postId = p.postId)) as 'countLike' 
                FROM Post p, Subject s, User u WHERE (p.subjectId = s.subjectId) AND (p.userId = u.userId AND u.userId = $userId) 
                ORDER BY p.created_at DESC";
        $posts = $this->database->query($sql)->fetchAll();
        
        return $posts;
    }

    public function getWall2Posts($userId)
    {
        $sql = "SELECT p.*, s.title, u.username,  u.picture,
                (SELECT COUNT(*) FROM File f WHERE (f.postId = p.postId)) as 'countFile', 
                (SELECT COUNT(*) FROM Link l WHERE (l.postId = p.postId)) as 'countLink', 
                (SELECT COUNT(*) FROM User_to_Post_Like l WHERE (l.postId = p.postId AND l.userId = $userId)) as 'isLike', 
                (SELECT COUNT(*) FROM Comment c WHERE (c.postId = p.postId)) as 'countComment', 
                (SELECT COUNT(*) FROM User_to_Post_Like l WHERE (l.postId = p.postId)) as 'countLike' 
                FROM Post p, Subject s, User u 
 WHERE p.userId IN (
    SELECT userId1 FROM User_to_User WHERE accepted = 1 AND userId2 = $userId
    UNION ALL
    SELECT userId2 FROM User_to_User WHERE accepted = 1 AND userId1 = $userId
) AND (p.subjectId = s.subjectId) AND (p.userId = u.userId) AND (p.visible = 2 OR p.visible = 4)
                ORDER BY p.created_at DESC";
        $posts = $this->database->query($sql)->fetchAll();

        return $posts;
    }

    public function getWall3Posts($userId)
    {
        $sql = "SELECT p.*, s.title, u.username,  u.picture,
                (SELECT COUNT(*) FROM File f WHERE (f.postId = p.postId)) as 'countFile',
                (SELECT COUNT(*) FROM User_to_Post_Like l WHERE (l.postId = p.postId AND l.userId = $userId)) as 'isLike', 
                (SELECT COUNT(*) FROM Comment c WHERE (c.postId = p.postId)) as 'countComment', 
                (SELECT COUNT(*) FROM User_to_Post_Like l WHERE (l.postId = p.postId)) as 'countLike' 
                FROM Post p, Subject s, User u WHERE (p.subjectId = s.subjectId) AND (p.userId = u.userId AND u.userId = $userId) 
                ORDER BY p.created_at DESC";
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
        
        $this->database->table('Teacher_Subject')->insert($ts);
    }

    public function getOnePost($postId, $userId)
    {
        $sql = "SELECT p.*, s.title, u.username, u.picture, 
                (SELECT COUNT(*) FROM File f WHERE (f.postId = p.postId)) as 'countFile', 
                (SELECT COUNT(*) FROM Link l WHERE (l.postId = p.postId)) as 'countLink', 
                (SELECT COUNT(*) FROM User_to_Post_Like l WHERE (l.postId = p.postId AND l.userId = $userId)) as 'isLike', 
                (SELECT COUNT(*) FROM Comment c WHERE (c.postId = p.postId)) as 'countComment', 
                (SELECT COUNT(*) FROM User_to_Post_Like l WHERE (l.postId = p.postId)) as 'countLike' 
                FROM Post p, Subject s, User u WHERE (p.subjectId = s.subjectId) AND (p.userId = u.userId) AND (p.postId = $postId)";
        $post = $this->database->query($sql)->fetch();
        
        return $post;
    }

    public function like($userId, $postId)
    {
        $this->database->table('User_to_Post_Like')->insert(array(
            'userId' => $userId,
            'postId' => $postId,
        ));
    }

    public function dislike($userId, $postId)
    {
        $this->database->table('User_to_Post_Like')->where(array("userId" => $userId, "postId" => $postId))->delete($userId, $postId);
    }

    public function getAuthorOfPost($postId)
    {
        $sql = "SELECT * FROM Post WHERE postId = $postId";
        $authorId = $this->database->query($sql)->fetch();
        
        return $authorId;
    }
}