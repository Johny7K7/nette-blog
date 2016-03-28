<?php

namespace App\Presenters;

use App\Model\Comment;
use App\Service\CommentService;
use App\Service\FileService;
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
     * @var $fileService FileService
     */
    private $fileService;

    /**
     * PostPresenter constructor.
     * @param PostService $postService
     * @param SubjectService $subjectService
     * @param CommentService $commentService
     * @param FileService $fileService
     */
    public function __construct(PostService $postService, SubjectService $subjectService, CommentService $commentService, FileService $fileService)
    {
        $this->postService = $postService;
        $this->subjectService = $subjectService;
        $this->commentService = $commentService;
        $this->fileService = $fileService;
    }

    protected function createComponentPostForm()
    {
        $userId = $this->user->getIdentity()->getId();
        $subjects = $this->subjectService->getSubjectFromTStoSelect($userId);

        $form = new UI\Form;
        $form->addSelect('subject', 'Predmet:', $subjects)
            ->setRequired('Vyberte predmet zo zoznamu.');
        $form->addTextArea('content', 'Obsah:')
            ->addRule(Form::MAX_LENGTH, 'Komentár je príliš dlhý.', 5000)
            ->setRequired('Zadajte obsah príspevku');
        $form->addHidden('postId');
        $form->addSubmit('post', 'Ulozit');
        $form->onSuccess[] = array($this, 'postFormSucceeded');
        return $form;
    }

    public function postFormSucceeded($form)
    {

        $post = new Post();

        $values = $form->getValues();
        
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
        $this->template->backlink = $this->getParameter('backlink');
        $this->template->postId = $this->getParameter('postId');
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
            throw new InvalidStateException("Študent nemá právo vyberať si predmet.");
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
    
    public function renderOnePostAndComments($postId)
    {
        $userId = $this->user->getIdentity()->getId();
        $this->template->post = $this->postService->getOnePost($postId, $userId);
        $this->template->comments = $this->commentService->getComments($postId);
    }

    public function renderAllFiles($postId)
    {
        $this->template->files = $this->fileService->getAllFiles($postId);
        $this->template->backlink = $this->getParameter('backlink');
        $this->template->postId = $this->getParameter('postId');
    }

    public function actionAddFile($postId)
    {
        $this->template->backlink = $this->getParameter('backlink');
        $this['addFileForm']->setDefaults(array('postId' => $postId));
    }

    protected function createComponentAddFileForm()
    {
        $form = new Form();

        $form->addHidden('postId');
        $form->addUpload('file', 'Súbory:')
            ->addRule(Form::MAX_FILE_SIZE, 'Maximálna veľkosť súboru je 5MB', 5*1024*1024)
            ->setRequired('Vyberte súbor');
        $form->addSubmit('save', 'Uložiť');
        $form->onSuccess[] = array($this, 'addFileFormSucceeded');

        return $form;
    }

    public function addFileFormSucceeded($form)
    {
        $values = $form->getValues();

        $this->fileService->addFile($values->postId, $values->file);

        $this->flashMessage('Príloha bola úspešne pridaná.');
        $this->redirect("Post:allFiles", array('postId' => $values->postId));
    }

    public function actionLike($postId)
    {
        $userId = $this->user->getIdentity()->getId();
        $this->postService->like($userId, $postId);

        $this->flashMessage('K príspevku bol pridaný váš Like.');
        $this->redirect('Post:onePostAndComments', array('postId' => $postId));
    }

    public function actionDislike($postId)
    {
        $userId = $this->user->getIdentity()->getId();
        $this->postService->dislike($userId, $postId);

        $this->flashMessage('Z príspevku bol odobratý váš Like.');
        $this->redirect('Post:onePostAndComments', array('postId' => $postId));
    }
}