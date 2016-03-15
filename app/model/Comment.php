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

    /**
     * @return int
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * @param int $commentId
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;
    }

    /**
     * @return date
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param date $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }


}