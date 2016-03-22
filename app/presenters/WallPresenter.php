<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;

class WallPresenter extends BasePresenter
{


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

    public function postFormSucceeded($form, $values)
    {
        $this->flashMessage('Príspevok bol úspešne pridaný.');
        $this->redirect('Homepage:');
    }
}