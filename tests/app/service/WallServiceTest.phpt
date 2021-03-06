<?php

namespace App\Service;

use App\Model\Post;
use Tester\Assert;
use Tester\TestCase;

/**
 * @var $container \Nette\DI\Container
 */
$container = require __DIR__ . '/../../bootstrap.php';

/**
 * Class WallServiceTest
 * @package App\Service
 */
class WallServiceTest extends TestCase
{
    /** @var \Nette\Database\Context */
    private $database;

    /**
     * @var $wallService \app\service\WallService
     */
    private $wallService;

    /**
     * WallServiceTest constructor.
     * @param \Nette\Database\Context $database
     * @param \app\service\WallService $wallService
     */
    public function __construct(\Nette\Database\Context $database, \app\service\WallService $wallService)
    {
        $this->database = $database;
        $this->wallService = $wallService;
    }

    public function testCreate()
    {
        $post = new Post();

        $post->content = "Ahoj";
        $post->link = "www";
        $post->userId = "1";
        $post->subjectId = "1";
        $this->wallService->createPost($post);

        Assert::notEqual(null, $post->getPostId());
        $row = $this->database->table(Post::TABLE)->get($post->getPostId());
        Assert::equal("Ahoj", $row->content);
        Assert::equal("www", $row->link);
    }

    public function testUpdate()
    {
        $post = new Post();

        $post->content = "Ahoj";
        $post->link = "ttt";
        $post->userId = "1";
        $post->subjectId = "1";
        $this->wallService->createPost($post);

        $post->setLink("abc");
        $this->wallService->updatePost($post);

        $updatedPost = Post::fromRow($this->database->table(Post::TABLE)->get($post->postId));
        Assert::equal("abc", $updatedPost->link);
    }

    public function testDelete()
    {
        $post = new Post();

        $post->content = "Cau";
        $post->link = "zzz";
        $post->userId = 1;
        $post->subjectId = 2;
        $this->wallService->createPost($post);

        $this->wallService->deletePost($post);

        Assert::false($this->database->table(Post::TABLE)->get($post->postId));
    }
}

$test = new WallServiceTest($container->getByType('\Nette\Database\Context'), $container->getByType('\App\Service\WallService'));
$test->run();