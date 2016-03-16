<?php
namespace app\model;
use Nette\Database\IRow;
use Nette\Object;
use Traversable;


class Subject extends Object implements \IteratorAggregate
{
    /**
     * @var $subjectId integer
     */
    private $subjectId;

    /**
     * @var $title string
     */
    private $title;

    public static function fromRow(IRow $row)
    {
        $subject = new self();
        $subject->subjectId = $row->subjectId;
        $subject->title = $row->title;
        return $subject;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getSubjectId()
    {
        return $this->subjectId;
    }

    /**
     * @param int $subjectId
     */
    public function setSubjectId($subjectId)
    {
        $this->subjectId = $subjectId;
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
            'subjectId' => $this->subjectId,
            'title' => $this->title,
        ));

    }
}