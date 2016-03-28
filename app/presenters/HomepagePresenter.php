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

	public function renderDefault()
	{
		$userId = $this->user->getIdentity()->getId();
		$this->template->posts = $this->postService->getAllPosts($userId);
	}
	
	public function actionLike($postId)
	{
		$userId = $this->user->getIdentity()->getId();
		$this->postService->like($userId, $postId);

		$this->flashMessage('K príspevku bol pridaný váš Like.');
		$this->redirect('Homepage:');
	}

	public function actionDislike($postId)
	{
		$userId = $this->user->getIdentity()->getId();
		$this->postService->dislike($userId, $postId);

		$this->flashMessage('Z príspevku bol odobratý váš Like.');
		$this->redirect('Homepage:');
	}
}
