<?php

namespace App\Model;

use Nette\Database\IRow;
use Nette\Object;
use Traversable;

class Post extends Object implements \IteratorAggregate
{
    const TABLE = 'Post';

    /**
     * @var $postId integer
     */
    private $postId;

    /**
     * @var $userId integer
     */
    private $userId;

    /**
     * @var $created_at date
     */
    private $created_at;

    /**
     * @var $content string
     */
    private $content;

    /**
     * @var $link string
     */
    private $link;

    /**
     * @var $subjectId integer
     */
    private $subjectId;

    /**
     * Post constructor.
     */
    public function __construct()
    {
    }

    public static function fromRow(IRow $row)
    {
        $post = new self();
        $post->postId = $row->postId;
        $post->userId = $row->userId;
        $post->created_at = $row->created_at;
        $post->content = $row->content;
        $post->link = $row->link;
        $post->subjectId = $row->subjectId;
        return $post;
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
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
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
    public function getSubjectId()
    {
        return $this->subjectId;
    }

    /**
     * @param int $subjectId
     */
    public function setSubjectId($subjectId)
    {
        $this->subjectId = $subjectId;
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
            'postId' => $this->postId,
            'userId' => $this->userId,
            'content' => $this->content,
            'link' => $this->link,
            'subjectId' => $this->subjectId,
        ));
    }
}