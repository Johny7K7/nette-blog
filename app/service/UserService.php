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

        $this->database->table(User::TABLE)->where('userId', $user->userId)->delete();
    }

    public function updateUser(User $user) {
        if ($user->getUserId() == null) {
            throw new Exception("Uzivatel, ktory ma byt obnoveny musi mat Id");
        }

        $this->database->table(User::TABLE)->where('userId', $user->userId)->update($user);

    }

    public function getUserProfile($userId)
    {
        $sql = "SELECT u.*, (SELECT COUNT(*) FROM User_to_User f WHERE ((f.userId1 = $userId OR f.userId2 = $userId) AND f.accepted IS NULL)) 
                as 'countRequest' FROM User u WHERE userId = $userId";
        $userProfile = $this->database->query($sql)->fetch();
        
        return $userProfile;
    }

    public function addUserToUser($userId1, $userId2)
    {
        $id1 = min($userId1, $userId2);
        $id2 = max($userId1, $userId2);
        
//        print_r($id1, $id2);
//        exit;

        $friend = $this->isFriend($userId1, $userId2);
        
        if ($friend->friend != 1) {
            $this->database->table('User_to_User')->insert(array(
                'userId1' => $id1,
                'userId2' => $id2
            ));
        } else {
            throw new Exception("Pouzivatelia s tymito userId uz maju priatelstvo");
        }
    }
    
    public function deleteUserToUser($userId1, $userId2)
    {
        $id1 = min($userId1, $userId2);
        $id2 = max($userId1, $userId2);
        
        if ($this->isFriend($userId1, $userId2) == true) {
            $this->database->table('User_to_User')->where(array('userId1' => $id1, 'userId2' => $id2))->delete();
        } else {
            throw new Exception("Pouzivatelia s tymito userId nemaju priatelstvo");
        }
    }

    public function acceptUserToUser($userId1, $userId2, $accepted)
    {
        $id1 = min($userId1, $userId2);
        $id2 = max($userId1, $userId2);
        
        if ($this->isFriend($userId1, $userId2) == true){
            $this->database->table('User_to_User')->where(array('userId1' => $id1, 'userId2' => $id2))->update(array(
                'accepted' => $accepted,
            ));
        } else {
            throw new Exception("Pouzivatelia s tymito userId nemaju priatelstvo");
        }
    }

    public function getAllRequests($userId)
    {
        $sql = "SELECT f.*, COALESCE((SELECT u.username FROM User u WHERE u.userId = f.userId1 AND u.userId <> $userId),( 
                SELECT u.username FROM User u WHERE u.userId = f.userId2 AND u.userId <> $userId)) as 'username2' 
                FROM User_to_User f WHERE ((f.userId1 = $userId OR f.userId2 = $userId) AND f.accepted IS NULL)";
        $requests = $this->database->query($sql)->fetchAll();

        return $requests;
    }

    public function isFriend($userId1, $userId2)
    {
        $id1 = min($userId1, $userId2);
        $id2 = max($userId1, $userId2);

        $sql = "SELECT COUNT(*) as 'friend' FROM User_to_User WHERE userId1 = $id1 AND userId2 = $id2 AND accepted = 1";
        $friend = $this->database->query($sql)->fetch();

        return $friend;
    }

    public function isRequest($userId1, $userId2)
    {
        $id1 = min($userId1, $userId2);
        $id2 = max($userId1, $userId2);

        $sql = "SELECT COUNT(*) as 'request' FROM User_to_User WHERE userId1 = $id1 AND userId2 = $id2 AND accepted IS NULL";
        $friend = $this->database->query($sql)->fetch();

        return $friend;
    }

    public function isTeacher($userId)
    {
        $sql = "SELECT teacher FROM User WHERE userId = $userId";
        $teacher = $this->database->query($sql)->fetch();
        
        return $teacher;
    }

    public function originOfFriend($userId1, $userId2)
    { 
        $friend = $this->isFriend($userId1, $userId2);
        
        if ($friend->friend == 1){
            if ($this->isTeacher($userId1) == true){
                $origin = "Kolega";
            }  else {
                $origin = "Učiteľ";
            } 
        } else $origin = null;
        
        return $origin;
    }
}