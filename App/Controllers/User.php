<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Flash;
use App\Utility\Session;
use \Core\View;
use Exception;
use OpenApi\Annotations\Flow;

/**
 * User controller
 */
class User extends \Core\Controller

{

    /**
     * Affiche la page de login
     */
    /**
     * @OA\Post(
     *     path="/login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="Connexion de l'utilisateur")
     * )
     */
    public function loginAction()
    {
        if (isset($_POST['submit'])) {
            $f = $_POST;
            if (!filter_var($f['email'], FILTER_VALIDATE_EMAIL)) {
                Flash::danger('Email invalide');
                throw new Exception('Email invalide');
            }
            if (empty($f['password']) || empty($f['email'])) {
                Flash::danger('Veuillez remplir tous les champs');
                throw new Exception('Veuillez remplir tous les champs');
            }

            try {

                if ($this->login($f)) {
                    // Si login OK, redirige vers le compte
                    header('Location: /account');
                    exit;
                }
            } catch (Exception $ex) {
                // TODO : Set flash if error
                Flash::danger($ex->getMessage());
            }
        }
        $flashMessages = Flash::getMessages();

        View::renderTemplate('User/login.html', [
            'flashMessages' => $flashMessages
        ]);
    }

    public function registerAction()
    {
        if (isset($_POST['submit'])) {
            $f = $_POST;
            if ($f['password'] !== $f['password-check']) {
                Flash::danger('Les mots de passe ne correspondent pas');
            }
            if (!filter_var($f['email'], FILTER_VALIDATE_EMAIL)) {
                Flash::danger('Email invalide');
            }
            // validation
            try {

                if ($this->register($f)) {
                    $this->login($f);
                }
            } catch (Exception $ex) {
                // TODO : Set flash if error
                Flash::danger($ex->getMessage());
            }
        }

        $flashMessages = Flash::getMessages();

        View::renderTemplate('User/register.html', [
            'flashMessages' => $flashMessages
        ]);
    }

    //...

    private function register($data)
    {
        try {
            // Generate a salt, which will be applied to the during the password
            // hashing process.
            $salt = Hash::generateSalt(32);

            $userID = \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt),
                "salt" => $salt
            ]);
            Flash::success('Vous êtes bien inscrit !');
            // dd($user)
            return $userID !== false;
        } catch (Exception $ex) {
            // TODO : Set flash if error : utiliser la fonction en dessous
            Flash::danger($ex->getMessage());
            throw $ex;
        }
    }




    private function login($data)
    {
        try {
            if (empty($data['email']) || empty($data["password"])) {
                Flash::danger('Email ou mot de passe non fourni');
                return false;
            }

            $user = \App\Models\User::getByLogin($data['email']);
            if (!$user || Hash::generate($data['password'], $user['salt']) !== $user['password']) {
                Flash::danger('Email ou mot de passe incorrect');
                return false;
            }

            // TODO: Create a remember me cookie if the user has selected the option
            // to remained logged in on the login form.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L86

            $userData = array(
                'id' => $user['id'],
                'username' => $user['username'],
            );
            $_SESSION['user'] = $userData;
            // Sérialiser les données de l'utilisateur
            if (isset($data['remember-me'])) {
                $cookieValue = serialize($userData);

                // Définir les autres valeurs du cookie
                $cookieName = 'user_cookie';
                $cookieExpiration = time() + (86400 * 30); // Expire dans 30 jours (86400 secondes par jour)
                $cookiePath = '/'; // Le cookie est disponible sur l'ensemble du site

                // Enregistrer le cookie
                setcookie($cookieName, $cookieValue, $cookieExpiration, $cookiePath);
            }

            return true;
        } catch (Exception $ex) {
            // TODO : Set flash if error : utiliser la fonction en dessous
            Flash::danger($ex->getMessage());
            throw $ex;
        }
    }





    /**
     * Affiche la page du compte
     */
    public function accountAction()
    {
        $cookieValue = $_COOKIE['user_cookie'];
        // Désérialiser la valeur du cookie
        $userData = unserialize($cookieValue);
        $_SESSION['user'] = $userData;
        $articles = Articles::getByUser(
            Session::get('user')['id'] ?? $userData['id']
        );
        View::renderTemplate('User/account.html', [
            'articles' => $articles,
        ]);
    }

    /*
    /**
     * Logout: Delete cookie and session. Returns true if everything is okay,
     * otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
    /* 
    * @OA\Post(
     *     path="/logout",
     *     @OA\Response(response="200", description="Déconnexion de l'utilisateur")
     * )


    */
    public function logoutAction()
    {

        /*
        if (isset($_COOKIE[$cookie])){
            // TODO: Delete the users remember me cookie if one has been stored.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L148
        }*/
        // Destroy all data registered to the session.

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        $cookieName = 'user_cookie';

        // Définir une date d'expiration passée pour le cookie
        $cookieExpiration = time() - 3600; // Par exemple, une heure avant l'heure actuelle

        // Supprimer le cookie en définissant une nouvelle valeur avec une date d'expiration passée
        setcookie($cookieName, '', $cookieExpiration);

        // Facultatif : supprimer également le cookie de la superglobale $_COOKIE
        unset($_COOKIE[$cookieName]);

        header("Location: /");

        return true;
    }

    public static function LoginWithCookie()
    {

        if (isset($_COOKIE["user_cookie"]) && !isset($_SESSION['user'])) {

            $cookieValue = $_COOKIE['user_cookie'];

            // Désérialiser la valeur du cookie
            $userData = unserialize($cookieValue);

            $_SESSION['user'] = $userData;
        }
    }
}
