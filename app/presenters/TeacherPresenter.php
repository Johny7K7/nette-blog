<?php


namespace App\Presenters;

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
     * TeacherPresenter constructor.
     * @param UserService $userService
     * @param SubjectService $subjectService
     */
    public function __construct(UserService $userService, SubjectService $subjectService)
    {
        $this->userService = $userService;
        $this->subjectService = $subjectService;
    }

    public function renderLoggedUserProfil()
    {
        $userId = $this->user->getIdentity()->getId(); 
        
        $loggedUser = $this->userService->GetUserProfil($userId);
        
        $subjects = $this->subjectService->getSubjectFromTStoTable($userId);
        
        $this->template->loggedUser = $loggedUser;
        $this->template->subjects = $subjects;
    }

    public function renderUserProfil($userId)
    {
        $profil = $this->userService->GetUserProfil($userId);

        $subjects = $this->subjectService->getSubjectFromTStoTable($userId);

        $this->template->profil = $profil;
        $this->template->subjects = $subjects;
    }

    public function actionAboutSubject($subjectId)
    {
        $this['aboutSubjectForm']->setDefaults(array('subjectId' => $subjectId));
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
        $this->redirect("Teacher:loggedUserProfil", array('userId' => $userId));
    }
    
    public function actionAddUserToUser($userId1)
    {
        $userId2 = $this->user->getIdentity()->getId();
        
        $this->userService->addUserToUser($userId1, $userId2);
        
        $this->redirect('Homepage:');
    }
}