<?php

use app\model\User;
use Tester\Assert;

/**
 * @var $container \Nette\DI\Container
 */
$container = require __DIR__ . '/../bootstrap.php';

class UserServiceTest extends Tester\TestCase
{
    /** @var \Nette\Database\Context */
    private $database;

    /**
     * @var $userService \app\service\UserService
     */
    private $userService;

    /**
     * UserServiceTest constructor.
     * @param \Nette\Database\Context $database
     * @param \app\service\UserService $userService
     */
    public function __construct(\Nette\Database\Context $database, \app\service\UserService $userService)
    {
        $this->database = $database;
        $this->userService = $userService;
    }

    public function testAdd()
    {
        $user = new User();

        $user->username = "Fero";
        $user->userlastname = "Ferko";
        $user->email = "fero@ferko.sk";
        $user->gender = "M";
        $user->birthdate = "1992-02-18";
        $user->password = "fero";
        $user->nickname = "ferino";
        $user->is_teacher = "1";
        $this->userService->addUser($user);

        Assert::notEqual(null, $user->userId);
        $row = $this->database->table(User::TABLE)->get($user->userId);
        Assert::equal("Fero", $row->username);
        Assert::equal("Ferko", $row->userlastname);
        Assert::equal("fero@ferko.sk", $row->email);
        Assert::equal("M", $row->gender);
        Assert::equal("1992-02-18", $row->birthdate);
        Assert::equal("fero", $row->password);
        Assert::equal("ferino", $row->nickname);
        Assert::equal("1", $row->is_teacher);
    }

}

$test = new UserServiceTest($container->getByType('\Nette\Database\Context'), $container->getByType('app\service\UserService'));
$test->run();