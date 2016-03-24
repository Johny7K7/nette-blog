<?php

namespace App\Presenters;

use App\Model\Subject;
use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;
use App\Service\PostService;
use App\Model\Post;
use App\Service\SubjectService;
use Nette\InvalidStateException;

class PostPresenter extends BasePresenter
{
    /**
     * @var $postService PostService
     */
    private $postService;

    /**
     * @var $subjectService SubjectService
     */
    private $subjectService;

    /**
     * PostPresenter constructor.
     * @param PostService $postService
     * @param SubjectService $subjectService
     */
    public function __construct(PostService $postService, SubjectService $subjectService)
    {
        $this->postService = $postService;
        $this->subjectService = $subjectService;
    }


    protected function createComponentPostForm()
    {
        $userId = $this->user->getIdentity()->getId();
        $subjects = $this->subjectService->getSubjectFromTS($userId);
        
        $form = new UI\Form;
        $form->addSelect('subject', 'Predmet:', $subjects)
            ->setRequired('Vyberte predmet zo zoznamu.');
        $form->addTextArea('content', 'Obsah:')
            ->setRequired('Zadajte obsah príspevku');
        $form->addUpload('link', 'Súbor');
        $form->addSubmit('post', 'Pridať');
        $form->onSuccess[] = array($this, 'postFormSucceeded');
        return $form;
    }

    public function postFormSucceeded($form)
    {
        $values = $form->getValues();
        $filename = $values->link->getSanitizedName();
        $values->link->move(WWW_DIR . "/../files/$filename");



        $post = new Post();

        $post->setContent($values->content);
        $post->setLink($filename);
        $post->userId = $this->user->getIdentity()->getId();
        $post->setSubjectId($values->subject);

        $this->postService->createPost($post);

        $this->flashMessage('Príspevok bol úspešne pridaný.');
        $this->redirect('Homepage:');
    }
    
    protected function createComponentTeacherSubjectForm()
    {
        if ($this->user->getIdentity()->teacher == 1)
        {
            $form = new UI\Form;

            $subjects = $this->subjectService->getAllSubjects();

            $form->addSelect('subject', 'Predmet:', $subjects)
                ->setRequired('Vyberte predmet zo zoznamu');

            $form->addSubmit('add', 'Pridať predmet');
            $form->onSuccess[] = array($this, 'teacherSubjectFormSucceeded');
            return $form;
        }
        else {
            throw new InvalidStateException("Študen nemá právo vyberať si predmet.");
        }
    }
    
    public function teacherSubjectFormSucceeded($form)
    {
        $values = $form->getValues();
        
        $userId = $this->user->getIdentity()->getId();
        
        $this->postService->addTeacherSubject($userId, $values->subject);
        
        $this->flashMessage('Predmet bol úspešne pridaný.');
        $this->redirect('Homepage:');
    }
    
}