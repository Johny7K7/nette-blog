<?php

namespace App\Presenters;

use Nette;

class SignPresenter extends BasePresenter
{
	/**
	 * Sign-in from factory
	 * @return Nette\Application\UI\Form
	 */

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

	public function signInFormSucceeded()
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
