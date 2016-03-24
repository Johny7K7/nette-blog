<?php

namespace App\Service;

use App\Model\Subject;
use Nette\Database\Context;
use Nette\InvalidStateException;
use Nette\Object;

class SubjectService extends Object
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

    public function getAllSubjects()
    {
        $sql = "SELECT subjectId, title FROM Subject";

        $subjects = array();
        foreach($this->database->query($sql)->fetchAll() as $subject) {
            $subjects[$subject->subjectId] = $subject->title;
        }
        
        return $subjects;
    }

    public function getSubjectFromTS($userId)
    {
        $sql = "SELECT subjectId, title FROM Subject WHERE subjectId IN (SELECT subjectId FROM Teacher_Subject WHERE userId = $userId)";

        $subjects = array();
        foreach($this->database->query($sql)->fetchAll() as $subject) {
            $subjects[$subject->subjectId] = $subject->title;
        }

        return $subjects;
    }

    public function getPostsAsSubjects($subjectId)
    {
        $sql = "SELECT p.*, s.title, u.username FROM Post p, Subject s, User u WHERE ((p.subjectId = s.subjectId) 
                AND (p.userId = u.userId) AND (p.subjectId = $subjectId)) ORDER BY created_at DESC";
        $posts = $this->database->query($sql)->fetchAll();
    }
}