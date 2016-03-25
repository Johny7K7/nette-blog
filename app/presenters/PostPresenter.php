<?php

namespace App\Presenters;

use App\Model\Comment;
use App\Service\CommentService;
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
     * @var $commentService CommentService
     */
    private $commentService;

    /**
     * PostPresenter constructor.
     * @param PostService $postService
     * @param SubjectService $subjectService
     * @param CommentService $commentService
     */
    public function __construct(PostService $postService, SubjectService $subjectService, CommentService $commentService)
    {
        $this->postService = $postService;
        $this->subjectService = $subjectService;
        $this->commentService = $commentService;
    }

    protected function createComponentPostForm()
    {
        $userId = $this->user->getIdentity()->getId();
        $subjects = $this->subjectService->getSubjectFromTS($userId);

        $form = new UI\Form;
        $form->addSelect('subject', 'Predmet:', $subjects)
            ->setRequired('Vyberte predmet zo zoznamu.');
        $form->addTextArea('content', 'Obsah:')
            ->addRule(Form::MAX_LENGTH, 'Komentár je príliš dlhý.', 5000)
            ->setRequired('Zadajte obsah príspevku');
        $form->addUpload('link', 'Súbor');
        $form->addHidden('postId');
        $form->addSubmit('post', 'Ulozit');
        $form->onSuccess[] = array($this, 'postFormSucceeded');
        return $form;
    }

    public function postFormSucceeded($form)
    {

        $post = new Post();

        $values = $form->getValues();
        if ($values->postId == null) {
            $filename = $values->link->getSanitizedName();
            $values->link->move(WWW_DIR . "/../files/$filename");
            $post->setLink($filename);
        }
        
        $post->userId = $this->user->getIdentity()->getId();
        
        $post->setPostId($values->postId);
        $post->setContent($values->content);
        $post->setSubjectId($values->subject);

        if ($values->postId) {
            $this->postService->updatePost($post);
        } else {
            $this->postService->createPost($post);
        }


        
        if ($values->postId){
            $this->flashMessage('Príspevok bol úspešne upravený.');
            $this->redirect("Post:onePostAndComments", array('postId' => $values->postId));
        }else{
            $this->flashMessage('Príspevok bol úspešne pridaný.');
            $this->redirect('Homepage:');
        }
    }

    public function actionChangePost($postId)
    {
        $post = $this->postService->getOnePost($postId);
        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }
        $this['postForm']->setDefaults($post);
    }

    protected function createComponentTeacherSubjectForm()
    {
        if ($this->user->getIdentity()->teacher == 1) {
            $form = new UI\Form;

            $subjects = $this->subjectService->getAllSubjects();

            $form->addSelect('subject', 'Predmet:', $subjects)
                ->setRequired('Vyberte predmet zo zoznamu');

            $form->addSubmit('add', 'Pridať predmet');
            $form->onSuccess[] = array($this, 'teacherSubjectFormSucceeded');
            return $form;
        } else {
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

    public function renderOnePostAndComments($postId)
    {
        $this->template->post = $this->postService->getOnePost($postId);

        $this->template->comments = $this->commentService->getComments($postId);
    }

    protected function createComponentCommentForm()
    {
        $form = new Form();

        $form->addTextArea('content', 'Komentár:')
            ->addRule(Form::MAX_LENGTH, 'Komentár je príliš dlhý.', 5000)
            ->setRequired('Napiíšte komentár.');
        $form->addSubmit('comment', 'Komentovať');
        $form->onSuccess[] = array($this, 'commentFormSucceeded');

        return $form;
    }

    public function commentFormSucceeded($form)
    {
        $values = $form->getValues();

        $postId = $this->getParameter('postId');
        $userId = $this->user->getIdentity()->getId();

        $comment = new Comment();

        $comment->setPostId($postId);
        $comment->setUserId($userId);
        $comment->setContent($values->content);

        $this->commentService->addComment($comment);
        $this->flashMessage('Komentár bol úspešne pridaný.');
        $this->redirect('this');
    }

}