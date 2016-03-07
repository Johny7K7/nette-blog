<?php

namespace app\service;
use app\model\User;
use Nette\Database\Context;
class WallService
{
    private $database;
    /**
     * WallService constructor.
     * @param $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }
    /**
     * @param ArrayList[Post] $user
     */
    public function getAllPosts(User $user) {
    }
}