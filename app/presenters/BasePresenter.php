<?php

namespace App\Presenters;

use Nette;
use App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    public function startup()
    {
        parent::startup();

        if ($this->name != 'Sing:in') {
            if (!$this->user->isLoggedIn()) {
                if ($this->user->getLogoutReason() === Nette\Security\User::INACTIVITY) {
                    $this->flashMessage('Boli ste odhlásený, z dôvodu neprítomnosti.');
                }

//                $this->redirect('Sign:in');

            } else {
                if (!$this->user->isAllowed($this->name, $this->action)) {
                    $this->flashMessage('Prístup zakázaný.');
                    $this->redirect('Homepage:default');
                }
            }
        }
    }
    
    public function renderLayout()
    {
                    
    }
}
