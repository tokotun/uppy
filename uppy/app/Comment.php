<?php
namespace Uppy;
class Comment
{
    public $id = '';
    public $fileId = '';    
    public $dateLoad = '';
    public $message = '';
    public $path = '';

    /**
    * 
    */
    public function getNewPathComment(\Uppy\CommentsMapper $commentsMapper, $idParentComment)
    {
        $parentPath = $commentsMapper->getCommentPaths($idParentComment);
        
        $this->path = $commentsMapper->getNewCommentPath($parentPath, $this->fileId);

    }
}