<?php

/* routes à revoir (URL rewriting)*/
require('controller/postController.php');
require('controller/homeController.php');
require('controller/securityController.php');
require('controller/userController.php');
require('controller/commentController.php');

require_once 'vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader(['view/frontoffice','view/backoffice']);
$twig = new \Twig\Environment($loader, [
    'cache' => '/var/cache', 'auto_reload' => true, 'strict_variables' => true,
]);


try
{
    if($_GET['action'] == 'home')
    {
        $template = $twig->load('home.html.twig');
        homeAction($template);
    }
    elseif($_GET['action'] == 'login')
    {
        $template = $twig->load('login.html.twig');
        loginAction($template);
    }
    elseif($_GET['action'] == 'adminLogin')
    {
        $template = $twig->load('login.html.twig');
        loginAction($template);
    }
    elseif ($_GET['action'] == 'register')
    {
        $template = $twig->load('register.html.twig');
        registerAction($template);
    }
    elseif($_GET['action'] == 'listPosts')
    {
        $template = $twig->load('posts.html.twig');
        listPostsAction($template);
    }
    elseif ($_GET['action'] == 'post')
    {
        if(isset($_GET['id']) && $_GET['id'] > 0)
        {
            $template = $twig->load('post.html.twig');
            postAction($template);
        }
        else
        {
            throw new Exception('Aucun blog post trouvé');
        }

    }
    elseif($_GET['action'] == 'dashboard')
    {
        $template = $twig->load('dashboard.html.twig');
        dashboardAction($template);
    }
    elseif($_GET['action'] == 'dashboard/listPosts')
    {
        $template = $twig->load('listPosts.html.twig');
        listPostsAction($template);
    }
    elseif($_GET['action'] == 'dashboard/addPost')
    {
        $template = $twig->load('addPost.html.twig');
        addPostAction($template);
    }
    elseif ($_GET['action'] == 'dashboard/editPost')
    {
        if(isset($_GET['id']) && $_GET['id'] > 0)
        {
            $template = $twig->load('editPost.html.twig');
            editPostAction($template);
        }
        else
        {
            throw new Exception('Aucun blog post trouvé');
        }
    }
    elseif($_GET['action'] == 'dashboard/readPost')
    {
        if(isset($_GET['id']) && $_GET['id'] > 0)
        {
            $template = $twig->load('readPost.html.twig');
            readPostAction($template);
        }
        else
        {
            throw new Exception('Aucun blog post trouvé');
        }
    }
    elseif($_GET['action'] == 'dashboard/listUsers')
    {
        $template = $twig->load('listUsers.html.twig');
        listUsersAction($template);
    }
    elseif($_GET['action'] == 'dashboard/addUser')
    {
        $template = $twig->load('addUser.html.twig');
        addUserAction($template);
    }
    elseif($_GET['action'] == 'dashboard/editUser')
    {
        $template = $twig->load('editUser.html.twig');
        editUserAction($template);
    }
    elseif($_GET['action'] == 'dashboard/readUser')
    {
        if(isset($_GET['id']) && $_GET['id'] > 0)
        {
            $template = $twig->load('readUser.html.twig');
            readUserAction($template);
        }
        else
        {
            throw new Exception('Aucun blog post trouvé');
        }
    }
    elseif($_GET['action'] == 'dashboard/listComments')
    {
        $template = $twig->load('listComments.html.twig');
        listCommentsAction($template);
    }
    elseif($_GET['action'] == 'dashboard/readComment')
    {
        if(isset($_GET['id']) && $_GET['id'] > 0)
        {
            $template = $twig->load('readComment.html.twig');
            readCommentAction($template);
        }
        else
        {
            throw new Exception('Aucun commentaire trouvé');
        }
    }
    else
    {
        throw new Exception('404 - Page inexistante');

    }

}

catch (Exception $e)
{
    die('Error : ' .$e->getMessage());
}

