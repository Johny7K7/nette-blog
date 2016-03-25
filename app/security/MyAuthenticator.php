<?php

use Nette\Security as NS;

class MyAuthenticator extends Nette\Object implements NS\IAuthenticator
{
    public $database;

    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $row = $this->database->table(\App\Model\User::TABLE)
            ->where('username', $username)->fetch();

        if (!$row) {
            throw new NS\AuthenticationException('Používateľ s týmto menom neexistuje.');
        }

        if ($row->password !== sha1($password)) {
            throw new NS\AuthenticationException('Nesprávne heslo.');
        }

        return new NS\Identity($row->userId, $row->teacher, array(
            'username' => $row->username,
            'email' => $row->email,
            'gender' => $row->gender,
            'birthdate' => $row->birthdate,
            'nickname' => $row->nickname,
            'teacher' => $row->teacher,
        ));
    }
}