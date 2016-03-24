<?php

namespace App\Presenters;

use App\Service\PostService;
use Nette;
use App\Model;


class HomepagePresenter extends BasePresenter
{
	/**
	 * @var $postService PostService
	 */
	private $postService;

	/**
	 * HomepagePresenter constructor.
	 * @param PostService $postService
	 */
	public function __construct(PostService $postService)
	{
		$this->postService = $postService;
	}

	public function actionIn()
	{
		if(!$this->user->isLoggedIn()){
			$this->redirect('Sign:in');
		}
	}

	public function renderDefault()
	{
		$this->template->posts = $this->postService->getAllPosts();
	}

	protected function createComponentPostsAsSubjectForm()
	{

	}


}
