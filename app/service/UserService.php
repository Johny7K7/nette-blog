<?php

namespace app;
use app\model\User;
use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\Selection;
use Nette\Neon\Exception;
class UserService
{
    /**
     * @var $userTable Selection
     */
    private $userTable;
    private $database;
    /**
     * UserService constructor.
     * @param Context $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
        $this->userTable = $this->database->table("users");
    }
    public function addUser(User $user) {
        if ($user->id != null) {
            throw new Exception("New user must not have id");
        }
        $this->userTable->insert($user);
    }
    /**
     * @param $id integer
     */
    public function removeUser($id) {
        // TODO: remove user
    }
    /**
     * @param User $user
     */
    public function updateUser(User $user) {
        // TODO: update user
    }
    /**
     * @param $userId integer
     * @return User
     */
    public function getUser($userId) {
        /**
         * @var $userRow IRow
         */
        $userRow = $this->userTable->get($userId);
        $user = User::fromRow($userRow);
        return $userRow;
    }
}