<?php
namespace app\model;
use Nette\Database\IRow;
use Nette\Object;
use Traversable;


class Comment extends Object implements \IteratorAggregate
{
    /**
     * @var $commentId integer
     */
    private $commentId;

    /**
     * @var $userId integer
     */
    private $userId;

    /**
     * @var $postId integer
     */
    private $postId;

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

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @param int $postId
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator(array(
            'commentId' => $this->commentId,
            'userId' => $this->userId,
            'postId' => $this->postId,
            'content' => $this->content,
        ));
    }
}