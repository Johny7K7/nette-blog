<?php


namespace App\Presenters;

use App\Service\FileService;
use App\Service\SubjectService;
use Nette;
use App\Service\UserService;
use Nette\Application\UI;
use Nette\Application\UI\Form;

class TeacherPresenter extends BasePresenter
{
    /**
     * @var $userService UserService
     */
    private $userService;

    /**
     * @var $subjectService SubjectService
     */
    private $subjectService;

    /**
     * @var $fileService FileService
     */
    private $fileService;

    /**
     * TeacherPresenter constructor.
     * @param UserService $userService
     * @param SubjectService $subjectService
     * @param FileService $fileService
     */
    public function __construct(UserService $userService, SubjectService $subjectService, FileService $fileService)
    {
        $this->userService = $userService;
        $this->subjectService = $subjectService;
        $this->fileService = $fileService;
    }

    public function renderUserProfile($userId)
    {        
        $userProfile = $this->userService->getUserProfile($userId);
        $subjects = $this->subjectService->getSubjectFromTStoTable($userId);
        
        $link = 'pictures' . DIRECTORY_SEPARATOR . $userProfile->picture;
        
        $request = $this->userService->isRequest($userId, $this->user->getIdentity()->getId());
        $friend = $this->userService->isFriend($userId, $this->user->getIdentity()->getId());
        $teachers = $this->userService->getTeachersOfStudentOrCollegesOfTeacher($userId);
        $students = $this->userService->getStudentsOfTeacher($userId);

        $this->template->userProfile = $userProfile;
        $this->template->userId = $userId;
        $this->template->subjects = $subjects;
        $this->template->backlink = $this->getParameter('backlink');
        $this->template->postId = $this->getParameter('postId');
        $this->template->isRequest = $request;
        $this->template->isFriend = $friend;
        $this->template->link = $link;
        $this->template->teachers = $teachers;
        $this->template->students = $students;
    }

    public function actionAboutSubject($subjectId)
    {
        $this['aboutSubjectForm']->setDefaults(array('subjectId' => $subjectId));
        $this->template->userId = $this->user->getIdentity()->getId();
        $this->template->title = $this->getParameter('title');
    }

    protected function createComponentAboutSubjectForm()
    {
        $form = new Form();
        
        $form->addHidden('subjectId');
        $form->addTextArea('about', 'O predmete:')
            ->addRule(Form::MAX_LENGTH, 'Maximálny počet znakov je %d', 5000)
            ->setRequired('Nepíšte niečo o predmete');
        $form->addSubmit('save', 'Uložiť');
        $form->onSuccess[] = array($this, 'aboutSubjectFormSucceeded');
        
        return $form;
    }
    
    public function aboutSubjectFormSucceeded($form)
    {
        $userId = $this->user->getIdentity()->getId();
        
        $values = $form->getValues();
        
        $this->subjectService->updateAboutSubject($values->subjectId, $userId, $values->about);

        $this->flashMessage('Poznámka o predmete bola úspešne pridaná.');
        $this->redirect("Teacher:userProfile", array('userId' => $userId));
    }
    
    public function renderRequests()
    {
        $userId = $this->user->getIdentity()->getId();
        $requests = $this->userService->getAllRequests($userId);
        
        $this->template->requests = $requests;
        $this->template->userId = $userId;
    }
    
    public function actionAddUserToUser($userId1)
    {
        $userId2 = $this->user->getIdentity()->getId();
        $this->userService->addUserToUser($userId1, $userId2);

        $this->redirect('Teacher:userProfile', array('userId' => $userId1));
    }

    public function actionConfirmUserToUser($userId1)
    {
        $userId2 = $this->user->getIdentity()->getId();

        $this->userService->acceptUserToUser($userId1, $userId2, true);

        $this->redirect('Teacher:userProfile', array('userId' => $userId2));
    }
    
    public function actionRejectUserToUser($userId1)
    {
        $userId2 = $this->user->getIdentity()->getId();

        $this->userService->acceptUserToUser($userId1, $userId2, false);

        $this->redirect('Teacher:userProfile', array('userId' => $userId2));
    }

    public function actionAddPicture($userId)
    {
        $user = $this->userService->getUserProfile($userId);
        $this['addPictureForm']->setDefaults(array('userId' => $user->userId));
        $this->template->userId = $this->getParameter('userId');
    }

    protected function createComponentAddPictureForm()
    {
        $form = new Form();

        $form->addHidden('userId');
        $form->addUpload('picture', 'Profilová fotka:')
            ->addRule(Form::IMAGE, 'Profilová fotka musí byť obrázok vo formáte JPEG, PNG alebo GIF')
            ->setRequired('Vložte profilovú fotku.');
        $form->addSubmit('post', 'Vložiť');
        $form->onSuccess[] = array($this, 'addPictureSucceeded');

        return $form;
    }

    public function addPictureSucceeded($form)
    {
        $values = $form->getValues();

        $this->fileService->addPicture($values->userId, $values->picture);

        $this->flashMessage('Profilová fotka bola úspešne pridaná.');
        $this->redirect("Teacher:userProfile", array('userId' => $values->userId));
    }
    
    protected function createComponentSearchUserForm()
    {
        $form = new Form();
        
        $form->addText('username', 'Meno a priezvisko');
        $form->addSubmit('seachr', 'Hľadať');
        $form->onSuccess[] = array($this, 'searchUserFormSucceeded');
        
        return $form;
    }
    
    public function searchUserFormSucceeded($form)
    {
        $values = $form->getValues();
        
        $username = $values->username;
        
        $this->redirect('Teacher:search', $username);
    }
    
    public function renderSearch($username)
    {
        $users = $this->userService->searchUser($username);
        
        $this->template->users = $users;
    }
}