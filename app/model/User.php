<?php
namespace app\model;
use Nette\Database\IRow;
use Nette\Object;
class User extends Object
{
    private static $GENDER_MALE = "M";
    private static $GENDER_FeMALE = "F";

    /**
     * @var $id integer
     */
    private $userId;

    /**
     * @var $username string
     */
    private $username;

    /**
     * @var $userlastname string
     */
    private $userlastname;

    /**
     * @var $email string
     */
    private $email;

    /**
     * @var $gender string
     */
    private $gender;

    /**
     * @var $birthdate date
     */
    private $birthdate;

    /**
     * @var $password string
     */
    private $password;

    /**
     * User constructor.
     */
    public function __construct()
    {
    }

    public static function fromRow(IRow $row)
    {
        $user = new self();
        $user->userId = $row->userId;
        $user->username = $row->username;
        $user->userlastname = $row->userlastname;
        $user->email = $row->email;
        $user->gender = $row->gender;
        $user->birthdate = $row->birthdate;
        $user->password = $row->password;
        return $user;
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
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUserlastname()
    {
        return $this->userlastname;
    }

    /**
     * @param string $userlastname
     */
    public function setUserlastname($userlastname)
    {
        $this->userlastname = $userlastname;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return date
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param date $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


}