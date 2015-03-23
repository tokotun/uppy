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
        $sql = "SELECT * FROM comments WHERE `file_id`=:idFile";
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
            $comment->path = preg_split($regexp, $r['path']); //разбивание пути типа 1.3.1 на отдельные элементы массива
            $comment->message = $r['text'];
            $comment->dateLoad = $r['date'];
            $comments[] = $comment;
        }
        return $comments;
    }
}