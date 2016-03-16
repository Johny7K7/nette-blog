<?php
namespace app\model;
use Nette\Database\IRow;
use Nette\Object;
use Traversable;

class User extends Object implements \IteratorAggregate
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
     * @var $nickname string
     */
    private $nickname;

    /**
     * @var $is_teacher boolean
     */
    private $is_teacher;

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
        $user->nickname = $row->nickname;
        $user->is_teacher = $row->is_teacher;
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

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return boolean
     */
    public function isIsTeacher()
    {
        return $this->is_teacher;
    }

    /**
     * @param boolean $is_teacher
     */
    public function setIsTeacher($is_teacher)
    {
        $this->is_teacher = $is_teacher;
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator(array(
            'userId' => $this->userId,
            'username' => $this->username,
            'userlastname' => $this->userlastname,
            'email' => $this->email,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'password' => $this->password,
            'nickname' => $this->nickname,
            'is_teacher' => $this->is_teacher,
        ));
    }
}