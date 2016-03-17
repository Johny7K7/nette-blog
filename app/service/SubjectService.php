<?php

namespace App\Service;

use App\Model\Subject;
use Nette\Database\Context;
use Nette\InvalidStateException;

class SubjectService
{
    /** @var \Nette\Database\Context */
    private $database;

    /**
     * SubjectService constructor.
     * @param $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    public function createSubject(Subject $subject)
    {
        if ($subject->getSubjectId() != null) {
            throw new InvalidStateException("Novy predmet nesmie mat ID.");
        }

        $row = $this->database->table(Subject::TABLE)->insert($subject);
        $subject->subjectId = $row->subjectId;

    }

    public function deleteSubject(Subject $subject)
    {
        if ($subject->getSubjectId() == null){
            throw new InvalidStateException("Predmet, ktory chete zmazat musi mat ID.");
        };

        $this->database->table(Subject::TABLE)->where('subjectId', $subject->subjectId)->delete($subject);
    }
}