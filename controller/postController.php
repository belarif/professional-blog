<?php

namespace ProfessionalBlog\Controller;

use ProfessionalBlog\Model\PostManager;
use ProfessionalBlog\Model\UserManager;
use ProfessionalBlog\Model\CommentManager;

require_once 'model/PostManager.php';
require_once 'model/UserManager.php';
require_once 'model/CommentManager.php';

class PostController {

    public function addPostAction($template)
    {
        try
        {
            if (isset($_POST['title']) && isset($_POST['chapo']) && isset($_POST['user_id'])
                && isset($_POST['content']) && isset($_POST['published']))
            {
                $title = $_POST['title'];
                $chapo = $_POST['chapo'];
                $user_id = $_POST['user_id'];
                $content = $_POST['content'];
                $published = $_POST['published'];

                if (isset($_POST['submit']))
                {
                    $PostManager = new \ProfessionalBlog\Model\PostManager();
                    $PostManager->createPost($title, $chapo, $user_id, $content, $published);
                    header("Location:index.php?action=dashboard/listPosts");
                }
                else
                {
                    throw new \Exception('Tous les champs sont obligatoires');
                }
            }
        }
        catch(\Exception $e)
        {
            $errorMessage = $e->getMessage();
            echo $template->render(['errorMessage' => $errorMessage]);
        }

        session_start();
        $logged_user = $_SESSION['logged_user'];
        $role = $_SESSION['role'];

        if ($logged_user && $role == 1)
        {

            $UserManager = new UserManager();
            $users = $UserManager->getUsers();

            echo $template->render(['users' => $users, 'logged_user' => $logged_user]);
        }
        else
        {
            header("Location:index.php?action=login");
        }

    }

    public function listPostsAction($template)
    {
        session_start();
        $logged_user = $_SESSION['logged_user'];
        $role = $_SESSION['role'];

        if ($logged_user && $role == 1)
        {

            $postManager = new PostManager();
            $listPosts = $postManager->getPosts();

            echo $template->render(['listPosts' => $listPosts, 'logged_user' => $logged_user]);
        }
        else
        {
            header("Location:index.php?action=login");
        }
    }

    public function readPostAction($template)
    {
        session_start();
        $logged_user = $_SESSION['logged_user'];
        $role = $_SESSION['role'];

        if ($logged_user && $role == 1)
        {
            $postManager = new PostManager();
            $id = $_GET['id'];
            $post = $postManager->getPost($id);

            echo $template->render(['post' => $post, 'logged_user' => $logged_user]);
        }
        else
        {
            header("Location:index.php?action=login");
        }
    }

    public function editPostAction($template)
    {
        session_start();
        $logged_user = $_SESSION['logged_user'];
        $role = $_SESSION['role'];

        if ($logged_user && $role == 1)
        {
            $id = $_GET['id'];
            $postManager = new PostManager();
            $post = $postManager->getPost($id);

            $UserManager = new UserManager();
            $users = $UserManager->getUsers();

            echo $template->render(['post' => $post, 'logged_user' => $logged_user, 'users' => $users]);

        }
        else
        {
            header("Location:index.php?action=login");
        }

    }

    public function updatePostAction()
    {
        if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['chapo']) && isset($_POST['user_id'])
            && isset($_POST['content']) && isset($_POST['published']))
        {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $chapo = $_POST['chapo'];
            $user_id = $_POST['user_id'];
            $content = $_POST['content'];
            $published = $_POST['published'];

            if(isset($_POST['submit']))
            {
                $postManager = new PostManager();
                $postManager->updatePost($id,$title,$chapo,$user_id,$content,$published);

                header("Location:index.php?action=dashboard/listPosts");
            }
        }
    }

    public function deletePostAction($template)
    {

        session_start();
        $logged_user = $_SESSION['logged_user'];
        $role = $_SESSION['role'];

        if ($logged_user && $role == 1)
        {
            $id = $_GET['id'];
            $postManager = new PostManager();
            $postManager->deletePost($id);
            $listPosts = $postManager->getPosts();

            echo $template->render(['listPosts' => $listPosts, 'logged_user' => $logged_user]);
        }
        else
        {
            header("Location:index.php?action=login");
        }

    }

    public function postAction($template)
    {
        try
        {
            $id = $_GET['id'];
            $postManager = new PostManager();
            $post = $postManager->getPost($id);
            $commentManager = new CommentManager();
            $comments = $commentManager->getCommentsPost($id);

            if(isset($_POST['content']) && !empty($_POST['content']))
            {
                $post_id = $id;
                $content = htmlspecialchars($_POST['content']);

                if(isset($_POST['submit']))
                {
                    $commentManager->createComment($content,$post_id);
                    header("Location:index.php?action=post&id=".$id);
                }
                else
                {
                    throw new \Exception("veuillez écrire votre commentaire");
                }
            }
        }
        catch (\Exception $e)
        {
            die('Error : '.$e->getMessage());
        }

        echo $template->render(['post' => $post, 'comments' => $comments]);
    }

    public function postsAction($template)
    {
        $postManager = new PostManager();
        $listPosts = $postManager->getPosts();

        echo $template->render(['listPosts' => $listPosts]);
    }

}
