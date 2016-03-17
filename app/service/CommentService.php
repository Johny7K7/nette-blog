<?php

namespace App\Service;

use App\Model\Comment;
use Nette\Database\Context;
use Nette\Neon\Exception;

class CommentService
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

    public function deleteComment (Comment $comment) {
        if ($comment->getCommentId() == null) {
            throw new Exception("Komentar, ktory ma byt vymazany musi mat Id");
        }

        $this->database->table(Comment::TABLE)->where('commentId', $comment->getCommentId())->delete($comment);
    }
}