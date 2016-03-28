<?php

namespace App\Service;

use App\Model\Post;
use App\Model\Subject;
use App\Model\User;
use Nette\Database\Context;
use Nette\InvalidStateException;
use Nette\Object;

class FileService extends Object
{
    /** @var \Nette\Database\Context */
    private $database;

    /**
     * FileService constructor.
     * @param $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    public function addFile($postId, $file)
    {
        $filename = $file->getSanitizedName();
        $file->move(WWW_DIR . "/../files/$filename");

        $row = $this->database->table('File')->insert(array(
            'postId' => $postId,
            'link' => $filename
        ));
    }

    public function getAllFiles($postId)
    {
        $sql = "SELECT f.*, p.userId, u.username FROM File f, Post p, User u WHERE f.postId = p.postId AND f.postId = $postId 
                AND p.userId = u.userId";
        $files = $this->database->query($sql)->fetchAll();
        
        return $files;
    }

    public function deleteFile($fileId, $postId)
    {

    }
}