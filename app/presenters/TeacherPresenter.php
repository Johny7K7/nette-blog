<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;
use App\Service\TeacherService;
use App\Model\Post;

class TeacherPresenter extends BasePresenter
{
    /**
     * @var $postService TeacherService
     */
    private $postService;

    /**
     * TeacherPresenter constructor.
     * @param TeacherService $postService
     */
    public function __construct(TeacherService $postService)
    {
        $this->postService = $postService;
    }
    
    protected function createComponentPostForm()
    {
        $form = new UI\Form;
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
        $post->setSubjectId('2');

        $this->postService->createPost($post);

        $this->flashMessage('Príspevok bol úspešne pridaný.');
        $this->redirect('Homepage:');
    }

}