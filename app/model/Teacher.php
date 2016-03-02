<?php
namespace app\model;
use Nette\Database\IRow;
use Nette\Object;

class Teacher extends Object
{
    /**
     * @var $teacherId integer
     */
    private $teacherId;

    /**
     * @var $nickname string
     */
    private $nickname;

    /**
     * @var $aboutSubject string
     */
    private $aboutSubject;

    public static function fromRow(IRow $row)
    {
        $teacher = new self();
        $teacher->teacherId = $row->teacherId;
        $teacher->nickname = $row->nickname;
        $teacher->aboutSubject = $row->aboutSubject;
        return $teacher;
    }
}