<?php

namespace app\service;
use app\model\User;
use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\Selection;
use Nette\Neon\Exception;
class UserService
{
    /** @var \Nette\Database\Context */
    private $database;
    /**
     * UserService constructor.
     * @param Context $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    public function addUser(User $user) {
        if ($user->userId != null) {
            throw new Exception("Novy uzivatel nesmie mat Id");
        }
        $row = $this->database->table(User::TABLE)->insert($user);
        $user->userId = $row->userId;
    }

    public function removeUser(User $user) {
        if ($user->id == null) {
            throw new Exception("Uzivatel, ktory ma byt vymazany musi mat Id");
        }

        $this->database->table(User::TABLE)->where('userId', $user->userId)->delete($user);
    }

    public function updateUser(User $user) {
        if ($user->id == null) {
            throw new Exception("Uzivatel, ktory ma byt obnoveny musi mat Id");
        }

        $this->database->table(User::TABLE)->where('userId', $user->userId)->update($user);

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