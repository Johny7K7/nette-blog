<?php

namespace App\Service;

use App\Model\Comment;
use Nette\Database\Context;
use Nette\Neon\Exception;
use Nette\Object;

class CommentService extends Object
{
    /** @var \Nette\Database\Context */
    private $database;
    /**
     * CommentService constructor.
     * @param Context $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    public function addComment(Comment $comment)
    {
        if ($comment->getCommentId() != null) {
            throw new Exception("Novy komentar nesmie mat Id");
        }
        $row = $this->database->table(Comment::TABLE)->insert($comment);
        $comment->commentId = $row->commentId;
    }

    public function updateComment(Comment $comment) {
        if ($comment->getCommentId() == null) {
            throw new Exception("Komentar, ktory ma byt obnoveny musi mat Id");
        }

        $this->database->table(Comment::TABLE)->where('commentId', $comment->getCommentId())->update($comment);

    }

    public function deleteComment ($commentId) {
        if ($commentId == null) {
            throw new Exception("Komentar, ktory ma byt vymazany musi mat Id");
        }

        $this->database->table(Comment::TABLE)->where('commentId', $commentId)->delete();
    }
    
    public function getComments($postId)
    {
        $sql = "SELECT c.*, u.username, u.picture FROM Comment c, User u WHERE c.postId = $postId AND c.userId = u.userId ORDER BY c.created_at DESC";
        $comment = $this->database->query($sql)->fetchAll();
        
        return $comment;
    }
    
    public function getOneComment($commentId)
    {
        $comment = $this->database->table(Comment::TABLE)->get($commentId);
        
        return $comment;
    }
}