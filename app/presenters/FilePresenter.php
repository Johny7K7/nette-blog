<?php


namespace App\Presenters;



use App\Service\FileService;
use Nette\Application\Responses\FileResponse;

class FilePresenter extends BasePresenter
{
    /**
     * @var $fileService FileService
     */
    private $fileService;

    /**
     * FilePresenter constructor.
     * @param FileService $fileService
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function actionDownload($fileId)
    {
        $file = $this->fileService->getFileById($fileId);

        $this->sendResponse(new FileResponse(WWW_DIR . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $file->link, $file->filename));
    }
}