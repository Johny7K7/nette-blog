<?php

namespace App\Service;

use App\Model\Subject;
use Tester\Assert;

/**
 * @var $container \Nette\DI\Container
 */
$container = require __DIR__ . '/../../bootstrap.php';

/**
 * Class SubjectServiceTest
 * @package App\Service
 *
 */
class SubjectServiceTest extends \Tester\TestCase
{
    /** @var \Nette\Database\Context */
    private $database;

    /**
     * @var $subjectService \app\service\SubjectService
     */
    private $subjectService;

    /**
     * SubjectServiceTest constructor.
     * @param \Nette\Database\Context $database
     * @param \app\service\SubjectService $subjectService
     */
    public function __construct(\Nette\Database\Context $database, \app\service\SubjectService $subjectService)
    {
        $this->database = $database;
        $this->subjectService = $subjectService;
    }

    public function testCreate()
    {
        $subject = new Subject();

        $subject->title = "Matematika";

        $this->subjectService->createSubject($subject);

        Assert::notEqual(null, $subject->getSubjectId());
        $row = $this->database->table(Subject::TABLE)->get($subject->getSubjectId());
        Assert::equal("Matematika", $row->title);
    }

}

$test = new SubjectServiceTest($container->getByType('\Nette\Database\Context'), $container->getByType('\App\Service\SubjectService'));
$test->run();