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

    public function getDepth()
    { 
        return 25 * (count($this->path) - 1);
    }
}