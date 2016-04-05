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
		
	}

	public function renderWall1()
	{
		$userId = $this->user->getIdentity()->getId();
		$posts = $this->postService->getWall1Posts($userId);
		$this->template->posts = $this->postService->getWall1Posts($userId);
	}

	public function renderWall2()
	{
		$userId = $this->user->getIdentity()->getId();
		$this->template->posts = $this->postService->getWall2Posts($userId);
	}

	public function renderWall3()
	{
		$userId = $this->user->getIdentity()->getId();
		$this->template->posts = $this->postService->getWall3Posts($userId);
	}
	
	public function actionLike($postId)
	{
		$userId = $this->user->getIdentity()->getId();
		$this->postService->like($userId, $postId);

		$backlink = $this->getParameter('backlink');
		
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
