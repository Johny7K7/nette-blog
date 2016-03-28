<?php

namespace App\Service;

use App\Model\User;
use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Neon\Exception;
use Nette\Object;

/**
 * Class UserService
 * @package App\Service
 */
class UserService extends Object
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
        if ($user->getUserId() != null) {
            throw new Exception("Novy uzivatel nesmie mat Id");
        }
        $row = $this->database->table(User::TABLE)->insert($user);
        $user->userId = $row->userId;
    }

    public function removeUser(User $user) {
        if ($user->getUserId() == null) {
            throw new Exception("Uzivatel, ktory ma byt vymazany musi mat Id");
        }

        $this->database->table(User::TABLE)->where('userId', $user->userId)->delete($user);
    }

    public function updateUser(User $user) {
        if ($user->getUserId() == null) {
            throw new Exception("Uzivatel, ktory ma byt obnoveny musi mat Id");
        }

        $this->database->table(User::TABLE)->where('userId', $user->userId)->update($user);

    }

    public function GetUserProfil($userId)
    {
        $sql = "SELECT u.*, f.* FROM User u, User_to_User f WHERE u.userId = $userId AND ((f.userId1 = $userId OR f.userId2 = $userId) AND f.accepted IS NULL)";
        $loggedUser = $this->database->query($sql)->fetch();
        
        return $loggedUser;
    }

    public function addUserToUser($userId1, $userId2)
    {
        $sql = "SELECT COUNT(*) FROM User_to_User WHERE (userId1 = $userId1 AND userId2 = $userId2) 
                OR (userId1 = $userId2 AND userId2 = $userId1)";
        $condition = $this->database->query($sql)->fetch();

        if ($condition != true) {
            $this->database->table('User_to_User')->insert(array(
                'userId1' => $userId1,
                'userId2' => $userId2
            ));
        } else {
            throw new Exception("Pouzivatelia s tymito userId uz maju priatelstvo");
        }
    }
    
    public function deleteUserToUser($userId1, $userId2)
    {
        $sql = "SELECT COUNT(*) FROM User_to_User WHERE (userId1 = $userId1 AND userId2 = $userId2) 
                OR (userId1 = $userId2 AND userId2 = $userId1)";
        $condition = $this->database->query($sql)->fetch();

        if ($condition == true) {
            $this->database->table('User_to_User')->where(array('userId1' => $userId1, 'userId2' => $userId2))->delete($userId1, $userId2);
        } else {
            throw new Exception("Pouzivatelia s tymito userId nemaju priatelstvo");
        }
    }

    public function acceptUserToUser($userId1, $userId2, $accepted)
    {
        $sql = "SELECT COUNT(*) FROM User_to_User WHERE (userId1 = $userId1 AND userId2 = $userId2) 
                OR (userId1 = $userId2 AND userId2 = $userId1)";
        $condition = $this->database->query($sql)->fetch();
        
        if ($condition == true){
            $this->database->table('User_to_User')->where(array('userId1' => $userId1, 'userId2' => $userId2))->update($userId1, $userId2, $accepted);
        } else {
            throw new Exception("Pouzivatelia s tymito userId nemaju priatelstvo");
        }
    }
}