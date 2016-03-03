<?php
namespace app\model;
use Nette\Database\IRow;
use Nette\Object;


class Comment extends Object
{
    /**
     * @var $commentId integer
     */
    private $commentId;

    /**
     * @var $created_at date
     */
    private $created_at;

    /**
     * @var $content string
     */
    private $content;

    public static function fromRow(IRow $row)
    {
        $comment = new self();
        $comment->commentId = $row->commentId;
        $comment->created_at = $row->created_at;
        $comment->content = $row->content;
        return $comment;
    }
}