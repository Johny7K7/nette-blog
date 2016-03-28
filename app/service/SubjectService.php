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

    public function updateAboutSubject($subjectId, $userId, $about)
    {
        $this->database->table('Teacher_Subject')->where(array("userId" => $userId, "subjectId" => $subjectId))->update(array('aboutSubject' => $about));
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

    public function getSubjectFromTStoSelect($userId)
    {
        $sql = "SELECT subjectId, title FROM Subject WHERE subjectId IN (SELECT subjectId FROM Teacher_Subject WHERE userId = $userId)";

        $subjects = array();
        foreach($this->database->query($sql)->fetchAll() as $subject) {
            $subjects[$subject->subjectId] = $subject->title;
        }

        return $subjects;
    }
    
    public function getSubjectFromTStoTable($userId)
    {
        $sql = "SELECT s.subjectId, s.title, t.aboutSubject, u.userId FROM Subject s, Teacher_Subject t, User u 
                WHERE s.subjectId = t.subjectId AND u.userId = t.userId AND u.userId = $userId";

        $subjects = $this->database->query($sql)->fetchAll();

        return $subjects;
    }

    public function getSubjectFromTStoSelectAboutNull($userId, $subjectId)
    {
        $sql = "SELECT s.subjectId, s.title, t.aboutSubject, u.userId FROM Subject s, Teacher_Subject t, User u 
                WHERE s.subjectId = t.subjectId AND u.userId = t.userId AND u.userId = $userId AND s.subjectId = $subjectId";

        $subjects = array();
        foreach($this->database->query($sql)->fetchAll() as $subject) {
            $subjects[$subject->subjectId] = $subject->title;
        }

        return $subjects;
    }

}