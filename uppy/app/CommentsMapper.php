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
        $regexp = '/\./u';
        foreach ($result as $r) {
            $comment = new \Uppy\Comment;
            $comment->id = $r['id'];
            $comment->path = preg_split($regexp, $r['comment_path']); //разбивание пути типа 1.3.1 на отдельные элементы массива
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
                return $parentPath . '.' . '1';
            }
            //извлекает последнюю цифру из строки  (из 2.1.13 извлекет 13)
            preg_match('/\d+$/u', $result['comment_path'], $n); 
            $numberChildComment = $n['0'] + 1;
            //родительский путь сложится с дочерним. Получится путь для нового коментария.
            $newCommentPath = $parentPath . '.' .$numberChildComment; 
        } else {

            //если не дан путь родительского коментария, то пытаемся создать новый родительский комментрарий
            $sql = "SELECT `comment_path` FROM comments WHERE file_id = :file_id 
            ORDER BY `comment_path` DESC LIMIT 1";
            $statment = $this->db->prepare($sql);
            $statment->bindValue(':file_id', $fileId);
            $statment->execute();
            $result = $statment->fetch(\PDO::FETCH_ASSOC);

            if (!$result) {
                return '1';
            }
            //извлекает первую цифру из строки  (из 2.1.13 извлекет 2)
            preg_match('/^\d+/u', $result['comment_path'], $n); 
            $newCommentPath = $n['0'] + 1;
        }

        return $newCommentPath;
    }

    public function saveComment(\Uppy\Comment $comment)
    {
        $sql = "INSERT INTO comments VALUES (NULL, :file_id, :comment_path, :message,  FROM_UNIXTIME(:date_comment))";
        $statment = $this->db->prepare($sql);
        $statment->bindValue(':file_id', $comment->fileId);
        $statment->bindValue(':comment_path', $comment->path);
        $statment->bindValue(':message', $comment->message);
        $statment->bindValue(':date_comment', $comment->dateLoad);
        $statment->execute();
    }
}