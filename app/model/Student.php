<?php
namespace app\model;
use Nette\Database\IRow;
use Nette\Object;


class Student extends Object
{
    /**
     * @var $studentId integer
     */
    private $studentId;

    /**
     * @var $nickname string
     */
    private $nickname;

    public static function fromRow(IRow $row)
    {
        $student = new self();
        $student->studentId = $row->studentId;
        $student->nickname = $row->nickname;
        return $student;
    }
}