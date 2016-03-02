<?php
namespace app\model;
use Nette\Database\IRow;
use Nette\Object;


class Subject extends Object
{
    /**
     * @var $subjectId integer
     */
    private $subjectId;

    /**
     * @var $title string
     */
    private $title

    public static function fromRow(IRow $row)
    {
        $subject = new self();
        $subject->subjectId = $row->subjectId;
        $subject->title = $row->title;
        return $subject;
    }
}