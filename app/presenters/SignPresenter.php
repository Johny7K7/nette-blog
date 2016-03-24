<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;
use App\Service\UserService;
use App\Model\User;

class SignPresenter extends BasePresenter
{
	/**
	 * Sign-in from factory
	 * @return Nette\Application\UI\Form
	 */

    /**
     * @var $userService UserService
     */
    private $userService;

    /**
     * SignPresenter constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    protected function createComponentRegistrationForm()
	{
		$form = new UI\Form;

		$gender = array(
			'M' => 'muž',
			'F' => 'žena'
		);
		$teacher = array(
			'true' => 'Učiteľ',
			'false' => 'Študent',
		);

		$form->addText('username', 'Meno a priezvisko:')
			->addRule(Form::PATTERN, 'Meno a priezvisko musí byť oddelené medzerou.', '.*[ ].*')
			->setRequired('Zadajte meno a priezvisko');
		$form->addText('nickname', 'Prezývka:')
			->addRule(Form::MAX_LENGTH, 'Prezývka nesmie obsahovať viac ako 10 znakov', 10);
		$form->addText('email', 'Email:')
			->addRule(Form::EMAIL, 'Zadaná emailová adresa nie je platná');
		$form->addRadioList('gender', 'Pohlavie:', $gender)
			->setRequired('Musíte vybrať pohlavie');
		$form->addText('birthdate', 'Dátum narodenia')
			->setAttribute('class', 'datepicker')
			->setRequired('Musíte vybrať dátum narodenia');
		$form->addPassword('password', 'Heslo:')
			->addRule(Form::MIN_LENGTH, 'Heslo musí mať aspoň %d znaky.', 3)
			->setRequired('Zadajte heslo');
		$form->addPassword('passwordVerify', 'Zopakujte heslo:')
			->addRule(Form::EQUAL, 'Heslá sa nezhodujú.', $form['password'])
			->setRequired('Zadajte heslo pre kontrolu.');
		$form->addRadioList('teacher', 'Vyberte:', $teacher)
			->setRequired('Vyberte položku zo zoznamu.');
		$form->addSubmit('register', 'Registrovať');
		$form->onSuccess[] = array($this, 'registrationFormSucceeded');

		return $form;
	}

	public function registrationFormSucceeded($form)
	{
		$values = $form->getValues();

		$user = new User();

        //$birthdate = strtotime($values->birthdate);

		$user->setUsername($values->username);
		$user->setEmail($values->email);
		$user->setGender($values->gender);
		$user->setBirthdate($values->birthdate);
		$user->setPassword($values->password);
		$user->setNickname($values->nickname);
		$user->setTeacher($values->teacher == 'true');

		$this->userService->addUser($user);

		$this->flashMessage('Uživateľ bol úspešne registrovaný.');
		$this->redirect('Sign:in');
	}

	protected function createComponentSignInForm()
	{
		$form = new Nette\Application\UI\Form;
		$form->addText('username', 'Meno:')
			->setRequired('Prosím, zadajte meno.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Prosím, zadajte heslo.');

		$form->addCheckbox('remember', 'Zostať prihlásený');

		$form->addSubmit('send', 'Prihlásiť');

		// call method signInFormSucceeded() on success
		$form->onSuccess[] = $this->signInFormSucceeded;
		return $form;
	}

	public function signInFormSucceeded($form)
	{
		$values = $form->getValues();

		if ($values->remember) {
			$this->getUser()->setExpiration('0', TRUE);
		} else {
			$this->getUser()->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->getUser()->login($values->username, $values->password);
			$this->redirect('Homepage:default');

		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Boli ste odhlásený.');
		$this->redirect('in');
	}

}
