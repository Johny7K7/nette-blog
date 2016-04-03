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
        $link = time() . $filename;
        $file->move(WWW_DIR . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR . $link);

        $row = $this->database->table('File')->insert(array(
            'postId' => $postId,
            'link' => $link,
            'filename' => $filename
        ));
    }

    public function addPicture($userId, $picture)
    {
        $file = $picture->getSanitizedName();
        $filename = $userId . $file;
        $picture->move(WWW_DIR . DIRECTORY_SEPARATOR . "pictures" . DIRECTORY_SEPARATOR . $filename);

        $row = $this->database->table('User')->where(array('userId' => $userId))->update(array(
            'picture' => $filename
        ));
    }

    public function getAllFiles($postId)
    {
        $sql = "SELECT f.*, p.userId, u.username FROM File f, Post p, User u WHERE f.postId = p.postId AND f.postId = $postId 
                AND p.userId = u.userId";
        $files = $this->database->query($sql)->fetchAll();
        
        return $files;
    }
    
    public function getFileById($fileId)
    {
        return $this->database->table('File')->get($fileId);
    }

    public function addLink($link)
    {
        $this->database->table('Link')->insert($link);
    }

    public function getAllLinks($postId)
    {
        $sql = "SELECT l.*, p.userId, u.username FROM Link l, Post p, User u WHERE l.postId = p.postId AND l.postId = $postId 
                AND p.userId = u.userId";
        $links = $this->database->query($sql)->fetchAll();
        
        return $links;
    }
}