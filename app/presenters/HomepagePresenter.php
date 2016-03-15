<?php

namespace App\Presenters;

use Nette;
use App\Model;


class HomepagePresenter extends BasePresenter
{
	public function actionIn()
	{
		if(!$this->user->isLoggedIn()){
			$this->redirect('Sign:in');
		}
	}

	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function renderDefault()
	{
		$this->template->post = $this->database->table('Post')
			->order('created_at DESC');
	}

}
