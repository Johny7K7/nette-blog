<?php
namespace app\model;
use Nette\Database\IRow;
use Nette\Object;

class Post extends Object
{
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

    /**
     * @var $link string
     */
    private $link;

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
        $post->created_at = $row->created_at;
        $post->content = $row->content;
        $post->link = $row->link;
        return $post;
    }
}