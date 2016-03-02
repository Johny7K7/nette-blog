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
}