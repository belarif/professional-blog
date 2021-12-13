<?php

namespace App\Controller;

use App\Model\UserManager;
use Exception;
use Twig\TemplateWrapper;

class SecurityController
{
    /**
     * @param $template
     */
    public function login(TemplateWrapper $template)
    {
        try {
            session_start();
            if (isset($_POST['email']) && isset($_POST['password'])) {
                if (!empty($_POST['email']) && !empty($_POST['password'])) {
                    $email = strip_tags($_POST['email']);
                    $password = strip_tags($_POST['password']);

                    if (isset($_POST['submit'])) {
                        $UserManager = new UserManager();
                        $user = $UserManager->getLoginUser($email);

                        if ($user) {
                            $hashPassword = $user['password'];
                            if (password_verify($password, $hashPassword)) {
                                $_SESSION['logged_user'] = $user['lastName'];
                                $_SESSION['role'] = $user['role'];
                                $_SESSION['id'] = $user['id'];
                                if (!isset($_SESSION['token'])) {
                                    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(20));
                                }

                                if (isset($_SESSION['logged_user'])) {
                                    $token = $_SESSION['token'];
                                    if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
                                        header("Location:index.php?action=dashboard");
                                    } else {
                                        if (isset($_SESSION['current_post_id']) && $_SESSION['current_post_id'] !== null) {
                                            $post_id = $_SESSION['current_post_id'];
                                            $_SESSION['current_post_id'] = null;
                                            header("Location:index.php?action=commentPost&id=$post_id&token=$token");
                                        } else {
                                            header("Location:index.php?action=listPosts");
                                        }
                                    }
                                } else {
                                    header("Location:index.php?action=login");
                                }
                            } else {
                                throw new Exception("Mot de passe invalide !!");
                            }
                        } else {
                            throw new Exception("Aucun compte existant avec l'adresse : $email");
                        }
                    }
                } else {
                    throw new Exception("Veuillez renseigner votre email et/ou mot de passe");
                }
            }

            if (isset($_SESSION['successRegister']) && $_SESSION['successRegister'] !== null) {
                $successRegister = $_SESSION['successRegister'];
                $_SESSION['successRegister'] = null;
                echo $template->render(['successRegister' => $successRegister]);
            } else {
                echo $template->render();
            }
        } catch (Exception $e) {
            $identificationError = $e->getMessage();
            echo $template->render(['identificationError' => $identificationError]);
        }
    }

    /**
     * @param $template
     */
    public function register(TemplateWrapper $template)
    {
        try {
            if (isset($_POST['lastName']) && isset($_POST['firstName']) && isset($_POST['email']) && isset($_POST['password'])) {
                if (!empty($_POST['lastName']) && !empty($_POST['firstName']) && !empty($_POST['email']) && !empty($_POST['password'])) {

                    $lastName = strip_tags($_POST['lastName']);
                    $firstName = strip_tags($_POST['firstName']);
                    $email = strip_tags($_POST['email']);
                    $password = strip_tags($_POST['password']);
                    $hashPassword = password_hash($password, PASSWORD_BCRYPT);
                    $role = '2';

                    if (isset($_POST['submit'])) {
                        $UserManager = new UserManager();
                        $user = $UserManager->getLoginUser($email);
                        if (!$user) {
                            $UserManager->createUser($lastName, $firstName, $email, $hashPassword, $role);
                            session_start();
                            $_SESSION['successRegister'] = "Vous vous êtes inscrit avec succès. Désomais,vous pouvez vous connecter";
                            header("Location:index.php?action=login");
                        } else {
                            throw new Exception("Un compte existe déjà avec l'adresse email : " . $email);
                        }
                    }
                } else {
                    throw new Exception("Tous les champs sont obligatoires");
                }
            }
            echo $template->render();

        } catch (Exception $e) {
            $registerError = $e->getMessage();
            echo $template->render(['registerError' => $registerError]);
        }
    }

    /**
     * @param $template
     */
    public function dashboard(TemplateWrapper $template)
    {
        session_start();
        if (isset($_SESSION['logged_user']) && $_SESSION['role'] == 1) {
            $logged_user = $_SESSION['logged_user'];
            $token = $_SESSION['token'];
            echo $template->render(['logged_user' => $logged_user, 'token' => $token]);
        } else {
            header("Location:index.php?action=login");
        }
    }

    /**
     * @param $template
     */
    public function frontOfficeError(TemplateWrapper $template)
    {
        session_start();
        if (!isset($_SESSION['logged_user'])) {
            echo $template->render();
        } else {
            $logged_user = $_SESSION['logged_user'];
            echo $template->render(['logged_user' => $logged_user]);
        }
    }

    /**
     * @param $template
     */
    public function backOfficeError(TemplateWrapper $template)
    {
        session_start();
        if (!isset($_SESSION['logged_user'])) {
            echo $template->render();
        } else {
            $logged_user = $_SESSION['logged_user'];
            $token = $_SESSION['token'];
            echo $template->render(['logged_user' => $logged_user, 'token' => $token]);
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("location:index.php?action=home");
    }
}
