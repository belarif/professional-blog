<?php

namespace ProfessionalBlog\Controller;

use ProfessionalBlog\Model\SecurityManager;
use ProfessionalBlog\Model\UserManager;

require_once 'model/SecurityManager.php';

class SecurityController
{

    public function registerAction($template)
    {
        try
        {
            if(isset($_POST['lastName']) && isset($_POST['firstName']) && isset($_POST['email'])
                && isset($_POST['password']) && isset($_POST['role']))
            {
                if(!empty($_POST['lastName']) && !empty($_POST['firstName']) && !empty($_POST['email'])
                    && !empty($_POST['password']) && !empty($_POST['role']))
                {
                    $lastName = $_POST['lastName'];
                    $firstName = $_POST['firstName'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $role = $_POST['role'];

                    if(isset($_POST['submit']))
                    {
                        $UserManager = new UserManager();
                        $UserManager->createUser($lastName,$firstName,$email,$password,$role);

                        $successMessage = "Vous vous êtes inscrit avec succès";
                        echo $template->render(['successMessage' => $successMessage]);

                    }
                }
                else
                {
                    throw new \Exception("tous les champs sont obligatoires");
                }
            }
        }
        catch (\Exception $e)
        {
            $errorMessage = $e->getMessage();
            echo $template->render(['errorMessage' => $errorMessage]);
        }

        echo $template->render();

    }

}
