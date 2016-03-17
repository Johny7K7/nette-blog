<?php

use app\model\Comment;
use Tester\Assert;

/**
 * @var $container \Nette\DI\Container
 */
$container = require __DIR__ . '/../bootstrap.php';

class CommentServiceTest extends Tester\TestCase
{
    /** @var \Nette\Database\Context */
    private $database;

    /**
     * @var $commentService \app\service\CommentService
     */
    private $commentService;

    /**
     * CommentServiceTest constructor.
     * @param \Nette\Database\Context $database
     * @param \app\service\CommentService $commentService
     */
    public function __construct(\Nette\Database\Context $database, \app\service\CommentService $commentService)
    {
        $this->database = $database;
        $this->commentService = $commentService;
    }

    public function testAdd(Comment $comment)
    {
        $comment = new Comment();

        $comment->userId = "1";
        $comment->postId = "1";
        $comment->content = "asdasdasd";
        $this->commentService->addComment($comment);

        Assert::notEqual(null, $comment->getCommentId());
        $row = $this->database->table(Comment::TABLE)->get($comment->getCommentId());
        Assert::equal("1", $row->userId);
        Assert::equal("1", $row->postId);
        Assert::equal("asdasdasd", $row->content);

    }

}

$test = new CommentServiceTest($container->getByType('\Nette\Database\Context'), $container->getByType('app\service\CommentService'));
$test->run();