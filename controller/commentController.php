<?php

namespace ProfessilnalBlog\Controller;

use mysql_xdevapi\Exception;
use ProfessionalBlog\Model\CommentManager;

require_once 'model\CommentManager.php';


class CommentController
{
    public function listCommentsAction($template)
    {
        $commentManager = new CommentManager();
        $listComments = $commentManager->getComments();

        echo $template->render(['listComments' => $listComments]);
    }

    public function readCommentAction($template)
    {
        $id = $_GET['id'];
        $commentManager = new CommentManager();
        $comment = $commentManager->getComment($id);

        echo $template->render(['comment' => $comment]);
    }

    public function editCommentAction($template)
    {
        $id = $_GET['id'];
        $commentManager = new CommentManager();
        $comment = $commentManager->getComment($id);

        echo $template->render(['comment' => $comment]);
    }

    public function updateCommentAction($template)
    {

        if(!isset($_POST['id']) || !isset($_POST['isEnabled']))
        {
            if(empty($_POST['id']) || empty($_POST['isEnabled']))
            {
                throw new \Exception("tous les champs sont obligatoires");
            }
        }
        else
        {
            $id = $_POST['id'];
            $isEnabled = $_POST['isEnabled'];
            if(isset($_POST['submit']))
            {
                $commentManager = new CommentManager();
                $commentManager->updateComment($id,$isEnabled);
                $listComments = $commentManager->getComments();

                echo $template->render(['listComments' => $listComments]);
            }
        }
    }


}


