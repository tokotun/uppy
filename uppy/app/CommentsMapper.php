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
            $comment->path = $this->pathToArray($r['comment_path']); 
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
        $result = $statment->fetchColumn();;

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function getNewCommentPath($parentPath, $fileId)
    {
        if ($parentPath) 
        {
            //если дан путь родительского коментария, то пытаемся создать путь для нового дочернего
            $sql = "SELECT MAX(`comment_path`) FROM comments WHERE file_id = :file_id AND
            `comment_path` LIKE :comment_path";// AND
            //`comment_path` <> :parent_path";
            $statment = $this->db->prepare($sql);
            $statment->bindValue(':file_id', $fileId);
            $statment->bindValue(':comment_path', $parentPath . '.___');
            //$statment->bindValue(':parent_path', $parentPath);
            $statment->execute();
            $result = $statment->fetchColumn();    

            if (!$result) {
                //преобразуем текстовый путь родительского комметария в массив
                $newCommentPath = $this->pathToArray($parentPath);    
                $newCommentPath[] = '001';
                return $newCommentPath;
            }
            
            //преобразуем текстовый путь последнего из детей в массив
            $newCommentPath = $this->pathToArray($result);

            //Увеличиваем последнюю цифру в пути
            $newCommentPath[count($newCommentPath) - 1] +=  1;
            
        } else {

            //если не дан путь родительского коментария, то пытаемся создать новый родительский комментрарий
            $sql = "SELECT MAX(`comment_path`) FROM comments WHERE file_id = :file_id AND
            `comment_path` LIKE :comment_path";
            $statment = $this->db->prepare($sql);
            $statment->bindValue(':file_id', $fileId);
            $statment->bindValue(':comment_path', '___');
            $statment->execute();
            $result = $statment->fetchColumn();
            if (!$result) {
                return array('001');
            }
            //Путь корневого коментария  увеличивается на 1)
                      
            $newCommentPath = array( $result + 1 ); 
        }
        
        return $newCommentPath;
    }

    public function saveComment(\Uppy\Comment $comment)
    {   
        
        $path = $this->pathToString($comment->path);
        $sql = "INSERT INTO comments VALUES (NULL, :file_id, :comment_path, :message,  FROM_UNIXTIME(:date_comment))";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':file_id', $comment->fileId);
        $statment->bindValue(':comment_path', $path);
        $statment->bindValue(':message', $comment->message);
        $statment->bindValue(':date_comment', $comment->dateLoad);
        $statment->execute();
    }

    public function pathToArray($path){
        
        $path = explode(".", $path);
        
        foreach ($path as $key => $value) {
            $path[$key] = sprintf('%03u', $path[$key]);
        }

        return $path;
    }

    public function pathToString($path){
        foreach ($path as $key => $value) {
            $path[$key] = sprintf('%03u', $path[$key]);
        }
        return implode(".", $path);
    }
}