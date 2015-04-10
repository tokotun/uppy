<?php
namespace Uppy;
class CommentsMapper
{
	protected $db;

	public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function loadComments($idFile)
    {
        $sql = "SELECT * FROM comments WHERE `file_id`=:idFile ORDER BY `comment_path`";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':idFile', $idFile);
        $statment->execute();
        $result = $statment->fetchAll(\PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        $comments = array();
        foreach ($result as $r) {
            $comment = new \Uppy\Comment;
            $comment->id = $r['id'];
            $comment->path = $this->strToArr($r['comment_path']); 
            $comment->message = $r['message'];
            $comment->dateLoad = $r['date_comment'];
            $comments[] = $comment;
        }
        return $comments;
    }


    public function getCommentPaths($idComment)
    {
        if ($idComment == false) 
        {
            return false;
        }

        $sql = "SELECT `comment_path` FROM comments WHERE `id`=:id";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':id', $idComment);
        $statment->execute();
        $result = $statment->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        return $result['comment_path'];
    }

    public function getNewCommentPath($parentPath, $fileId)
    {
        if ($parentPath) 
        {
            //если дан путь родительского коментария, то пытаемся создать путь для нового дочернего
            $sql = "SELECT `comment_path` FROM comments WHERE file_id = :file_id AND
            `comment_path` LIKE :comment_path AND
            `comment_path` <> :parent_path ORDER BY `comment_path` DESC LIMIT 1";
            $statment = $this->db->prepare($sql);
            $statment->bindValue(':file_id', $fileId);
            $statment->bindValue(':comment_path', $parentPath . '%');
            $statment->bindValue(':parent_path', $parentPath);
            $statment->execute();
            $result = $statment->fetch(\PDO::FETCH_ASSOC);

            
            if (!$result) {
                $newCommentPath = $this->strToArr($parentPath);
                $newCommentPath[] = '001';
                return $newCommentPath;
            }
            
            //преобразуем текстовый путь родительского комметария в массив
            $newCommentPath = $this->strToArr($parentPath);

            //текстовый путь одного из потомков
            $r = $this->strToArr($result['comment_path']);
            
            $newCommentPath[] = $r[count($newCommentPath)] + 1;
            
        } else {

            //если не дан путь родительского коментария, то пытаемся создать новый родительский комментрарий
            $sql = "SELECT `comment_path` FROM comments WHERE file_id = :file_id 
            ORDER BY `comment_path` DESC LIMIT 1";
            $statment = $this->db->prepare($sql);
            $statment->bindValue(':file_id', $fileId);
            $statment->execute();
            $result = $statment->fetch(\PDO::FETCH_ASSOC);

            if (!$result) {
                return array('001');
            }
            //В пути 002.001.013  берётся первый элемент. И увеличивается на 1)
            $newCommentPath = $this->strToArr($parentPath);
            $newCommentPath = array( $newCommentPath[0] + 1 ); 
        }
        
        return $newCommentPath;
    }

    public function saveComment(\Uppy\Comment $comment)
    {   
        
        $path = $this->arrToStr($comment->path);

        $sql = "INSERT INTO comments VALUES (NULL, :file_id, :comment_path, :message,  FROM_UNIXTIME(:date_comment))";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':file_id', $comment->fileId);
        $statment->bindValue(':comment_path', $path);
        $statment->bindValue(':message', $comment->message);
        $statment->bindValue(':date_comment', $comment->dateLoad);
        $statment->execute();
    }

    public function strToArr($path){
        
        $path = explode(".", $path);
        
        foreach ($path as $key => $value) {
            $path[$key] = sprintf('%03u', $path[$key]);
        }

        return $path;
    }

    public function arrToStr($path){
        foreach ($path as $key => $value) {
            $path[$key] = sprintf('%03u', $path[$key]);
        }
        return implode(".", $path);
    }
}